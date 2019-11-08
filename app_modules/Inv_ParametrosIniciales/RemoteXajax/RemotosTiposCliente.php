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
  $sql = AutoCarga::factory("TiposClienteSQL","classes","app","Inv_ParametrosIniciales");
  
  $request['buscador']['tipo_cliente']=$datos['tipo_cliente'];
  $action['recarga']=ModuloGetURL("app", "Inv_ParametrosIniciales", "controller", "TiposCliente",array("buscador"=>$request['buscador']));
  
  if(trim($datos['tipo_cliente'])=="")
	$mensaje .=" DEBE DILIGENCIAR EL CODIGO DEL TIPO DE CLIENTE <br>";
  if(trim($datos['descripcion'])=="")
	$mensaje .=" DEBE DILIGENCIAR LA DESCRIPCION DEL TIPO CLIENTE ";
  
  if(trim($mensaje)=="")
  {
  if($datos['consulta']=='1')
  $token=$sql->Insertar_TipoCliente($datos);
	else
	$token=$sql->Modificar_TipoCliente($datos);
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
  
  function Formulario_TiposCliente($tipo_cliente)
  {
		$objResponse = new xajaxResponse();

		$sql = AutoCarga::factory("TiposClienteSQL","classes","app","Inv_ParametrosIniciales");
		$request['buscador']['tipo_cliente']=$tipo_cliente;
		$Listado_TiposCliente = $sql->Listado_TiposCliente($request['buscador'],$request['offset']);
		//action del formulario= Donde van los datos del formulario.
		$html .= "	<div id=\"error\" class=\"label_error\"></div>";
		$html .= "  <form name=\"Formulario_TipoCliente\" id=\"Formulario_TipoCliente\" method=\"post\" action=\"\">";
		$html .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\" colspan=\"2\">";
		$html .= "      TIPOS DE CLIENTES";
		$html .= "      </td>";
		$html .= "      </tr>";
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"left\" >";
		$html .= "      CODIGO :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\">";
		$html .= '      <input style="width:100%" class="input-text" type="Text" name="tipo_cliente" id="tipo_cliente" maxlength="4" onkeyup="this.value=this.value.toUpperCase()" value="'.$Listado_TiposCliente[0]['tipo_cliente'].'">';
		$html .= "      </td>";
		$html .= "      </tr>";
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"left\" >";
		$html .= "      DESCRIPCION :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" >";
		$html .= '      <input style="width:100%" class="input-text" type="Text" name="descripcion" id="descripcion" maxlength="40" onkeyup="this.value=this.value.toUpperCase()" value="'.$Listado_TiposCliente[0]['descripcion'].'">';
		$html .= "      </td>";
		$html .= "      </tr>";
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">";
		if(empty($Listado_TiposCliente))
		{
		$html .= '      <input type="hidden" name="consulta" value="1">'; /*1) INSERT 0) UPDATE*/
		}
		else
		{
		$html .= '      <input type="hidden" name="consulta" value="0">'; /*1) INSERT 0) UPDATE*/
		$html .= '      <input type="hidden" name="tipo_cliente_old" id="tipo_cliente_old" value="'.$Listado_TiposCliente[0]['tipo_cliente'].'">'; /*1) INSERT 0) UPDATE*/
		}
		
		$html .= "      <input class=\"input-submit\" type=\"button\" value=\"Enviar\" name=\"boton\" onclick=\"xajax_GuardarDatos(xajax.getFormValues('Formulario_TipoCliente'));\">";
		$html .= "      </td>";
		$html .= "      </tr>";

		$html .= "		</form>";
		$html .= "      </table>";
		  
  
  
    $objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->call("MostrarSpan");
    return $objResponse;
  }
  
 
  
  
?>
