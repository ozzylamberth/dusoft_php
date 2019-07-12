<?PHP

class app_Tarifarios_Equivalencias_userclasses_HTML extends app_Tarifarios_Equivalencias_user
{
  
  function main()
  {
    $this->iniciar();
  
    return true;
  }
  //=================================================================================
  function iniciar()
  {
    $vec_tarifarios=$this->consultarTarifarios(); //Trae los tarifarios para llenar el select
    $this->mostrarFormulario($vec_tarifarios);
    
    return true;
  }
  //=======================================================================================
  function mostrarFormulario($vec)
  {
    include_once("app_modules/Tarifarios_Equivalencias/RemoteXajax/AJAX.php");
    $this->SetXajax(Array("actualizarSelect","resultado","consultarCups"));
    //,"app_modules/Tarifarios_Equivalencias/RemoteXajax/AJAX.php");
    
    $this->salida=ThemeAbrirTabla("TARIFARIOS - EQUIVALENCIAS");
    
    //*****************************Tabla 1: Tarifarios**************************************************
    $this->salida.="<form name=\"form_tarifario\"  method=\"post\">";
    
	//-------Fieldset---------------
	$this->salida.="<table border=\"0\" width=\"70%\" align=\"center\" class=\"normal_10\">";
	$this->salida.="<tr><td><fieldset><legend class=\"field\"> TARIFARIOS </legend>";
	//---------------------------
	
	$this->salida.="<table border=\"1\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
    //----------------------------------------
	
	$this->salida.="  <tr>";
    $this->salida.="    <td width=\"15%\" class=\"modulo_table_list_title\"> <b>TARIFARIO</b> </td>";
    
    $this->salida.="    <td width=\"40%\" class=\"modulo_list_oscuro\">";
    $this->salida.="      <select name=\"s_tarifarios\" class=\"select\" onChange=\"asignarTari();xajax_actualizarSelect(document.form_tarifario.s_tarifarios.value);\">";
                            
                            $this->salida.="<option value=\"-1\">>----------------------SELECCIONE-------------------------<";
                            
			    foreach($vec as $key => $vlr) //Llena el select de Tarifarios
                              $this->salida.="<option value=\"".$vlr['tarifario_id']."\">".$vlr['descripcion'];

    $this->salida.="      </select>";
    $this->salida.="    </td>";
    
    $this->salida.="    <td class=\"modulo_table_list_title\"> <b>RELACION</b> </td>"; //------------Relaciones para la consulta
    
    $this->salida.="    <td class=\"modulo_list_oscuro\">";
    $this->salida.="      <input type=\"radio\" name=\"r_relacion\" value=\"c-t\" checked onClick=\"javascript:asignarRelacion();\"> Cups - Tarifario </input>";
    $this->salida.="      <input type=\"radio\" name=\"r_relacion\" value=\"t-c\" onClick=\"javascript:asignarRelacion();\"> Tarifario - Cups </input>";
    $this->salida.="    </td>";
    $this->salida.="  </tr>";
    //--------------------------------------
    $this->salida.="  <tr>";
    
    $this->salida.="  </tr>";
    $this->salida.="</table>";
    
    /*****************************Tabla 2: ************************************************************
    Muestra el Detalle de los Tarifarios
    **************************************************************************************************/
    $this->salida.="<table width=\"100%\" border=\"1\" align=\"center\" class=\"modulo_table_list\">";
    $this->salida.="  <tr>";
    $this->salida.="    <td width=\"40%\" class=\"modulo_table_list_title\"> <b>DESCRIPCION TARIFARIO</b> </td>";
    $this->salida.="    <td class=\"modulo_list_oscuro\">";
    $this->salida.="       <div id=\"div1\">\n";
    $this->salida.="         <select name=\"s_tari_detalle\" width=\"100%\" class=\"select\">\n";
   
                              $vec_detalle=$this->consultarTarifariosDetalle($vec[0]["tarifario_id"]); //Busca los registro del primer tarifario del select
			      $lines="-------------------------";
                              $this->salida.="<option value=\"-1\">>".$lines.$lines.$lines."SELECCIONE".$lines.$lines.$lines."<</option>";
			      
                              foreach($vec_detalle as $key=> $val) //Llena
                                $this->salida.="<option value=\"".$val['cargo']."\">".substr($val['descripcion'],0,85)."</option>\n";
   
    $this->salida.="        </select>\n";
    $this->salida.="      </div>\n";
   
    $this->salida.="    </td>";
    $this->salida.="  </tr>";
    $this->salida.="</table>";
	
	//-------------Fin Fieldset-----------------------------------------------
	$this->salida.="</fieldset></td></tr>";
	$this->salida.="</table>";	
    //----------------------------------
    
    //*****************************Tabla 3: Cups********************************************************
	//-------Fieldset---------------
	$this->salida.="<table border=\"0\" width=\"70%\" align=\"center\" class=\"normal_10\">";
	$this->salida.="<tr><td><fieldset><legend class=\"field\"> CUPS </legend>";
	//---------------------------
	
    $this->salida.="<table border=\"1\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
    //--------------------------
    $this->salida.="  <tr>";
    $this->salida.="    <td width=\"25%\" class=\"modulo_table_list_title\"> <b>BUSCAR POR</b>";
    $this->salida.="    </td>";
    
    $this->salida.="    <td class=\"modulo_list_oscuro\">";
    $this->salida.="      <input type=\"radio\" name=\"r_busqueda\" value=\"c\" checked> CARGO </input>";
    $this->salida.="      <input type=\"radio\" name=\"r_busqueda\" value=\"d\"> DESCRIPCION </input>";
    $this->salida.="    </td>";
    $this->salida.="  </tr>";
    //--------------------------
    $this->salida.="  <tr>";
    $this->salida.="    <td class=\"modulo_table_list_title\"> <b>BUSCAR</b> </td>";
    
    $this->salida.="    <td class=\"modulo_list_oscuro\">";
    $this->salida.="      <input type=\"text\" name=\"t_buscar\" size=\"40%\" class=\"input-text\"> </input>";
    $this->salida.="      <input type=\"button\" name=\"b_Buscar\" value=\"BUSCAR CUP\" class=\"input-submit\" onClick=\"consultarCups();\"> </input>";
    $this->salida.="    </td>";
    $this->salida.="  </tr>";
    //--------------------------
    $this->salida.="  <tr>";
    $this->salida.="    <td class=\"modulo_table_list_title\"> <b>RESULTADOS</b>";
    $this->salida.="    </td>";
    
    $this->salida.="    <td class=\"modulo_list_oscuro\">";
    $this->salida.="    <div id=\"divCups\">";
    $this->salida.="      <select name=\"s_cups\" width=\"90%\" class=\"select\" id=\"s_cups\" onChange=\"javascript:asignarCup();\">";
    $this->salida.="        <option value=\"-1\">>".$lines."SELECCIONE".$lines."<</option>";   
    $this->salida.="      </select>";
    $this->salida.="    </div>";
    $this->salida.="    </td>";
    $this->salida.="  </tr>";
    
    $this->salida.="</table>";
	
	//-------------Fin Fieldset-----------------------------------------------
	$this->salida.="</fieldset></td></tr>";
	$this->salida.="</table>";
    $this->salida.="<br>";
    
    //*****************************Tabla: Boton Resultado************************************************************
    $this->salida.="<table align=\"center\">";
    $this->salida.="  <tr>";
    $this->salida.="    <td>";
    $this->salida.="          <tr>";
    $this->salida.="            <td>";
    $this->salida.="              <input type=\"button\" name=\"b_Resultado\" value=\"MOSTRAR RELACIONES\" class=\"input-submit\"  onClick=\"llamar();\"> </input>";
    $this->salida.="            </td>";
    $this->salida.="          </tr>";
    $this->salida.="    </td>";
	//-------------------------------
    $this->salida.="  </tr>";
    $this->salida.="</table>";
	
    $this->salida.="<br>";
    //*******************************DivResultado: muestra el resultado*************************************
    $this->salida.="<div id=\"divResult\" style=\"display:none\" >";
    $this->salida.="</div>";
 	$this->salida.="<input type=\"hidden\" name=\"h_relacion\" id=\"h_relacion\" value=\"c-t\">";
	$this->salida.="</form>"; //form_tarifario
	
	$this->salida.="<br>";
	//*******************************DivAdd: muestra la tabla con la nueva relacion para adicionar ***************
	//if($_REQUEST['h_relacion']=="c-t")
	//echo "HR: ".$_REQUEST['h_relacion'];
		$action=ModuloGetUrl('app','Tarifarios_Equivalencias','user','guardarTarifario');
		$this->salida.="<form name=\"form_adicionar\" method=\"post\" action=\"$action\">";
		$this->salida.="<div id=\"divAdd\"  style=\"display:none\" align=\"center\">";
		$this->salida.="	<table border=\"1\" width=\"60%\" class=\"modulo_table_list\">";
		$this->salida.="		<tr>";
		$this->salida.="			<td colspan=\"4\" class=\"modulo_table_list_title\"> TARIFARIO RELACION </td>";
		$this->salida.="			</td>";
		$this->salida.="		</tr>";
		$this->salida.="		<tr>";
		$this->salida.="			<td width=\"10%\" class=\"modulo_table_list_title\"> ID </td>";
		$this->salida.="			<td id=\"td_tari_id\" class=\"modulo_list_oscuro\"width=\"40%\"> <div id=\"divTariId\">  </div> </td>";
		$this->salida.="			<td class=\"modulo_table_list_title\" width=\"10%\"> CARGO </td>";
		$this->salida.="			<td id=\"td_tari_cargo\" class=\"modulo_list_oscuro\"width=\"40%\"> <div id=\"divTariCargo\">  </div> </td>";
		$this->salida.="		</tr>";
		$this->salida.="		<tr>";
		$this->salida.="			<td class=\"modulo_table_list_title\"> DESCRIPCION </td>";
		$this->salida.="			<td id=\"td_tari_desc\" class=\"modulo_list_oscuro\" colspan=\"3\"> <div id=\"divTariDesc\">  </div> </td>";
		$this->salida.="		</tr>";
		$this->salida.="		<tr>";
		$this->salida.="			<td colspan=\"4\" align=\"center\" class=\"modulo_list_oscuro\">";
		$this->salida.="				<input type=\"submit\" name=\"b_guardar\" id=\"b_guardar\" value=\"Guardar Relacion\" class=\"input-submit\">";
		$this->salida.="			</td>";
		$this->salida.="		</tr>";
		$this->salida.="	</table>";
		$this->salida.="</div>";
		$this->salida.="<input type=\"hidden\" name=\"h_tar_id\" id=\"h_tar_id\" >"; //Valor del Tarifario ID
		$this->salida.="<input type=\"hidden\" name=\"h_cargo\" id=\"h_cargo\" >"; //Valor del Cargo
		$this->salida.="<input type=\"hidden\" name=\"h_cargo_base\" id=\"h_cargo_base\" >"; //Valor del Cargo Base
		$this->salida.="</form>"; //form_adicionar
		//****************Div2*********************************************************
		$action2=ModuloGetUrl('app','Tarifarios_Equivalencias','user','guardarCup');
		$this->salida.="<form name=\"form_adicionar2\" method=\"post\" action=\"$action2\">";
		$this->salida.="<div id=\"divAdd2\"  style=\"display:none\" align=\"center\">";
		$this->salida.="	<table border=\"1\" width=\"60%\" class=\"modulo_table_list\">";
		$this->salida.="		<tr>";
		$this->salida.="			<td colspan=\"4\" class=\"modulo_table_list_title\"> CUPS RELACION </td> </td>";
		$this->salida.="		</tr>";
		$this->salida.="		<tr>";
		$this->salida.="			<td width=\"10%\" class=\"modulo_table_list_title\"> CARGO </td>";
		$this->salida.="			<td id=\"td_cargo\" class=\"modulo_list_oscuro\"width=\"40%\"> <div id=\"divCupCargo\">  </div> </td>";
		$this->salida.="			<td class=\"modulo_table_list_title\" width=\"10%\"> DESCRIPCION </td>";
		$this->salida.="			<td id=\"td_desc\" class=\"modulo_list_oscuro\"width=\"40%\"> <div id=\"divCupDesc\">  </div> </td>";
		//$this->salida.="		</tr>";
		//$this->salida.="		<tr>";
		//$this->salida.="			<td class=\"modulo_table_list_title\"> DESCRIPCION </td>";
		//$this->salida.="			<td id=\"td_tari_desc\" class=\"modulo_list_oscuro\" colspan=\"3\"> <div id=\"divTariDesc\">  </div> </td>";
		//$this->salida.="		</tr>";
		$this->salida.="		<tr>";
		$this->salida.="			<td colspan=\"4\" align=\"center\" class=\"modulo_list_oscuro\">";
		$this->salida.="				<input type=\"submit\" name=\"b_guardar2\" id=\"b_guardar2\" value=\"Guardar Relacion\" class=\"input-submit\">";
		$this->salida.="			</td>";
		$this->salida.="		</tr>";
		$this->salida.="	</table>";
		$this->salida.="</div>";
		$this->salida.="<input type=\"hidden\" name=\"h_cup_cargo\" id=\"h_cup_cargo\" >";
		$this->salida.="<input type=\"hidden\" name=\"h_cup_desc\" id=\"h_cup_desc\" >";
		$this->salida.="<input type=\"hidden\" name=\"h_tar_id2\" id=\"h_tar_id2\" >";
		$this->salida.="<input type=\"hidden\" name=\"h_cargo2\" id=\"h_cargo2\" >";
		$this->salida.="</form>"; //form_adicionar2
		//*****************************************************************************
	
    //************************************SCRIPTS***************************************************
    $this->salida .= "<script>\n";
    $this->salida .= "	function llamar()\n";
    $this->salida .= "	{ var relacion; \n";
    $this->salida .= "    for(i=0; i<form_tarifario.r_relacion.length; i++)  \n";
    $this->salida .= "    { if(form_tarifario.r_relacion[i].checked) \n";
    $this->salida .= "      { relacion=form_tarifario.r_relacion[i].value;  \n";
    $this->salida .= "        form_tarifario.h_relacion.value=relacion; break;\n";
    $this->salida .= "      }\n";
    $this->salida .= "    } \n";

    $this->salida .= "	  var tarifario_id=      document.form_tarifario.s_tarifarios.value;\n";
    $this->salida .= "	  var cup=               document.form_tarifario.s_cups.value;\n";
    $this->salida .= "	  var tari_detalle_cargo=document.form_tarifario.s_tari_detalle.value;\n";
    $this->salida .= "	  xajax_resultado(tarifario_id,relacion,cup,tari_detalle_cargo);\n";
    $this->salida .= "	}\n";
 
    $this->salida .= "	function consultarCups()\n";
    $this->salida .= "  { var busqueda;\n";
    $this->salida .= "    for(i=0; i<form_tarifario.r_busqueda.length; i++)\n";
    $this->salida .= "	  { if(form_tarifario.r_busqueda[i].checked)\n";
    $this->salida .= "	    {  busqueda=form_tarifario.r_busqueda[i].value;  \n";
	$this->salida .= "	       break; \n";
	$this->salida .= "	    }\n";
    $this->salida .= "	  }\n";
    $this->salida .= "    xajax_consultarCups(busqueda,document.form_tarifario.t_buscar.value);\n";
	$this->salida .= "	  asignarCup();\n";
    $this->salida .= "  }\n";
	$this->salida .= "	function borrarCupTar(tar_cargo)\n";
	$this->salida .= "	{ \n";
	$this->salida .= "	  var cup=document.form_tarifario.s_cups.value;\n";
	$this->salida .= "	  var tar_id=document.form_tarifario.s_tarifarios.value;\n";
	$this->salida .= "	  alert('Tar_Cargo: '+tar_cargo+'   Cup: '+cup+'   Tar Id: '+tar_id);\n";
	$this->salida .= "	}\n";
	$this->salida .= "	function borrarTarCup(te_cargo)\n";
	$this->salida .= "	{ \n";
	$this->salida .= "	  var cup=document.form_tarifario.s_cups.value;\n";
	$this->salida .= "	  var tar_id=document.form_tarifario.s_tarifarios.value;\n";
	$this->salida .= "	  alert('Cargo Base: '+cup+'   Tar Id: '+tar_id+'  Tar_Cargo: '+te_cargo);\n";
	$this->salida .= "	}\n";
	$this->salida .= "	function abrirVentana(url)\n";
	$this->salida .= "	{ \n";
	$this->salida .= "	  window.open(url,'Tarifarios','toolbar=no,width=700,height=400,resizable=no,scrollbars=yes');\n";
	$this->salida .= "	}\n";
	$this->salida .= "	function asignarCup()\n";
	$this->salida .= "	{ \n";
	$this->salida .= "	  document.form_adicionar.h_cargo_base.value=document.form_tarifario.s_cups.value;\n";
	$this->salida .= "	  document.form_adicionar2.h_cup_cargo.value=document.form_tarifario.s_cups.value;\n";
	$this->salida .= "	}\n";
	$this->salida .= "	function asignarRelacion()\n";
	$this->salida .= "	{ var relacion;\n";
    $this->salida .= "    for(i=0; i<form_tarifario.r_relacion.length; i++)  \n";
    $this->salida .= "    { if(form_tarifario.r_relacion[i].checked) \n";
    $this->salida .= "      { relacion=form_tarifario.r_relacion[i].value;  \n";
    $this->salida .= "        form_tarifario.h_relacion.value=relacion; break;\n";
    $this->salida .= "      }\n";
    $this->salida .= "    } \n";
	$this->salida .= "	}\n";
	$this->salida .= "	function asignarTari()\n";
	$this->salida .= "	{ \n";
	$this->salida .= "	  document.form_adicionar2.h_tar_id2.value=document.form_tarifario.s_tarifarios.value;\n";
	$this->salida .= "	  document.form_adicionar2.h_cargo2.value=document.form_tarifario;\n";
	$this->salida .= "	}\n";
    $this->salida .= "</script>\n";
   
    $this->salida.=ThemeCerrarTabla();
    
    return true;
  }
  
  //=======================================================================================
  function guardarTarifario()
  {
  	echo "Guardando Tari....<br>";
	
	$this->guardarRelacion($_REQUEST["h_tar_id"], $_REQUEST["h_cargo"], $_REQUEST["h_cargo_base"]);
	/*
	echo "Tar ID-->".$_REQUEST["h_tar_id"];
	echo "<br>Cargo--->".$_REQUEST["h_cargo"];
	echo "<br>Base---->".$_REQUEST["h_cargo_base"];
	*/
	return true;
  }
  
  //=======================================================================================
  function guardarCup()
  {
  	echo "Guardando Cuppp....<br>";
	$this->guardarRelacion($_REQUEST["h_tar_id2"], $_REQUEST["h_cargo2"], $_REQUEST["h_cup_cargo"]);
	//echo "Cup Cargo-->".$_REQUEST["h_cup_cargo"];
	//echo "<br>Cup Desc--->".$_REQUEST["h_cup_desc"];
	//echo "<br>Tari---->".$_REQUEST["h_tar_id2"];
  	return true;
  }
  
  //=======================================================================================
  function FormaVentanaCups()
  {
	$this->salida="<br><hr>CUPS";
	$this->mostrarPaginadorCups();
	return true;
  }
  
  //=======================================================================================
  function FormaVentanaTarifarios()
  {
	$this->salida="<br><hr>TARIFARIOS";
	$this->mostrarPaginadorTarifarios();
	
	return true;
  }
  
  //==============================================================================
  function mostrarPaginadorTarifarios() //Para la ventana emergente de los tarifarios
	{
		//include_once("app_modules/Tarifarios_Equivalencias/RemoteXajax/AJAX.php");
    	//$this->SetXajax(Array("mostrarTari"));
		
		$vec=$this->consultarTarifariosDetalle2(); //Para la ventana de los tarifarios

		if(count($vec)<=0)
			$this->salida.="<p align=\"center\" class=\"label\"> <font color=\"red\"> NO HAY REGISTROS </font> </p>";
		else
		{
			$this->salida.="<table border=\"0\" align=\"center\" width=\"90%\" class=\"modulo_table_list\">";
			//--------------------------
			$this->salida.="  <tr>";
			
			$this->salida.="    <td align=\"center\" class=\"modulo_table_list_title\"> <b>TARIFARIO ID</b> </td>";
			$this->salida.="    <td align=\"center\" class=\"modulo_table_list_title\"> <b>CARGO</b> </td>";
			$this->salida.="    <td align=\"center\" class=\"modulo_table_list_title\"> <b>DESCRIPCION</b> </td>";
			
			$this->salida.="  </tr>";
			
			$color="";
			//--------------------------------------------
			foreach($vec as $key=> $val)
			{
				if($key%2==0)
				  $color="\"modulo_list_claro\"";
				else
				  $color="modulo_list_oscuro";
				
				$this->salida.="<tr>";
				$this->salida.="	<td class=".$color.">".$val["tarifario_id"]."</td>";
				$this->salida.="	<td class=".$color.">".$val["cargo"]."</td>";
				$this->salida.="	<td class=".$color.">  <a href=\"javascript:tablaTari('".$val['tarifario_id']."','".$val["cargo"]."','".$val['descripcion']."');\">".$val["descripcion"]." </a> </td>";
				$this->salida.="</tr>";
			}
			
			$this->salida.="</table>";
			
			//----------------Paginador---------------------------------------------------------------------
			$action=ModuloGetUrl("app","Tarifarios_Equivalencias","user","FormaVentanaTarifarios");
			IncludeClass('ClaseHTML');
			$paginador = new ClaseHTML();
			
			$this->salida.=$paginador->ObtenerPaginado($this->cont,$this->paginaActual,$action);
		}
		
		//************************************SCRIPTS***************************************************
		$this->salida .="<script>\n";
		$this->salida .="	function tablaTari(id,cargo,desc)\n"; //Muestra la tabla de la relacion a guardar
		$this->salida .="	{ window.opener.document.getElementById('divAdd2').style.display='none';\n";
		$this->salida .=" 	  window.opener.document.getElementById('divAdd').style.display='';\n";
		$this->salida .=" 	  window.opener.document.getElementById('divTariId').innerHTML=id;\n";
		$this->salida .=" 	  window.opener.document.getElementById('divTariCargo').innerHTML=cargo;\n";
		$this->salida .=" 	  window.opener.document.getElementById('divTariDesc').innerHTML=desc;\n";
		$this->salida .=" 	  window.opener.document.getElementById('h_tar_id').value=id;\n";
		$this->salida .=" 	  window.opener.document.getElementById('h_cargo').value=cargo;\n";
		$this->salida .=" 	  window.close();";
		$this->salida .="	} \n";
		$this->salida .="</script>\n";
		//document.form_tarifario.s_cups.value
		return true;
	}
	
	//==============================================================================
	function mostrarPaginadorCups() //Para la ventana emergente de los Cups
	{
		//include_once("app_modules/Tarifarios_Equivalencias/RemoteXajax/AJAX.php");
		//$this->SetXajax(Array("mostrarTari"));
		$vec=$this->consultaCups2();
	
		if(count($vec)<=0)
			$this->salida.="<p align=\"center\" class=\"label\"> <font color=\"red\"> NO HAY REGISTROS </font> </p>";
		else
		{
			$this->salida.="<table border=\"0\" align=\"center\" width=\"90%\" class=\"modulo_table_list\">";
			//--------------------------
			$this->salida.="  <tr>";
			$this->salida.="    <td align=\"center\" class=\"modulo_table_list_title\"> <b>CARGO</b> </td>";
			$this->salida.="    <td align=\"center\" class=\"modulo_table_list_title\"> <b>DESCRIPCION</b> </td>";
			$this->salida.="  </tr>";
			
			$color="";
			//--------------------------------------------
			foreach($vec as $key=> $val)
			{
				if($key%2==0)
					$color="\"modulo_list_claro\"";
				else
					$color="modulo_list_oscuro";
				
				$this->salida.="<tr>";
				$this->salida.="	<td class=".$color.">".$val["cargo"]."</td>";
				$this->salida.="	<td class=".$color.">  <a href=\"javascript:tablaCup('".$val['cargo']."','".$val['descripcion']."');\">".$val["descripcion"]." </a> </td>";
				$this->salida.="</tr>";
			}
			
			$this->salida.="</table>";
			
			//----------------Paginador---------------------------------------------------------------------
			$action=ModuloGetUrl("app","Tarifarios_Equivalencias","user","FormaVentanaCups");
			IncludeClass('ClaseHTML');
			$paginador = new ClaseHTML();
			
			$this->salida.=$paginador->ObtenerPaginado($this->cont,$this->paginaActual,$action);
		}
		
		//************************************SCRIPTS***************************************************
		$this->salida .="<script>\n";
		$this->salida .="	function tablaCup(cup_cargo,desc)\n"; //Muestra la tabla de la relacion a guardar
		$this->salida .="	{ window.opener.document.getElementById('divAdd').style.display='none'; \n";
		$this->salida .=" 	  window.opener.document.getElementById('divAdd2').style.display='';\n";
		$this->salida .=" 	  window.opener.document.getElementById('divCupCargo').innerHTML=cup_cargo;\n";
		$this->salida .=" 	  window.opener.document.getElementById('divCupDesc').innerHTML=desc;\n";
		$this->salida .=" 	  window.opener.document.getElementById('h_cup_desc').value=desc;\n";
		$this->salida .=" 	  window.opener.document.getElementById('h_cup_cargo').value=cup_cargo;\n";
		//$this->salida .=" 	  window.opener.document.getElementById('h_cargo2').value=;\n";
		$this->salida .=" 	  window.close();";
		$this->salida .="	} \n";
		$this->salida .="</script>\n";
		//document.form_tarifario.s_cups.value
		return true;
	}
}

?>