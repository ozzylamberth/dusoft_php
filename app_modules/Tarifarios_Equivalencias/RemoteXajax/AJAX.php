<?PHP
    
function actualizarSelect($cod)
{
  $x=new xajaxResponse();
  $obj=new app_Tarifarios_Equivalencias_user();
  
  $matriz=$obj->consultarTarifariosDetalle($cod);
 
  $html = "";
  $html .= "<select name=\"s_tari_detalle\" width=\"100%\" class=\"select\">\n";
  $sel = "selected";
  $lines="-------------------------";
  $html.="<option value=\"-1\">>".$lines.$lines.$lines."SELECCIONE".$lines.$lines.$lines."<</option>";  
    
    foreach($matriz as $key=> $val)
    {
      $html.="<option value=\"".$val['cargo']."\" $sel>".substr($val['descripcion'],0,85)."</option>\n";
      $sel = "";
    }
   
  $html.="</select>";
  
  $x->assign("div1","innerHTML",$html);
   
  return $x;
}
//============================================================================================================
function consultarCups($tipo_busqueda,$cad)
{
  $obj=new app_Tarifarios_Equivalencias_user();
  $matriz=$obj->consultarCups($tipo_busqueda,$cad); //c ò d
  
  $lines="-------------------------";
  $sel="selected";
  $html="<select name=\"s_cups\" width=\"90%\" class=\"select\" id=\"s_cups\"  onChange=\"javascript:asignarCup();\">";
  $html.=" <option value=\"-1\">>".$lines."SELECCIONE".$lines."<</option>";
 
        foreach($matriz as $key => $val)
        { $html.="<option value=\"".$val['cargo']."\"  ".$sel.">".substr($val['descripcion'],0,60);
	      $sel="";
	    }
   
  $html.="      </select>";
  
  $x=new xajaxResponse();
  $x->assign("divCups","innerHTML",$html);
  
  return $x;
}
//============================================================================================================
function resultado($tari_id,$relacion,$cup,$td_cargo)
{ 
  $obj=new app_Tarifarios_Equivalencias_user();
  $x=new xajaxResponse();
  
  $matriz=$obj->consultarRelaciones($relacion,$cup,$tari_id,$td_cargo);
  $url2=ModuloGetURL('app','Tarifarios_Equivalencias','user','FormaVentanaCups');
  $url1=ModuloGetURL('app','Tarifarios_Equivalencias','user','FormaVentanaTarifarios');
  $html="";
  
  if(count($matriz)<=0)
  {
  	//if($tari_id!="-1" && $cup!="-1")
  		$x->alert("NO HAY RELACIONES.  ");
	
	if($relacion=="c-t")
	{
		if($cup!="-1")
		{	//-------Fieldset---------------
			$html="<table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
			$html.="<tr><td><fieldset><legend class=\"field\"> RELACIONES (CUPS-TARIFARIOS) </legend>";
			//----------------TABLA ENCABEZADO--------------------
			$html.="<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		
			$html.="<tr>";
			$html.="  <td> <b>CARGO CUPS: </b>".$cup."        ( )</td>";
			$html.="</tr>";
			$html.="<tr>";
			$html.="	<td> <b>TARIFARIO RELACIONADO: </b>".$tari_id."        (  )";
			$html.="	</td>";
		
			$html.="	<td>";
			$html.="		<input type=\"button\" name=\"buscar_tarifario\" value=\"Buscar Tarifarios\" class=\"input-submit\" onClick=\"Javascript:abrirVentana('".$url1."');\"> </input>";
			$html.="	</td>";
			$html.="</tr>";
			$html.="</table>";
			//------------------------RESULT---------------------
			$html.="<table border=\"1\" align=\"center\" class=\"modulo_table_list\" width=\"100%\">";
			$html.="  <tr>";
			//$html.="    <td align=\"center\" class=\"modulo_list_oscuro\"> <b>CUP_ID</b> </td>";
			$html.="    <td align=\"center\" class=\"modulo_table_list_title\"> <b>TARIFARIO CARGO</b> </td>";
			$html.="    <td align=\"center\" class=\"modulo_table_list_title\"> <b>TARIFARIO DESCRIPCION</b> </td>";
			$html.="    <td align=\"center\" class=\"modulo_table_list_title\" width=\"5%\">  </td>";
			$html.="  </tr>";
			$html.="</table>";
			
			//-------------Fin Fieldset-----------------------------------------------
			$html.="</fieldset></td></tr>";
			$html.="</table>";
		}
		else
		 	$x->alert("DEBE SELECCIONAR UN CUP");
	}
	else //if($relacion="t-c")
	{
		if($tari_id!="-1")
		{	//-------Fieldset---------------
			$html="<table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
			$html.="<tr><td><fieldset><legend class=\"field\"> RELACIONES (TARIFARIOS-CUPS) </legend>";
			//----------------TABLA ENCABEZADO--------------------
			$html.="<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		
			$html.="<tr>";
			$html.="  <td> <b>TARIFARIO: </b>".$tari_id."        ( )</td>";
			$html.="</tr>";
			$html.="<tr>";
			$html.="	<td> <b>CARGO CUPS RELACIONADO: </b>".$cup."        (  )";
			$html.="	</td>";
		
			$html.="	<td>";
			$html.="		<input type=\"button\" name=\"buscar_tarifario\" value=\"Buscar CUPS\" class=\"input-submit\" onClick=\"Javascript:abrirVentana('".$url2."');\"> </input>";
			$html.="	</td>";
			//--------------------------------
			$html.="</tr>";
			$html.="</table>";
			
			//-------------Fin Fieldset-----------------------------------------------
			$html.="</fieldset></td></tr>";
			$html.="</table>";
		}
		else
			$x->alert("DEBE SELECCIONAR UN TARIFARIO");
	}
	
	$x->assign("divResult","style.display","block");   
    $x->assign("divResult","innerHTML",$html);
  }
  else
  {
    //$x->alert("Tari: ".$tari_id."\nRela: ".$relacion."\nCup: ".$cup."\nTD_Cargo: ".$td_cargo);
	$tari_desc=$obj->consultarTarifario($tari_id); //Campo 'descripcion' de Tarifarios
	$rutaImage=GetThemePath()."/images";
	
    if($relacion=="c-t")
    {
		//-------Fieldset---------------
		$html="<table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
		$html.="<tr><td><fieldset><legend class=\"field\"> RELACIONES (CUPS-TARIFARIOS) </legend>";
		
		//----------------TABLA ENCABEZADO--------------------
		$html.="<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html.="<tr>";
    	$html.="  <td> <b>CARGO CUPS: </b>".$cup."        (".substr($matriz[0]['c_desc'],0,120).")</td>";
    	$html.="</tr>";
		$html.="<tr>";
		$html.="	<td> <b>TARIFARIO RELACIONADO: </b>".$tari_id."        (".$tari_desc[0]['descripcion'].")";
		$html.="	</td>";
		$html.="	<td>";
		$html.="		<input type=\"button\" name=\"buscar_tarifario\" value=\"Buscar Tarifario\" class=\"input-submit\" onClick=\"javascript:abrirVentana('".$url1."');\"> </input>";
		$html.="	</td>";
		$html.="</tr>";
		$html.="</table>";
		
		//-----------TABLA RESULTADO-----------------------------
    	$html.="<table border=\"1\" align=\"center\" class=\"modulo_table_list\" width=\"100%\">";
      
      $html.="  <tr>";
      //$html.="    <td align=\"center\" class=\"modulo_list_oscuro\"> <b>CUP_ID</b> </td>";
      $html.="    <td align=\"center\" class=\"modulo_table_list_title\"> <b>TARIFARIO CARGO</b> </td>";
      $html.="    <td align=\"center\" class=\"modulo_table_list_title\"> <b>TARIFARIO DESCRIPCION</b> </td>";
	  $html.="    <td align=\"center\" class=\"modulo_table_list_title\" width=\"5%\">  </td>";
      $html.="  </tr>";
	  
      foreach($matriz as $key => $val)
      {
        $html.="  <tr>";
        /*
		$html.="    <td>";
        $html.=        $val['c_cargo'];
        $html.="    </td>";
		*/
        $html.="    <td id=\"td_cargo\"".$key." width=\"10%\">";
        $html.=        $val['td_cargo'];
        $html.="    </td>";
	
		$html.="    <td id=\"td_desc\"".$key.">";
        $html.=        $val['td_descripcion'];
        $html.="    </td>";
		
		$html.="    <td> ";
		$html.="    <a href=\"javascript:borrarCupTar('".$val['td_cargo']."');\">";
		$html.="		<image src=\"".$rutaImage."/delete2.gif\"".">";
		$html.="    </a>";
		$html.="    </td>";
        $html.="  </tr>";
      }
	  $html.="</table>";
	  
	  	//-------------Fin Fieldset-----------------------------------------------
		$html.="</fieldset></td></tr>";
		$html.="</table>";
    }
    else
    {
		$cargo_cup=$obj->consultarCup($cup); //Campo 'descripcion' de Cups
		//-------Fieldset---------------
		$html="<table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
		$html.="<tr><td><fieldset><legend class=\"field\"> RELACIONES (TARIFARIOS-CUPS) </legend>";
		
		//----------------TABLA ENCABEZADO--------------------
		$html.="<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html.="<tr>";
    	$html.="  <td> <b>TARIFARIO: </b>".$tari_id."        (".$tari_desc[0]['descripcion'].")</td>";
    	$html.="</tr>";
		$html.="<tr>";
		$html.="	<td> <b>CARGO CUPS RELACIONADO: </b>".$cup."        (".substr($cargo_cup[0]['descripcion'],0,120).")";
		$html.="	</td>";
		$html.="	<td>";
		$html.="		<input type=\"button\" name=\"Buscar_CUP\" value=\"Buscar CUP\" class=\"input-submit\" onClick=\"Javascript:abrirVentana('".$url2."');\">  </input>";
		$html.="	</td>";
		$html.="	</td>";
		$html.="</tr>";
		$html.="</table>";
		
		//------------Tabla Resultado-----------------------------
    	$html.="<table border=\"1\" align=\"center\" class=\"modulo_table_list\" width=\"100%\">";
	
      $html.="  <tr>";
      //$html.="    <td align=\"center\" class=\"modulo_table_list_title\"> <b>TARIFARIO_ID</b> </td>";
      $html.="    <td align=\"center\" class=\"modulo_table_list_title\"> <b>CARGO CUP</b> </td>";
      $html.="    <td align=\"center\" class=\"modulo_table_list_title\"> <b>DESCRIPCION CUP</b> </td>";
	  $html.="    <td align=\"center\" class=\"modulo_table_list_title\" width=\"5%\">  </td>";
      $html.="  </tr>";
      
	  
      foreach($matriz as $key0 => $val)
      {
        $html.="  <tr>";
		/*
        $html.="    <td>";
        $html.=        $val['tarifario_id'];
        $html.="    </td>";
    	*/
        $html.="    <td width=\"10%\">";
        //$html.=        $val['te_cargo'];
		$html.=        $val['c_cargo'];
        $html.="    </td>";
	
		$html.="    <td>";
        $html.=        $val['c_descripcion'];
        $html.="    </td>";
		
		$html.="    <td> ";
		$html.="    <a href=\"javascript:borrarTarCup('".$val['c_cargo_base']."');\">";
		$html.="		<image src=\"".$rutaImage."/delete2.gif\"".">";
		$html.="    </a>";
		$html.="    </td>";
        $html.="  </tr>";
      }
	  
	  $html.="</table>";
	  
	  //-------------Fin Fieldset-----------------------------------------------
		$html.="</fieldset></td></tr>";
		$html.="</table>";
    }
    //----------------------------
    //$html.="</table>";
  
    $x->assign("divResult","style.display","block");
    $x->assign("divResult","innerHTML",$html);
  }
  return $x;
}

//==============================================================

?>