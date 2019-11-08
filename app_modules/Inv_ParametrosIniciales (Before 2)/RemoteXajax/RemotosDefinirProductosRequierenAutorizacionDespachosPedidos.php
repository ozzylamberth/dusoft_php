<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: RemotosDefinirProductosRequierenAutorizacionDespachosPedidos.php,v 1.1 2009/11/27 15:36:55 mauricio Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */
  /**
  * Archivo Xajax
  * Tiene como responsabilidad hacer el manejo de las funciones
  * que son invocadas por medio de xajax
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina Santacruz
  */
 
 
 function Buscador($CodigoEmpresa,$NombreEmpresa)
 {
 $objResponse = new xajaxResponse();


  
 $objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
  $objResponse->call("MostrarSpan");
          return $objResponse;
 
 }
 
 
 
 
 
 
 
 
 
 function ProductosT($offset)
 {
 $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasDefinirProductosRequierenAutorizacionDespachosPedidos","classes","app","Inv_ParametrosIniciales");
  
  $productos=$sql->Lista_Productos_Creados($offset);
 
 
 $action['paginador'] = "Paginador_2(";
    
    //1) Primer paso para la implementacion de un Buscador, Crear un objeto de la clase buscador
    // 1) Primer paso: Objeto paginador
    
    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);     
  $html .="<center>";  
  $html .= "<fieldset class=\"fieldset\">\n";
        $html .= "  <legend class=\"normal_10AN\">PRODUCTOS PARA ASIGNAR % COSTO VENTA - EMPRESA: ".$NombreEmpresa."</legend>\n";
        
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        
		$html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td width=\"5%\">GRUPO</td>\n";
        $html .= "      <td width=\"10%\">CLASE</td>\n";
        $html .= "      <td width=\"10%\">Princ.Activo/SubClase</td>\n";
        $html .= "      <td width=\"10%\">CODIGO-PRODUCTO</td>\n";
        $html .= "      <td width=\"10%\">DESCRIPCION</td>\n";
        $html .= "      <td width=\"10%\">PRESENTACION</td>\n";
        $html .= "      <td width=\"10%\">FORMA FARMACOLOGICA</td>\n";
        $html .= "      <td width=\"5%\">IVA</td>\n";
        $html .= "      <td width=\"5%\">MDTO</td>\n";
        $html .= "      <td width=\"5%\">RQ.AUTORIZACION</td>\n";
        
        
				
        $html .= "    </tr>\n";

        $est = "modulo_list_claro";
        $bck = "#DDDDDD";
        foreach($productos as $key => $prod)
        {
          ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
          ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

          $html .= "    <tr class=\"".$est."\" >\n";
          $html .= "      <td >".$prod['grupo']."</td><td>".$prod['clase']." </td>\n";
          $html .= "      <td>".$prod['subclase']." </td>";
          $html .= "      <td>".$prod['codigo_producto']." </td>";
          $html .= "      <td>".$prod['descripcion']." </td>";
          $html .= "      <td>".$prod['presentacion']." </td>";
          $html .= "      <td>".$prod['forma']." </td>";
          $html .= "      <td>".$prod['iva']." </td>";
          if($prod['sw_medicamento']==1)
          $html .= "<td align=\"center\"><img title=\"MEDICAMENTO\" src=\"".GetThemePath()."/images/si.png\" border=\"0\"></td>\n";
            else
              $html .= "<td align=\"center\"><img title=\"INSUMO\" src=\"".GetThemePath()."/images/no.png\" border=\"0\"></td>\n";
          
          $html .= "      <td align=\"center\">\n";
                    if($prod['sw_requiereautorizacion_despachospedidos']=='1')
                    {
                            $html .= "        <a href=\"#\" onclick=\"xajax_AutorizaDesautoriza('inventarios_productos','sw_requiereautorizacion_despachospedidos','0','".$prod['codigo_producto']."','codigo_producto','".$offset."')\">\n";
                            $html .= "          <img title=\"REQUIERE AUTORIZACION\" src=\"".GetThemePath()."/images/alarma.gif\" border=\"0\">\n";
                                                           // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
                            $html .= "        </a>\n";
                    }
                      else
                          {
                            $html .= "        <a href=\"#\" onclick=\"xajax_AutorizaDesautoriza('inventarios_productos','sw_requiereautorizacion_despachospedidos','1','".$prod['codigo_producto']."','codigo_producto','".$offset."')\">\n";
                            $html .= "          <img title=\"NO REQUIERE AUTORIZACION\" src=\"".GetThemePath()."/images/ok.png\" border=\"0\">\n";
                                                           // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
                            $html .= "        </a>\n";
                          }
          $html .= "      </td>\n";
            
        }
        
        $html .= "    </table>\n";
        $html .= "</fieldset><br>\n";
        $html .= "</center>";

 $objResponse->assign("listado_productos","innerHTML",$objResponse->setTildes($html));
          return $objResponse;
 
 } 
 
 function AutorizaDesautoriza($tabla,$campo,$valor,$id,$campo_id,$offset)
  {
  $objResponse = new xajaxResponse();
  $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ClaseUtil"); //cargo Funcion General para cambiar de estado un registro.
    $sql->Cambio_Estado($tabla,$campo,$valor,$id,$campo_id);
        
    $objResponse->script("xajax_ProductosT('".$offset."');");
    return $objResponse;	
  
  return $objResponse;
  }
 
 
 function buscar_clases_grupo($CodigoGrupo)
 {
 $objResponse = new xajaxResponse();
  
   $sql = AutoCarga::factory("ConsultasDefinirProductosRequierenAutorizacionDespachosPedidos","classes","app","Inv_ParametrosIniciales");
   
    $Clases=$sql->ListadoClasesxGrupo($CodigoGrupo);
    
    
    $SelectClases = '<SELECT NAME="clase_id" SIZE="1" class="select" style="width:100%;height:100%">';
    $SelectClases .= '<OPTION VALUE="" onclick="xajax_buscar_subclases_clase_grupo(\''.$CodigoGrupo.'\',this.value);"></OPTION>';
    foreach($Clases as $key => $cla)
			{
				$SelectClases .= '<OPTION VALUE="'.$cla['laboratorio_id'].'" onclick="xajax_buscar_subclases_clase_grupo(\''.$CodigoGrupo.'\',this.value);">'.$cla['descripcion'].'</OPTION>';
			}
			$SelectClases .='</SELECT>';   
    
 $html=$SelectClases;
 $mensaje = "Seleccione Grupo y Clase...";
 $objResponse->assign("select_clases","innerHTML",$objResponse->setTildes($html));
 $objResponse->assign("select_subclases","innerHTML",$mensaje);
 
 return $objResponse;
 
 }
  
  function buscar_subclases_clase_grupo($CodigoGrupo,$CodigoClase)
 {
 $objResponse = new xajaxResponse();
  
   $sql = AutoCarga::factory("ConsultasDefinirProductosRequierenAutorizacionDespachosPedidos","classes","app","Inv_ParametrosIniciales");
   
    $SubClases=$sql->ListadoSubClasesConClase($CodigoGrupo,$CodigoClase);
    
    
    $SelectSubClases = '<SELECT NAME="subclase_id" SIZE="1" class="select" style="width:100%;height:100%">';
    $SelectSubClases .= '<OPTION VALUE=""></OPTION>';
    foreach($SubClases as $key => $sub)
			{
				$SelectSubClases .= '<OPTION VALUE="'.$sub['molecula_id'].'">'.$sub['molecula'].'-'.$sub['concentracion'].'-'.$sub['unidad'].'</OPTION>';
			}
			$SelectSubClases .='</SELECT>';   
    
 $html=$SelectSubClases;
 $objResponse->assign("select_subclases","innerHTML",$objResponse->setTildes($html));
  
 return $objResponse;
 
 }
 
 
  function Productos_CreadosBuscados($Grupo_Id,$Clase_Id,$SubClase_Id,$Descripcion,$CodAnatofarmacologico,$CodigoBarras,$offset)
 {
 $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasDefinirProductosRequierenAutorizacionDespachosPedidos","classes","app","Inv_ParametrosIniciales");
  
  $productos=$sql->Lista_Productos_CreadosBuscados($Grupo_Id,$Clase_Id,$SubClase_Id,$Descripcion,$CodAnatofarmacologico,$CodigoBarras,$offset);
 
 
 $action['paginador'] = "Paginador_3('".$Grupo_Id."','".$Clase_Id."','".$SubClase_Id."','".$Descripcion."','".$CodAnatofarmacologico."','".$CodigoBarras."'";
    
    //1) Primer paso para la implementacion de un Buscador, Crear un objeto de la clase buscador
    // 1) Primer paso: Objeto paginador
    
    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);     
  $html .="<center>";  
  $html .= "<fieldset class=\"fieldset\">\n";
        $html .= "  <legend class=\"normal_10AN\">PRODUCTOS PARA ASIGNAR % COSTO VENTA - EMPRESA: ".$NombreEmpresa."</legend>\n";
        
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        
		$html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td width=\"5%\">GRUPO</td>\n";
        $html .= "      <td width=\"10%\">CLASE</td>\n";
        $html .= "      <td width=\"10%\">Princ.Activo/SubClase</td>\n";
        $html .= "      <td width=\"10%\">CODIGO-PRODUCTO</td>\n";
        $html .= "      <td width=\"10%\">DESCRIPCION</td>\n";
        $html .= "      <td width=\"10%\">PRESENTACION</td>\n";
        $html .= "      <td width=\"10%\">FORMA FARMACOLOGICA</td>\n";
        $html .= "      <td width=\"5%\">IVA</td>\n";
        $html .= "      <td width=\"5%\">MDTO</td>\n";
        $html .= "      <td width=\"5%\">RQ.AUTORIZACION</td>\n";
        
        
				
        $html .= "    </tr>\n";

        $est = "modulo_list_claro";
        $bck = "#DDDDDD";
        foreach($productos as $key => $prod)
        {
          ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
          ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

          $html .= "    <tr class=\"".$est."\" >\n";
          $html .= "      <td >".$prod['grupo']."</td><td>".$prod['clase']." </td>\n";
          $html .= "      <td>".$prod['subclase']." </td>";
          $html .= "      <td>".$prod['codigo_producto']." </td>";
          $html .= "      <td>".$prod['descripcion']." </td>";
          $html .= "      <td>".$prod['presentacion']." </td>";
          $html .= "      <td>".$prod['forma']." </td>";
          $html .= "      <td>".$prod['iva']." </td>";
          if($prod['sw_medicamento']==1)
          $html .= "<td align=\"center\"><img title=\"MEDICAMENTO\" src=\"".GetThemePath()."/images/si.png\" border=\"0\"></td>\n";
            else
              $html .= "<td align=\"center\"><img title=\"INSUMO\" src=\"".GetThemePath()."/images/no.png\" border=\"0\"></td>\n";
          
          $html .= "      <td align=\"center\">\n";
                    if($prod['sw_requiereautorizacion_despachospedidos']=='1')
                    {
                            $html .= "        <a href=\"#\" onclick=\"xajax_AutorizaDesautoriza('inventarios_productos','sw_requiereautorizacion_despachospedidos','0','".$prod['codigo_producto']."','codigo_producto','".$offset."')\">\n";
                            $html .= "          <img title=\"REQUIERE AUTORIZACION\" src=\"".GetThemePath()."/images/alarma.gif\" border=\"0\">\n";
                                                           // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
                            $html .= "        </a>\n";
                    }
                      else
                          {
                            $html .= "        <a href=\"#\" onclick=\"xajax_AutorizaDesautoriza('inventarios_productos','sw_requiereautorizacion_despachospedidos','1','".$prod['codigo_producto']."','codigo_producto','".$offset."')\">\n";
                            $html .= "          <img title=\"NO REQUIERE AUTORIZACION\" src=\"".GetThemePath()."/images/ok.png\" border=\"0\">\n";
                                                           // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
                            $html .= "        </a>\n";
                          }
          $html .= "      </td>\n";
            
        }
        
        $html .= "    </table>\n";
        $html .= "</fieldset><br>\n";
        $html .= "</center>";
          $objResponse->assign("listado_productos","innerHTML",$objResponse->setTildes($html));
          return $objResponse;
 
 }
 
 
 function AsignarCostosXProducto($CodigoProducto,$EmpresaId,$NombreProducto)
  {
  $objResponse = new xajaxResponse();
  
  $sql=AutoCarga::factory("ConsultasDefinirProductosRequierenAutorizacionDespachosPedidos", "classes", "app","Inv_ParametrosIniciales");
  $Codigo=$CodigoProducto."".$EmpresaId;
  
  $datos=$sql->BuscarDaticos_CVP($Codigo);  
  
  if(empty($datos))
      {
      $Datos['empresa_id']=$EmpresaId;
      $Datos['codigo_producto']=$CodigoProducto;
      $Datos['codigo']=$Codigo;
      $Datos['porcentaje_venta']='0';
      $rta=$sql->InsertarCostoVentaXProducto($Datos); 
      }
   $datos=$sql->BuscarDaticos_CVP($Codigo);  
   
    $html .= "<center>";
    $html .= "<fieldset class=\"fieldset\" style=\"width:80%\">\n";
    $html .= "  <legend class=\"normal_10AN\">ASIGNAR %COSTO DE VENTA A PRODUCTO: ".$NombreProducto."</legend>\n";
    $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        
		      $html .= "    <tr class=\"modulo_list_claro\" >\n";
          $html .= "      <td  width=\"50%\" align=\"center\" class=\"formulacion_table_list\">Porcentaje de venta :</td>
          <td align=\"center\" width=\"50%\"><div id=\"campo\" ><a href=\"#\" onclick=\"xajax_FormaDinamica2('".$datos[0]['porcentaje_venta']."','".$EmpresaId."','".$Codigo."','".$NombreProducto."','".$CodigoProducto."');\">".$datos[0]['porcentaje_venta']."%</a></div></td>
          </tr>\n";
         
        $html .= "    </table>\n";
        
        $html .= "</fieldset><br>\n";
        $html .= "    </center>\n";
  
  $objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
  $objResponse->call("MostrarSpan");
  return $objResponse;
  }
 
 
 
 
 
 
 
 /*
  * Funcion Que Refrescará el listado de Tipos de Insumos a desplegar en la pagina.
  * BuscarTercero
  */  
  function AsignarCPxEM($Empresa_Id,$TipoProducto)
  {
  $objResponse = new xajaxResponse();
			$sql=AutoCarga::factory("ConsultasDefinirProductosRequierenAutorizacionDespachosPedidos", "classes", "app","Inv_ParametrosIniciales");
			$rta=$sql->InsertarCostoVentaTipoProducto($Empresa_Id,$TipoProducto); 

      if($rta)
        {
        $objResponse->script("xajax_ListarTiposDeProductosSinAsignar('".$Empresa_Id."');");
        $objResponse->script("xajax_ListarTiposParaAsignarCostos('".$Empresa_Id."');");
        }
        else
        $objResponse->alert("Error!!!");
      return $objResponse;
  }
 
 
?>