<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: RemotosTiposCliente.php
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */
  
  function GuardarDatos($datos)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("TransportadorasSQL","classes","app","Inv_ParametrosIniciales");
  
  $request['buscador']['descripcion']=$datos['descripcion'];
  $action['recarga']=ModuloGetURL("app", "Inv_ParametrosIniciales", "controller", "Transportadoras",array("buscador"=>$request['buscador']));
  
  if(trim($datos['descripcion'])=="")
	$mensaje .=" DEBE DILIGENCIAR LA DESCRIPCION DEL TIPO CLIENTE ";
  
  if(trim($mensaje)=="")
  {
  if($datos['consulta']=='1')
  $token=$sql->Insertar_Transportadora($datos);
	else
	$token=$sql->Modificar_Transportadora($datos);
  if($token)
	{
	$objResponse->alert("CONSULTA EXITOSA!!");
	$script = "window.location=\"".$action['recarga']."\";";
	$objResponse->script($script);
	}
		else
			$objResponse->alert("ERROR EN LA CONSULTA!!");
  }
	else
		 $objResponse->assign("error","innerHTML",$objResponse->setTildes($mensaje));
  return $objResponse;
  }
  
  function Formulario_Transportadora($transportadora_id)
  {
		$objResponse = new xajaxResponse();

		$sql = AutoCarga::factory("TransportadorasSQL","classes","app","Inv_ParametrosIniciales");
		$request['buscador']['transportadora_id']=$transportadora_id;
		$Listado_Transportadoras = $sql->Listado_Transportadoras($request['buscador'],$request['offset']);
		
		//action del formulario= Donde van los datos del formulario.
		$html .= "	<div id=\"error\" class=\"label_error\"></div>";
		$html .= "  <form name=\"Formulario_Transportadora\" id=\"Formulario_Transportadora\" method=\"post\" action=\"\">";
		$html .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\" colspan=\"2\">";
		$html .= "      TRASNPORTADORAS";
		$html .= "      </td>";
		$html .= "      </tr>";
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"left\" >";
		$html .= "      DESCRIPCION :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" >";
		$html .= '      <input style="width:100%" class="input-text" type="Text" name="descripcion" id="descripcion" maxlength="40" onkeyup="this.value=this.value.toUpperCase()" value="'.$Listado_Transportadoras[0]['descripcion'].'">';
		$html .= "      </td>";
		$html .= "      </tr>";
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"left\" >";
		$html .= "      CARRO PROPIO :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\">";
		$html .= "      	<select id=\"sw_carropropio\" name=\"sw_carropropio\" class=\"select\" style=\"width:100%\">;";
		$html .= "				<option value=\"1\">SI";
		$html .= "				</option>";
		$html .= "				<option value=\"0\">NO";
		$html .= "				</option>";
		$html .= "			</select>";
		$html .= "      </td>";
		$html .= "      </tr>";
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">";
		if(empty($Listado_Transportadoras))
		{
		$html .= '      <input type="hidden" name="consulta" value="1">'; /*1) INSERT 0) UPDATE*/
		}
		else
		{
		$html .= '      <input type="hidden" name="consulta" value="0">'; /*1) INSERT 0) UPDATE*/
		$html .= '      <input type="hidden" name="transportadora_id_old" id="transportadora_id_old" value="'.$Listado_Transportadoras[0]['transportadora_id'].'">'; /*1) INSERT 0) UPDATE*/
		}
		
		$html .= "      <input class=\"input-submit\" type=\"button\" value=\"Enviar\" name=\"boton\" onclick=\"xajax_GuardarDatos(xajax.getFormValues('Formulario_Transportadora'));\">";
		$html .= "      </td>";
		$html .= "      </tr>";

		$html .= "		</form>";
		$html .= "      </table>";
		
		$script = "
		for (var i=0;i < document.getElementById('sw_carropropio').length;i++){
        if(document.getElementById('sw_carropropio')[i].value=='".$Listado_Transportadoras[0]['sw_carropropio']."'){
        document.getElementById('sw_carropropio')[i].selected=true;}}		";
  
  
    $objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->script($script);
    $objResponse->call("MostrarSpan");
    return $objResponse;
  }
  
 
  
  
?>
