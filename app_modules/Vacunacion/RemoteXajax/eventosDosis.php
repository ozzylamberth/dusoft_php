<?php
 /**
  * @package IPSOFT-SIIS
  * @version $Id: eventosDosis.php,v 1.1 2009/11/05 19:57:49 alexander Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Alexander Biedma Vargas
  */
  /**
  * Clase: eventos_dosis.php
  *
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Alexander Biedma Vargas
  */
  
  /*
  *Esta funcion es el HTML de el registro de las dosis de las vacunas
  *tiene 2 parametros
  *$form: me trae las dosis de la vacuna
  *$cargo: me trae el cargo de la vacuna
  */
  function registroDosis($form,$cargo)
  {
    $objResponse = new xajaxResponse();
    $mdl = AutoCarga::factory("Consultas_vacunas","classes","app","Vacunacion");
         
    $unidades_tiempo=$mdl->unidades_tiempo();
    $datosDosis = array();
    if($cargo) $datosDosis=$mdl->traerDatosDosis($cargo);   
    $html ="<table class=\"modulo_table_list\"  align=\"center\" width=\"100%\" border=\"0\">";
    $html.="    <tr class=\"formulacion_table_list\">";
    $html.="        <td colspan=\"4\">REGISTRAR DOSIS</td>";
    $html.="    </tr>";
    $html.="    <tr>";
    $html.="        <td>Numero de Dosis</td>";
    $html.="        <td>Edad de Aplicacion</td>";
    $html.="    </tr>";
    $num_dosis = $form['dosis'];
    
    for($i = 0; $i< $num_dosis; $i++)
    {
       ($estilo=='modulo_list_oscuro')? $estilo='modulo_list_claro':$estilo='modulo_list_oscuro'; 
       ($background == "#CCCCCC")? $background = "#DDDDDD":$background = "#CCCCCC";
       $html.=" <tr>";
       $html.="     <td>".($i+1)."</td>";
       $html.="     <td class=\"modulo_list_claro\">";
       $html.="         <input type=\"text\" name=\"edad_aplicacion_d[]\" id=\"edad_aplicacion_".$i."\" class=\"input-text\" onkeypress=\"return acceptNum(event)\" value=\"".$datosDosis[($i+1)]['edad_aplicacion']."\">";
       $html.="     </td>";
       $html.="     <td class=\"modulo_list_claro\">";
       $html.="         <select name=\"unidad_edad_aplicacion_d[]\" id=\"unidad_edad_aplicacion_".$i."\" class=\"select\" onchange=\"seleccionarValor(document.buscador_cargos)\">";
       $html.="             <option value='-1'>--Seleccionar--</option>";
       $slt = "";
       foreach($unidades_tiempo as $key => $detalle)
       {
          ($datosDosis[($i+1)]['unidad_edad_aplicacion'] == $detalle['unidad_tiempo_id'])? $slt= "selected":$slt = "";
            $html.="        <option value='".$detalle["unidad_tiempo_id"]."' ".$slt.">".$detalle["descripcion"]."</option>"; 
          $script.="      unidades[".$detalle["unidad_tiempo_id"]."] = ".$detalle["indice_orden"].";\n";
           
          if($i == 0 && $detalle['unidad_tiempo_id'] == '1') unset($unidades_tiempo[$key]);
        }
       $html.="         </select>";
       $html.="     </td> ";
       $html.=" </tr>";
    }
    $html.="</table>\n";
    
    $objResponse->assign("tabla_dosis","innerHTML",$html);
    return $objResponse;
  }
  
  /*
  *Esta funcion es el HTML del registro de los refuerzos de las vacunas
  *tiene 2 parametros
  *$form: me trae los refuerzos de la vacuna
  *$cargo: me trae el cargo de la vacuna
  */
  function registroRefuerzos($form, $cargo)
  {
    $objResponse = new xajaxResponse();
    $mdl = AutoCarga::factory("Consultas_vacunas","classes","app","Vacunacion");
    
    $unidades_tiempo=$mdl->unidades_tiempo();
    $datosRefuerzos = array();
    if($cargo) $datosRefuerzos=$mdl->traerDatosRefuerzos($cargo);
           
    $html ="<table class=\"modulo_table_list\"  align=\"center\" width=\"100%\" border=\"0\">";
    $html.="    <tr class=\"formulacion_table_list\">";
    $html.="        <td colspan=\"4\">REGISTRAR REFUERZOS</td>";
    $html.="    </tr>";
    $html.="    <tr>";
    $html.="        <td>Numero de Refuerzos</td>";
    $html.="        <td>Edad de Aplicacion</td>";
    $html.="    </tr>";
    $num_refuerzos = $form['refuerzos'];
    
    for($i = 0; $i< $num_refuerzos; $i++)
    {
       ($estilo=='modulo_list_oscuro')? $estilo='modulo_list_claro':$estilo='modulo_list_oscuro'; 
       ($background == "#CCCCCC")? $background = "#DDDDDD":$background = "#CCCCCC";
       $html.="  <tr>";
       $html.="     <td>".($i+1)."</td>";
       $html.="     <td class=\"modulo_list_claro\">";
       $html.="         <input type=\"text\" name=\"edad_aplicacion_r[]\" id=\"edad_aplicacion_r_".$i."\" class=\"input-text\" onkeypress=\"return acceptNum(event)\" value=\"".$datosRefuerzos[($i+1)]['edad_aplicacion']."\">";
       $html.="     </td>";
       $html.="     <td class=\"modulo_list_claro\">";
       $html.="         <select name=\"unidad_edad_aplicacion_r[]\" id=\"unidad_edad_aplicacion_r_".$i."\" class=\"select\" onchange=\"seleccionarValor(document.buscador_cargos)\">";
       $html.="             <option value='-1'>--Seleccionar--</option>";
       foreach($unidades_tiempo as $key => $detalle)
       {
          if($detalle["descripcion"]!="recien nacido")
          {
                ($datosRefuerzos[($i+1)]['unidad_edad_aplicacion'] == $detalle['unidad_tiempo_id'])? $slt= "selected":$slt = "";
                $html.="    <option value='".$detalle["unidad_tiempo_id"]."' ".$slt.">".$detalle["descripcion"]."</option>";
                $script.= " unidades[".$detalle["unidad_tiempo_id"]."] = ".$detalle["indice_orden"].";\n";
          }
       }
       $html.="        </select>";
       $html.="    </td> ";
       $html.=" </tr> ";
    }
    $html.="</table>\n";
    
    $objResponse->assign("tabla_refuerzos","innerHTML",$html);
    return $objResponse;
  }
?>