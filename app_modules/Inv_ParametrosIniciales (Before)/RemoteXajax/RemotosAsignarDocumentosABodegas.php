<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: RemotosAsignarDocumentosABodegas.php
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
    
  function CentrosDeUtilidad($EmpresaId,$Div,$url)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasAsignarDocumentosABodegas","classes","app","Inv_ParametrosIniciales");
  
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
            $html .= "<a onclick=\"xajax_Bodegas('".$EmpresaId."','".$CU['centro_utilidad']."','BodegasEmp".$CU['centro_utilidad']."','".$url."')\">\n";
            $html .= "<img title=\"Continuar...\" src=\"".GetThemePath()."/images/flecha_der.gif\" border=\"0\">   ".$CU['descripcion']."</a>";
            $html .= "</td>\n";
            $html .= "      <td align=\"center\">";
            $html .= "<div id=\"BodegasEmp".$CU['centro_utilidad']."\">";
            $html .= "</div>";
            $html .= "      </td>";
            $html .= "   </tr>";
            
          }
          
          $html .= "    </table>\n";
          $html .= "</fieldset>\n";
          
          $objResponse->assign($Div,"innerHTML",$html);
          return $objResponse;
          
  }
  
  function Bodegas($EmpresaId,$CentroUtilidad,$Div,$url)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasAsignarDocumentosABodegas","classes","app","Inv_ParametrosIniciales");
  //$url=ModuloGetURL("app","Inv_ParametrosIniciales","controller","AsignarDocumentosABodegas");
  $Bodegas=$sql->BodegasXCentroUtilXEmpresa($EmpresaId,$CentroUtilidad);
  
      $html .= "<fieldset class=\"fieldset\" style=\"width:70%\">\n";
      $html .= "  <legend class=\"normal_10AN\">Bodegas</legend>\n";
          
      $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          
          $est = "modulo_list_claro";
          $bck = "#DDDDDD";
          foreach($Bodegas as $key => $BO)
          {
            ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
            ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

            $html .= "    <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
            $html .= "      <td>";
            $html .= "<a href=\"".$url."&empresa_id=".$EmpresaId."&centro_utilidad=".$CentroUtilidad."&bodega=".$BO['bodega']."\" \">\n";
            $html .= "<li>".$BO['descripcion']."</li></a>";
            $html .= "</td>\n";
            $html .= "<div id=\"BodegasEmp".$BO['bodega']."\">";
            $html .= "</div>";
            
            $html .= "   </tr>";
            
          }
          
          
          $html .= "    </table>\n";
          $html .= "</fieldset><br>\n";
          
          $objResponse->assign($Div,"innerHTML",$html);
          return $objResponse;
          
  }
  

  function DocumentosT($Empresa_Id,$CentroUtilidad,$Bodega,$Documento_Id,$Descripcion,$Prefijo,$offset)
  {
  $objResponse = new xajaxResponse();
  
  $sql = AutoCarga::factory("ConsultasAsignarDocumentosABodegas","classes","app","Inv_ParametrosIniciales");
  
  if($Documento_Id=="" && $Descripcion=="" && $Prefijo=="")
  $Documentos=$sql->Listar_Documentos($Empresa_Id,$CentroUtilidad,$Bodega,$offset);
    else
        $Documentos=$sql->Listar_DocumentosBuscados($Empresa_Id,$CentroUtilidad,$Bodega,$Documento_Id,$Descripcion,$Prefijo,$offset);
        
  $action['paginador'] = "paginador('".$Empresa_Id."','".$CentroUtilidad."','".$Bodega."','".$Documento_Id."','".$Descripcion."','".$Prefijo."'";
  
  $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);  
  
  
     $html .= "<center>";
      $html .= "<fieldset class=\"fieldset\" style=\"width:100%\">\n";
   $html .= "  <legend class=\"normal_10AN\">DOCUMENTOS</legend>\n";
        
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        
		$html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td width=\"5%\">DOC. ID</td>\n";
        $html .= "      <td width=\"40%\">DESCRIPCION</td>\n";
        $html .= "      <td width=\"10%\">TIPO DOCUMENTO</td>\n";
        $html .= "      <td width=\"10%\">PREFIJO</td>\n";
        $html .= "      <td width=\"10%\">AS.DOC BODEGA</td>\n";
				
        $html .= "    </tr>\n";

        $est = "modulo_list_claro";
        $bck = "#DDDDDD";
        foreach($Documentos as $key => $doc)
        {
          ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
          ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

          $html .= "    <tr align=\"center\" class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
          $html .= "      <td >".$doc['documento_id']."</td><td>".$doc['descripcion']." </td>\n";
          $html .= "      <td >".$doc['tipo_doc_general_id']."-".$doc['tipo_documento']."</td><td>".$doc['prefijo']." </td>\n";
          
                                                                      //$Empresa_Id,$CentroUtilidad,$Bodega,$Documento_Id,$Descripcion,$Prefijo,$offset
          $html .= "<td align=\"center\">
                <a href=\"#\" onclick=\"xajax_AsignarDocumentoABodega('".$doc['documento_id']."','".$Empresa_Id."','".$CentroUtilidad."','".$Bodega."','".$Documento_Id."','".$Descripcion."','".$Prefijo."','".$offset."')\">\n";
          $html .="<img title=\"ASIGNAR\" src=\"".GetThemePath()."/images/endturn.png\" border=\"0\"></a></td>\n";
          $html .= "      </tr>";  
        }
        
        
        
        $html .= "    </table>\n";
        
        $html .= "</fieldset><br>\n";
        $html .= "    </center>\n";
      
          $objResponse->assign("ListadoDocumentos","innerHTML",$html);
          return $objResponse;
          
  }
  
  
  
  
  //empresa_id,centro_utilidad,bodega,documento_id,descripcion,prefijo,offset
  function DocumentosAsignadoABodegaT($Empresa_Id,$CentroUtilidad,$Bodega,$Documento_Id,$Descripcion,$Prefijo,$offset)
  {
  $objResponse = new xajaxResponse();
  
  $sql = AutoCarga::factory("ConsultasAsignarDocumentosABodegas","classes","app","Inv_ParametrosIniciales");
  
  if($Documento_Id=="" && $Descripcion=="" && $Prefijo=="")
    $DocumentosAsignados=$sql->Listar_DocumentosAsignadoABodega($Empresa_Id,$CentroUtilidad,$Bodega,$offset);
      else
        $DocumentosAsignados=$sql->Listar_DocumentosAsignadoABodegaBuscados($Empresa_Id,$CentroUtilidad,$Bodega,$Documento_Id,$Descripcion,$Prefijo,$offset);
        
  $action['paginador'] = "paginador_('".$Empresa_Id."','".$CentroUtilidad."','".$Bodega."','".$Documento_Id."','".$Descripcion."','".$Prefijo."'";
  
  
    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);  
  
  
     $html .= "<center>";
      $html .= "<fieldset class=\"fieldset\" style=\"width:100%\">\n";
   $html .= "  <legend class=\"normal_10AN\">DOCUMENTOS DE BODEGA</legend>\n";
        
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        
		$html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td width=\"5%\">DOC. ID</td>\n";
        $html .= "      <td width=\"40%\">DESCRIPCION</td>\n";
        $html .= "      <td width=\"10%\">TIPO DOCUMENTO</td>\n";
        $html .= "      <td width=\"10%\">PREFIJO</td>\n";
        $html .= "      <td width=\"10%\">SW. ESTADO</td>\n";
				
        $html .= "    </tr>\n";

        $est = "modulo_list_claro";
        $bck = "#DDDDDD";
        foreach($DocumentosAsignados as $key => $docA)
        {
          ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
          ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

          $html .= "    <tr align=\"center\" class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
          $html .= "      <td >".$docA['documento_id']."</td><td>".$docA['descripcion']." </td>\n";
          $html .= "      <td >".$docA['tipo_doc_general_id']."-".$docA['tipo_documento']."</td><td>".$docA['prefijo']." </td>\n";
          

        if($docA['sw_estado']==1)
          {
          $html .= "<td align=\"center\">
                <a href=\"#\" onclick=\"xajax_CambioEstado('inv_bodegas_documentos','sw_estado','0','".$docA['bodegas_doc_id']."','bodegas_doc_id','".$Empresa_Id."','".$CentroUtilidad."','".$Bodega."','".$Documento_Id."','".$Descripcion."','".$Prefijo."','".$offset."')\">\n";
          $html .="<img title=\"INACTIVAR\" src=\"".GetThemePath()."/images/checksi.png\" border=\"0\"></a></td>\n";
          }
          else
            {
            $html .= "<td align=\"center\"><a href=\"#\" onclick=\"xajax_CambioEstado('inv_bodegas_documentos','sw_estado','1','".$docA['bodegas_doc_id']."','bodegas_doc_id','".$Empresa_Id."','".$CentroUtilidad."','".$Bodega."','".$Documento_Id."','".$Descripcion."','".$Prefijo."','".$offset."')\">\n";
            $html .="<img title=\"ACTIVAR\" src=\"".GetThemePath()."/images/checkno.png\" border=\"0\"></a></td>\n";
            }
        
        }
        
        
        
        $html .= "    </table>\n";
        
        $html .= "</fieldset><br>\n";
        $html .= "    </center>\n";
      
          $objResponse->assign("ListadoDocumentosAsignados","innerHTML",$html);
          return $objResponse;
          
  }
  
  
  
  
  
  function BusquedaDocumentosT($Empresa_Id,$CentroUtilidad,$Bodega,$offset)
  {
  $objResponse = new xajaxResponse();
  
  $sql = AutoCarga::factory("ConsultasAsignarDocumentosABodegas","classes","app","Inv_ParametrosIniciales");
  
  $Documentos=$sql->Listar_Documentos($Empresa_Id,$CentroUtilidad,$Bodega,$offset);
  
  
  $action['paginador'] = "paginador('".$Empresa_Id."','".$CentroUtilidad."','".$Bodega."'";
  
    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);  
  
  
     $html .= "<center>";
      $html .= "<fieldset class=\"fieldset\" style=\"width:100%\">\n";
   $html .= "  <legend class=\"normal_10AN\">DOCUMENTOS</legend>\n";
        
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        
		$html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td width=\"5%\">DOC. ID</td>\n";
        $html .= "      <td width=\"40%\">DESCRIPCION</td>\n";
        $html .= "      <td width=\"10%\">TIPO DOCUMENTO</td>\n";
        $html .= "      <td width=\"10%\">PREFIJO</td>\n";
        $html .= "      <td width=\"10%\">AS.DOC BODEGA</td>\n";
				
        $html .= "    </tr>\n";

        $est = "modulo_list_claro";
        $bck = "#DDDDDD";
        foreach($Documentos as $key => $doc)
        {
          ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
          ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

          $html .= "    <tr align=\"center\" class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
          $html .= "      <td >".$doc['documento_id']."</td><td>".$doc['descripcion']." </td>\n";
          $html .= "      <td >".$doc['tipo_doc_general_id']."-".$doc['tipo_documento']."</td><td>".$doc['prefijo']." </td>\n";
          

          $html .= "<td align=\"center\">
                <a href=\"#\" onclick=\"xajax_AsignarDocumentoABodega('".$doc['documento_id']."','".$Empresa_Id."','".$CentroUtilidad."','".$Bodega."','".$offset."')\">\n";
          $html .="<img title=\"ASIGNAR\" src=\"".GetThemePath()."/images/endturn.png\" border=\"0\"></a></td>\n";
          $html .= "      </tr>";  
        }
        
        
        
        $html .= "    </table>\n";
        
        $html .= "</fieldset><br>\n";
        $html .= "    </center>\n";
      
          $objResponse->assign("ListadoDocumentos","innerHTML",$html);
          return $objResponse;
          
  }
  
  


  function AsignarDocumentoABodega($DocumentoId,$Empresa_Id,$CentroUtilidad,$Bodega,$Documento_Id,$Descripcion,$Prefijo,$offset)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasAsignarDocumentosABodegas","classes","app","Inv_ParametrosIniciales");
  
  $token=$sql->AsignarDocumentoABodega($DocumentoId,$Empresa_Id,$CentroUtilidad,$Bodega);
  
  if($token)
  {
  $objResponse->script("xajax_DocumentosT('".$Empresa_Id."','".$CentroUtilidad."','".$Bodega."','".$Documento_Id."','".$Descripcion."','".$Prefijo."','".$offset."');");
  $objResponse->script("xajax_DocumentosAsignadoABodegaT('".$Empresa_Id."','".$CentroUtilidad."','".$Bodega."');");
  }
  else
  $objResponse->alert("Error en el Ingreso... Revisa Que El Documento, esté Asignado a la Bodega!!");
  
  return $objResponse;
  }
 
  
  /*
  Funcion Xajax para Cambiar el estado de un registro
  */
  function CambioEstado($tabla,$campo,$valor,$id,$campo_id,$Empresa_Id,$CentroUtilidad,$Bodega,$Documento_Id,$Descripcion,$Prefijo,$offset)
  {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ClaseUtil"); //cargo Funcion General para cambiar de estado un registro.
    $sql->Cambio_Estado($tabla,$campo,$valor,$id,$campo_id);
        
    $objResponse->script("xajax_DocumentosAsignadoABodegaT('".$Empresa_Id."','".$CentroUtilidad."','".$Bodega."','".$Documento_Id."','".$Descripcion."','".$Prefijo."','".$offset."')");
    return $objResponse;	
	}
  
 
 
 function UsuariosDocumentosBodegasT($Empresa_Id,$CentroUtilidad,$Bodega,$Usuario_Id,$Nombre,$Descripcion,$offset)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasAsignarDocumentosABodegas","classes","app","Inv_ParametrosIniciales");
  
  if($Usuario_Id=="" && $Nombre=="" && $Descripcion=="")
  $Usuarios=$sql->Listar_UsuariosSinDocumentosBodegas($Empresa_Id,$CentroUtilidad,$Bodega,$offset);
      else
            $Usuarios=$sql->Listar_UsuariosSinDocumentosBodegasBuscados($Empresa_Id,$CentroUtilidad,$Bodega,$Usuario_Id,$Nombre,$Descripcion,$offset);
      
  
  $action['paginador'] = "Paginador('".$Empresa_Id."','".$CentroUtilidad."','".$Bodega."','".$Usuario_Id."','".$Nombre."','".$Descripcion."'";
        
        
    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);
    
    $html .= "<fieldset class=\"fieldset\">\n";
      $html .= "  <legend class=\"normal_10AN\">USUARIOS DE DOCUMENTOS DE BODEGA</legend>\n";
          
      $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td width=\"7%\">USUARIO ID</td>\n";
      $html .= "      <td width=\"25%\">NOMBRE</td>\n";
      $html .= "      <td width=\"20%\">DESCRIPCION</td>\n";
      $html .= "      <td width=\"20%\">OP</td>\n";
      
      
      $html .= "    </tr>\n";

          $est = "modulo_list_claro";
          $bck = "#DDDDDD";
          foreach($Usuarios as $key => $u)
          {
            ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
            ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

            $html .= "    <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
            $html .= "      <td >".$u['usuario_id']."</td><td>".$u['nombre']." </td><td>".$u['descripcion']." </td>\n";
                             
            $html .= "      <td align=\"center\">\n";
            $html .= "        <a href=\"#\" onclick=\"xajax_DocumentosBodega('".$u['usuario_id']."','".$Empresa_Id."','".$CentroUtilidad."','".$Bodega."','".$offset."')\">\n";
            $html .= "          <img title=\"DAR PERMISOS\" src=\"".GetThemePath()."/images/endturn.png\" border=\"0\">\n";
                                           // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
            $html .= "        </a>\n";
            $html .= "      </td>\n";
          }
          
        
          $html .= "    </table>\n";
          $html .= "</fieldset><br>\n";
          
          $objResponse->assign("ListadoUsuarios","innerHTML",$html);
          return $objResponse;
          
  }
 
 
 
 function UsuariosDocumentosT($Empresa_Id,$CentroUtilidad,$Bodega,$Usuario_Id,$Nombre,$Descripcion,$offset)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasAsignarDocumentosABodegas","classes","app","Inv_ParametrosIniciales");
  
  if($Usuario_Id=="" && $Nombre=="" && $Descripcion=="")
  $Usuarios=$sql->Listar_UsuariosSinDocumentosBodegas($Empresa_Id,$CentroUtilidad,$Bodega,$offset);
      else
            $Usuarios=$sql->Listar_UsuariosSinDocumentosBodegasBuscados($Empresa_Id,$CentroUtilidad,$Bodega,$Usuario_Id,$Nombre,$Descripcion,$offset);
      
  
  $action['paginador'] = "Paginador_Tab2('".$Empresa_Id."','".$CentroUtilidad."','".$Bodega."','".$Usuario_Id."','".$Nombre."','".$Descripcion."'";
        
        
    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);
    
    $html .= "<fieldset class=\"fieldset\">\n";
      $html .= "  <legend class=\"normal_10AN\">USUARIOS DE DOCUMENTOS DE BODEGA</legend>\n";
          
      $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td width=\"7%\">USUARIO ID</td>\n";
      $html .= "      <td width=\"25%\">NOMBRE</td>\n";
      $html .= "      <td width=\"20%\">DESCRIPCION</td>\n";
      $html .= "      <td width=\"20%\">OP</td>\n";
      
      
      $html .= "    </tr>\n";

          $est = "modulo_list_claro";
          $bck = "#DDDDDD";
          foreach($Usuarios as $key => $u)
          {
            ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
            ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

            $html .= "    <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
            $html .= "      <td >".$u['usuario_id']."</td><td>".$u['nombre']." </td><td>".$u['descripcion']." </td>\n";
                             
            $html .= "      <td align=\"center\">\n";
            $html .= "        <a href=\"#\" onclick=\"xajax_DocumentosBodegaXUsuario('".$u['usuario_id']."','".$Empresa_Id."','".$CentroUtilidad."','".$Bodega."','".$offset."')\">\n";
            $html .= "          <img title=\"Ver\" src=\"".GetThemePath()."/images/auditoria.png\" border=\"0\">\n";
                                           // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
            $html .= "        </a>\n";
            $html .= "      </td>\n";
          }
          
        
          $html .= "    </table>\n";
          $html .= "</fieldset><br>\n";
          
          $objResponse->assign("UsuariosBodega","innerHTML",$html);
          return $objResponse;
          
  }
 
 
 
  function DocumentosBodega($UsuarioId,$Empresa_Id,$CentroUtilidad,$Bodega,$Documento_Id,$Descripcion,$Prefijo,$offset)
  {
  $objResponse = new xajaxResponse();
  
  
      $html .= "<fieldset class=\"fieldset\" style=\"width:100%\">\n";
      $html .= "  <legend class=\"normal_10AN\">Documentos de Bodega</legend>\n";
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
    $html .= "CODIGO DOCUMENTO :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input style=\"width:100%;height:100%\" class=\"input-text\" type=\"text\" id=\"documento_id\" maxlength=\"10\" onkeyup=\"this.value=this.value.toUpperCase()\">";
    $html .= "</td>";
    
        
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "DESCRIPCION :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input style=\"width:100%;height:100%\" class=\"input-text\" type=\"text\" id=\"descripcion\" maxlength=\"40\" onkeyup=\"this.value=this.value.toUpperCase()\">";
    $html .= "</td>";
    
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "PREFIJO :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input class=\"input-text\" type=\"text\" id=\"prefijo\" style=\"width:100%;height:100%;\" maxlength=\"40\" onkeyup=\"this.value=this.value.toUpperCase()\">";
    $html .= "</td>";
    
    $html .= "</tr>";
    
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td colspan=\"6\" align=\"center\">";                                                        
    $html .= "<input class=\"input-submit\" type=\"button\" value=\"Buscar\" onclick=\"paginador_('".$UsuarioId."','".$Empresa_Id."','".$CentroUtilidad."','".$Bodega."',document.getElementById('documento_id').value,document.getElementById('descripcion').value,document.getElementById('prefijo').value);\">";
    $html .= "</td>";
    $html .= "</tr>";
    
    
    
    
    $html .="</table>
            </form>";

      $html .= "<div id=\"Documentos\">";
      $html .= "</div>";
      
      $html .= "</fieldset><br>\n";
          
      $objResponse->assign("DocumentosBodega","innerHTML",$html);
      $objResponse->script("xajax_ListadoDocumentosBodega('".$UsuarioId."','".$Empresa_Id."','".$CentroUtilidad."','".$Bodega."');");
      return $objResponse;
          
  }
 
 
 
  function DocumentosBodegaXUsuario($UsuarioId,$Empresa_Id,$CentroUtilidad,$Bodega,$Documento_Id,$Descripcion,$Prefijo,$offset)
  {
  $objResponse = new xajaxResponse();
  
  
      $html .= "<fieldset class=\"fieldset\" style=\"width:100%\">\n";
      $html .= "  <legend class=\"normal_10AN\">Documentos de Bodega</legend>\n";
      //BUSCADOR
    $html .= "<br>";
    $html .= "<form name=\"buscadortab2\" method=\"POST\">";
    $html .= "<table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "<tr class=\"modulo_table_list_title\">";
    $html .= "<td colspan=\"6\" align=\"center\">";
    $html .= "BUSCADOR";
    $html .= "</td>";
    $html .= "</tr>";
    
    
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "CODIGO DOCUMENTO :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input style=\"width:100%;height:100%\" class=\"input-text\" type=\"text\" id=\"documento_idbuscadortab2\" maxlength=\"10\" onkeyup=\"this.value=this.value.toUpperCase()\">";
    $html .= "</td>";
    
        
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "DESCRIPCION :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input style=\"width:100%;height:100%\" class=\"input-text\" type=\"text\" id=\"descripcionbuscadortab2\" maxlength=\"40\" onkeyup=\"this.value=this.value.toUpperCase()\">";
    $html .= "</td>";
    
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "PREFIJO :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input class=\"input-text\" type=\"text\" id=\"prefijobuscadortab2\" style=\"width:100%;height:100%;\" maxlength=\"40\" onkeyup=\"this.value=this.value.toUpperCase()\">";
    $html .= "</td>";
    
    $html .= "</tr>";
    
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td colspan=\"6\" align=\"center\">";                                                        
    $html .= "<input class=\"input-submit\" type=\"button\" value=\"Buscar\" onclick=\"paginador_tab2('".$UsuarioId."','".$Empresa_Id."','".$CentroUtilidad."','".$Bodega."',document.getElementById('documento_idbuscadortab2').value,document.getElementById('descripcionbuscadortab2').value,document.getElementById('prefijobuscadortab2').value);\">";
    $html .= "</td>";
    $html .= "</tr>";
    
    
    
    
    $html .="</table>
            </form>";

      $html .= "<div id=\"DocumentosXUsuario\">";
      $html .= "</div>";
      
      $html .= "</fieldset><br>\n";
          
      $objResponse->assign("DocumentosBodegaXUsuario","innerHTML",$html);
      $objResponse->script("xajax_ListadoDocumentosBodegaXUsuario('".$UsuarioId."','".$Empresa_Id."','".$CentroUtilidad."','".$Bodega."');");
      return $objResponse;
          
  }
  
  
 
     //empresa_id,centro_utilidad,bodega,documento_id,descripcion,prefijo,offset
  function ListadoDocumentosBodega($Usuario_id,$Empresa_Id,$CentroUtilidad,$Bodega,$Documento_Id,$Descripcion,$Prefijo,$offset)
  {
  $objResponse = new xajaxResponse();
  
  $sql = AutoCarga::factory("ConsultasAsignarDocumentosABodegas","classes","app","Inv_ParametrosIniciales");
  
  if($Documento_Id=="" && $Descripcion=="" && $Prefijo=="")
    $DocumentosAsignados=$sql->Listar_DocumentosAsignadoABodegaxUsuario($Usuario_id,$Empresa_Id,$CentroUtilidad,$Bodega,$offset);
      else
        $DocumentosAsignados=$sql->Listar_DocumentosAsignadoABodegaxUsuarioBuscados($Usuario_id,$Empresa_Id,$CentroUtilidad,$Bodega,$Documento_Id,$Descripcion,$Prefijo,$offset);
        
  $action['paginador'] = "paginador_('".$Usuario_id."','".$Empresa_Id."','".$CentroUtilidad."','".$Bodega."','".$Documento_Id."','".$Descripcion."','".$Prefijo."'";
  
  
    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);  
  
  
     $html .= "<center>";
      $html .= "<fieldset class=\"fieldset\" style=\"width:100%\">\n";
   $html .= "  <legend class=\"normal_10AN\">DOCUMENTOS DE BODEGA</legend>\n";
        
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        
		$html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td width=\"5%\">DOC. ID</td>\n";
        $html .= "      <td width=\"40%\">DESCRIPCION</td>\n";
        $html .= "      <td width=\"10%\">TIPO DOCUMENTO</td>\n";
        $html .= "      <td width=\"10%\">PREFIJO</td>\n";
        $html .= "      <td width=\"10%\">GUARDAR</td>\n";
				
        $html .= "    </tr>\n";

        $est = "modulo_list_claro";
        $bck = "#DDDDDD";
        foreach($DocumentosAsignados as $key => $docA)
        {
          ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
          ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

          $html .= "    <tr align=\"center\" class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
          $html .= "      <td >".$docA['documento_id']."</td><td>".$docA['descripcion']." </td>\n";
          $html .= "      <td >".$docA['tipo_doc_general_id']."-".$docA['tipo_documento']."</td><td>".$docA['prefijo']." </td>\n";
          

          $html .= "<td align=\"center\">
                   <a href=\"#\" onclick=\"xajax_GuardarDocumentoUsuarioBodega('".$docA['documento_id']."','".$Usuario_id."','".$Empresa_Id."','".$CentroUtilidad."','".$Bodega."','".$Documento_Id."','".$Descripcion."','".$Prefijo."','".$offset."')\">\n";
          $html .="<img title=\"Guardar\" src=\"".GetThemePath()."/images/guarda.png\" border=\"0\"></a></td>\n";
        }
        
        
        $html .= "    </table>\n";
        
        $html .= "</fieldset><br>\n";
        $html .= "    </center>\n";
      
          $objResponse->assign("Documentos","innerHTML",$html);
          return $objResponse;
          
  }
  
  
  
      //empresa_id,centro_utilidad,bodega,documento_id,descripcion,prefijo,offset
  function ListadoDocumentosBodegaXUsuario($Usuario_id,$Empresa_Id,$CentroUtilidad,$Bodega,$Documento_Id,$Descripcion,$Prefijo,$offset)
  {
  $objResponse = new xajaxResponse();
  
  $sql = AutoCarga::factory("ConsultasAsignarDocumentosABodegas","classes","app","Inv_ParametrosIniciales");
  
  if($Documento_Id=="" && $Descripcion=="" && $Prefijo=="")
    $DocumentosAsignados=$sql->Listar_DocumentosAsignadoUsuario($Usuario_id,$Empresa_Id,$CentroUtilidad,$Bodega,$offset);
      else
        $DocumentosAsignados=$sql->Listar_DocumentosAsignadoUsuarioBuscados($Usuario_id,$Empresa_Id,$CentroUtilidad,$Bodega,$Documento_Id,$Descripcion,$Prefijo,$offset);
        
  $action['paginador'] = "paginador_tab2('".$Usuario_id."','".$Empresa_Id."','".$CentroUtilidad."','".$Bodega."','".$Documento_Id."','".$Descripcion."','".$Prefijo."'";
  
  
    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);  
  
  
     $html .= "<center>";
      $html .= "<fieldset class=\"fieldset\" style=\"width:100%\">\n";
   $html .= "  <legend class=\"normal_10AN\">DOCUMENTOS DE BODEGA</legend>\n";
        
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        
		$html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td width=\"5%\">DOC. ID</td>\n";
        $html .= "      <td width=\"40%\">DESCRIPCION</td>\n";
        $html .= "      <td width=\"10%\">TIPO DOCUMENTO</td>\n";
        $html .= "      <td width=\"10%\">PREFIJO</td>\n";
        $html .= "      <td width=\"10%\">QUITAR</td>\n";
				
        $html .= "    </tr>\n";

        $est = "modulo_list_claro";
        $bck = "#DDDDDD";
        foreach($DocumentosAsignados as $key => $docA)
        {
          ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
          ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

          $html .= "    <tr align=\"center\" class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
          $html .= "      <td >".$docA['documento_id']."</td><td>".$docA['descripcion']." </td>\n";
          $html .= "      <td >".$docA['tipo_doc_general_id']."-".$docA['tipo_documento']."</td><td>".$docA['prefijo']." </td>\n";
          

          $html .= "<td align=\"center\">
                   <a href=\"#\" onclick=\"xajax_QuitarDocumentoUsuarioBodega('".$docA['documento_id']."','".$Usuario_id."','".$Empresa_Id."','".$CentroUtilidad."','".$Bodega."','".$Documento_Id."','".$Descripcion."','".$Prefijo."','".$offset."')\">\n";
          $html .="<img title=\"Quitar Permiso\" src=\"".GetThemePath()."/images/delete.gif\" border=\"0\"></a></td>\n";
        }
        
        
        $html .= "    </table>\n";
        
        $html .= "</fieldset><br>\n";
        $html .= "    </center>\n";
      
          $objResponse->assign("DocumentosXUsuario","innerHTML",$html);
          return $objResponse;
          
  }
  
  
  
   function GuardarDocumentoUsuarioBodega($DocumentoId,$Usuario_id,$Empresa_Id,$CentroUtilidad,$Bodega,$Documento_Id,$Descripcion,$Prefijo,$offset)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasAsignarDocumentosABodegas","classes","app","Inv_ParametrosIniciales");
  
  $token=$sql->GuardarDocumentoUsuarioBodega($DocumentoId,$Usuario_id,$Empresa_Id,$CentroUtilidad,$Bodega);
  
  if($token)
  {
  $objResponse->script("xajax_ListadoDocumentosBodega('".$Usuario_id."','".$Empresa_Id."','".$CentroUtilidad."','".$Bodega."','".$Documento_Id."','".$Descripcion."','".$Prefijo."','".$offset."');");
  //$objResponse->script("xajax_DocumentosAsignadoABodegaT('".$Empresa_Id."','".$CentroUtilidad."','".$Bodega."');");
  }
  else
  $objResponse->alert("Error en el Ingreso... Revisa Que El Documento, esté Asignado a la Bodega!!");
  
  return $objResponse;
  }
  
  
  function QuitarDocumentoUsuarioBodega($DocumentoId,$Usuario_id,$Empresa_Id,$CentroUtilidad,$Bodega,$Documento_Id,$Descripcion,$Prefijo,$offset)
  {
    $objResponse = new xajaxResponse();
    $sql = AutoCarga::factory("ConsultasAsignarDocumentosABodegas","classes","app","Inv_ParametrosIniciales");
    $sql->Borrar_PermisosDocumentosBodegas($DocumentoId,$Usuario_id,$Empresa_Id,$CentroUtilidad,$Bodega);
    
    
    $objResponse->script("xajax_ListadoDocumentosBodegaXUsuario('".$Usuario_id."','".$Empresa_Id."','".$CentroUtilidad."','".$Bodega."','".$Documento_Id."','".$Descripcion."','".$Prefijo."','".$offset."');");  
    return $objResponse;
  
  }
?>
