<?php

   /**
  * @package IPSOFT-SIIS
  * @version $Id: AsignarDocumentosABodegas_HTML.class.php,
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

  /**
  * Clase Vista: AsignarDocumentosABodegas_HTML
  * Clase Contiene Metodos para el despliegue de Formularios del Módulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.4 $
  * @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Adrian Medina Santacruz
  */

	class AsignarDocumentosABodegas_HTML
	{
		/**
		* Constructor de la clase
		*/
		function AsignarDocumentosABodegas_HTML(){}
		/**
	    * @param array 
      * $action Vector de links de la aplicaion
		* 
		*/
		function main($action,$request,$Empresas)
    {
    $accion=$action['volver'];
    $url=$request['url_destino'];
	  
    //print_r($request);
    
      $html .= ThemeAbrirTabla($request['nombre_opcion']);
    
      $html .= "<script>\n";
      $html .= "  function mOvr(src,clrOver)\n";
      $html .= "  {\n";
      $html .= "    src.style.background = clrOver;\n";
      $html .= "  }\n";
      $html .= "  function mOut(src,clrIn)\n";
      $html .= "  {\n";
      $html .= "    src.style.background = clrIn;\n";
      $html .= "  }\n";
      $html .= "  function acceptDate(evt)\n";
      $html .= "  {\n";
      $html .= "    var nav4 = window.Event ? true : false;\n";
      $html .= "    var key = nav4 ? evt.which : evt.keyCode;\n";
      $html .= "    return (key <= 13 ||(key >= 47 && key <= 57));\n";
      $html .= "  }\n";
      $html .= "</script>\n";
     
     
      $html .= "<center>\n";
      $html .= "<fieldset class=\"fieldset\" style=\"width:40%\">\n";
      $html .= "  <legend class=\"normal_10AN\">SELECCIONE LA EMPRESA</legend>\n";
          
      $html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
          
      
      foreach($Empresas as $key => $Em)
          {
            ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
            ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

            $html .= "    <tr align=\"center\" class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
            $html .= "    <td class=\"normal_10AN\">";
            $html .= "<a onclick=\"xajax_CentrosDeUtilidad('".$Em['empresa_id']."','CentroUtilidadEmp".$Em['empresa_id']."','".$url."')\">\n";
            $html .= $Em['empresa'];
            $html .="<div id=\"CentroUtilidadEmp".$Em['empresa_id']."\"></div>";
            $html .= "</td>";
            $html .= "</tr>";
            
          }
          
          $html .= "    </table>\n";
          $html .= "</fieldset>\n";
      
    
      
      $html .= "<form name=\"forma\" action=\"".$accion."\" method=\"post\">\n";
      $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\">\n";
      $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";
      $html .= "</center>\n";
      $html .= ThemeCerrarTabla();
    return($html);
    }
  
    
    
    
    function AsignarDocumentosABodegas($action,$request)
    {
    $accion=$action['volver'];
			  
    //print_r($request);
    $EmpresaId=$request['empresa_id'];
    $CentroUtilidad=$request['centro_utilidad'];
    $Bodega=$request['bodega'];
   
   
    $html .="<script>";
    $html .="function paginador(empresa_id,centro_utilidad,bodega,documento_id,descripcion,prefijo,offset)
              {
              xajax_DocumentosT(empresa_id,centro_utilidad,bodega,documento_id,descripcion,prefijo,offset);
              }";
    $html .="</script>";
    
    
    $html .="<script>";
    $html .="function paginador_(empresa_id,centro_utilidad,bodega,documento_id,descripcion,prefijo,offset)
              {
              xajax_DocumentosAsignadoABodegaT(empresa_id,centro_utilidad,bodega,documento_id,descripcion,prefijo,offset);
              }";
    $html .="</script>";
    
    
    
    $html .= ThemeAbrirTabla('ASIGNAR DOCUMENTOS A BODEGAS');
    
      $html .= "<script>\n";
      $html .= "  function mOvr(src,clrOver)\n";
      $html .= "  {\n";
      $html .= "    src.style.background = clrOver;\n";
      $html .= "  }\n";
      $html .= "  function mOut(src,clrIn)\n";
      $html .= "  {\n";
      $html .= "    src.style.background = clrIn;\n";
      $html .= "  }\n";
      $html .= "  function acceptDate(evt)\n";
      $html .= "  {\n";
      $html .= "    var nav4 = window.Event ? true : false;\n";
      $html .= "    var key = nav4 ? evt.which : evt.keyCode;\n";
      $html .= "    return (key <= 13 ||(key >= 47 && key <= 57));\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      
      $html .= "<center>";
      $html .= "	<table width=\"98%\" align=\"center\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td>\n";
			$html .= "				<table width=\"90%\" align=\"center\">\n";
			$html .= "					<tr>\n";
			$html .= "						<td>\n";
			$html .= "							<div class=\"tab-pane\" id=\"asignar_documentos_bodegas\">\n";
			$html .= "								<script>	tabPane = new WebFXTabPane( document.getElementById( \"asignar_documentos_bodegas\" )); </script>\n";
      
      //PRIMER TAB
			$html .= "								<div class=\"tab-page\" id=\"documentos\">\n";
			$html .= "									<h2 class=\"tab\">DOCUMENTOS EN GENERAL</h2>\n";
      $html .= "									<script>	tabPane.addTabPage( document.getElementById(\"documentos\")); </script>\n";
      
      $html .="         <center>";
    
    //BUSCADOR
    $html .= "<br>";
    $html .= "<form name=\"buscador\" method=\"POST\">";
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
    $html .= "<input style=\"width:100%;height:100%\" class=\"input-text\" type=\"text\" id=\"documento_id1\" maxlength=\"10\" onkeyup=\"this.value=this.value.toUpperCase()\">";
    $html .= "</td>";
    
        
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "DESCRIPCION :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input style=\"width:100%;height:100%\" class=\"input-text\" type=\"text\" id=\"descripcion1\" maxlength=\"40\" onkeyup=\"this.value=this.value.toUpperCase()\">";
    $html .= "</td>";
   
    
   
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "PREFIJO :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input class=\"input-text\" type=\"text\" id=\"prefijo1\" style=\"width:100%;height:100%;\" maxlength=\"40\" onkeyup=\"this.value=this.value.toUpperCase()\">";
    $html .= "</td>";
    
    $html .= "</tr>";
    
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td colspan=\"6\" align=\"center\">";                                                       
    $html .= "<input class=\"input-submit\" type=\"button\" value=\"Buscar\" onclick=\"paginador('".$EmpresaId."','".$CentroUtilidad."','".$Bodega."',document.getElementById('documento_id1').value,document.getElementById('descripcion1').value,document.getElementById('prefijo1').value);\">";
    $html .= "</td>";
    $html .= "</tr>";
    
    
    
    
    $html .="</table>
            </form>";
    
     //FIN BUSCADOR 
      
      
      $html .="<div id=\"ListadoDocumentos\">";
      $html .="</div>";
      $html .="         </center><br>";
      $html .= "								</div>\n";
        
        //SEGUNDO TAB.
        $html .= "								<div class=\"tab-page\" id=\"documentos_asignados\">\n";
        $html .= "									<h2 class=\"tab\">DOCUMENTOS DE BODEGA (ASIGNADOS)</h2>\n";
        $html .= "									<script>	tabPane.addTabPage( document.getElementById(\"documentos_asignados\")); </script>\n";
        //BUSCADOR
    $html .= "<br>";
    $html .= "<form name=\"buscador\" method=\"POST\">";
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
    $html .= "<input class=\"input-submit\" type=\"button\" value=\"Buscar\" onclick=\"paginador_('".$EmpresaId."','".$CentroUtilidad."','".$Bodega."',document.getElementById('documento_id').value,document.getElementById('descripcion').value,document.getElementById('prefijo').value);\">";
    $html .= "</td>";
    $html .= "</tr>";
    
    
    
    
    $html .="</table>
            </form>";
    
     //FIN BUSCADOR 
        
    
        $html .="<div id=\"ListadoDocumentosAsignados\">";
        $html .="</div>";
                  
        $html .= "								</div>\n";
        
     
    $html .="<script>";
    $html .= "xajax_DocumentosT('".$EmpresaId."','".$CentroUtilidad."','".$Bodega."');";
    $html .="</script>";
        
    $html .="<script>";
    $html .= "xajax_DocumentosAsignadoABodegaT('".$EmpresaId."','".$CentroUtilidad."','".$Bodega."');";
    $html .="</script>";
    
    	$html .= "							</div>\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "				</table>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "  </table>\n";
      
      
      
      $html .= "<form name=\"forma\" action=\"".$accion."\" method=\"post\">\n";
      $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\">\n";
      $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";
      $html .= ThemeCerrarTabla();
    
    
    
    
    
    return($html);
    }
    
    
    
    
    
    
    
    
    function AsignarDocumentosBodegasAUsuarios($action,$request)
    {
    $accion=$action['volver'];
			  
    //print_r($request);
    $EmpresaId=$request['empresa_id'];
    $CentroUtilidad=$request['centro_utilidad'];
    $Bodega=$request['bodega'];
   
   
    $html .="<script>";
    $html .="function Paginador(empresa_id,centro_utilidad,bodega,usuario_id,nombre,descripcion,offset)
              {
              xajax_UsuariosDocumentosBodegasT(empresa_id,centro_utilidad,bodega,usuario_id,nombre,descripcion,offset);
              }";
    $html .="</script>";
    
    
    $html .="<script>";
    $html .="function paginador_(usuario_id,empresa_id,centro_utilidad,bodega,documento_id,descripcion,prefijo,offset)
              {
              xajax_ListadoDocumentosBodega(usuario_id,empresa_id,centro_utilidad,bodega,documento_id,descripcion,prefijo,offset);
              }";
    $html .="</script>";
    
    
    
    
    $html .="<script>";
    $html .="function Paginador_Tab2(empresa_id,centro_utilidad,bodega,usuario_id,nombre,descripcion,offset)
              {
              xajax_UsuariosDocumentosT(empresa_id,centro_utilidad,bodega,usuario_id,nombre,descripcion,offset);
              }";
    $html .="</script>";
    
    
    $html .="<script>";
    $html .="function paginador_tab2(usuario_id,empresa_id,centro_utilidad,bodega,documento_id,descripcion,prefijo,offset)
              {
              xajax_ListadoDocumentosBodegaXUsuario(usuario_id,empresa_id,centro_utilidad,bodega,documento_id,descripcion,prefijo,offset);
              }";
    $html .="</script>";
  
    $html .= ThemeAbrirTabla('ASIGNAR DOCUMENTOS DE BODEGAS A USUARIOS');
    
      $html .= "<script>\n";
      $html .= "  function mOvr(src,clrOver)\n";
      $html .= "  {\n";
      $html .= "    src.style.background = clrOver;\n";
      $html .= "  }\n";
      $html .= "  function mOut(src,clrIn)\n";
      $html .= "  {\n";
      $html .= "    src.style.background = clrIn;\n";
      $html .= "  }\n";
      $html .= "  function acceptDate(evt)\n";
      $html .= "  {\n";
      $html .= "    var nav4 = window.Event ? true : false;\n";
      $html .= "    var key = nav4 ? evt.which : evt.keyCode;\n";
      $html .= "    return (key <= 13 ||(key >= 47 && key <= 57));\n";
      $html .= "  }\n";
      $html .= "</script>\n";
      
      $html .= "<center>";
      $html .= "	<table width=\"98%\" align=\"center\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td>\n";
			$html .= "				<table width=\"90%\" align=\"center\">\n";
			$html .= "					<tr>\n";
			$html .= "						<td>\n";
			$html .= "							<div class=\"tab-pane\" id=\"asignar_documentosbodegaausuarios\">\n";
			$html .= "								<script>	tabPane = new WebFXTabPane( document.getElementById( \"asignar_documentosbodegaausuarios\" )); </script>\n";
      
      //PRIMER TAB
			$html .= "								<div class=\"tab-page\" id=\"usuarios\">\n";
			$html .= "									<h2 class=\"tab\">USUARIOS EN GENERAL</h2>\n";
      $html .= "									<script>	tabPane.addTabPage( document.getElementById(\"usuarios\")); </script>\n";
      
      $html .="         <center>";
    
    //BUSCADOR USUARIOS
    $html .= "<br>";
    $html .= "<form name=\"buscador\" method=\"POST\">";
    $html .= "<table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "<tr class=\"modulo_table_list_title\">";
    $html .= "<td colspan=\"6\" align=\"center\">";
    $html .= "BUSCADOR";
    $html .= "</td>";
    $html .= "</tr>";
    
    
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "USUARIO ID :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input style=\"width:100%;height:100%\" class=\"input-text\" type=\"text\" name=\"usuario_id\" maxlength=\"10\" onkeyup=\"this.value=this.value.toUpperCase()\">";
    $html .= "</td>";
    
        
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "NOMBRE :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input style=\"width:100%;height:100%\" class=\"input-text\" type=\"text\" name=\"nombre\" maxlength=\"40\" onkeyup=\"this.value=this.value.toUpperCase()\">";
    $html .= "</td>";
   
    
   
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "DESCRIPCION :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input class=\"input-text\" type=\"text\" name=\"descripcion\" style=\"width:100%;height:100%;\" maxlength=\"40\" onkeyup=\"this.value=this.value.toUpperCase()\">";
    $html .= "</td>";
    
    $html .= "</tr>";
    
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td colspan=\"6\" align=\"center\">";                                                       
    $html .= "<input class=\"input-submit\" type=\"button\" value=\"Buscar\" onclick=\"Paginador('".$EmpresaId."','".$CentroUtilidad."','".$Bodega."',document.buscador.usuario_id.value,document.buscador.nombre.value,document.buscador.descripcion.value);\">";
    $html .= "</td>";
    $html .= "</tr>";
    $html .="</table>
            </form>";
    
     //FIN BUSCADOR USUARIOS
      
      
      $html .="<div id=\"ListadoUsuarios\">";
      $html .="</div>";
      
      $html .="<div id=\"DocumentosBodega\">";
      $html .="</div>";
      
      
      $html .="         </center><br>";
      $html .= "								</div>\n";
        
        //SEGUNDO TAB.
        $html .= "								<div class=\"tab-page\" id=\"usuarios_bodega\">\n";
        $html .= "									<h2 class=\"tab\">USUARIOS DE BODEGA</h2>\n";
        $html .= "									<script>	tabPane.addTabPage( document.getElementById(\"usuarios_bodega\")); </script>\n";
        
        
        //BUSCADOR USUARIOS
    $html .= "<br>";
    $html .= "<form name=\"buscador_tab2\" method=\"POST\">";
    $html .= "<table border=\"0\" width=\"80%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "<tr class=\"modulo_table_list_title\">";
    $html .= "<td colspan=\"6\" align=\"center\">";
    $html .= "BUSCADOR";
    $html .= "</td>";
    $html .= "</tr>";
    
    
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "USUARIO ID :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input style=\"width:100%;height:100%\" class=\"input-text\" type=\"text\" name=\"usuario_id\" maxlength=\"10\" onkeyup=\"this.value=this.value.toUpperCase()\">";
    $html .= "</td>";
    
        
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "NOMBRE :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input style=\"width:100%;height:100%\" class=\"input-text\" type=\"text\" name=\"nombre\" maxlength=\"40\" onkeyup=\"this.value=this.value.toUpperCase()\">";
    $html .= "</td>";
   
    
   
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "DESCRIPCION :";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input class=\"input-text\" type=\"text\" name=\"descripcion\" style=\"width:100%;height:100%;\" maxlength=\"40\" onkeyup=\"this.value=this.value.toUpperCase()\">";
    $html .= "</td>";
    
    $html .= "</tr>";
    
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td colspan=\"6\" align=\"center\">";                                                       
    $html .= "<input class=\"input-submit\" type=\"button\" value=\"Buscar\" onclick=\"Paginador_Tab2('".$EmpresaId."','".$CentroUtilidad."','".$Bodega."',document.buscador_tab2.usuario_id.value,document.buscador_tab2.nombre.value,document.buscador_tab2.descripcion.value);\">";
    $html .= "</td>";
    $html .= "</tr>";
    $html .="</table>
            </form>";
    
     //FIN BUSCADOR USUARIOS
   
   
    
       $html .="<div id=\"UsuariosBodega\">";
       $html .="</div>";
      
      $html .="<div id=\"DocumentosBodegaXUsuario\">";
      $html .="</div>";
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
     
    $html .="<script>";
    $html .= "xajax_UsuariosDocumentosBodegasT('".$EmpresaId."','".$CentroUtilidad."','".$Bodega."');";
    $html .="</script>";
    
    $html .="<script>";
    $html .= "xajax_UsuariosDocumentosT('".$EmpresaId."','".$CentroUtilidad."','".$Bodega."');";
    $html .="</script>";
        
    
    
        
    
    
    	$html .= "							</div>\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "				</table>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "  </table>\n";
      
      
      
      $html .= "<form name=\"forma\" action=\"".$accion."\" method=\"post\">\n";
      $html .= "  <table border=\"0\" width=\"50%\" align=\"center\" >\n";
      $html .= "    <tr>\n";
      $html .= "      <td align=\"center\">\n";
      $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";
      $html .= ThemeCerrarTabla();
    
    
    
    
    
    return($html);
    }
    
    
    
    
  
  }
?>