<?php
 /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.11 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz 
  */
  
   /**
  * Funcion que permite seleccionar el numero de contrato relacionado a un Proveedor  para modificar el contrato
  * @param string $noId cadena numero de identificacion del proveedor
  * @param string $tipoId cadena con el tipo de identificacion del proveedor
  * @return Object $objResponse objeto de respuesta al formulario  
  */
      
		function ListaPrecios($Formulario,$offset)
		{
  			$objResponse = new xajaxResponse();
			$sql = AutoCarga::factory("Contratacion_ListaPreciosSQL", "classes", "app", "Contratacion_ListaPrecios");
			
			$datos =$sql->ObtenerListaPrecios($Formulario,$_REQUEST['datos']['empresa'],$offset);
			
			if(!empty($datos))
			{
				
				$action['paginador'] = "Paginador(''";
       
				$pghtml = AutoCarga::factory("ClaseHTML");
				$html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);  
				//$url=ModuloGetURL("app","Contratacion_ListaPrecios","controller","MenuPrincipal");
		//		print_r($_REQUEST);
				
				$html .= "<table width=\"50%\" class=\"modulo_table_list_title\" align=\"center\">";
				$html .= "  <tr align=\" class=\"modulo_table_list_title\" >\n";
				
				$html .= "      <td width=\"5%\">CODIGO LISTA</td>\n";
				$html .= "      <td width=\"25%\">DESCRIPCION</td>\n";
				$html .= "      <td width=\"3%\">OP</td>\n";
				$html .= "  </tr>\n";
				$est = "modulo_list_claro"; $back = "#DDDDDD";
				foreach($datos as $key => $dtl)
				{
		      //$url_final= $url."&num_contrato=".$dtl['num_contrato']."&plan_descripcion=".$dtl['plan_descripcion']."&datos[empresa]=".$_REQUEST['datos']['empresa']."";
          $html .= "  <tr class=\"modulo_list_claro\">\n";
          $html .= "      <td align=\"left\">".$dtl['codigo_lista']."</td>\n";
          $html .= "      <td align=\"left\">".$dtl['descripcion']."</td>\n";
          $html .= "      <td align=\"center\">\n";
					$html .= "      <a onclick=\"xajax_ListaPreciosDetalle('".$dtl['codigo_lista']."','1');\">\n";
					$html .= "      <img src=\"".GetThemePath()."/images/producto_precio.png\" border=\"0\" title=\"DefinirListaDePrecios\">";
					$html .= "      </a>\n";
					$html .= "      </td>\n";
          $html .= "  </tr>\n";
				}
             
	        $html .= "	</table><br>\n";

	        $html .= "	<br>\n";
	        }
	        else
	        {
	           $html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
	        }
			
			$objResponse->assign("ListaPrecios","innerHTML",$objResponse->setTildes($html));
			return $objResponse;
		}


	function ListaPreciosDetalle($codigo_lista,$offset)
		{
      $objResponse = new xajaxResponse();
      $sql = AutoCarga::factory("Contratacion_ListaPreciosSQL", "classes", "app", "Contratacion_ListaPrecios");
      
      $html .= "	<table align=\"center\" border=\"0\" width=\"60%\" class=\"modulo_table_list\">\n";
      $html .= "		<tr class=\"formulacion_table_list\" >\n";
      $html .= "      <td>";
      $html .= "      GRUPO";
      $html .= "      </td>";
      $html .= "      <td>";
      $html .= "      <input type=\"text\" class=\"input-text\" name=\"grupo_id\" id=\"grupo_id\" style=\"width:100%\">";
      $html .= "      </td>";
      $html .= "      <td>";
      $html .= "      CLASE";
      $html .= "      </td>";
      $html .= "      <td>";
      $html .= "      <input type=\"text\" class=\"input-text\" name=\"clase_id\" id=\"clase_id\" style=\"width:100%\">";
      $html .= "      </td>";
      $html .= "      <td>";
      $html .= "      SUBCLASE";
      $html .= "      </td>";
      $html .= "      <td>";
      $html .= "      <input type=\"text\" class=\"input-text\" name=\"subclase_id\" id=\"subclase_id\" style=\"width:100%\">";
      $html .= "      </td>";
      $html .= "		</tr>\n";
      
      $html .= "		<tr class=\"formulacion_table_list\" >\n";
      $html .= "      <td>";
      $html .= "      CODIGO PRODUCTO";
      $html .= "      </td>";
      $html .= "      <td>";
      $html .= "      <input type=\"text\" class=\"input-text\" name=\"codigo_descripcion\" id=\"codigo_descripcion\" style=\"width:100%\">";
      $html .= "      </td>";
      $html .= "      <td>";
      $html .= "      DESCRIPCION";
      $html .= "      </td>";
      $html .= "      <td>";
      $html .= "      <input type=\"text\" class=\"input-text\" name=\"descripcion\" id=\"descripcion\" style=\"width:100%\">";
      $html .= "      </td>";
      $html .= "      <td colspan=\"2\">";
      $html .= "      <input type=\"hidden\" name=\"codigo_lista\" id=\"codigo_lista\" value=\"".$codigo_lista."\" >";
      $html .= "      <input type=\"button\" class=\"input-submit\" value=\"buscar\" style=\"width:100%\" onclick=\"xajax_ListaPrecios_Detalle(xajax.getFormValues('buscador_listaproductos'));\" >";
      $html .= "      </td>";
      $html .= "		</tr>\n";
      $html .= "  </table>\n";
 
      $objResponse->assign("ListaPreciosDetalle","innerHTML",$html);
      return $objResponse;
		}


		function ListaPrecios_Detalle($Formulario,$offset)
		{
      $objResponse = new xajaxResponse();

      $sql = AutoCarga::factory("Contratacion_ListaPreciosSQL", "classes", "app", "Contratacion_ListaPrecios");
       $datos =$sql->ConsultarListaDetalle($Formulario,$_REQUEST['datos']['empresa_id'],$offset);

      $pghtml = AutoCarga::factory("ClaseHTML");
      
      if(!empty($datos))
      {
      $action['paginador'] = "Paginador(xajax.getFormValues('buscador_listaproductos')";
      $paginador = $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);  
      $html .= "   <form id=\"ProductosEnLista\" name=\"ProductosEnLista\"> ";
      $html .= "	<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">\n";
     
      $html .= "		<tr class=\"formulacion_table_list\" >\n";
      $html .= "			<td width=\"10%\">CODIGO PRODUCTO</td>\n";
      $html .= "			<td width=\"30%\" >DESCRIPCION</td>\n";
      $html .= "			<td width=\"8%\">PRECIO</td>\n";
      $html .= "			<td width=\"5%\">PORC. ADICIONAL</td>\n";
      $html .= "			<td width=\"5%\">PORCENTAJE</td>\n";
      $html .= "			<td width=\"5%\">SELECCIONAR</td>\n";
      $html .= "			<td width=\"5%\">ELIMINAR</td>\n";
      //$html .= "			<td width=\"30%\">ADICIONAR</td>\n";
      $html .= "		</tr>\n";
      $html .= "		<tr class=\"formulacion_table_list\" >\n";
      $html .= "			<td align=\"center\" colspan=\"8\">PRODUCTOS REGISTRADOS EN LA LISTA</td>";
      $html .= "		</tr>\n";
      
      $est = "modulo_list_claro";
      $bck = "#DDDDDD";
      $i=0;
      foreach($datos as $k1 => $dtl)
      {
      ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
      ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
      
      $html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE'); >\n";
      $html .= "			<td align=\"center\"><b>".$dtl['codigo_producto']."</b></td>\n";
      $html .= "			<td align=\"left\">".$dtl['descripcion']."</td>\n";
      $html .= "			<td align=\"center\"><input type=\"text\" name=\"precio".$i."\" id=\"precio".$i."\" class=\"input-text\" style=\"width:100%\" value=\"".$dtl['precio']."\"></td>\n";
      if($dtl['sw_porcentaje']=='1')
        {
        $checked=" checked ";
        }
        else
            {
            $checked=" ";
            }
       if($dtl['resultado']=='0')
        {
        $disabled=" disabled ";
        }
        else
            {
            $disabled=" ";
            }     
       
      $html .= "";
      $html .= "			<td align=\"center\"><input value=\"1\" type=\"checkbox\" ".$checked." class=\"checkbox\" name=\"sw_porcentaje".$i."\" id=\"sw_porcentaje".$i."\"></td>\n";
      $html .= "			<td align=\"center\"><input type=\"text\" name=\"porcentaje".$i."\" id=\"porcentaje".$i."\" class=\"input-text\" style=\"width:100%\" value=\"".$dtl['porcentaje']."\"></td>\n";
      $html .= "			<td align=\"center\" id=\"ok".$i."\"><input type=\"checkbox\" value=\"".$dtl['codigo_producto']."\" class=\"checkbox\" name=\"".$i."\" id=\"".$i."\"></td>\n";
      $html .= "			<td align=\"center\">
                                          <input type=\"checkbox\" value=\"1\" class=\"checkbox\" name=\"eliminar".$i."\" id=\"eliminar".$i."\" ".$disabled.">
                                          <input type=\"hidden\" name=\"valor_inicial".$i."\" id=\"valor_inicial".$i."\" value=\"".$dtl['valor_inicial']."\">
                                          <input type=\"hidden\" name=\"empresa_id\" id=\"empresa_id\" value=\"".$_REQUEST['datos']['empresa']."\">
                                          <input type=\"hidden\" name=\"codigo_lista\" id=\"codigo_lista\" value=\"".$Formulario['codigo_lista']."\">
                                          <input type=\"hidden\" name=\"registros\" id=\"registros\" value=\"".$i."\">
                                          </td>\n";
      //$html .= "			<td align=\"center\"><input type=\"checkbox\" class=\"checkbox\" name=\"\" id=\"\"></td>\n";
      $html .= "		</tr>\n";
      $i++;
      }
      $html .= "    </table>";
      $boton= "<input type=\"button\" class=\"input-submit\" value=\"GUARDAR CAMBIOS\" onclick=\"xajax_GuardarCambios(xajax.getFormValues('Productos'));\">";
      } 
       else
					      {
					        $html .= "<center>\n";
					        $html .= "  <label class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</label>\n";
					        $html .= "</center>\n";
					      }
      $objResponse->assign("paginador","innerHTML",$paginador);
      $objResponse->assign("BotonListaPreciosRegistrados","innerHTML",$boton);
      $objResponse->assign("ListaPreciosRegistrados","innerHTML",$html);
      $objResponse->script("ListaPreciosRegistrados.style.display=\"\";");
     // $objResponse->script("xajax_BuscadorNoRegistrados('".$Formulario['codigo_lista']."');");
      return $objResponse;
		}
    
    
     function GuardarCambios($Formulario)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("Contratacion_ListaPreciosSQL","classes","app","Contratacion_ListaPrecios");
  $k=0;
  for($i=0;$i<=$Formulario['registros'];$i++)
  {
    if($Formulario[$i]!="")
    {
      if($Formulario['precio'.$i]!="")
        {
        $datos=$sql->Consultar_ProductoLista($Formulario['empresa_id'],$Formulario[$i],$Formulario['codigo_lista']);
        
        if(empty($datos))
          $token=$sql->Insertar_ProductoLista($Formulario['empresa_id'],$Formulario['codigo_lista'],$Formulario[$i],$Formulario['precio'.$i],$Formulario['sw_porcentaje'.$i],$Formulario['porcentaje'.$i],$Formulario['valor_inicial'.$i]);
          else
              {
              if($Formulario['eliminar'.$i]=='1')
                {
                $token=$sql->Eliminar_ProductoLista($Formulario['empresa_id'],$Formulario['codigo_lista'],$Formulario[$i],$Formulario['precio'.$i],$Formulario['sw_porcentaje'.$i],$Formulario['porcentaje'.$i],$Formulario['valor_inicial'.$i]);
                $k++;
                }
                    else
                    {
                    $token=$sql->Modificar_ProductoLista($Formulario['empresa_id'],$Formulario['codigo_lista'],$Formulario[$i],$Formulario['precio'.$i],$Formulario['sw_porcentaje'.$i],$Formulario['porcentaje'.$i],$Formulario['valor_inicial'.$i]);
                    }
              }
          if($token)
              $objResponse->script("document.getElementById('ok".$i."').style.backgroundColor='green';");
              else
                $objResponse->script("document.getElementById('ok".$i."').style.backgroundColor='red';");
        }
        else
            $objResponse->script("document.getElementById('ok".$i."').style.backgroundColor='red';");
    }
  
  }
  
  if($k>0)
  {
  $objResponse->script("xajax_ListaPrecios_Detalle(xajax.getFormValues('buscador_listaproductos'));");
  }
  
  if($token)
  {
  //$objResponse->script("Cerrar('Contenedor');");
 // $objResponse->script("xajax_Listado_ProfesionalesSinEsm(document.getElementById('esm_empresas').value,document.getElementById('nombre').value,'1');");
 // $objResponse->script("xajax_Listado_ProfesionalesEnEsm(document.getElementById('esm_empresas_').value,document.getElementById('nombre_').value,'1');");
  //$objResponse->alert("Proce Exitoso!!");
  }
  else
  $objResponse->alert("Error en el Ingreso...!!");
  
  return $objResponse;
  }
    
/*
function BuscadorNoRegistrados($codigo_lista)
		{
      $objResponse = new xajaxResponse();
      $sql = AutoCarga::factory("Contratacion_ListaPreciosSQL", "classes", "app", "Contratacion_ListaPrecios");
      $html .= "  <br>";
      $html .= "	<table align=\"center\" border=\"0\" width=\"60%\" class=\"modulo_table_list\">\n";
      $html .= "		<tr class=\"formulacion_table_list\" >\n";
      $html .= "      <td>";
      $html .= "      GRUPO";
      $html .= "      </td>";
      $html .= "      <td>";
      $html .= "      <input type=\"text\" class=\"input-text\" name=\"grupo_id\" id=\"grupo_id\" style=\"width:100%\">";
      $html .= "      </td>";
      $html .= "      <td>";
      $html .= "      CLASE";
      $html .= "      </td>";
      $html .= "      <td>";
      $html .= "      <input type=\"text\" class=\"input-text\" name=\"clase_id\" id=\"clase_id\" style=\"width:100%\">";
      $html .= "      </td>";
      $html .= "      <td>";
      $html .= "      SUBCLASE";
      $html .= "      </td>";
      $html .= "      <td>";
      $html .= "      <input type=\"text\" class=\"input-text\" name=\"subclase_id\" id=\"subclase_id\" style=\"width:100%\">";
      $html .= "      </td>";
      $html .= "		</tr>\n";
      
      $html .= "		<tr class=\"formulacion_table_list\" >\n";
      $html .= "      <td>";
      $html .= "      CODIGO PRODUCTO";
      $html .= "      </td>";
      $html .= "      <td>";
      $html .= "      <input type=\"text\" class=\"input-text\" name=\"codigo_descripcion\" id=\"codigo_descripcion\" style=\"width:100%\">";
      $html .= "      </td>";
      $html .= "      <td>";
      $html .= "      DESCRIPCION";
      $html .= "      </td>";
      $html .= "      <td>";
      $html .= "      <input type=\"text\" class=\"input-text\" name=\"descripcion\" id=\"descripcion\" style=\"width:100%\">";
      $html .= "      </td>";
      $html .= "      <td colspan=\"2\">";
      $html .= "      <input type=\"hidden\" name=\"codigo_lista\" id=\"codigo_lista\" value=\"".$codigo_lista."\" >";
      $html .= "      <input type=\"button\" class=\"input-submit\" value=\"buscar\" style=\"width:100%\" onclick=\"xajax_ListaNoRegistrada(xajax.getFormValues('buscador_noRegistrados'));\" >";
      $html .= "      </td>";
      $html .= "		</tr>\n";
      $html .= "  </table>\n";
 
      $objResponse->assign("BuscadorNoRegistrados","innerHTML",$html);
      return $objResponse;
		}
		
function ListaNoRegistrada($Formulario)
		{
      $objResponse = new xajaxResponse();

      $sql = AutoCarga::factory("Contratacion_ListaPreciosSQL", "classes", "app", "Contratacion_ListaPrecios");
      $datos =$sql->ConsultarListaNoRegistrados($Formulario,$_REQUEST['datos']['empresa']);

      //$pghtml = AutoCarga::factory("ClaseHTML");
      
      if(!empty($datos))
      {
      //$action['paginador'] = "Paginador('".$EmpresaId."','".$CodigoProveedorId."','".$fecha_Inicio."','".$Fecha_Final."'";
      //$html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);  
      $html .= "   <form id=\"ProductosEnLista\" name=\"ProductosEnLista\"> ";
      $html .= "	<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">\n";
     
      $html .= "		<tr class=\"formulacion_table_list\" >\n";
      $html .= "			<td width=\"10%\">CODIGO PRODUCTO</td>\n";
      $html .= "			<td width=\"30%\">DESCRIPCION</td>\n";
      $html .= "			<td width=\"8%\">PRECIO</td>\n";
      $html .= "			<td width=\"5%\">PORC. ADICIONAL</td>\n";
      $html .= "			<td width=\"5%\">PORCENTAJE</td>\n";
      $html .= "			<td width=\"5%\">MODIFICAR</td>\n";
      $html .= "			<td width=\"5%\">ELIMINAR</td>\n";
      //$html .= "			<td width=\"30%\">ADICIONAR</td>\n";
      $html .= "		</tr>\n";
      $html .= "		<tr class=\"formulacion_table_list\" >\n";
      $html .= "			<td align=\"center\" colspan=\"8\">PRODUCTOS NO REGISTRADOS EN LA LISTA</td>";
      $html .= "		</tr>\n";
      
      $est = "modulo_list_claro";
      $bck = "#DDDDDD";
      $i=0;
      foreach($datos as $k1 => $dtl)
      {
      ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
      ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
      
      $html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE'); >\n";
      $html .= "			<td align=\"center\"><b>".$dtl['codigo_producto']."</b><input type=\"hidden\" name=\"codigo_producto".$i."\" id=\"codigo_producto".$i."\" value=\"".$dtl['codigo_producto']."\"></td>\n";
      $html .= "			<td align=\"center\">".$dtl['descripcion']."</td>\n";
      $html .= "			<td align=\"center\"><input type=\"text\" name=\"precio".$i."\" id=\"precio".$i."\" class=\"input-text\" style=\"width:100%\" value=\"".$dtl['costo']."\"></td>\n";
      if($dtl['sw_porcentaje']=='1')
        {
        $checked=" checked ";
        }
        else
            {
            $checked=" ";
            }
      $html .= "";
      $html .= "			<td align=\"center\"><input type=\"checkbox\" ".$checked." class=\"checkbox\" name=\"sw_porcentaje".$i."\" id=\"sw_porcentaje".$i."\"></td>\n";
      $html .= "			<td align=\"center\"><input type=\"text\" name=\"porcentaje".$i."\" id=\"porcentaje".$i."\" class=\"input-text\" style=\"width:100%\" value=\"".$dtl['porcentaje']."\"></td>\n";
      $html .= "			<td align=\"center\"><input type=\"checkbox\" class=\"checkbox\" name=\"modi".$i."\" id=\"modi".$i."\"></td>\n";
      $html .= "			<td align=\"center\"><input type=\"checkbox\" class=\"checkbox\" name=\"eli".$i."\" id=\"eli".$i."\"></td>\n";
      //$html .= "			<td align=\"center\"><input type=\"checkbox\" class=\"checkbox\" name=\"\" id=\"\"></td>\n";
      $html .= "		</tr>\n";
      $i++;
      }
      $html .= "    </table>";
      $boton= "<input type=\"button\" class=\"input-submit\" value=\"ADICIONAR A LA LISTA\" onclick=\"xajax_IngresarALista(xajax.getFormValues('ProductosFueraDeLista'));\">";
      } 
       else
					      {
					        $html .= "<center>\n";
					        $html .= "  <label class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</label>\n";
					        $html .= "</center>\n";
					      }
      $objResponse->assign("BotonListaPreciosNoRegistrados","innerHTML",$boton);
      $objResponse->assign("ListaPreciosNoRegistrados","innerHTML",$html);
      $objResponse->script("ListaPreciosNoRegistrados.style.display=\"\";");
      return $objResponse;
		}*/
?>