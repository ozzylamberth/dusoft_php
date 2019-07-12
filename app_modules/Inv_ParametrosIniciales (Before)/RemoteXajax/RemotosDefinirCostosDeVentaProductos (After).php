<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: RemotosDefinirCostosDeVentaProductos.php,v 1.2 2009/11/20 21:42:44 mauricio Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */
  
  function Esconder($EmpresaId)
  {
      $objResponse = new xajaxResponse();

      $objResponse->assign("centro".$EmpresaId,"innerHTML","");
      $objResponse->assign("bodega".$EmpresaId,"innerHTML","");
      $enlace ="<a href=\"#\" onclick=\"xajax_CentrosUtilidad('".$EmpresaId."')\"><img title=\"Seleccionar Empresa\" src=\"".GetThemePath()."/images/abajo.png\" border=\"0\"></a>";
      $objResponse->assign("Boton".$EmpresaId,"innerHTML",$enlace);

      return $objResponse;
  }
 
 
 /*
  * Funcion Que Refrescará el listado de Centros de Utilidad por Empresa
  */  
  function CentrosUtilidad($EmpresaId)
  {
  $objResponse = new xajaxResponse();
   					
			//Generar de Busqueda de Permisos SQL
			$obj_busqueda=AutoCarga::factory("ConsultasDefinirCostosDeVentaProductos", "classes", "app","Inv_ParametrosIniciales");
			//Obtenemos los resultados del Query realizado en Classes. Accediendo al metodo del Objeto $obj_busqueda.
			$CentrosUtilidad=$obj_busqueda->Listar_CentrosUtilidad($EmpresaId); 
		  
   
    
      $pghtml = AutoCarga::factory("ClaseHTML");
      
        foreach($CentrosUtilidad as $key => $c)
        {
          
          $html .= "<li class=\"label_error\">";
          $html .= "<a onclick=\"xajax_Bodegas('".$EmpresaId."','".$c['centro_utilidad']."')\">";
          $html .= "".$c['descripcion']."</a>";
          $html .= "</li>";
          
            
        }
   
      $enlace ="<a onclick=\"xajax_Esconder('".$EmpresaId."')\"><img title=\"Esconder\" src=\"".GetThemePath()."/images/arriba.png\" border=\"0\"></a>";
   
      $objResponse->assign("centro".$EmpresaId,"innerHTML",$objResponse->setTildes($html));
      $objResponse->assign("Boton".$EmpresaId,"innerHTML",$enlace);
      return $objResponse;
  }
  
  /*
  * Funcion Que Refrescará el listado de Bodegas Segun Centros de Utilidad por Empresa
  */  
  function Bodegas($EmpresaId,$CentroUtilidad)
  {
  $objResponse = new xajaxResponse();
   					
			//Generar de Busqueda de Permisos SQL
			$obj_busqueda=AutoCarga::factory("ConsultasDefinirCostosDeVentaProductos", "classes", "app","Inv_ParametrosIniciales");
			//Obtenemos los resultados del Query realizado en Classes. Accediendo al metodo del Objeto $obj_busqueda.
			$Bodegas=$obj_busqueda->Listar_Bodegas($EmpresaId,$CentroUtilidad); 
		      
      $pghtml = AutoCarga::factory("ClaseHTML");
      
        foreach($Bodegas as $key => $b)
        {
          
          $html .= "<li class=\"label_error\">";
          $html .= "<a onclick=\"xajax_Listado_Precios('".$EmpresaId."','".$CentroUtilidad."','".$b['bodega']."')\">";
          $html .= "".$b['descripcion']."</a>";
          $html .= "</li>";
          
            
        }
   
      $objResponse->assign("bodega".$EmpresaId,"innerHTML",$objResponse->setTildes($html));
      
      return $objResponse;
  }
 
 
 /*
  * Funcion Que Refrescará el listado de Empresas
  * BuscarTercero
  */  
  function EmpresasT_2($offset)
  {
	$objResponse = new xajaxResponse();

	//Generar de Busqueda de Permisos SQL
	$obj_busqueda=AutoCarga::factory("ConsultasDefinirCostosDeVentaProductos", "classes", "app","Inv_ParametrosIniciales");
	//Obtenemos los resultados del Query realizado en Classes. Accediendo al metodo del Objeto $obj_busqueda.
	$Empresas=$obj_busqueda->Listar_Empresas($offset);
	$action['volver'] = ModuloGetURL("app","Inv_ParametrosIniciales","controller","MenuParametrosIniciales");

	$VolverMenu=$action['volver'];

	$action['paginador'] = "PaginadorEmp(";


	$pghtml = AutoCarga::factory("ClaseHTML");
	$html .= $pghtml->ObtenerPaginadoXajax($obj_busqueda->conteo,$obj_busqueda->pagina,$action['paginador']);  


	$html .= "<center>";
	$html .= "<fieldset class=\"fieldset\" style=\"width:80%\">\n";
	$html .= "  <legend class=\"normal_10AN\">EMPRESAS</legend>\n";

	$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";

	$html .= "    <tr class=\"formulacion_table_list\">\n";

	$html .= "          <td align=\"center\" width=\"8%\">
	<a title=\"CODIGO DE LA EMPRESA\">EMPRESA ID</a>                      </td>
	<td align=\"center\" width=\"23%\">
	<a title=\"RAZON SOCIAL\">NOMBRE</a>                      </td>
	<td align=\"center\" width=\"23%\">
	<a title=\"DIRECCION\">DIRECCION</a></td>

	<td align=\"center\" width=\"23%\">
	<a title=\"TELEFONO\">TELEFONO</a></td>

	<td align=\"center\" width=\"10%\">
	<a title=\"DEPARTAMENTO\">DEPARTAMENTO</a><a>                      </a></td>
	<td align=\"center\" width=\"10%\">
	<a title=\"MUNICIPIO ID\">MUNICIPIO</a><a>                      </a></td>

	<td align=\"center\" width=\"9%\">
	<a title=\"ASIGNAR COSTO DE VENTAS\"></a><a>                      </a></td>
	";      



	$est = "modulo_list_claro";
	$bck = "#DDDDDD";
	foreach($Empresas as $key => $e)
	{
	($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
	($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

	$html .= "    <tr class=\"".$est."\" >\n";
	$html .= "      <td >".$e['empresa_id']."</td><td>".$e['empresa']." </td>
	<td>".$e['direccion']." </td>
	<td>".$e['telefonos']." </td>
	<td>".$e['departamento']." </td>
	<td>".$e['municipio']." </td>
	<td align=\"center\"><div id=\"Boton".$e['empresa_id']."\"><a onclick=\"xajax_CentrosUtilidad('".$e['empresa_id']."')\"><img title=\"SELECCIONAR EMPRESA\" src=\"".GetThemePath()."/images/abajo.png\" border=\"0\"></a></div></td>
	</tr>\n";
	$html .= "    </tr>\n";
	$html .= "              <tr class=\"modulo_list_oscuro\">";
	$html .= "                  <td colspan=\"3\"><div id=\"centro".$e['empresa_id']."\"</td>";
	$html .= "                  <td colspan=\"4\"><div id=\"bodega".$e['empresa_id']."\"</td>";
	$html .= "              </tr>";

	}
	$html .= "    </table>\n";

	$html .= "</fieldset><br>\n";
	$html .= "    </center>\n";
	$html .= "     <br>";
	$html .= "    <div id=\"productos_creados\">";
	$html .= "    </div>";

	$objResponse->assign("sub_pantalla","innerHTML",$objResponse->setTildes($html));
	//LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
	return $objResponse;
  }
 
 
  function Listado_Precios($EmpresaId,$CentroUtilidad,$Bodega)
  {
  $objResponse = new xajaxResponse();
  $sql=AutoCarga::factory("ConsultasDefinirCostosDeVentaProductos", "classes", "app","Inv_ParametrosIniciales");
  $ListaDePrecios=$sql->Lista_Precios($EmpresaId,$CentroUtilidad,$Bodega);

  //print_r($ListaDePrecios);
  $objResponse->call("MostrarSpan");
         if(empty($ListaDePrecios))
         {
            
            $html .= "<center>";
            $html .= "  <div class=\"label_error\" id=\"error\"></div>";
            $html .= "</center>";
             $html .= "    <form name=\"FormularioListaPrecios\" id=\"FormularioListaPrecios\">";
            $html .= "	<table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
             
            $html .= "		<tr class=\"modulo_list_oscuro\">\n";
           
            $html .= "          <td class=\"formulacion_table_list\" colspan=\"2\">";
            $html .= "          INGRESAR LISTA DE PRECIOS";
            $html .= "          </td>";
            
            $html .= "		</tr>\n";
            
            $html .= "		<tr class=\"modulo_list_oscuro\">\n";
            
            $html .= "          <td class=\"formulacion_table_list\">";
            $html .= "          CODIGO DE LISTA";
            $html .= "          </td>";
            $html .= "          <td>";
           
            $html .= "          <input type=\"text\" class=\"input-text\" name=\"codigo_lista\" maxlength=\"4\">";
            $html .= "          </td>";
            $html .= "		</tr>\n";
            $html .= "		<tr class=\"modulo_list_oscuro\">\n";
            $html .= "          <td class=\"formulacion_table_list\">";
            $html .= "          DESCRIPCION";
            $html .= "          </td>";
            $html .= "          <td>";
            $html .= "          <input type=\"text\" class=\"input-text\" name=\"descripcion\" maxlength=\"60\">";
            
            $html .= "          <input type=\"hidden\" class=\"input-text\" name=\"empresa_id\" value=\"".$EmpresaId."\">";
            $html .= "          <input type=\"hidden\" class=\"input-text\" name=\"centro_utilidad\" value=\"".$CentroUtilidad."\">";
            $html .= "          <input type=\"hidden\" class=\"input-text\" name=\"bodega\" value=\"".$Bodega."\">";
            $html .= "          </td>";
            $html .= "		</tr>\n";
            $html .= "		<tr class=\"modulo_list_oscuro\">\n";
            $html .= "          <td colspan=\"2\" align=\"center\">";
            $html .= "          <input type=\"button\" class=\"modulo_table_list\" value=\"Enviar\" onclick=\"ValidarDatos(xajax.getFormValues('FormularioListaPrecios'));\">";
            $html .= "          </td>";
             
            $html .= "		</tr>\n";
            $html .= "    </form>";
            $html .= "  </table>\n";
           
         }
         else
            {
            $objResponse->script("xajax_CatalogoProductosFarmacia('".$EmpresaId."','".$CentroUtilidad."','".$Bodega."','".$ListaDePrecios[0]['codigo_lista']."');");
            $objResponse->script("OcultarSpan();");
            }
 
  $objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
 //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
  
  return $objResponse;
  }
 
 
 function RegistrarListaPrecios($Formulario)
  {
  $objResponse = new xajaxResponse();
	$sql=AutoCarga::factory("ConsultasDefinirCostosDeVentaProductos", "classes", "app","Inv_ParametrosIniciales");
			
      $rta=$sql->InsertarListaPrecios($Formulario); 

      
      if($rta)
        {
        $objResponse->script("xajax_Listado_Precios('".$Formulario['empresa_id']."','".$Formulario['centro_utilidad']."','".$Formulario['bodega']."');");
        }
        else
        $objResponse->assign("error","innerHTML","Error en el Ingreso :".$sql->mensajeDeError);
      return $objResponse;
  }
    
 function RegistrarItemListaPrecios($Formulario)
  {
  $objResponse = new xajaxResponse();
	$sql=AutoCarga::factory("ConsultasDefinirCostosDeVentaProductos", "classes", "app","Inv_ParametrosIniciales");
			
      if($Formulario['modifica']=="1")
        {
        $rta=$sql->ModificarItemListaPrecios($Formulario); 
        }
        else
            {
            $rta=$sql->InsertarItemListaPrecios($Formulario); 
            }

      
      if($rta)
        {
        $objResponse->script("QuitarProducto();");
        $objResponse->script("xajax_ListadoItemsListaPrecios('".$Formulario['empresa_id']."','".$Formulario['codigo_lista']."');");
        }
        else
        $objResponse->assign("error","innerHTML","Error en el Ingreso :".$sql->mensajeDeError);
      return $objResponse;
  }    
 
 function EliminarItemListaPrecios($EmpresaId,$CodigoLista,$CodigoProducto)
  {
  $objResponse = new xajaxResponse();
	$sql=AutoCarga::factory("ConsultasDefinirCostosDeVentaProductos", "classes", "app","Inv_ParametrosIniciales");
			
      $rta=$sql->EliminarItemListaPrecios($EmpresaId,$CodigoLista,$CodigoProducto); 

      
      if($rta)
        {
       $objResponse->script("QuitarProducto();");
        $objResponse->script("xajax_ListadoItemsListaPrecios('".$EmpresaId."','".$CodigoLista."');");
        }
        else
        $objResponse->assign("error","innerHTML","Error en el Ingreso :".$sql->mensajeDeError);
      return $objResponse;
  }
 
function CatalogoProductosFarmacia($EmpresaId,$CentroUtilidad,$Bodega,$CodigoLista)
  {
  $objResponse = new xajaxResponse();
   					
	$sql = AutoCarga::factory("ConsultasDefinirCostosDeVentaProductos","classes","app","Inv_ParametrosIniciales");
	$ListaDePrecios=$sql->Lista_Precios($EmpresaId,$CentroUtilidad,$Bodega);
    
          $html .= "<center>";
          $html .= "  <div class=\"label_error\" id=\"error\"></div>";
          $html .= "</center>";
          $html .= "<center>";
          $html .= "<fieldset class=\"fieldset\" style=\"width:80%\">\n";
          $html .= "  <legend class=\"normal_10AN\">ASIGNAR PROCENTAJES/PRECIOS DE VENTA A PRODUCTOS</legend>\n";
          $html .= "    <form name=\"FormularioPrecioProducto\" id=\"FormularioPrecioProducto\">";    
      		$html .= "                 <table width=\"60%\" align=\"center\" class=\"modulo_table_list\">\n";
					$html .= "				  <tr>";
					
					$html .= "				  <tr>";
					$html .= "				 	 <td>";
					$html .= "				      <b>PRODUCTO:</b>";
					$html .= "				  	 </td>";
					$html .= "				 	 <td>";
					$html .= "				      <a onclick=\"xajax_SeleccionDeProductos('".$EmpresaId."','".$CentroUtilidad."','".$Bodega."','".$CodigoLista."')\">";
					$html .="<img title=\"SELECCIONAR PRODUCTOS\" src=\"".GetThemePath()."/images/producto.png\" border=\"0\"></a>\n";
					$html .= "				  	 </td>";
					$html .= "				  </tr>";
					
					$html .= "				  <tr>";
					$html .= "				 	 <td>";
					$html .= "				      <b>CODIGO PRODUCTO:</b>";
					$html .= "				  	 </td>";
					$html .= "				 	 <td>";
					$html .= "				      <input id=\"codigo_producto\" name=\"codigo_producto\" type=\"text\" class=\"input-text\" readonly>";
					$html .= "				  	 </td>";
					$html .= "				  </tr>";
					
					$html .= "				  <tr>";
					$html .= "				 	 <td>";
					$html .= "				      <b>DESCRIPCION:</b>";
					$html .= "				  	 </td>";
					$html .= "				 	 <td>";
					$html .= "				      <div id=\"DescripcionProducto\" class=\"label_error\"></div>";
					$html .= "				  	 </td>";
					$html .= "				  </tr>";
					
					$html .= "				  <tr>";
					$html .= "				 	 <td>";
					$html .= "				      <b>PORCENTAJE ADICIONAL</b>";
					$html .= "				  	 </td>";
					$html .= "				 	 <td>";
					$html .= "				      <input class=\"input-checkbox\" name=\"sw_porcentaje\" id=\"sw_porcentaje\" type=\"checkbox\" class=\"input-text\" value=\"1\">";
					$html .= "              %<input type=\"text\" maxlength=\"4\" name=\"porcentaje\" id=\"porcentaje\" class=\"input-text\" size=\"5\" onkeypress=\"return acceptNum(event)\">";
          $html .= "				  	 </td>";
					$html .= "				  </tr>";
										
          $html .= "				 	 <td>";
					$html .= "				      <b>PRECIO DE VENTA:</b>";
					$html .= "				  	 </td>";
					$html .= "				 	 <td>";
					$html .= "				      <input type=\"text\" maxlength=\"15\" class=\"input-text\" name=\"precio\" id=\"precio\" onkeypress=\"return acceptNum(event)\">";
          $html .= "              <input type=\"hidden\" name=\"valor_inicial\" id=\"valor_inicial\">";
          $html .= "              <input type=\"hidden\" name=\"modifica\" id=\"modifica\">";
          $html .= "              <input type=\"hidden\" name=\"empresa_id\" value=\"".$EmpresaId."\">";
          $html .= "              <input type=\"hidden\" name=\"centro_utilidad\" value=\"".$CentroUtilidad."\">";
          $html .= "              <input type=\"hidden\" name=\"bodega\" value=\"".$Bodega."\">";
          $html .= "              <input type=\"hidden\" name=\"codigo_lista\" value=\"".$CodigoLista."\">";
					$html .= "				  	 </td>";
					$html .= "				  </tr>";
					
					
					$html .= "				  <tr>";
					$html .= "				 	 <td align=\"center\" colspan=\"2\">";
										
					$html .= "				      <input type=\"button\" onclick=\"SeleccionarProducto(xajax.getFormValues('FormularioPrecioProducto'));\"  class=\"modulo_table_list\" value=\"ADICIONAR PRODUCTO\">";
					$html .= "				  	 </td>";
					$html .= "				  </tr>";
					$html .= "          </form>";
					$html .= "					</table>";
          
          $html .= "          <div id=\"ItemsListaPrecios\"></div>";
          
      
      $objResponse->assign("sub_pantalla","innerHTML",$objResponse->setTildes($html));
      $objResponse->script("xajax_ListadoItemsListaPrecios('".$EmpresaId."','".$CodigoLista."');");
      
      //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
      return $objResponse;
  }

  function Refresh(){
    $objResponse = new xajaxResponse();

    $objResponse->assign("DescripcionProducto","innerHTML","Error en el Ingreso :dsdsds";
    return $objResponse;
    //echo 'Eyyyy';
    //xajax_refreshPriceInitial();
  }

  function SeleccionDeProductos($EmpresaId,$CentroUtilidad,$Bodega,$CodigoLista)
		{
					$objResponse = new xajaxResponse();
					$html .= "<table width=\"90%\" class=\"modulo_table_list\" align=\"center\" class=\"modulo_list_claro\">";
					$html .= "		<tr class=\"modulo_table_list_title\">";
					$html .= "			<td>";
					$html .= "			CODIGO";
					$html .= "			</td>";
					$html .= "			<td>";
					$html .= "			<input type=\"text\" id=\"codigo_producto_b\" class=\"input-text\">";
					$html .= "			</td>";
					
					$html .= "			<td>";
					$html .= "			DESCRIPCION";
					$html .= "			</td>";
					$html .= "			<td>";
					$html .= "			<input type=\"text\" id=\"descripcion_b\" class=\"input-text\">";
					$html .= "			</td>";
					
					$html .= "			<td>";
					$html .= "			CONCENTRACION";
					$html .= "			</td>";
					$html .= "			<td>";
					$html .= "			<input type=\"text\" id=\"contenido_unidad_venta_b\" class=\"input-text\">";
					$html .= "			</td>";
					$html .= "		</tr>";
					
					$html .= "		<tr class=\"modulo_table_list_title\">";
					$html .= "			<td colspan=\"6\" align=\"center\">";
					$html .= "			<input type=\"button\" onclick=\"xajax_BuscarProductos(document.getElementById('codigo_producto_b').value,document.getElementById('descripcion_b').value,document.getElementById('contenido_unidad_venta_b').value,'".$EmpresaId."','".$CentroUtilidad."','".$Bodega."','".$CodigoLista."','1');\"  class=\"modulo_table_list\" value=\"buscar\">";
					$html .= "			</td>";
					$html .= "		</tr>";
					$html .= "</table>";
					
					$html .= "<div id=\"ListadoProductos\"></div>";
					//$CodigoProducto,$Descripcion,$Concentracion,$Empresa_Id,$CentroUtilidad,$Bodega,$CodigoLista,$offset
					$objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
					$objResponse->script("MostrarSpan();");
					$objResponse->script("xajax_BuscarProductos('','','','".$EmpresaId."','".$CentroUtilidad."','".$Bodega."','".$CodigoLista."','1');");
					
					return $objResponse;
		}	
  
  
  function  BuscarProductos($CodigoProducto,$Descripcion,$Concentracion,$Empresa_Id,$CentroUtilidad,$Bodega,$CodigoLista,$offset)
		{
		$objResponse = new xajaxResponse();
		$sql = AutoCarga::factory("ConsultasDefinirCostosDeVentaProductos","classes","app","Inv_ParametrosIniciales");


		$Productos=$sql->ListaProductosInventario($CodigoProducto,$Descripcion,$Concentracion,$Empresa_Id,$CentroUtilidad,$Bodega,$CodigoLista,$offset);
		$pghtml = AutoCarga::factory('ClaseHTML');

		$action['paginador']="Paginador('".$CodigoProducto."','".$Descripcion."','".$Concentracion."','".$Empresa_Id."','".$CentroUtilidad."','".$Bodega."','".$CodigoLista."'";
		$html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);					

		$html .= "  <table width=\"90%\" class=\"modulo_table_list_title\" border=\"0\"  align=\"center\">";
		$html .= "	  <tr  align=\" class=\"modulo_table_list_title\" >\n";
		$html .= "      <td >CODIGO PRODUCTO</td>\n";
		$html .= "      <td >DESCRIPCION</td>\n";
		$html .= "      <td >$$ COSTO</td>";
		$html .= "      <td >EXIST.</td>";
		$html .= "      <td >OP</td>\n";
		$html .= "  </tr>\n";

		foreach($Productos as $k => $valor)
		{
		$html .= "  <tr class=\"modulo_list_claro\">\n";
		$html .= "      <td  align=\"center\">".$valor['codigo_producto']." </td>\n";
		$html .= "      <td align=\"left\">".$valor['descripcion']." ".$valor['presentacion']."-".$valor['clase']."</td>\n";

		$html .= "      <td align=\"left\">".$valor['costo']."</td>\n";
		$html .= "      <td align=\"left\">".$valor['existencia']."</td>\n";

		$html .= "      <td align=\"center\">\n";
		//codigo_producto,Descripcion,precio,sw_porcentaje,porcentaje,valor_inicial
		$html .= "		<a onclick=\"AdicionarProducto('".$valor['codigo_producto']."','".$valor['descripcion']."','".$valor['costo']."','','','".$valor['costo']."','0');\">";
		$html .="<img title=\"ADICIONAR PRODUCTOS\" src=\"".GetThemePath()."/images/checkno.png\" border=\"0\"></a>\n";
		$html .= "      </td>\n";
		$html .= "  </tr>\n";
		}
		$html .= "	</table>\n";




		$objResponse->assign("ListadoProductos","innerHTML",$objResponse->setTildes($html));
		return $objResponse;
		}
 
 
  function ListadoItemsListaPrecios($EmpresaId,$CodigoLista)
		{
					$objResponse = new xajaxResponse();
					$html .= "<table width=\"90%\" class=\"modulo_table_list\" align=\"center\" class=\"modulo_list_claro\">";
					$html .= "		<tr class=\"modulo_table_list_title\">";
					$html .= "			<td>";
					$html .= "			CODIGO";
					$html .= "			</td>";
					$html .= "			<td>";
					$html .= "			<input type=\"text\" id=\"codigo_producto_c\" class=\"input-text\">";
					$html .= "			</td>";
					
					$html .= "			<td>";
					$html .= "			DESCRIPCION";
					$html .= "			</td>";
					$html .= "			<td>";
					$html .= "			<input type=\"text\" id=\"descripcion_c\" class=\"input-text\">";
					$html .= "			</td>";
					
					$html .= "			<td>";
					$html .= "			CONCENTRACION";
					$html .= "			</td>";
					$html .= "			<td>";
					$html .= "			<input type=\"text\" id=\"contenido_unidad_venta_c\" class=\"input-text\">";
					$html .= "			</td>";
					$html .= "		</tr>";
					
					$html .= "		<tr class=\"modulo_table_list_title\">";
					$html .= "			<td colspan=\"6\" align=\"center\">";
					$html .= "			<input type=\"button\" onclick=\"xajax_BuscarProductosListaPrecios(document.getElementById('codigo_producto_c').value,document.getElementById('descripcion_c').value,document.getElementById('contenido_unidad_venta_c').value,'".$EmpresaId."','".$CodigoLista."','1');\"  class=\"modulo_table_list\" value=\"buscar\">";
					$html .= "			</td>";
					$html .= "		</tr>";
					$html .= "</table>";
					
					$html .= "<div id=\"ListadoProductosLista\"></div>";
					//$CodigoProducto,$Descripcion,$Concentracion,$Empresa_Id,$CentroUtilidad,$Bodega,$CodigoLista,$offset
					$objResponse->assign("ItemsListaPrecios","innerHTML",$objResponse->setTildes($html));
					$objResponse->script("xajax_BuscarProductosListaPrecios('','','','".$EmpresaId."','".$CodigoLista."','1');");
					
					return $objResponse;
		}	
  
  
  function  BuscarProductosListaPrecios($CodigoProducto,$Descripcion,$Concentracion,$Empresa_Id,$CodigoLista,$offset)
		{
					$objResponse = new xajaxResponse();
					$sql = AutoCarga::factory("ConsultasDefinirCostosDeVentaProductos","classes","app","Inv_ParametrosIniciales");
					
				
				$Productos=$sql->ListarItemsListaPrecios($CodigoProducto,$Descripcion,$Concentracion,$Empresa_Id,$CodigoLista,$offset);
				$pghtml = AutoCarga::factory('ClaseHTML');
				
				$action['paginador']="Paginador2('".$CodigoProducto."','".$Descripcion."','".$Concentracion."','".$Empresa_Id."','".$CodigoLista."'";
				$html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);					
				
				$html .= "  <table width=\"90%\" class=\"modulo_table_list_title\" border=\"0\"  align=\"center\">";
				$html .= "	  <tr  align=\" class=\"modulo_table_list_title\" >\n";
				$html .= "      <td >CODIGO PRODUCTO</td>\n";
				$html .= "      <td >DESCRIPCION</td>\n";
        $html .= "      <td >PORCENTAJE ADICIONAL</td>";
        $html .= "      <td >PORCENTAJE</td>";
        $html .= "      <td >VALOR INICIAL</td>";
        $html .= "      <td >PRECIO</td>";
        $html .= "      <td >MOD</td>";
				$html .= "      <td >SUPR.</td>\n";
				$html .= "  </tr>\n";
				
				foreach($Productos as $k => $valor)
				{
  				$html .= "  <tr class=\"modulo_list_claro\">\n";
  				$html .= "      <td  align=\"center\">".$valor['codigo_producto']." </td>\n";
  				$html .= "      <td align=\"left\">".$valor['descripcion']."</td>\n";
				  if($valor['sw_porcentaje']=="1")
          {
          $imagen = "si.png";
          }
          else
              $imagen = "no.png";
          $html .= "      <td align=\"center\">";
          $html .="  <img title=\"Maneja Porcentaje?\" src=\"".GetThemePath()."/images/".$imagen."\" border=\"0\"></a>\n";
          $html .="       </td>\n";
          
          
          $html .= "      <td align=\"left\">".$valor['porcentaje']."</td>\n";
          $html .= "      <td align=\"left\">".$valor['valor_inicial']."</td>\n";
          $html .= "      <td align=\"left\">".$valor['precio']."</td>\n";
        //AdicionarProducto(codigo_producto,Descripcion,precio,sw_porcentaje,porcentaje,valor_inicial)";
        $html .= "      <td align=\"center\">\n";
				$html .= "		<a onclick=\"AdicionarProducto('".$valor['codigo_producto']."','".$valor['descripcion']."','".$valor['precio']."','".$valor['sw_porcentaje']."','".$valor['porcentaje']."','".$valor['valor_inicial']."','1');\">";
				$html .="<img title=\"MODIFICAR PRODUCTOS\" src=\"".GetThemePath()."/images/pmodificar.png\" border=\"0\"></a>\n";
  				$html .= "      </td>\n";
        
        $html .= "      <td align=\"center\">\n";
				$html .= "		<a onclick=\"xajax_EliminarItemListaPrecios('".$Empresa_Id."','".$CodigoLista."','".$valor['codigo_producto']."');\">";
				$html .="<img title=\"ADICIONAR PRODUCTOS\" src=\"".GetThemePath()."/images/delete.gif\" border=\"0\"></a>\n";
  				$html .= "      </td>\n";
  				$html .= "  </tr>\n";
				}
				$html .= "	</table>\n";
				
					
					
					
					$objResponse->assign("ListadoProductosLista","innerHTML",$objResponse->setTildes($html));
					return $objResponse;
		}
 
 
 
?>