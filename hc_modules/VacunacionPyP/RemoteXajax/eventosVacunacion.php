<?php
 /**
  * @package IPSOFT-SIIS
  * @version $Id: eventosVacunacion.php,v 1.1 2009/12/03 14:58:54 alexander Exp $
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
  *$cargo: me trae el cargo de la vacuna
  *&edad: me trae la edad del paciente
  *link: es el link para ver u ocultar la ventana del xajax
  */
  function verDosisVacunas($cargo, $edad, $link)
  {
    $objResponse = new xajaxResponse();
    
    $mdl = AutoCarga::factory("VacunacionSQL","classes","hc1","VacunacionPyP");
         
    $unidades_tiempo=$mdl->unidades_tiempo();

    $datosDosis = array();
    $datosRefuerzos = array();
    
    if($cargo) $datosDosis=$mdl->traerDatosDosis($cargo);
    if($cargo) $datosRefuerzos=$mdl->traerDatosRefuerzos($cargo);
    
    $html ="<table class=\"modulo_table_list\"  align=\"center\" width=\"100%\" class=\"modulo_table_list\">";
    $html.="    <tr class=\"formulacion_table_list\">";
    $html.="        <td colspan=\"10\">INFORMACION DOSIS</td>";
    $html.="    </tr>";
    $html.="    <tr class=\"formulacion_table_list\">";
    $html.="        <td width=\"10%\">Dosis</td>";
    $html.="        <td width=\"10%\">Edad de Aplicacion</td>";
    $html.="        <td width=\"15%\">Unidad Edad Aplicacion</td>";
    $html.="        <td width=\"50%\">Observaciones</td>";
    $html.="        <td width=\"15%\">Opciones</td>";
    $html.="    </tr>";
 
    foreach($datosDosis as $key => $detalle)
    {
      $html.="  <tr>" ;
      $html.="      <td>".$detalle['numero_dosis']."</td>";
      $html.="      <td>".$detalle['edad_aplicacion']."</td>";
      $html.="      <td>".$detalle['descripcion']."</td>";
      $html.="      <td>".$detalle['observacion_dosis']."</td>";
      $html.="      <td>\n";

      if(!empty($detalle['dosis']))
      {
        $html.="        <img src=\"".GetThemePath()."/images/ok.png\" border=\"0\">APLICADA\n";
      }       
      else if($detalle['edad_aplicacion'] <= $edad)
      {
        $html.="        <a  href=\"#\" onclick=\"xajax_registrarDosisVacuna(xajax.getFormValues('registro_dosis'), '".$cargo."', '".$detalle['dosis_vacuna_id']."')\"  class=\"label_error\" >\n";
        $html.="            <img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">APLICAR\n";
        $html.="        </a>\n";
      }
      $html.="      </td>\n";
      $html.="  </tr>"; 
    }
    $html.="</table><br>\n";

    $html.="<table class=\"modulo_table_list\"  align=\"center\" width=\"100%\" border=\"0\">";
    $html.="    <tr class=\"formulacion_table_list\">";
    $html.="        <td colspan=\"10\">INFORMACION REFUERZOS</td>";
    $html.="    </tr>";
    $html.="    <tr class=\"formulacion_table_list\">";
    $html.="        <td width=\"10%\">Refuerzo</td>";
    $html.="        <td width=\"10%\">Edad de Aplicacion</td>";
    $html.="        <td width=\"15%\">Unidad Edad Aplicacion</td>";
    $html.="        <td width=\"50%\">Observaciones</td>";
    $html.="        <td width=\"15%\">Opciones</td>";
    $html.="    </tr>";
 
    foreach($datosRefuerzos as $key => $detalle)
    {
      $html.="  <tr>" ;
      $html.="     <td>".$detalle['numero_dosis']."</td>";
      $html.="     <td>".$detalle['edad_aplicacion']."</td>";
      $html.="     <td>".$detalle['descripcion']."</td>";
      $html.="     <td>".$detalle['observacion_refuerzo']."</td>";
      $html.="     <td>\n";
       
      if(empty($detalle['dosis']))
      {
        $html.="        <a  href=\"#\" onclick=\"xajax_registrarDosisVacuna(xajax.getFormValues('registro_dosis'), '".$cargo."', '".$detalle['dosis_vacuna_id']."')\" class=\"label_error\" >\n";
        $html.="            <img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">APLICAR\n";
        $html.="        </a>\n";
        $html.="   </td>\n";
        $html.=" </tr>";
      }
      else
      {
        $html.=" <img src=\"".GetThemePath()."/images/ok.png\" border=\"0\">APLICADA\n";
      }
    }
    $html.="</table><br>\n";
    //Esta es la sentencia para mostrar y ocultar la ventana xajax mas las dos ultimas sentencias $objResponse
    $htm =" <a  href=\"#\" onclick=\"xajax_verDosisVacunas('".$cargo."', '".$edad."',".(($link == 1)? "0": "1").")\" class=\"label_error\" >\n";
    $htm.="   <img src=\"".GetThemePath()."/images/flecha.png\" border=\"0\">".(($link == 1)? "OCULTAR": "VER")."\n";
    $htm.=" </a>\n";
    $objResponse->assign("tabla_dosis_".$cargo,"innerHTML",$html);
    $objResponse->assign("link_".$cargo,"innerHTML",$htm);
    $objResponse->assign("tabla_dosis_".$cargo,"style.display",(($link == 1)? "block": "none"));
    return $objResponse;
  }
  
  /**
  * Funcion HTML para hacer el registro de la dosis seleccionada 
  * &form:recive la forma de la clase vacunacionHTML
  * &cargo:el cargo de la vacuna
  * &dosis:id de la dosis
  */
  function registrarDosisVacuna($form,$cargo,$dosis)
  {
    $objResponse = new xajaxResponse();  
    //$objResponse->alert (print_r($form,true));
    //$objResponse->alert ($cargo." ".$dosis);
    $html ="<input type=\"hidden\" name=\"evolucion_id\" value=\"".$form['evolucion_id']."\">\n";
    $html.="<input type=\"hidden\" name=\"cargo\" value=\"".$cargo."\">\n";
    $html.="<input type=\"hidden\" name=\"dosis_vacuna_id\" value=\"".$dosis."\">\n";
    $html.="<table class=\"modulo_table_list\"  align=\"center\" width=\"100%\" class=\"modulo_table_list\">";
    $html.="    <tr class=\"formulacion_table_list\">";
    $html.="        <td colspan=\"2\">REGISTRO DOSIS Y REFUERZOS</td>";
    $html.="    </tr>";
    $html.="    <tr>";
    $html.="        <td class=\"formulacion_table_list\">Lugar de Aplicacion de la Dosis</td>";
    $html.="        <td>";
    $html.="            <input type=\"text\" style=\"width:100%\" name=\"lugar\" class=\"input-text\" value=\"".$datos['lugar_aplicacion']."\">\n";
    $html.="        </td>";
    $html.="    </tr>";
    $html.="    <tr>";
    $html.="        <td class=\"formulacion_table_list\">Fecha de aplicacion</td>";
    $html.="        <td>";
    $html.="            <input type=\"text\" name=\"fecha_aplicacion\" id=\"fecha_aplicacion\" class=\"input-text\" style=\"width:40%\" maxlength=\"10\" onkeyPress=\"return acceptDate()\" value=\"".date("d/m/Y")."\">\n";
    $html.="            ".ReturnOpenCalendarioHTML('registro_aplicacion','fecha_aplicacion','/',1);
    $html.="        </td>";
    $html.="    </tr>";
    $html.="    <tr class=\"formulacion_table_list\">";
    $html.="            <td colspan=\"2\">Observaciones</td>";
    $html.="    </tr>";
    $html.="    <tr>";
    $html.="      <td colspan=\"2\">";
    $html.="            <textarea style=\"width:100%\" rows=\"2\" name=\"observaciones\" class=\"textarea\"></textarea>";
    $html.="      </td>";
    $html.="    </tr>";
    $html.="    <tr class=\"formulacion_table_list\"><td colspan=\"2\">";
    $html.="            <input type=\"submit\" name=\"Guardar\" value=\"Guardar\" class=\"modulo_table_list\" onclick=\"xajax_GuardarAplicacion(xajax.getFormValues('registro_aplicacion'))\">";  
    $html.="    </tr>";
    $html.="</table>";
    $objResponse->assign("aplicar","innerHTML",$html);
    $objResponse->script("MostrarSpan('Contenedor');Iniciar()");
    return $objResponse;
  }
  
  /**
  * Funcion que llama la funcion insertar y guarda el registro de la dosis
  * $form: recive la forma de registrarDosisVacuna
  */
  function GuardarAplicacion($form)
  {
    $objResponse = new xajaxResponse();  
    //$objResponse->alert (print_r($form,true));
    //$objResponse->alert ($cargo." ".$dosis);
    $mdl = AutoCarga::factory("VacunacionSQL","classes","hc1","VacunacionPyP");
    $insertar_dosis=$mdl->insertar_registro_dosis($form);
    if($insertar_dosis)
        $mensaje='Los datos se han guardados satisfactoriamente';
    else
        $mensaje='Error no se han podido guardar los datos '.$mdl->mensajeDeError;

    $objResponse->assign("mensaje","innerHTML",$mensaje);
    $objResponse->script("ocultarVentana()");
    $objResponse->script("xajax_verDosisVacunas('cargo')");
    return $objResponse;
  }
?>