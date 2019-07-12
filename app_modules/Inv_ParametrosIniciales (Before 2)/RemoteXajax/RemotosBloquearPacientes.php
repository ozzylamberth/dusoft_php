<?php
/**
  * @package IPSOFT-SIIS
  * @version $Id: RemotosBloquearPacientes.php,v 1.2 2009/11/06 19:48:40 mauricio Exp $
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
 

 
 /*
  * Funcion Que Refrescará el listado de Tipos de Insumos a desplegar en la pagina.
  */  
  function TiposBloqueos($offset,$TerceroId,$TipoBloqueoId,$NombreTercero,$offset1)
  {
  $objResponse = new xajaxResponse();
  
  $sql1 = AutoCarga::factory("ConsultasTiposBloqueos","classes","app","Inv_ParametrosIniciales");
  
  
	$TiposBloqueos=$sql1->ListadoTiposBloqueos($offset1);
  
     
    $action['paginador'] = "Paginador('".$offset."','".$TerceroId."','".$TipoBloqueoId."','".$NombreTercero."'";
   
    
    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($sql1->conteo,$sql1->pagina,$action['paginador']);  
  
  
    $html .= "<center>";
      $html .= "<fieldset class=\"fieldset\" style=\"width:60%\">\n";
   $html .= "  <legend class=\"normal_10AN\">BLOQUEOS PARA ".$NombreTercero."</legend>\n";
        
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        
		$html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td width=\"5%\">CODIGO</td>\n";
        $html .= "      <td width=\"40%\">BLOQUEO</td>\n";
        $html .= "      <td width=\"10%\">OP</td>\n";
				
        $html .= "    </tr>\n";

        $est = "modulo_list_claro";
        $bck = "#DDDDDD";
        foreach($TiposBloqueos as $key => $tb)
        {
          ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
          ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

          $html .= "    <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
          $html .= "      <td >".$tb['codigo']."</td><td>".$tb['descripcion']." </td>\n";
         // $objResponse->alert($TipoBloqueoId);
         if((strcmp($tb['codigo'],$TipoBloqueoId))==0)
          { 
          
          $html .= "<td align=\"center\">
                <a href=\"#\" onclick=\"xajax_CambioEstadoPaciente('".$offset."','".$TerceroId."','".$TipoBloqueoId."','".$NombreTercero."','".$offset1."','".$tb['codigo']."')\">\n";
          $html .="<img title=\"ASIGNADO\" src=\"".GetThemePath()."/images/checksi.png\" border=\"0\"></a></td>\n";
          }
          else
            {
            $html .= "<td align=\"center\"><a href=\"#\" onclick=\"xajax_CambioEstadoPaciente('".$offset."','".$TerceroId."','".$TipoBloqueoId."','".$NombreTercero."','".$offset1."','".$tb['codigo']."')\">\n";
            $html .="<img title=\"NO ASIGNADO\" src=\"".GetThemePath()."/images/checkno.png\" border=\"0\"></a></td>\n";
            }
          $html .= "      </td>\n";
            
        }
        
        
        
        $html .= "    </table>\n";
        
        $html .= "</fieldset><br>\n";
        $html .= "    </center>\n";
      
    
      $objResponse->assign("listadoclientesbloqueos","innerHTML",$objResponse->setTildes($html));
       //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
      return $objResponse;
  }
 
 
 
 
 
 
 
 
 
 /*
  * Funcion Que Refrescará el listado de Tipos de Insumos a desplegar en la pagina.
  * BuscarTercero
  */  
  function PacientesT($offset)
  {
  $objResponse = new xajaxResponse();
   					
			//Generar de Busqueda de Permisos SQL
			$obj_busqueda=AutoCarga::factory("ConsultasBloquearTerceros", "classes", "app","Inv_ParametrosIniciales");
			//Obtenemos los resultados del Query realizado en Classes. Accediendo al metodo del Objeto $obj_busqueda.
			$Pacientes=$obj_busqueda->ListarPacientes($offset); 
		  $action['volver'] = ModuloGetURL("app","Inv_ParametrosIniciales","controller","MenuParametrosIniciales");
    
      $VolverMenu=$action['volver'];
		  
      $action['paginador'] = "paginador(";
   
    
    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($obj_busqueda->conteo,$obj_busqueda->pagina,$action['paginador']);  
  
  
    $html .= "<center>";
    $html .= "<fieldset class=\"fieldset\" style=\"width:90%\">\n";
    $html .= "  <legend class=\"normal_10AN\">TERCEROS - CLIENTES</legend>\n";
        
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        
		$html .= "    <tr class=\"formulacion_table_list\">\n";
       
    $html .= "          <td align=\"center\" width=\"8%\">
                        <a title=\"TIPO DE DOCUMENTO\">TIPO ID</a>                      </td>
                      <td align=\"center\" width=\"10%\">
                        <a title=\"NUMERO DE IDENTIFICACION\">NUMERO</a>                      </td>
                      <td align=\"center\" width=\"23%\">
                        <a title=\"NOMBRE COMPLETO\">NOMBRE                      </a></td>

                      <td align=\"center\" width=\"4%\">
                        <a title=\"SEXO\">SEXO</a><a>                      </a></td>
                      
                      <td align=\"center\" width=\"4%\">
                        <a title=\"PAIS ID\">PAIS</a><a>                      </a></td>
                      
                      <td align=\"center\" width=\"4%\">
                        <a title=\"DEPARTAMENTO ID\">DPTO</a><a>                      </a></td>

                      <td align=\"center\" width=\"15%\">
                        <a title=\"MUNICIPIO ID\">MPIO</a><a>                      </a></td>
                    
                         <td align=\"center\" width=\"9%\">
                        <a title=\"BLOQUEO\">BLOQUEO</a><a>                      </a></td>
              ";      
				
        $html .= "    </tr>\n";

        $est = "modulo_list_claro";
        $bck = "#DDDDDD";
        foreach($Pacientes as $key => $t)
        {
          ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
          ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

          $html .= "    <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
          $html .= "      <td >".$t['tipo_id_paciente']."</td><td>".$t['paciente_id']." </td>
          <td>".$t['primer_nombre']." ".$t['segundo_nombre']." ".$t['primer_apellido']." ".$t['segundo_apellido']." </td>
          <td>".$t['sexo_id']." </td>
          <td>".$t['pais']." </td>
          <td>".$t['departamento']." </td>
          <td>".$t['municipio']." </td>
          <td><a href=\"#\" onclick=\"xajax_CambioBloqueo('".$offset."','".$t['paciente_id']."','".$t['tipo_bloqueo_id']."','".$t['primer_nombre']." ".$t['segundo_nombre']." ".$t['primer_apellido']." ".$t['segundo_apellido']."')\">".$t['tipo_bloqueo_id']."-".$t['bloqueo']."</a></td>
          </tr>\n";
          
            
        }
        
        
        
        $html .= "    </table>\n";
        
        $html .= "</fieldset><br>\n";
        $html .= "    </center>\n";
      
      
      $objResponse->assign("ListadoPacientes","innerHTML",$objResponse->setTildes($html));
       //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
      return $objResponse;
  }
 
 
 
 
 
 /*
  * Funcion Que Refrescará el listado de Tipos de Insumos a desplegar en la pagina.
  * BuscarTercero
  */ 
  function BuscarPaciente($tipo_id,$paciente_id,$primero_nombre,$primer_apellido,$offset)
  {
  $objResponse = new xajaxResponse();
   					
			//Generar de Busqueda de Permisos SQL
			$obj_busqueda=AutoCarga::factory("ConsultasBloquearTerceros", "classes", "app","Inv_ParametrosIniciales");
			//Obtenemos los resultados del Query realizado en Classes. Accediendo al metodo del Objeto $obj_busqueda.
			$Pacientes=$obj_busqueda->BuscarPaciente($tipo_id,$paciente_id,$primero_nombre,$primer_apellido,$offset); 
		  $action['volver'] = ModuloGetURL("app","Inv_ParametrosIniciales","controller","MenuParametrosIniciales");
    
      $VolverMenu=$action['volver'];
		  
      $action['paginador'] = "PaginadorBusqueda('".$tipo_id."','".$paciente_id."','".$primero_nombre."','".$primer_apellido."'";
   
    
    $pghtml = AutoCarga::factory("ClaseHTML");
    $html .= $pghtml->ObtenerPaginadoXajax($obj_busqueda->conteo,$obj_busqueda->pagina,$action['paginador']);  
  
  
    $html .= "<center>";
    $html .= "<fieldset class=\"fieldset\" style=\"width:90%\">\n";
    $html .= "  <legend class=\"normal_10AN\">TERCEROS - CLIENTES</legend>\n";
        
		$html .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        
		$html .= "    <tr class=\"formulacion_table_list\">\n";
       
    $html .= "          <td align=\"center\" width=\"8%\">
                        <a title=\"TIPO DE DOCUMENTO\">TIPO ID</a>                      </td>
                      <td align=\"center\" width=\"10%\">
                        <a title=\"NUMERO DE IDENTIFICACION\">NUMERO</a>                      </td>
                      <td align=\"center\" width=\"23%\">
                        <a title=\"NOMBRE COMPLETO\">NOMBRE                      </a></td>

                      <td align=\"center\" width=\"4%\">
                        <a title=\"SEXO\">SEXO</a><a>                      </a></td>
                      
                      <td align=\"center\" width=\"4%\">
                        <a title=\"PAIS ID\">PAIS</a><a>                      </a></td>
                      
                      <td align=\"center\" width=\"4%\">
                        <a title=\"DEPARTAMENTO ID\">DPTO</a><a>                      </a></td>

                      <td align=\"center\" width=\"15%\">
                        <a title=\"MUNICIPIO ID\">MPIO</a><a>                      </a></td>
                    
                         <td align=\"center\" width=\"9%\">
                        <a title=\"BLOQUEO\">BLOQUEO</a><a>                      </a></td>
              ";      
				
        $html .= "    </tr>\n";

        $est = "modulo_list_claro";
        $bck = "#DDDDDD";
        foreach($Pacientes as $key => $t)
        {
          ($est == "modulo_list_claro")? $est = 'modulo_list_oscuro' : $est = 'modulo_list_claro';
          ($bck == "#CCCCCC")?  $bck = "#DDDDDD" : $bck = "#CCCCCC";

          $html .= "    <tr class=\"".$est."\" onmouseout=mOut(this,\"".$bck."\"); onmouseover=mOvr(this,'#FFFFFE');>\n";
          $html .= "      <td >".$t['tipo_id_paciente']."</td><td>".$t['paciente_id']." </td>
          <td>".$t['primer_nombre']." ".$t['segundo_nombre']." ".$t['primer_apellido']." ".$t['segundo_apellido']." </td>
          <td>".$t['sexo_id']." </td>
          <td>".$t['pais']." </td>
          <td>".$t['departamento']." </td>
          <td>".$t['municipio']." </td>
          <td><a href=\"#\" onclick=\"xajax_CambioBloqueo('".$offset."','".$t['paciente_id']."','".$t['tipo_bloqueo_id']."','".$t['primer_nombre']." ".$t['segundo_nombre']." ".$t['primer_apellido']." ".$t['segundo_apellido']."')\">".$t['tipo_bloqueo_id']."-".$t['bloqueo']."</a></td>
          </tr>\n";
          
            
        }
        
        
        
        $html .= "    </table>\n";
        
        $html .= "</fieldset><br>\n";
        $html .= "    </center>\n";
      
      
      $objResponse->assign("ListadoPacientes","innerHTML",$objResponse->setTildes($html));
       //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
      return $objResponse;
  }
 
 
 
 
 
 
 /*
  Funcion Xajax para Modificar El Tipo de bloqueo Asignado a un Cliente
  cargado en un Xajax
  */
  function CambioEstadoPaciente($offset,$PacienteId,$TipoBloqueoId,$NombrePaciente,$offset1,$NuevoBloqueo)
  {
  $objResponse = new xajaxResponse();
  $sql = AutoCarga::factory("ConsultasBloquearTerceros","classes","app","Inv_ParametrosIniciales");
  $TiposBloqueos=$sql->ModificarPaciente($PacienteId,$NuevoBloqueo);
  
   // $objResponse->alert($NombreTercero);
    $objResponse->script("xajax_TiposBloqueos('".$offset."','".$PacienteId."','".$NuevoBloqueo."','".$NombrePaciente."','".$offset1."');");
    $objResponse->script("xajax_PacientesT('".$offset."');");
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    return $objResponse;
  
  }
 
 
 
 
 
 
 
 
 
 /*
  Funcion Xajax para Modificar El Tipo de bloqueo Asignado a un Cliente
  cargado en un Xajax
  */
  function CambioBloqueo($offset,$TerceroId,$TipoBloqueoId,$NombreTercero)
  {
  $objResponse = new xajaxResponse();
  
  
	$html .="<div id=\"listadoclientesbloqueos\">";
  $html .="</div>";
  
   // $objResponse->alert($NombreTercero);
    $objResponse->assign("Contenido","innerHTML",$objResponse->setTildes($html));
    $objResponse->script("xajax_TiposBloqueos('".$offset."','".$TerceroId."','".$TipoBloqueoId."','".$NombreTercero."');");
    //LLamado a funcion Javascript que muestre la ventanita (en capa) con Ajax
    $objResponse->call("MostrarSpan");
    return $objResponse;
  
  }
 
?>