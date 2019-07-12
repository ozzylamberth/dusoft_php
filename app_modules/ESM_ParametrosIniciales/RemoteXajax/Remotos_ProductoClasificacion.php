<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: Remotos_ProductoClasificacion.php
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
 
  /*
  * Funcion Que Refrescará el listado de Estados de Documentos a desplegar en la pagina.
  */  
    
  function Listado_ProductosEmpresa($empresa_id,$codigo_producto,$descripcion,$offset)
  {
	$objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("Consultas_ProductoClasificacion","classes","app","ESM_ParametrosIniciales");

	$ProductosEmpresa=$sql->Listado_ProductosEmpresa($empresa_id,$codigo_producto,$descripcion,$offset);
  $TiposClasificacionProductos = $sql->Obtener_ClasificacionesProductos();
	$action['paginador'] = "Paginador('".$empresa_id."','".$codigo_producto."','".$descripcion."'";


	$pghtml = AutoCarga::factory("ClaseHTML");
	$html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);
	$html .= "<center>";
	$html .= "<fieldset style=\"width:80%\" class=\"fieldset\">\n";
	$html .= "  <legend class=\"normal_10AN\">PRODUCTOS ASIGNADOS A LA EMPRESA SELECCIONADA</legend>\n";

	$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";

	$html .= "    <tr class=\"formulacion_table_list\">\n";
	$html .= "      <td width=\"15%\">CODIGO PRODUCTO</td>\n";
	$html .= "      <td width=\"50%\">DESCRIPCION</td>\n";
	$html .= "      <td width=\"25%\">CLASIFICACION</td>\n";
	$html .= "      <td width=\"7%\">SELECCIONAR</td>\n";
	$html .= "      <td width=\"7%\"><img title=\"Quitar la Clasificacion\" src=\"".GetThemePath()."/images/delete2.gif\" border=\"0\"></td>\n";


	$html .= "    </tr>\n";

	$est = "modulo_list_claro";
	$bck = "#DDDDDD";
  $i=0;
 // print_r($ProductosEmpresa);
	foreach($ProductosEmpresa as $key => $ED)
	{
	($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
	($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";
  
      $select  = "<select class=\"select\" style=\"width:100%;\" name=\"tipo_clasificacion_id".$i."\" id=\"tipo_clasificacion_id".$i."\">";      
      $select .= "<option value=\"\">SELECCIONAR</option>";
      $selected = "";
      foreach($TiposClasificacionProductos as $key=>$tcp)
      {
        if(trim($ED['tipo_clasificacion_id'])==trim($tcp['tipo_clasificacion_id']))
        {
        $selected = "selected";
        }
        else
            $selected = ""; 
        $select .= "<option ".$selected." value=\"".$tcp['tipo_clasificacion_id']."\">".$tcp['descripcion']."</option>";
      }
      $select .= "</select>";
  
	$html .= "    <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
	$html .= "      <td>".$ED['codigo_producto']."</td>\n";
	$html .= "      <td>".$ED['descripcion']." </td>\n";
	$html .= "      <td>".$select." </td>\n";
	$html .= "      <td align=\"center\" id=\"ok".$i."\">\n";
	$html .= "        <input type=\"checkbox\" value=\"".$ED['codigo_producto']."\" class=\"input-checkbox\" name=\"".$i."\" id=\"".$i."\">\n";
  $html .= "        <input type=\"hidden\" value=\"".$empresa_id."\" name=\"empresa_id\" id=\"empresa_id\">\n";
	$html .= "      </td>\n";
  $html .= "      <td align=\"center\" id=\"ok".$i."\">\n";
	$html .= "        <input type=\"checkbox\" value=\"1\" class=\"input-checkbox\" name=\"eliminar".$i."\" id=\"eliminar".$i."\">\n";
  $html .= "      </td>\n";

	$html .= "    </tr>\n";
  $i++;
	}
	$html .= "<center>";

  $html .= "    <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
  $html .= "      <td colspan=\"4\" align=\"center\">";
  $html .= "     <input type=\"hidden\" value=\"".$i."\" name=\"registros\" id=\"registros\">";
  $html .= "     <input class=\"input-submit\" type=\"button\" value=\"GUARDAR\" onclick=\"xajax_Asignar_ProductoClasificacion(xajax.getFormValues('Formulario_ProductosClasificacion'));\">";
  $html .= "     </td>\n";
  $html .= "    </tr>";
	$html .= "    </table>\n";
	$html .= "</fieldset><br>\n";
          
  $objResponse->assign("Listado_Productos","innerHTML",$objResponse->setTildes($html));
  return $objResponse;
  }
 
  
  function Asignar_ProductoClasificacion($Formulario)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("Consultas_ProductoClasificacion","classes","app","ESM_ParametrosIniciales");
  
  for($i=0;$i<$Formulario['registros'];$i++)
  {
    if($Formulario[$i]!="")
    {
      if($Formulario['tipo_clasificacion_id'.$i]!="")
        {
        $datos=$sql->Consultar_ClasificacionProducto($Formulario['empresa_id'],$Formulario[$i]);
        
        if(empty($datos))
          $token=$sql->Insertar_ClasificacionProducto($Formulario[$i],$Formulario['tipo_clasificacion_id'.$i],$Formulario['empresa_id']);
          else
              {
              if($Formulario['eliminar'.$i]=='1')
                {
                $token=$sql->Eliminar_ClasificacionProducto($Formulario[$i],$Formulario['tipo_clasificacion_id'.$i],$Formulario['empresa_id']);
                }
                    else
                    {
                    $token=$sql->Modificar_ClasificacionProducto($Formulario[$i],$Formulario['tipo_clasificacion_id'.$i],$Formulario['empresa_id']);
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
  
?>