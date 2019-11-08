<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: RemotosUnidadesNegocio.php
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
  $sql = AutoCarga::factory("UnidadesNegocioSQL","classes","app","Inv_ParametrosIniciales");
  
  $request['buscador']['codigo_unidad_negocio']=$datos['codigo_unidad_negocio'];
  $action['recarga']=ModuloGetURL("app", "Inv_ParametrosIniciales", "controller", "UnidadesNegocio",array("buscador"=>$request['buscador']));
  
  if(trim($datos['codigo_unidad_negocio'])=="")
	$mensaje .=" DEBE DILIGENCIAR EL CODIGO DE LA UNIDAD DE NEGOCIO <br>";
  if(trim($datos['descripcion'])=="")
	$mensaje .=" DEBE DILIGENCIAR LA DESCRIPCION DE LA UNIDAD DE NEGOCIO ";
  
  if(trim($mensaje)=="")
  {
  if($datos['consulta']=='1')
  $token=$sql->Insertar_UnidadNegocio($datos);
	else
	$token=$sql->Modificar_UnidadNegocio($datos);
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
  
  function Formulario_UnidadesNegocio($codigo_unidad_negocio)
  {
		$objResponse = new xajaxResponse();

		$sql = AutoCarga::factory("UnidadesNegocioSQL","classes","app","Inv_ParametrosIniciales");
		$request['buscador']['codigo_unidad_negocio']=$codigo_unidad_negocio;
		$Listado_UnidadesNegocio = $sql->Listado_UnidadesNegocio($request['buscador'],$request['offset']);
		//action del formulario= Donde van los datos del formulario.
		$html .= "	<div id=\"error\" class=\"label_error\"></div>";
		$html .= "  <form name=\"Formulario_UnidadNegocio\" id=\"Formulario_UnidadNegocio\" method=\"post\" action=\"\">";
		$html .= "      <table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\" colspan=\"2\">";
		$html .= "      UNIDADES DE NEGOCIO";
		$html .= "      </td>";
		$html .= "      </tr>";
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"left\" >";
		$html .= "      CODIGO :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\">";
		$html .= '      <input style="width:100%" class="input-text" type="Text" name="codigo_unidad_negocio" id="codigo_unidad_negocio" maxlength="2" onkeyup="this.value=this.value.toUpperCase()" value="'.$Listado_UnidadesNegocio[0]['codigo_unidad_negocio'].'">';
		$html .= "      </td>";
		$html .= "      </tr>";
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"left\" >";
		$html .= "      DESCRIPCION :";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" >";
		$html .= '      <input style="width:100%" class="input-text" type="Text" name="descripcion" id="descripcion" maxlength="50" onkeyup="this.value=this.value.toUpperCase()" value="'.$Listado_UnidadesNegocio[0]['descripcion'].'">';
		$html .= "      </td>";
		$html .= "      </tr>";
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"left\" >";
		$html .= "      IMAGEN : (Ej: imagen.jpg) /images/";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" >";
		$html .= '      <input style="width:100%" class="input-text" type="text" name="imagen" id="imagen" maxlength="30" value="'.$Listado_UnidadesNegocio[0]['imagen'].'">';
		$html .= "      </td>";
		$html .= "      </tr>";
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" colspan=\"2\">";
		if(empty($Listado_UnidadesNegocio))
		{
		$html .= '      <input type="hidden" name="consulta" value="1">'; /*1) INSERT 0) UPDATE*/
		}
		else
		{
		$html .= '      <input type="hidden" name="consulta" value="0">'; /*1) INSERT 0) UPDATE*/
		$html .= '      <input type="hidden" name="codigo_unidad_negocio_old" id="codigo_unidad_negocio_old" value="'.$Listado_UnidadesNegocio[0]['codigo_unidad_negocio'].'">'; /*1) INSERT 0) UPDATE*/
		}
		
		$html .= "      <input class=\"input-submit\" type=\"button\" value=\"Enviar\" name=\"boton\" onclick=\"xajax_GuardarDatos(xajax.getFormValues('Formulario_UnidadNegocio'));\">";
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
