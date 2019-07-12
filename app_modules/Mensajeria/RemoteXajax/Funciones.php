<?php

function procesarformulario($tema,$descripcion,$caducidad,$cadena,$chequear){
    $respuesta = new xajaxResponse();
    $error_form="";
 
     if ($tema == "")
      $error_form = "Debe Digitar un Tema";
   elseif ($descripcion == "")
      $error_form = "Debe Digitar una Descripcion";
  elseif ($caducidad == "")
      $error_form = "Debe Digitar La Fecha de Caducidad ";
    
   if ($error_form != ""){
      //Hubo un error en el formulario
      //en la capa donde se muestran mensajes, muestro el error
      $respuesta->assign('mensaje',"innerHTML","<span style='color:red;'><h6>$error_form</h6></span>");//"<span style='color:red;'>$error_form</span>")
   }else{
       $separacion='';
     for($i=0;$i<sizeof($cadena);$i++){
         $harray.= $separacion.$cadena[$i];
         $cheq.= $separacion.$chequear[$i];
         $separacion=',';
     }
 
     $url = ModuloGetURL('app', 'Mensajeria', 'user', 'crear',array('modif'=>'guardar','asunto'=>$tema,'descripcion'=>$descripcion,'caducidad'=>$caducidad,'cargos'=>$harray,'chequeados'=>$cheq));
    $respuesta->redirect($url);
    $harray='';
    $cheq='';
   }
    return $respuesta;
}

function procesarformularioMof($tema,$descripcion,$caducidad,$actualizacion_id,$cadena,$chequear){
    $respuesta = new xajaxResponse();
    $error_form="";
  if($actualizacion_id=='')
      $error_form = "Debes Digitar un id";
     elseif ($tema == "")
      $error_form = "Debes Digitar un Tema";
   elseif ($descripcion == "")
      $error_form = "Debes Digitar una Descripci�n";
  elseif ($caducidad == "")
      $error_form = "Debes Digitar La Fecha de Caducidad ";

   if ($error_form != ""){
      //Hubo un error en el formulario
      //en la capa donde se muestran mensajes, muestro el error
      $respuesta->assign('mensaje',"innerHTML","<span style='color:red;'><h6>$error_form.$b</h6></span>");//"<span style='color:red;'>$error_form</span>")
   }else{
       for($i=0;$i<sizeof($cadena);$i++){
         $harray.= $cadena[$i].',';
         $cheq.= $chequear[$i].',';
     }
     $url = ModuloGetURL('app', 'Mensajeria', 'user', 'crear',array('modif'=>'Modificar','asunto'=>$tema,'descripcion'=>$descripcion,'caducidad'=>$caducidad,'actualizacion_id'=>$actualizacion_id,'cargos'=>$harray,'chequeados'=>$cheq));
     $respuesta->redirect($url);
     $harray='';
     $cheq='';
   }
    return $respuesta;
}

function glectura($actualiza_id,$sw,$obli){
    $respuesta = new xajaxResponse();
    $objSql = AutoCarga::factory("ConsultasSql", "classes", "app", "Mensajeria");
    //$id=$objSql->Consultar();
    $tpid = $objSql->Ingrlectura($actualiza_id,$sw);

    if($tpid==true){
    $salida.="Guardada la Actualizacion No.$actualiza_id como Leido";
    }else{
     $salida.="Error al Guardar la Actualizacion No.$actualiza_id";
    }
    $salida2.="";

   $div1="boton$actualiza_id";
   $div2="mensaje$actualiza_id";

    $respuesta->assign('as',"innerHTML",$salida);
    $respuesta->assign($div1,"innerHTML",$salida2);
    $respuesta->assign($div2,"innerHTML",$salida2);
if($obli!=''){
    $idy=UserGetUID();
    $suma=0;
    $mensObli=$objSql->ConsultarControlObligatorio($idy);
    for($i=0;$i<sizeof($mensObli);$i++){
           if($mensObli[$i][sw]==0 && $mensObli[$i][obligatorio]==1){
            $suma+=1;
           }
        }
    if($suma==0){
     $url = ModuloGetURL('system','Menu','user','main');
     $respuesta->redirect($url);
    }
}
    return $respuesta;
}

function envios($envio_id,$envio_text,$cadena,$chequear){
    $respuesta = new xajaxResponse();
    $yesno="checked=yes";

    $html.="<tr id='$envio_id'>
    <td  height=10 width=200 align='top'><h5>$envio_text</h5></td>
    <td width=150><h6>Obligatorio
    <input type='checkbox' id='sc$envio_id'></h6></td>
    <td><h6>
    <img src=\"".GetThemePath()."/images/delete2.gif\">&nbsp;<a href=\"javascript:eliminar('$envio_id');\" >ELIM</a></h6></td></tr>";
    $respuesta->append('usuario',"innerHTML",$html);

    for($i=0;$i<sizeof($cadena);$i++){
        $id="sc$cadena[$i]";
        if($chequear[$i]=='1'){
           $b='true';
        }else{
           $b='';
        }
        $respuesta->assign($id,"checked",$b);
    }
    return $respuesta;
}

function enviosModif($envio_id,$envio_text){
    $respuesta = new xajaxResponse();
    $html.="<p id='$envio_id'>
    <input type='checkbox' id='sc$envio_id' >
    $envio_text&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/delete2.gif\">&nbsp;<a href=\"javascript:eliminar('$envio_id');\" >ELIM</a></p>";
    $respuesta->append('usuario',"innerHTML",$html);
    return $respuesta;
}

function eliminar($id){
    $respuesta = new xajaxResponse();
    $html.="<p id='$id'></p>";
    $respuesta->assign($id,"innerHTML",$html);
    return $respuesta;
}

function filtro_mensaje($fecha1,$fecha2,$usuario){
  $respuesta = new xajaxResponse();
  $objSql = AutoCarga::factory("ConsultasSql", "classes", "app", "Mensajeria");
  $consulta=$objSql->filtroEnvioMensajes($fecha1,$fecha2,$usuario);
  $objHtml = AutoCarga::factory("Agregar_Actual_HTML", "views", "app", "Mensajeria");
  $html ="";
  $html.="<br>";
  $html.="<br>";
  $html.="<center><h3><b><a name='consulta'>Resultado de la Consulta</a></h3></b></center>";
  $html.=$objHtml->TablaMensajes($consulta);
  $respuesta->assign('filtro',"innerHTML",$html);
  return $respuesta;
}

?>