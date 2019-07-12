<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: RemotosAsignarEspecialidadesAMedicamentos.php,v 1.1 2010/01/19 13:23:00 mauricio Exp $
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
  /*
  * Funcion Que Refrescará el listado de Laboratorios a desplegar en la pagina.
  */  
 
  function CentrosDeUtilidad($EmpresaId,$Div,$url,$NombreEmpresa)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasAsignarEspecialidadesAMedicamentos", "", "app","Inv_CodificacionProductos");
  
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
          
          $objResponse->assign($Div,"innerHTML",$html);
          return $objResponse;
          
  }
  
  function UnidadesFuncionales($EmpresaId,$CentroUtilidad,$url,$NombreEmpresa,$NCentroUtilidad)
  {
  
  $url=ModuloGetURL("app","Inv_CodificacionProductos","controller","AsignarEspecialidadesAMedicamentos2");
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasAsignarEspecialidadesAMedicamentos", "", "app","Inv_CodificacionProductos");
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
          
          $objResponse->assign("Contenido","innerHTML",$html);
          //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
          $objResponse->call("MostrarSpan");
          return $objResponse;
          
  }
  
 
 
 function ListadoMedicamentos($EmpresaId,$CentroUtilidad,$UnidadFuncional,$Departamento,$offset)
  {
  $objResponse = new xajaxResponse();
  
  $html .= ThemeAbrirTabla('LISTADO DE PRODUCTOS');
//Buscador de Productos Creados
    $html .= "<form name=\"BuscadorProductos\" method=\"POST\" action=\"#\">";
    $html .= "<table border=\"0\" width=\"50%\" align=\"center\" class=\"modulo_table_list\">";
    $html .= "<tr class=\"modulo_table_list_title\">";
    $html .= "<td colspan=\"6\" align=\"center\">";
    $html .= "BUSCADOR";
    $html .= "</td>";
    $html .= "</tr>";
  
    
    
    $html .= "<tr class=\"modulo_list_claro\">";
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "DESCRIPCION : ";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input class=\"input-text\" type=\"text\" id=\"descripcion\"  maxlength=\"30\" onkeyup=\"this.value=this.value.toUpperCase();\" style=\"width:100%;height:100%\" >";
    $html .= "</td>";
    
    
    $html .= "<td class=\"formulacion_table_list\">";
    $html .= "MOLECULA : ";
    $html .= "</td>";
    $html .= "<td>";
    $html .= "<input class=\"input-text\" type=\"text\" id=\"molecula\"  maxlength=\"30\" onkeyup=\"this.value=this.value.toUpperCase();\" style=\"width:100%;height:100%\" >";
    $html .= "</td>";
    $html .= "      </tr>";
  
    $html .= "      <tr class=\"modulo_list_claro\">";
    $html .= "      <td class=\"label\" align=\"center\" colspan=\"6\">";
		$html .= "     <input class=\"input-submit\" type=\"button\" value=\"BUSCAR\" name=\"boton\" onclick=\"xajax_ListarMedicamentos('".$EmpresaId."','".$CentroUtilidad."','".$UnidadFuncional."','".$Departamento."',document.getElementById('descripcion').value,document.getElementById('molecula').value);\" >";
		$html .= "      </td>";
		$html .= "      </tr>";
    
    
    $html .="</table>
            </form>";
    
    
    $html .="<div id=\"medicamentos\"></div>";

       
    $html .= ThemeCerrarTabla();
  
    $objResponse->script("xajax_ListarMedicamentos('".$EmpresaId."','".$CentroUtilidad."','".$UnidadFuncional."','".$Departamento."','','');");
    $objResponse->assign("ListadoMedicamentos","innerHTML",$html);
	$objResponse->script("tabPane.setSelectedIndex(1);");
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    //$objResponse->call("MostrarSpan");
    return $objResponse;
  }
  
   function ListarMedicamentos($EmpresaId,$CentroUtilidad,$UnidadFuncional,$Departamento,$DescripcionMedicamento,$Molecula,$offset)
  {
  $objResponse = new xajaxResponse();
  
  $sql = AutoCarga::factory("ConsultasAsignarEspecialidadesAMedicamentos", "", "app","Inv_CodificacionProductos");
  $Medicamentos=$sql->Listar_MedicamentosBuscar($DescripcionMedicamento,$Molecula,$offset);
      
  
	

 
    
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
        $html .= "<fieldset class=\"fieldset\" style=\"width:80%\">\n";
        $html .= "  <legend class=\"normal_10AN\">LISTADO DE MEDICAMENTOS</legend>\n";
        $action['paginador'] = "paginador('".$EmpresaId."','".$CentroUtilidad."','".$UnidadFuncional."','".$Departamento."','".$DescripcionMedicamento."','".$Molecula."'";
				    $pghtml = AutoCarga::factory("ClaseHTML");
				    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);
					
					
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        
		$html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td >COD.</td>\n";
        $html .= "      <td >PRODUCTO</td>\n";
        $html .= "      <td width=\"5%\">ESPECIALIDADES</td>\n";
        
        $html .= "    </tr>\n";

        $est = "modulo_list_claro";
        $bck = "#DDDDDD";
        foreach($Medicamentos as $key => $med)
        {
          ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
          ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

          $html .= "    <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
          $html .= "      <td >".$med['codigo_producto']."</td>\n";
          $html .= "<td>".$med['producto']." </td>\n";
                       
          $html .= "      <td align=\"center\">\n";
          $html .= "        <a href=\"#\" onclick=\"xajax_Especialidades('".$EmpresaId."','".$CentroUtilidad."','".$UnidadFuncional."','".$Departamento."','".$med['codigo_producto']."');\">\n";
          $html .= "          <img title=\"Especialidades\" src=\"".GetThemePath()."/images/especialidad.png\" border=\"0\">\n";
                                         // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
          $html .= "        </a>\n";
          $html .= "      </td>\n";
    			  
        }
        $html .= "    </table>\n";
        $html .= "</fieldset><br>\n";
        $html .= "</center>";
       
   
  
    $objResponse->assign("medicamentos","innerHTML",$html);
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    //$objResponse->call("MostrarSpan");
    return $objResponse;
  }
  
  
  
  
  
  function EspecialidadesAsignadas($EmpresaId,$CentroUtilidad,$UnidadFuncional,$Departamento,$CodigoMedicamento,$offset)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasAsignarEspecialidadesAMedicamentos", "", "app","Inv_CodificacionProductos");
  $datos=$sql->Listar_EspecialidadesAsignadas($EmpresaId,$CentroUtilidad,$UnidadFuncional,$Departamento,$CodigoMedicamento,$offset);
  
  
   $html .= "<fieldset class=\"fieldset\">\n";
        $html .= "  <legend class=\"normal_10AN\">ESPECIALIDADES ASIGNADAS</legend>\n";
   
   $action['paginador'] = "Paginador('".$EmpresaId."','".$CentroUtilidad."','".$UnidadFuncional."','".$Departamento."','".$CodigoMedicamento."'";
    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql->conteo,$sql->pagina,$action['paginador']);
	
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        
		$html .= "    <tr class=\"formulacion_table_list\">\n";
        
        $html .= "      <td width=\"10%\">ESPECIALIDAD</td>\n";
        $html .= "      <td width=\"10%\">QUITAR</td>\n";
				
        $html .= "    </tr>\n";

        $est = "modulo_list_claro";
        $bck = "#DDDDDD";
        foreach($datos as $key => $esp)
        {
          ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
          ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

          $html .= "    <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
          $html .= "      <td>".$esp['descripcion']."</td>\n";
          
          $html .= "      <td align=\"center\">\n";
          $html .= "        <a href=\"#\" onclick=\"ConfirmaBorrar('".$EmpresaId."','".$CentroUtilidad."','".$UnidadFuncional."','".$Departamento."','".$CodigoMedicamento."','".$esp['especialidad']."');\">\n";
          $html .= "          <img title=\"BORRAR\" src=\"".GetThemePath()."/images/delete.gif\" border=\"0\">\n";
                                         // Aca en GetThemePath(), extrae la ruta del tema que contiene los iconos a incluir en la pagina
          $html .= "        </a>\n";
          $html .= "      </td>\n";
        }
        $html .= "    </table>\n";
        $html .= "</fieldset><br>\n";
          
          $objResponse->assign("EspecialidadesAsignadas","innerHTML",$html);
          return $objResponse;
          
  }
  
  
  
  
   function EspecialidadesSinAsignar($EmpresaId,$CentroUtilidad,$UnidadFuncional,$Departamento,$CodigoMedicamento)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasAsignarEspecialidadesAMedicamentos", "", "app","Inv_CodificacionProductos");
  $datos=$sql->Listar_Especialidades($EmpresaId,$CentroUtilidad,$UnidadFuncional,$Departamento,$CodigoMedicamento);
  
			$Arreglo = '<SELECT style="width:100%;height:100%" NAME="especialidad" SIZE="10" class="input-text">';
			$i=0;
			foreach($datos as $key => $esp)
			{
				$Arreglo .= '<OPTION VALUE="'.$esp['especialidad'].'" ondblclick="xajax_InsertarEspecialidades(\''.$EmpresaId.'\',\''.$CentroUtilidad.'\',\''.$UnidadFuncional.'\',\''.$Departamento.'\',\''.$CodigoMedicamento.'\',\''.$esp['especialidad'].'\');">'.$esp['especialidad'].' '.$esp['descripcion'].'</OPTION>';
				$i=$i+1;
			}
			$Arreglo .='</SELECT>';
	
    $objResponse->assign("EspecialidadesSinAsignar","innerHTML",$Arreglo);
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    //$objResponse->call("MostrarSpan");
    return $objResponse;
  }
  
  
  
  
   function Especialidades($EmpresaId,$CentroUtilidad,$UnidadFuncional,$Departamento,$CodigoMedicamento)
  {
  $objResponse = new xajaxResponse();
  
	

	
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "  <tr><td>";
		
		$html .= "      <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
		$html .= "      <tr class=\"modulo_table_list_title\">";
		$html .= "      <td align=\"center\" colspan=\"2\">";
		$html .= "      ASIGNACION DE ESPECIALIDADES";
		$html .= "      </td>";
		$html .= "      </tr>";
		
		$html .= "      <tr class=\"modulo_list_claro\">";
		$html .= "      <td class=\"label\" align=\"center\" width=\"20%\">";
		$html .= "      Especialidades Sin Asignar (Doble Click Para Asignar)";
		$html .= "      </td>";
		$html .= "      <td align=\"center\" width=\"30%\">";
		$html .= "      <div id=\"EspecialidadesSinAsignar\"></div>";
		$html .= "      </td>";
		$html .= "      </tr>";
		$html .= "      </table><br>";
		
		$html .= "<div id=\"EspecialidadesAsignadas\"></div>";

		
  
  
    $objResponse->assign("Contenido","innerHTML",$html);
	$objResponse->script("xajax_EspecialidadesSinAsignar('".$EmpresaId."','".$CentroUtilidad."','".$UnidadFuncional."','".$Departamento."','".$CodigoMedicamento."');");
	$objResponse->script("xajax_EspecialidadesAsignadas('".$EmpresaId."','".$CentroUtilidad."','".$UnidadFuncional."','".$Departamento."','".$CodigoMedicamento."');");
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->call("MostrarSpan");
    return $objResponse;
  }
  


function InsertarEspecialidades($EmpresaId,$CentroUtilidad,$UnidadFuncional,$Departamento,$CodigoMedicamento,$Especialidad)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasAsignarEspecialidadesAMedicamentos", "", "app","Inv_CodificacionProductos");
  
  $token=$sql->InsertarEspecialidades($EmpresaId,$CentroUtilidad,$UnidadFuncional,$Departamento,$CodigoMedicamento,$Especialidad);
  
  if($token)
  {
$objResponse->script("xajax_EspecialidadesSinAsignar('".$EmpresaId."','".$CentroUtilidad."','".$UnidadFuncional."','".$Departamento."','".$CodigoMedicamento."');");
	$objResponse->script("xajax_EspecialidadesAsignadas('".$EmpresaId."','".$CentroUtilidad."','".$UnidadFuncional."','".$Departamento."','".$CodigoMedicamento."');");
  //$objResponse->alert("Ingreso Exitoso!!");
  }
  else
  $objResponse->alert("Error en el Ingreso...!!!");
  
  
  
  return $objResponse;
  }
  
  
  function BorrarEspecialidades($EmpresaId,$CentroUtilidad,$UnidadFuncional,$Departamento,$CodigoMedicamento,$Especialidad)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasAsignarEspecialidadesAMedicamentos", "", "app","Inv_CodificacionProductos");
  
  $token=$sql->BorrarEspecialidades($EmpresaId,$CentroUtilidad,$UnidadFuncional,$Departamento,$CodigoMedicamento,$Especialidad);
  
  if($token)
  {
    $objResponse->script("xajax_EspecialidadesSinAsignar('".$EmpresaId."','".$CentroUtilidad."','".$UnidadFuncional."','".$Departamento."','".$CodigoMedicamento."');");
	$objResponse->script("xajax_EspecialidadesAsignadas('".$EmpresaId."','".$CentroUtilidad."','".$UnidadFuncional."','".$Departamento."','".$CodigoMedicamento."');");
  //$objResponse->alert("Ingreso Exitoso!!");
  }
  else
  $objResponse->alert("Error en el Borrado...!!!");
  
  
  
  return $objResponse;
  }


 
  
  
  
?>
