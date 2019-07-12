<?php
//ESTE ES EL QUE VA A QUEDAR
/**
* Submodulo de Reserva de Sangre.
*
* Submodulo para manejar la reserva y/o cruzada de sangre.
* @author Claudia Liliana Zuñiga Cañon <claudia_zc@hotmail.com
* @version 1.0
* @package SIIS
* $Id: hc_ReservaSangre_HTML.php,v 1.15 2006/12/21 22:34:01 lorena Exp $
*/

class ReservaSangre_HTML extends ReservaSangre
{
    //clzc
	function ReservaSangre_HTML()
	{
		$this->ReservaSangre();//constructor del padre
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
					$this->salida= ThemeAbrirTablaSubModulo('RESERVA DE SANGRE Y/O CRUZADA');
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

		//$accion1=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$pfj=>'ConfirmarComponentes'));
		//$this->salida.="<form name=\"forma\" action=\"$accion1\" method=\"post\">";
		$this->frmConsulta();
    //$this->salida.="</form>";

    $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$pfj=>'ReservaComponentes'));
    $this->salida.="<form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida.="<table width=\"95%\" border=\"0\" align=\"center\">";
		$this->salida.="<tr class=\"modulo_table_list_title\">";
		$this->salida.="<td align=\"center\" colspan=\"4\">SOLICITUD DE RESERVA DE SANGRE</td>";
		$this->salida.="</tr>";
		$this->salida.="        <tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="        <td align=\"left\" colspan =\"4\">GRUPO SANGUINEO DEL PACIENTE</td>";
		$this->salida.="        </tr>";
		$this->salida.="        <tr class = modulo_list_claro>";
		$this->salida.="        <td class=\"".$this->SetStyle("grupo_sanguineo")."\">FACTOR </td>";
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

		$this->salida.="        <tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="        <td width=\"25%\" colspan=\"2\" align=\"left\">NIVEL DE URGENCIA</td>";
		if ($_REQUEST["sw_urgencia".$pfj]!= '1'){
			$this->salida.="      <td width=\"25%\" colspan=\"1\" align=\"left\">URGENTE<input type=\"radio\"  name=\"sw_urgencia$pfj\" value=\"1\" $desabilitado onclick=\"ponerFecha(this.form,this.value,'".date('d-m-Y')."')\"></td>";
			$this->salida.="      <td width=\"20%\" colspan=\"1\" align=\"left\">RESERVAR<input type=\"radio\"  name=\"sw_urgencia$pfj\" value=\"2\" checked $desabilitado onclick=\"ponerFecha(this.form,this.value,'".date('d-m-Y')."')\"></td>";
		}else{
			$this->salida.="      <td width=\"25%\" colspan=\"1\" align=\"left\">URGENTE<input type=\"radio\"  name=\"sw_urgencia$pfj\" value=\"1\" checked $desabilitado onclick=\"ponerFecha(this.form,this.value,'".date('d-m-Y')."'></td>";
			$this->salida.="      <td width=\"20% \" colspan=\"1\" align=\"left\">RESERVAR<input type=\"radio\"  name=\"sw_urgencia$pfj\" value=\"2\" $desabilitado onclick=\"ponerFecha(this.form,this.value,'".date('d-m-Y')."')\"></td>";
		}
		$this->salida.="        </tr>";
    $this->salida.="        <tr class=\"hc_table_submodulo_list_title\" ><br>";
		$this->salida.="        <td align=\"left\" colspan=\"4\">COMPONENTES A RESERVAR</td>";
    $this->salida.="        </tr>";
		$comp =$this->ConsultaComponente();
    $i=0;
		while($i<sizeof($comp)){
		  $this->salida.="      <tr class =\"modulo_list_claro\">";
		  $this->salida.="      <td align=\"left\" class=\"label\">".$comp[$i][componente]."</td>";
  		$this->salida.="      <td align=\"left\" colspan=\"3\">";
      $this->salida.="      <input type=\"text\" class=\"input-text\" name=\"Cantidad".$comp[$i]['hc_tipo_componente']."$pfj\" value=\"".$_REQUEST['Cantidad'.$comp[$i]['hc_tipo_componente'].$pfj]."\" size=\"2\">";
			$this->salida.="      <label class=\"label\">Und</label>";
			$this->salida.="      </td>";
			$this->salida.="      </tr>";
			$i++;
		}

		$this->salida.="        <tr class=\"hc_table_submodulo_list_title\" ><br>";
		$this->salida.="        <td align=\"left\">RESERVA AUTOLOGA</td>";
		if($_REQUEST['autologa'.$pfj]){$c='checked';}else{$c='';}
    $this->salida.="        <td align=\"left\" colspan=\"3\"><input type=\"checkbox\" value=\"1\" name=\"autologa$pfj\" $c></td>";
    $this->salida.="        </tr>";

		$this->salida .="       <tr class = \"modulo_list_claro\">";
		if(!$_REQUEST["fecha_reserva".$pfj]){
      $_REQUEST["fecha_reserva".$pfj]=date('d-m-Y');
		}
		$this->salida .="       <td class=\"".$this->SetStyle("fecha_reserva")."\" align=\"left\">FECHA DE LA RESERVA</td>";
		$this->salida .="       <td align=\"left\"><input type=\"text\" readonly class=\"input-text\" size=\"10\" maxlength=\"10\" value=\"".$_REQUEST["fecha_reserva".$pfj]."\" name=\"fecha_reserva$pfj\" onBlur=\"IsValidDate(this,'YYYY-MM-DD')\" onKeyUp=\"setDate(this,'YYYY-MM-DD','es')\">
		".ReturnOpenCalendario('forma','fecha_reserva','-')."</td>" ;
		$this->salida.="        <td align=\"left\" colspan =\"2\">";
    $this->salida.="        <table>";
		$this->salida.="          <td class=".$this->SetStyle("hora")." align=\"left\">HORA DE LA RESERVA</td>";
	  $this->salida.="          <td>";
		$this->salida.="          <select size=\"1\" name=\"hora$pfj\" class=\"select\" $desabilitado>";
		$this->salida.="          <option value = -1>Seleccione Hora </option>";
		if(!$_REQUEST["hora".$pfj]){
      $_REQUEST["hora".$pfj]=date('H');
		}
	  for ($j=0;$j<=23; $j++){
      if (($j >= 0) AND ($j<= 9)){
				$hora = '0'.$j;
				if($_REQUEST["hora".$pfj]==$hora){
				  $this->salida.="    <option selected value = \"$hora\">0$j</option>";
				}else{
				  $this->salida.="    <option value = \"$hora\">0$j</option>";
				}
			}else{
			  if($_REQUEST["hora".$pfj]==$j){
					$this->salida.="    <option selected value = $j>$j</option>";
				}else{
					$this->salida.="    <option value = $j>$j</option>";
				}
			}
    }
    $this->salida.="          </select>";
    $this->salida.="          </td>";
		$this->salida.="          <td>";
    $this->salida.="          <select size=\"1\"  name=\"minutos$pfj\" class=\"select\" $desabilitado>";
	  $this->salida.="          <option value = -1>Seleccione Minutos</option>";
		if(!$_REQUEST["minutos".$pfj]){
      $_REQUEST["minutos".$pfj]=date('i');
		}
		for ($j=0;$j<=59; $j++){
			if(($j >= 0) AND ($j<= 9)){
				$min = '0'.$j;
				if($_REQUEST["minutos".$pfj]==$min){
					$this->salida.="    <option selected value = \"$min\" >0$j</option>";
				}else{
					$this->salida.="    <option value=\"$min\">0$j</option>";
				}
			}else{
			  if($_REQUEST["minutos".$pfj]==$j){
					$this->salida.="    <option selected value=$j>$j</option>";
				}else{
					$this->salida.="    <option value=$j>$j</option>";
				}
			}
    }
    $this->salida.="          </select>";
    $this->salida.="          </td>";
    $this->salida.="        </tr>";
		$this->salida.="        </table>";
    $this->salida.="        </tr>";
		if($this->SexodePaciente()== 'F'){
      $this->salida.="      <tr class = \"hc_table_submodulo_list_title\">";
		  $this->salida.="      <td align = \"left\" colspan = \"4\">GESTACIONES</td>";
      $this->salida.="      </tr>";
      $this->salida.="      <tr class = \"modulo_list_claro\">";
		  $this->salida.="      <td align = \"left\" class=\"label\" colspan = \"1\">EMBARAZOS PREVIOS</td>";
		  if($_REQUEST["embarazos_previos".$pfj]){
        $this->salida.="    <td align=\"left\" colspan=\"1\">Si<input type=\"radio\"  name=\"embarazos_previos$pfj\" value=\"1\" checked $desabilitado></td>";
        $this->salida.="    <td align=\"left\" colspan=\"2\">No<input type=\"radio\"  name=\"embarazos_previos$pfj\" value=\"0\" $desabilitado></td>";
      }else{
        $this->salida.="    <td align=\"left\" colspan=\"1\">Si<input type=\"radio\"  name=\"embarazos_previos$pfj\" value=\"1\" $desabilitado></td>";
        $this->salida.="    <td align=\"left\" colspan=\"2\">No<input type=\"radio\"  name=\"embarazos_previos$pfj\" value=\"0\" checked $desabilitado></td>";
		  }
			$this->salida.="     <tr class = \"modulo_list_claro\">";
			$this->salida.="     <td align = \"left\" class=\"label\">FECHA ULTIMO EMBARAZO (dd-mm-aaa)</td>";
			$this->salida.="     <td align = \"left\" colspan = \"3\"><input type = \"text\" value = \"".$_REQUEST["fecha_ultimo_embarazo".$pfj]."\" name = \"fecha_ultimo_embarazo$pfj\" class =\"input-text\" $desabilitado1></td>";
			$this->salida.="     </tr>";

      $this->salida.="      </tr>";
			$this->salida.="      <tr class = \"modulo_list_claro\">";
			$this->salida.="      <td align = \"left\" colspan = \"1\" class=\"label\">EN GESTACION</td>";
      if($_REQUEST["estado_gestacion".$pfj] == '1'){
        $this->salida.="    <td align = left colspan =\"1\">Si<input type = \"radio\"  name = \"estado_gestacion$pfj\" value = \"1\" checked $desabilitado></td>";
        $this->salida.="    <td align = left colspan =\"2\">No<input type = \"radio\"  name = \"estado_gestacion$pfj\" value = \"0\" $desabilitado></td>";
			}else{
        $this->salida.="    <td align = \"left\" colspan = \"1\">Si<input type = \"radio\"  name = \"estado_gestacion$pfj\" value = \"1\" $desabilitado></td>";
        $this->salida.="    <td align = \"left\" colspan = \"2\">No<input type = \"radio\"  name = \"estado_gestacion$pfj\" value = \"0\" checked $desabilitado></td>";
		  }
       $this->salida.="     </tr>";
		}
		$this->salida.="        <tr class=\"modulo_list_claro\">";
		$this->salida.="        <td align=\"left\" class=\"label\">MOTIVO DE LA RESERVA</td>";
    $this->salida.="        <td align=\"left\" colspan=\"3\"><textarea style=\"width:100%\" name=\"motivo_reserva$pfj\" class=\"textarea\" rows=\"3\" cols=\"60\" $desabilitado1>".$_REQUEST["motivo_reserva".$pfj]."</textarea></td>";
    $this->salida.="        </tr>";
		$this->salida.="        <tr>";
    /*$this->salida.="        <tr class=\"modulo_list_claro\">";
		$this->salida.="        <td align=\"left\" class=\"label\">CONFIRMAR RESERVA COMPONENTES</td>";
		if($_REQUEST["confirmarR".$pfj]){
		  $check='checked';
		}
		$this->salida.="        <td align=\"left\" colspan=\"3\"><input type=\"checkbox\" name=\"confirmarR$pfj\" value=\"1\" $desabilitado $check></td>";
    $this->salida.="        </tr>";
		*/
		$this->salida.="       <tr class = \"hc_table_submodulo_list_title\">";
		/*$servicios=$this->OtrosServiciosSolicitud();
		if($servicios){
		$this->salida.="       <td align = \"left\" colspan = \"4\">OTROS SERVICIOS</td>";
		$this->salida.="       </tr>";
		$this->salida.="       <tr class=\"modulo_list_claro\">";
    $this->salida.="       <td align = \"center\" colspan = \"4\">";
    $this->salida.="       <table width=\"95%\" border=\"0\" align=\"center\">";
		for($i=0;$i<sizeof($servicios);$i++){
    $this->salida.="       <tr class=\"modulo_list_oscuro\">";
		$che='';
		if(in_array($servicios[$i]['cargo'],$_REQUEST['seleccion'.$pfj])){
      $che='checked';
		}
    $this->salida.="       <td><input type=\"checkbox\" name=\"seleccion".$pfj."[]\" value=\"".$servicios[$i]['cargo']."\" $che></td>";
		$this->salida.="       <td>".$servicios[$i]['descripcion']."</td>";
		$this->salida.="       </tr>";
		}
    $this->salida.="       </table>";
		$this->salida.="       </td>";
		$this->salida.="       </tr>";
		}*/
		$this->salida.="        <td colspan=\"5\" align=\"center\"><input type=\"submit\" value=\"INSERTAR\" name=\"Guardar$pfj\" class=\"input-submit\"></td>";
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
		$this->salida .="    <td colspan=\"3\" align=\"left\"><input type=\"text\" readonly class=\"input-text\" size=\"10\" maxlength=\"10\" value=\"".$_REQUEST["fecha_examen".$pfj]."\" name=\"fecha_examen$pfj\" onBlur=\"IsValidDate(this,'YYYY-MM-DD')\" onKeyUp=\"setDate(this,'YYYY-MM-DD','es')\">
		".ReturnOpenCalendario('forma','fecha_examen'.$pfj,'-')."</td>" ;
    $this->salida.="     </tr>";
		$this->salida .="    <tr class = \"modulo_list_claro\">";
		$this->salida.="     <td class=\"".$this->SetStyle("laboratorio")."\" align=\"left\">LABORATORIO</td>";
    $this->salida.="     <td colspan=\"3\" align=\"left\"><input type=\"text\" name=\"laboratorio$pfj\" value=\"".$_REQUEST["laboratorio".$pfj]."\" size=\"40\" class=\"input-submit\"></td>";
    $this->salida.="     </tr>";
    $this->salida .="    <tr class = \"modulo_list_claro\">";
		$this->salida .= "   <td class=\"".$this->SetStyle("bacteriologo")."\">PROFESIONAL</td>";
		$this->salida .= "   <td colspan=\"3\"><select name=\"bacteriologo$pfj\" class=\"select\">";
		$bacteriologos=$this->TotalBacteriologos();
		$this->BuscarProfesionlesEspecialistas($bacteriologos,'False',$_REQUEST["bacteriologo".$pfj]);
		$this->salida .= "    </select></td>";
    $this->salida.="     </tr>";
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

/**
* frmConsulta - Esta es la funcion que muestra la consulta de los registros insertados en la base de datos
*
* @return boolean
*/

//pendiente cuadrarlo
	function frmConsulta()
	{ $pfj=$this->frmPrefijo;
		$vectorTot=$this->ConsultaReservaSangre();
    if($vectorTot){
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\" class=\"hc_table_submodulo_list\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"6\">RESERVAS REALIZADAS</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="<td width=\"13%\" nowrap>No. SOLICITUD<BR>FECHA</td>";
			$this->salida.="<td width=\"8%\" nowrap>NIVEL<br>URGENCIA</td>";
			$this->salida.="<td width=\"8%\" nowrap>AOB / RH<BR>PACIENTE</td>";
			$this->salida.="<td width=\"18%\" nowrap>COMPONENTES</td>";
			$this->salida.="<td width=\"9%\" nowrap>CANTIDAD<BR>SOLICITADA</td>";
			$this->salida.="<td width=\"9%\" nowrap>CANTIDAD<BR>PENDIENTE</td>";
			//$this->salida.="<td width=\"9%\" nowrap>CANTIDAD<BR>CONFIRMAR</td>";
			//$this->salida.="<td width=\"9%\" nowrap>CONFIRMAR</td>";
			//$this->salida.="<td width=\"17%\" nowrap>RECEPCION<br>BOLSAS</td>";
			$this->salida.="</tr>";
			$solicitudAnt=-1;
			foreach($vectorTot as $solicitud=>$arreglo){
			  foreach($arreglo as $componente=>$vector){
					if($k % 2){$estilo='modulo_list_claro';$estilo1='modulo_list_oscuro';}else{$estilo='modulo_list_oscuro';$estilo1='modulo_list_claro';}
					if($solicitud!=$solicitudAnt){
						$this->salida.="<tr class=\"modulo_list_oscuro\">";
						$this->salida.="  <td align=\"center\" rowspan=\"".sizeof($arreglo)."\">- ".$vector['solicitud_reserva_sangre_id']." -<BR>";
						(list($fecha,$hora)=explode(' ',$vector[fecha_hora_reserva]));
						(list($ano,$mes,$dia)=explode('-',$fecha));
						(list($hh,$mm)=explode(':',$hora));
						$FechaConver=mktime($hh,$mm,0,$mes,$dia,$ano);
						$this->salida.="  ".strftime("%b %d de %Y %H:%M",$FechaConver)."</td>";
						if ($vector[sw_urgencia] == '1'){
							$this->salida.="  <td rowspan=\"".sizeof($arreglo)."\">ALTO</td>";
						}else{
							$this->salida.="  <td rowspan=\"".sizeof($arreglo)."\">NORMAL</td>";
						}
						if($vector[grupo_sanguineo] && $vector[rh]){
							if($vector[rh]=='+'){
								$this->salida.="  <td rowspan=\"".sizeof($arreglo)."\">".$vector[grupo_sanguineo]."&nbsp&nbsp;/&nbsp&nbsp;<label class=\"label\">POSITIVO</label></td>";
							}else{
								$this->salida.="  <td rowspan=\"".sizeof($arreglo)."\">".$vector[grupo_sanguineo]."&nbsp&nbsp;/&nbsp&nbsp;<label class=\"label_error\">NEGATIVO</label></td>";
							}
						}else{
							$this->salida.="  <td rowspan=\"".sizeof($arreglo)."\">SIN REGISTRO</td>";
						}
						$this->salida.="<td>".$vector[componente]."</td>";
						$this->salida.="<td>".$vector[cantidad_componente]."</td>";
						if(($vector[cantidad_componente]-$vector[confirmadas])>0){
							$this->salida.="<td>".($vector[cantidad_componente]-$vector[confirmadas])."</td>";
							/*if($_REQUEST[$vector['tipo_componente_id']."||//".$vector['solicitud_reserva_sangre_id']] && array_key_exists($vector['tipo_componente_id']."||//".$vector['solicitud_reserva_sangre_id'],$_REQUEST['Solicitar'.$pfj])){
                $this->salida.="<td align=\"center\"><input type=\"text\" class=\"input-text\" name=\"".$vector['tipo_componente_id']."||//".$vector['solicitud_reserva_sangre_id']."$pfj\" size=\"2\" maxlength=\"2\" value=\"".$_REQUEST[$vector['tipo_componente_id']."||//".$vector['solicitud_reserva_sangre_id'].$pfj]."\"></td>";
							}else{
							  $this->salida.="<td align=\"center\"><input type=\"text\" class=\"input-text\" name=\"".$vector['tipo_componente_id']."||//".$vector['solicitud_reserva_sangre_id']."$pfj\" size=\"2\" maxlength=\"2\" value=\"".($vector[cantidad_componente]-$vector[confirmadas])."\"></td>";
							}
              $this->salida.="<input type=\"hidden\" name=\"ValorPendiente".$pfj."[".$vector['tipo_componente_id']."||//".$vector['solicitud_reserva_sangre_id']."]\" value=\"".($vector[cantidad_componente]-$vector[confirmadas])."\">";
							if(array_key_exists($vector['tipo_componente_id']."||//".$vector['solicitud_reserva_sangre_id'],$_REQUEST['Solicitar'.$pfj])){
                $che='checked';
							}else{
                $che='';
							}
							$this->salida.="<td align=\"center\"><input $che type=\"checkbox\" name=\"Solicitar".$pfj."[".$vector['tipo_componente_id']."||//".$vector['solicitud_reserva_sangre_id']."]\"></td>";
              */
						}else{
              $this->salida.="<td class=\"label\" colspan=\"3\" align=\"center\">CONFIRMADAS</td>";
						}
						/*if($vector[sw_estado]==1){
							$this->salida.="<td>SIN CONFIRMAR</td>";
						}else{
							$this->salida.="<td>CONFIRMADO</td>";
						}*/
						/*$this->salida.="<td>";
						$componentes=$this->UnidadesPatinaje($vector['solicitud_reserva_sangre_id'],$vector['tipo_componente_id']);
						if($componentes){
            $this->salida.="<table  align=\"center\" border=\"0\"  width=\"95%\">";
						for($i=0;$i<sizeof($componentes);$i++){
						  $this->salida.="<tr class=\"$estilo1\">";
							if($componentes[$i]['numero_alicuota']==0){$alic='PRINCIPAL';}else{$alic=$componentes[$i]['numero_alicuota'];}
								$this->salida.="<td class=\"label\" width=\"70\">- ".$componentes[$i]['bolsa_id']." -<BR>".$alic."</td>";
								if($componentes[$i]['recibido']==1){
									$this->salida.="<td width=\"30%\" class=\"label\">RECIBIDA</td>";
								}else{
									$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$pfj=>'ResistroRecepcionBolsa','IngresoId'.$pfj=>$componentes[$i]['ingreso_bolsa_id'],'alicuota'.$pfj=>$componentes[$i]['numero_alicuota'],'bolsaId'.$pfj=>$componentes[$i]['bolsa_id']));
									$this->salida.="<td width=\"30%\"><a href=\"$accion\"><img title=\"Recibir Bolsa\" border=\"0\" src=\"".GetThemePath()."/images/entregabolsa.png\"></a></td>";
								}
							  $this->salida.="</tr>";
						  }
						  $this->salida.="</table>";
						}
						$this->salida.="</td>";
            */
						$this->salida.="</tr>";
						$solicitudAnt=$solicitud;
						$k=1;
					}else{
            $this->salida.="<tr class=\"$estilo\">";
						$this->salida.="<td>".$vector[componente]."</td>";
						$this->salida.="<td>".$vector[cantidad_componente]."</td>";
            if(($vector[cantidad_componente]-$vector[confirmadas])>0){
							/*$this->salida.="<td>".($vector[cantidad_componente]-$vector[confirmadas])."</td>";
							if($_REQUEST[$vector['tipo_componente_id']."||//".$vector['solicitud_reserva_sangre_id']] && array_key_exists($vector['tipo_componente_id']."||//".$vector['solicitud_reserva_sangre_id'],$_REQUEST['Solicitar'])){
                $this->salida.="<td align=\"center\"><input type=\"text\" class=\"input-text\" name=\"".$vector['tipo_componente_id']."||//".$vector['solicitud_reserva_sangre_id']."$pfj\" size=\"2\" maxlength=\"2\" value=\"".$_REQUEST[$vector['tipo_componente_id']."||//".$vector['solicitud_reserva_sangre_id'].$pfj]."\"></td>";
							}else{
							  $this->salida.="<td align=\"center\"><input type=\"text\" class=\"input-text\" name=\"".$vector['tipo_componente_id']."||//".$vector['solicitud_reserva_sangre_id']."$pfj\" size=\"2\" maxlength=\"2\" value=\"".($vector[cantidad_componente]-$vector[confirmadas])."\"></td>";
							}
              $this->salida.="<input type=\"hidden\" name=\"ValorPendiente".$pfj."[".$vector['tipo_componente_id']."||//".$vector['solicitud_reserva_sangre_id']."]\" value=\"".($vector[cantidad_componente]-$vector[confirmadas])."\">";

							if(array_key_exists($vector['tipo_componente_id']."||//".$vector['solicitud_reserva_sangre_id'],$_REQUEST['Solicitar'.$pfj])){
                $che='checked';
							}else{
                $che='';
							}
							$this->salida.="<td align=\"center\"><input type=\"checkbox\" name=\"Solicitar".$pfj."[".$vector['tipo_componente_id']."||//".$vector['solicitud_reserva_sangre_id']."]\" $che></td>";
              */
						}else{
              $this->salida.="<td class=\"label\" colspan=\"3\" align=\"center\">CONFIRMADAS</td>";
						}
						/*if($vector[sw_estado]==1){
							$this->salida.="<td>SIN CONFIRMAR</td>";
						}else{
							$this->salida.="<td>CONFIRMADO</td>";
						}*/
						/*$this->salida.="<td>";
						$componentes=$this->UnidadesPatinaje($vector['solicitud_reserva_sangre_id'],$vector['tipo_componente_id']);
						if($componentes){
            $this->salida.="<table  align=\"center\" border=\"0\"  width=\"95%\">";
						for($i=0;$i<sizeof($componentes);$i++){
						  $this->salida.="<tr class=\"$estilo1\">";
							if($componentes[$i]['numero_alicuota']==0){$alic='PRINCIPAL';}else{$alic=$componentes[$i]['numero_alicuota'];}
								$this->salida.="<td class=\"label\" width=\"70\">- ".$componentes[$i]['bolsa_id']." -<BR>".$alic."</td>";
								if($componentes[$i]['recibido']==1){
									$this->salida.="<td width=\"30%\" class=\"label\">RECIBIDA</td>";
								}else{
									$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$pfj=>'ResistroRecepcionBolsa','IngresoId'.$pfj=>$componentes[$i]['ingreso_bolsa_id'],'alicuota'.$pfj=>$componentes[$i]['numero_alicuota'],'bolsaId'.$pfj=>$componentes[$i]['bolsa_id']));
									$this->salida.="<td width=\"30%\"><a href=\"$accion\"><img title=\"Recibir Bolsa\" border=\"0\" src=\"".GetThemePath()."/images/entregabolsa.png\"></a></td>";
								}
							  $this->salida.="</tr>";
						  }
						  $this->salida.="</table>";
						}
						$this->salida.="</td>";
            */
            $this->salida.="</tr>";
						$k++;
					}
        }
			}
			/*$this->salida.="<tr class=\"$estilo\">";
      $this->salida.="<td colspan=\"8\" align=\"right\"><input type=\"submit\" class=\"input-submit\" name=\"confirmar$pfj\" value=\"CONFIRMAR\"></td>";
      $this->salida.="<td>&nbsp;</td>";*/
			$this->salida.="</table>";
		}
		return true;
	}

/**
* FormaRegistroRecepcionBolsa - Esta es la funcion que muestra la forma para recibir un componente sanguineo
*
* @return boolean
* @param $IngresoId - numero unico del ingreso del paciente
* @param $alicuota - numero de la alicuota de la bolsa
* @param $bolsaId - numero que identifica a la bolsa
*/

	function FormaRegistroRecepcionBolsa($IngresoId,$alicuota,$bolsaId){
    $pfj=$this->frmPrefijo;
		$this->salida= ThemeAbrirTablaSubModulo('RECEPCION COMPONENTE SANGUINEO');
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$pfj=>'ResistroRecepcionBolsa',
		'IngresoId'.$pfj=>$IngresoId,'alicuota'.$pfj=>$alicuota,'bolsaId'.$pfj=>$bolsaId));
		$this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
    $this->salida .= "  <table border=\"0\" width=\"50%\" align=\"center\" class=\"normal_10\">";
		$this->salida .= "  <tr><td align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "  </td><tr>";
		if($alicuota==0){$ali='PRINCIPAL';}else{$ali=$alicuota;	}
    $this->salida.="    <tr class=\"modulo_table_title\"><td align=\"center\">BOLSA No. $bolsaId <BR> ALICUOTA: $ali</td></tr>";
    $this->salida.="    <tr class = \"modulo_list_claro\"><td><label class=\"label\">OBSERVACIONES</label><BR>";
		$this->salida .= "  <textarea class =\"textarea\" rows =\"5\" cols =\"80\"  name=\"observaciones$pfj\"></textarea>";
		$this->salida.="    </td></tr>";
		$this->salida .= "  </table>";
    $this->salida .= "   <table border=\"0\" width=\"50%\" align=\"center\" class=\"normal_10\">";
    $this->salida.="     <tr><td align=\"center\">";
		$this->salida.="     <input type=\"submit\" name=\"guardarDatos$pfj\" value=\"GUARDAR\" class=\"input-submit\">";
		$this->salida.="     <input type=\"submit\" name=\"cancelarDatos$pfj\" value=\"VOLVER\" class=\"input-submit\">";
		$this->salida.="     </td></tr>";
    $this->salida.="     </table>";
    $this->salida.="     </form>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}

/**
* frmHistoria -
*
* @return boolean
*/


	function frmHistoria()
	{
    $vectorTot=$this->ConsultaReservaSangre();
    if($vectorTot){
      $salida.="<table  align=\"center\" border=\"1\"  width=\"100%\">";
			$salida.="<tr class=\"modulo_table_title\">";
			$salida.="<td align=\"center\" colspan=\"6\">RESERVAS REALIZADAS</td>";
			$salida.="</tr>";
			$salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$salida.="<td width=\"13%\" nowrap>No. SOLICITUD<BR>FECHA</td>";
			$salida.="<td width=\"8%\" nowrap>NIVEL<br>URGENCIA</td>";
			$salida.="<td width=\"8%\" nowrap>AOB / RH<BR>PACIENTE</td>";
			$salida.="<td width=\"18%\" nowrap>COMPONENTES</td>";
			$salida.="<td width=\"9%\" nowrap>CANTIDAD<BR>SOLICITADA</td>";
			$salida.="<td width=\"9%\" nowrap>CANTIDAD<BR>PENDIENTE</td>";
			$salida.="</tr>";
			$solicitudAnt=-1;
			foreach($vectorTot as $solicitud=>$arreglo){
			  foreach($arreglo as $componente=>$vector){
					if($k % 2){$estilo='modulo_list_claro';$estilo1='modulo_list_oscuro';}else{$estilo='modulo_list_oscuro';$estilo1='modulo_list_claro';}
					if($solicitud!=$solicitudAnt){
						$salida.="<tr class=\"modulo_list_oscuro\">";
						$salida.="<td align=\"center\" rowspan=\"".sizeof($arreglo)."\">- ".$vector['solicitud_reserva_sangre_id']." -<BR>";
						(list($fecha,$hora)=explode(' ',$vector[fecha_hora_reserva]));
						(list($ano,$mes,$dia)=explode('-',$fecha));
						(list($hh,$mm)=explode(':',$hora));
						$FechaConver=mktime($hh,$mm,0,$mes,$dia,$ano);
						$salida.="".strftime("%b %d de %Y %H:%M",$FechaConver)."</td>";
						if ($vector[sw_urgencia] == '1'){
							$salida.="<td rowspan=\"".sizeof($arreglo)."\">ALTO</td>";
						}else{
							$salida.="<td rowspan=\"".sizeof($arreglo)."\">NORMAL</td>";
						}
						if($vector[grupo_sanguineo] && $vector[rh]){
							if($vector[rh]=='+'){
								$salida.="<td rowspan=\"".sizeof($arreglo)."\">".$vector[grupo_sanguineo]."&nbsp&nbsp;/&nbsp&nbsp;<label class=\"label\">POSITIVO</label></td>";
							}else{
								$salida.="<td rowspan=\"".sizeof($arreglo)."\">".$vector[grupo_sanguineo]."&nbsp&nbsp;/&nbsp&nbsp;<label class=\"label_error\">NEGATIVO</label></td>";
							}
						}else{
							$salida.="<td rowspan=\"".sizeof($arreglo)."\">SIN REGISTRO</td>";
						}
						$salida.="<td>".$vector[componente]."</td>";
						$salida.="<td>".$vector[cantidad_componente]."</td>";
						if(($vector[cantidad_componente]-$vector[confirmadas])>0){
							$salida.="<td>".($vector[cantidad_componente]-$vector[confirmadas])."</td>";
						}else{
              $salida.="<td class=\"label\" colspan=\"3\" align=\"center\">CONFIRMADAS</td>";
						}
						$salida.="</tr>";
						$solicitudAnt=$solicitud;
						$k=1;
					}else{
            $salida.="<tr class=\"$estilo\">";
						$salida.="<td>".$vector[componente]."</td>";
						$salida.="<td>".$vector[cantidad_componente]."</td>";
            if(($vector[cantidad_componente]-$vector[confirmadas])>0){
              $salida.="<td>".($vector[cantidad_componente]-$vector[confirmadas])."</td>";
						}else{
              $salida.="<td class=\"label\" colspan=\"3\" align=\"center\">CONFIRMADAS</td>";
						}
            $salida.="</tr>";
						$k++;
					}
        }
			}
			$salida.="</table><br>";
		}
		return $salida;
	}

}

?>
