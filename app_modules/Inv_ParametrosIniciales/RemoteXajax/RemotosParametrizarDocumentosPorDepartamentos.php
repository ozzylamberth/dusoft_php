<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: RemotosParametrizarDocumentosPorDepartamentos.php
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
    
  function CentrosDeUtilidad($EmpresaId,$Div,$url,$NombreEmpresa)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasParametrizarDocumentosPorDepartamentos","classes","app","Inv_ParametrosIniciales");
  
  $CentrosUtilidad=$sql->CentroUtilidadXEmpresa($EmpresaId);
  
      $html .= "<fieldset class=\"fieldset\" style=\"width:80%\">\n";
      $html .= "  <legend class=\"normal_10AN\">Centros De Utilidad</legend>\n";
          
      $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          
          $est = "modulo_list_claro";
          $bck = "#DDDDDD";
          foreach($CentrosUtilidad as $key => $CU)
          {
            ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
            ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

            $html .= "    <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
            $html .= "      <td>";
            $html .= "<a href=\"#\" onclick=\"xajax_UnidadesFuncionales('".$EmpresaId."','".$CU['centro_utilidad']."','".$url."','".$NombreEmpresa."','".$CU['descripcion']."')\">\n";
            $html .= "<img title=\"Continuar...\" src=\"".GetThemePath()."/images/flecha_der.gif\" border=\"0\">   ".$CU['descripcion']."</a>";
            $html .= "</td>\n";
            $html .= "   </tr>";
            
          }
          
          $html .= "    </table>\n";
          $html .= "</fieldset>\n";
          
          $objResponse->assign($Div,"innerHTML",$objResponse->setTildes($html));
          return $objResponse;
          
  }
  
  function UnidadesFuncionales($EmpresaId,$CentroUtilidad,$url,$NombreEmpresa,$NCentroUtilidad)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasParametrizarDocumentosPorDepartamentos","classes","app","Inv_ParametrosIniciales");
  //$url=ModuloGetURL("app","Inv_ParametrosIniciales","controller","AsignarDocumentosABodegas");
  $UnidadesFuncionales=$sql->UnidadesFuncionalesXCentroUtilXEmpresa($EmpresaId,$CentroUtilidad);
       
      $html .= ThemeAbrirTabla("Unidades Funcionales de: ".$NombreEmpresa." Centro Utilidad: ".$NCentroUtilidad."");
      $html .= "<center>";
      $html .= "<fieldset class=\"fieldset\" style=\"width:70%\">\n";
      $html .= "  <legend class=\"normal_10AN\">UNIDADES FUNCIONALES</legend>\n";
          
      $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          
          $est = "modulo_list_claro";
          $bck = "#DDDDDD";
          foreach($UnidadesFuncionales as $key => $BO)
          {
            ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
            ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

            $html .= "    <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
            $html .= "      <td>";
            $html .= "<a href=\"".$url."&empresa_id=".$EmpresaId."&centro_utilidad=".$CentroUtilidad."&unidad_funcional=".$BO['unidad_funcional']."\" \">\n";
            $html .= "<li> <img title=\"Continuar...\" src=\"".GetThemePath()."/images/flecha_der.gif\" border=\"0\"> ".$BO['descripcion']."</li></a>";
            $html .= "</td>\n";
            $html .= "   </tr>";
            
          }
          
          
          $html .= "    </table>\n";
          $html .= "</fieldset><br>\n";
          $html .= "</center>";
          $html .= ThemeCerrarTabla();
          
          $objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
          //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
          $objResponse->call("MostrarSpan");
          return $objResponse;
          
  }
  

  function DepartamentosT($Empresa_Id,$CentroUtilidad,$UnidadFuncional,$Departamento_Id,$Descripcion,$offset)
  {
  $objResponse = new xajaxResponse();
  
  $sql = AutoCarga::factory("ConsultasParametrizarDocumentosPorDepartamentos","classes","app","Inv_ParametrosIniciales");
  
  if($Departamento_Id=="" && $Descripcion=="")
  $Departamentos=$sql->Listar_Departamentos($Empresa_Id,$CentroUtilidad,$UnidadFuncional,$offset);
    else
        $Departamentos=$sql->Listar_DepartamentosBuscados($Empresa_Id,$CentroUtilidad,$UnidadFuncional,$Departamento_Id,$Descripcion,$offset);
        
  $action['paginador'] = "PaginadorDepartamentos('".$Empresa_Id."','".$CentroUtilidad."','".$UnidadFuncional."','".$Departamento_Id."','".$Descripcion."'";
  
  $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);  
  
  
     $html .= "<center>";
      $html .= "<fieldset class=\"fieldset\" style=\"width:70%\">\n";
   $html .= "  <legend class=\"normal_10AN\">DEPARTAMENTOS</legend>\n";
        
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        
		$html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td width=\"5%\">DEPTO. ID</td>\n";
        $html .= "      <td width=\"40%\">DEPARTAMENTO</td>\n";
        $html .= "      <td width=\"10%\">UBICACION</td>\n";
        $html .= "      <td width=\"10%\">SELECCIONAR</td>\n";
        $html .= "      <td width=\"10%\">VER</td>\n";
				
        $html .= "    </tr>\n";

        $est = "modulo_list_claro";
        $bck = "#DDDDDD";
        foreach($Departamentos as $key => $doc)
        {
          ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
          ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

          $html .= "    <tr align=\"center\" class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
          $html .= "      <td >".$doc['departamento']."</td><td>".$doc['descripcion']." </td>\n";
          $html .= "      <td >".$doc['ubicacion']."</td>";
          
          $html .= "<td align=\"center\">
                <a href=\"#\" onclick=\"xajax_ListarTiposDocumentosNoAsignados('".$doc['departamento']."','".$Empresa_Id."','".$CentroUtilidad."','".$UnidadFuncional."','".$Departamento_Id."','".$Descripcion."')\">\n";
          $html .="<img title=\"SELECCIONAR\" src=\"".GetThemePath()."/images/flecha_der.gif\" border=\"0\"></a></td>\n";
          
                                                                      //$Empresa_Id,$CentroUtilidad,$Bodega,$Documento_Id,$Descripcion,$Prefijo,$offset
          $html .= "<td align=\"center\">
                <a href=\"#\" onclick=\"xajax_Listar_TiposDocumentosAsignadosADepartamentos('".$doc['departamento']."')\">\n";
          $html .="<img title=\"VER\" src=\"".GetThemePath()."/images/pconsultar.png\" border=\"0\"></a></td>\n";
          $html .= "      </tr>";  
        }
        
        
        
        $html .= "    </table>\n";
        
        $html .= "</fieldset><br>\n";
        $html .= "    </center>\n";
      
          $objResponse->assign("Departamentos","innerHTML",$objResponse->setTildes($html));
          return $objResponse;
          
  }
  
  
  
  
  
  function ListarTiposDocumentosNoAsignados($Departamento,$Empresa_Id,$CentroUtilidad,$UnidadFuncional,$Departamento_Id,$Descripcion)
  {
  $objResponse = new xajaxResponse();
  
  
    $html .= "<fieldset class=\"fieldset\" style=\"width:100%\">\n";
    $html .= "  <legend class=\"normal_10AN\">DOCUMENTOS DE BODEGA, PARA ASIGNAR</legend>\n";
      //BUSCADOR
    $html .= "<br>";
    $html .= "<form name=\"buscador_\" method=\"POST\">";
    $html .= "<table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "<tr class=\"modulo_table_list_title\">";
    $html .= "<td colspan=\"6\" align=\"center\">";
    $html .= "BUSCADOR";
    $html .= "</td>";
    $html .= "</tr>";
    
    
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "COD. TIP. DOCUMENTO :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input style=\"width:100%;height:100%\" class=\"input-text\" type=\"text\" id=\"tipo_doc_id\" maxlength=\"10\" onkeyup=\"this.value=this.value.toUpperCase()\">";
    $html .= "</td>";
    
        
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "DESCRIPCION :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input style=\"width:100%;height:100%\" class=\"input-text\" type=\"text\" id=\"descripcion_\" maxlength=\"40\" onkeyup=\"this.value=this.value.toUpperCase()\">";
    $html .= "</td>";
    
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "T. MOVIMIENTO :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input class=\"input-text\" type=\"text\" id=\"tipo_movimiento\" style=\"width:100%;height:100%;\" maxlength=\"40\" onkeyup=\"this.value=this.value.toUpperCase()\">";
    $html .= "</td>";
    
    $html .= "</tr>";
    
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td colspan=\"6\" align=\"center\">";                                                        
    $html .= "<input class=\"input-submit\" type=\"button\" value=\"Buscar\" onclick=\"paginador_('".$Departamento."',document.getElementById('tipo_doc_id').value,document.getElementById('descripcion_').value,document.getElementById('tipo_movimiento').value);\">";
    $html .= "</td>";
    $html .= "</tr>";
    
    
    
    
    $html .="</table>
            </form>";

      $html .= "<div id=\"ListadoDeDocumentosSinAsignar\">";
      $html .= "</div>";
      
      $html .= "</fieldset><br>\n";
          
      $objResponse->assign("TiposDocumentosSinAsignar","innerHTML",$objResponse->setTildes($html));
      $objResponse->script("xajax_ListadoTiposDocumentosSinAsignar('".$Departamento."');");
      return $objResponse;
          
  }
 
  
  
  
    //empresa_id,centro_utilidad,bodega,documento_id,descripcion,prefijo,offset
  function ListadoTiposDocumentosSinAsignar($Departamento,$TDocumentoId,$Descripcion,$TMovimiento,$offset)
  {
  $objResponse = new xajaxResponse();
  
  $sql = AutoCarga::factory("ConsultasParametrizarDocumentosPorDepartamentos","classes","app","Inv_ParametrosIniciales");
  
  if($TDocumentoId=="" && $Descripcion=="" && $TMovimiento=="")
    $TDocumentosSinAsignar=$sql->Listar_TiposDocumentosSinAsignar($Departamento,$offset);
      else
        $TDocumentosSinAsignar=$sql->Listar_TiposDocumentosSinAsignarBuscados($Departamento,$TDocumentoId,$Descripcion,$TMovimiento,$offset);
        
  $action['paginador'] = "paginador_('".$Departamento."','".$TDocumentoId."','".$Descripcion."','".$TMovimiento."'";
  
  
    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);  
  
  
     $html .= "<center>";
      $html .= "<fieldset class=\"fieldset\" style=\"width:100%\">\n";
   $html .= "  <legend class=\"normal_10AN\">TIPOS DE DOCUMENTOS</legend>\n";
        
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        
		$html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td width=\"5%\">T.DOC. ID</td>\n";
        $html .= "      <td width=\"40%\">DESCRIPCION</td>\n";
        $html .= "      <td width=\"10%\">TIPO MOVIMIENTO</td>\n";
        $html .= "      <td width=\"10%\">GUARDAR</td>\n";
				
        $html .= "    </tr>\n";

        $est = "modulo_list_claro";
        $bck = "#DDDDDD";
        foreach($TDocumentosSinAsignar as $key => $docA)
        {
          ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
          ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

          $html .= "    <tr align=\"center\" class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
          $html .= "      <td >".$docA['tipo_doc_general_id']."</td><td>".$docA['descripcion']."</td>\n";
          $html .= "      <td >".$docA['inv_tipo_movimiento']."</td>\n";
          

          $html .= "<td align=\"center\">
                   <a href=\"#\" onclick=\"xajax_AsignarTipoDocumentoADepartamentos('".$docA['tipo_doc_general_id']."','".$Departamento."','".$TDocumentoId."','".$Descripcion."','".$TMovimiento."','".$offset."')\">\n";
          $html .="<img title=\"Guardar\" src=\"".GetThemePath()."/images/guarda.png\" border=\"0\"></a></td>\n";
        }
        
        
        $html .= "    </table>\n";
        
        $html .= "</fieldset><br>\n";
        $html .= "    </center>\n";
      
          $objResponse->assign("ListadoDeDocumentosSinAsignar","innerHTML",$objResponse->setTildes($html));
          return $objResponse;
          
  }
  
  
  
  function AsignarTipoDocumentoADepartamentos($TipoDocGeneralId,$Departamento,$TDocumentoId,$Descripcion,$TMovimiento,$offset)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasParametrizarDocumentosPorDepartamentos","classes","app","Inv_ParametrosIniciales");
  
  $token=$sql->AsignarTipoDocumentoADepartamentos($TipoDocGeneralId,$Departamento);
  
  if($token)
  {
  $objResponse->script("xajax_ListadoTiposDocumentosSinAsignar('".$Departamento."','".$TDocumentoId."','".$Descripcion."','".$TMovimiento."','".$offset."');");
  //$objResponse->script("xajax_DocumentosAsignadoABodegaT('".$Empresa_Id."','".$CentroUtilidad."','".$Bodega."');");
  }
  else
  $objResponse->alert("Error en el Ingreso... Revisa Que El Tipo de Documento, esté Asignado al Departamento!!");
  
  return $objResponse;
  }
  
  
  
   function Listar_TiposDocumentosAsignadosADepartamentos($Departamento)
  {
  $objResponse = new xajaxResponse();
  
  $html .= ThemeAbrirTabla("Tipos De Documentos Asignados");
  $html .= "<form name=\"buscador_\" method=\"POST\">";
    $html .= "<table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "<tr class=\"modulo_table_list_title\">";
    $html .= "<td colspan=\"6\" align=\"center\">";
    $html .= "BUSCADOR";
    $html .= "</td>";
    $html .= "</tr>";
    
    
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "COD. TIP. DOCUMENTO :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input style=\"width:100%;height:100%\" class=\"input-text\" type=\"text\" id=\"tipo_documento_id\" maxlength=\"10\" onkeyup=\"this.value=this.value.toUpperCase()\">";
    $html .= "</td>";
    
        
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "DESCRIPCION :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input style=\"width:100%;height:100%\" class=\"input-text\" type=\"text\" id=\"descripcion__\" maxlength=\"40\" onkeyup=\"this.value=this.value.toUpperCase()\">";
    $html .= "</td>";
    
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "T. MOVIMIENTO :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input class=\"input-text\" type=\"text\" id=\"tipo_movimiento_\" style=\"width:100%;height:100%;\" maxlength=\"40\" onkeyup=\"this.value=this.value.toUpperCase()\">";
    $html .= "</td>";
    
    $html .= "</tr>";
    
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td colspan=\"6\" align=\"center\">";                                                        
    $html .= "<input class=\"input-submit\" type=\"button\" value=\"Buscar\" onclick=\"PaginadorTDBuscados('".$Departamento."',document.getElementById('tipo_documento_id').value,document.getElementById('descripcion__').value,document.getElementById('tipo_movimiento_').value);\">";
    $html .= "</td>";
    $html .= "</tr>";
    
    
    
    
    $html .="</table>
            </form>";
  
  $html .= "<DIV id=\"ListadoTiposDocumentosAsignados\"></DIV>";
  $html .= ThemeCerrarTabla();
    
  $objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
  //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
  $objResponse->call("MostrarSpan");
  $objResponse->script("xajax_ListadoTiposDocumentosAsignados('".$Departamento."')");
  return $objResponse;
          
  }
  
  
  
     //empresa_id,centro_utilidad,bodega,documento_id,descripcion,prefijo,offset
  function ListadoTiposDocumentosAsignados($Departamento,$TDocumentoId,$Descripcion,$TMovimiento,$offset)
  {
  $objResponse = new xajaxResponse();
  
  $sql = AutoCarga::factory("ConsultasParametrizarDocumentosPorDepartamentos","classes","app","Inv_ParametrosIniciales");
  //$url=ModuloGetURL("app","Inv_ParametrosIniciales","controller","AsignarDocumentosABodegas");
  if($TDocumentoId=="" && $Descripcion=="" && $TMovimiento=="")
  $TDocumentosAsignados=$sql->Listar_TiposDocumentosAsignados($Departamento,$offset);
    else
        $TDocumentosAsignados=$sql->Listar_TiposDocumentosAsignadosBuscados($Departamento,$TDocumentoId,$Descripcion,$TMovimiento,$offset);
       
      
      $html .= "<center>";

    $action['paginador'] = "PaginadorTDBuscados('".$Departamento."','".$TDocumentoId."','".$Descripcion."','".$TMovimiento."'";
  
    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);  
      $html .= "<fieldset class=\"fieldset\" style=\"width:100%\">\n";
   $html .= "  <legend class=\"normal_10AN\">TIPOS DE DOCUMENTOS</legend>\n";
        
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        
		$html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td width=\"5%\">T.DOC. ID</td>\n";
        $html .= "      <td width=\"40%\">DESCRIPCION</td>\n";
        $html .= "      <td width=\"10%\">TIPO MOVIMIENTO</td>\n";
        $html .= "      <td width=\"10%\">DES/HABILITAR</td>\n";
				
        $html .= "    </tr>\n";

        $est = "modulo_list_claro";
        $bck = "#DDDDDD";
        foreach($TDocumentosAsignados as $key => $docA)
        {
          ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
          ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

          $html .= "    <tr align=\"center\" class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
          $html .= "      <td >".$docA['tipo_doc_general_id']."</td><td>".$docA['descripcion']."</td>\n";
          $html .= "      <td >".$docA['inv_tipo_movimiento']."</td>\n";
          

         if($docA['estado']==1)
          {
          $html .= "<td align=\"center\">
                <a href=\"#\" onclick=\"xajax_CambioEstado('departamentos_tipos_doc_generales','estado','0','".$docA['codigo']."','departamento_tipo_doc_general_id','".$Departamento."','".$offset."')\">\n";
          $html .="<img title=\"DESHABILITAR PERMISO\" src=\"".GetThemePath()."/images/checksi.png\" border=\"0\"></a></td>\n";
          }
          else
            {
            $html .= "<td align=\"center\"><a href=\"#\" onclick=\"xajax_CambioEstado('departamentos_tipos_doc_generales','estado','1','".$docA['codigo']."','departamento_tipo_doc_general_id','".$Departamento."','".$offset."')\">\n";
            $html .="<img title=\"HABILITAR PERMISO\" src=\"".GetThemePath()."/images/checkno.png\" border=\"0\"></a></td>\n";
            }
        }
        
        
        $html .= "    </table>\n";
        
        $html .= "</fieldset><br>\n";
        $html .= "    </center>\n";
      
          $objResponse->assign("ListadoTiposDocumentosAsignados","innerHTML",$objResponse->setTildes($html));
          return $objResponse;
          
  }
  
  
  
  /*
  Funcion Xajax para Cambiar el estado de un registro
  */
  function CambioEstado($tabla,$campo,$valor,$id,$campo_id,$Departamento,$offset)
  {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ClaseUtil"); //cargo Funcion General para cambiar de estado un registro.
    $sql->Cambio_Estado($tabla,$campo,$valor,$id,$campo_id);
        
    $objResponse->script("xajax_ListadoTiposDocumentosAsignados('".$Departamento."','".$offset."')");
    return $objResponse;	
	}
 

 
?>
