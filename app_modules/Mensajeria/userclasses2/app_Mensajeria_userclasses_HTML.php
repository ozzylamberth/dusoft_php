<?php

IncludeClass("ClaseHTML");

class app_Mensajeria_userclasses_HTML extends app_Mensajeria_user {

    function app_Mensajeria_userclasses_HTML() {
        return true;
    }
function Agregar(){
   $objSql = AutoCarga::factory("ConsultasSql", "classes", "app", "Mensajeria");
    //script configuracion del editor
    $html.="<script type='text/javascript' src='app_modules/Mensajeria/classes/tinymce/jscripts/tiny_mce/tiny_mce.js'></script>
<script type='text/javascript'>
	tinyMCE.init({
                autosave_ask_before_unload: false,
		mode : 'textareas',
		theme : 'advanced',
		plugins : 'pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave',
                theme_advanced_buttons1 : '|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect',
		theme_advanced_buttons2 : 'pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup|,insertdate,inserttime,preview,|,forecolor,backcolor',
		theme_advanced_buttons3 : 'tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,advhr,|,print,|,ltr,rtl',
		theme_advanced_buttons4 : 'insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak',
		theme_advanced_toolbar_location : 'top',
		theme_advanced_toolbar_align : 'left',
		theme_advanced_statusbar_location : 'bottom',
		theme_advanced_resizing : true,
		content_css : false,
		browser_preferred_colors : true,
		detect_highcontrast : true,
		template_external_list_url : 'app_modules/Mensajeria/classes/tinymce/examples/lists/template_list.js',
		external_link_list_url : 'app_modules/Mensajeria/classes/tinymce/examples/lists/link_list.js',
		external_image_list_url : 'app_modules/Mensajeria/classes/tinymce/examples/lists/image_list.js',
		media_external_list_url : 'app_modules/Mensajeria/classes/tinymce/examples/lists/media_list.js',
		style_formats : [
			{title : 'Bold text', inline : 'b'},
			{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
			{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
			{title : 'Example 1', inline : 'span', classes : 'example1'},
			{title : 'Example 2', inline : 'span', classes : 'example2'},
			{title : 'Table styles'},
			{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
		],
		template_replace_values : {
			username : 'Some User',
			staffid : '991234'
		}
	});
</script> ";
  //  $url1 = ModuloGetURL('app', 'ActualizacionesSistema', 'controller', 'main',array('modif'=>'guardar','asunto'=>$asunto));
   // $objHtml = AutoCarga::factory("Sub_Menu_HTML", "views", "app", "ActualizacionesSistema");
    $html.="<script>
        var cadena = new Array();
        var chequear = new Array();
        var rec=0;
                 function formulariol()
                  {
                    var error;
                    var asunto;
                    var descripcion;
                    var fecha;
                    var chequear = new Array();
                    var envioId = document.getElementById('cargo').value;
                    asunto = document.getElementById('asunto').value;
                    
                      
                    for(var i=0;i<cadena.length;i++){
                      var idch='sc'+cadena[i];
                      check = document.getElementById(idch).checked;
                      if(check==false){
                      chequear[i]=0;
                      }else{
                      chequear[i]=1;
                      }
                    }
                    
                    if(cadena.length!='0'){
                       descripcion = tinyMCE.get('descripcion').getContent();
                       fecha = document.getElementById('caducidad').value;
                       xajax_procesarformulario(asunto,descripcion,fecha,cadena,chequear);
                      }else{
                            alert('No se ha Ingresado el Destinatario');
                           }
                  }
                  function formularioModif(cadenaer)
                  {
                     var cadenaAnterior = new Array();
                     var t=cadenaer;
                     var c=cadena.length;
                    for(var j=0;j<t;j++){
                      var nameC='campo'+j;
                      cadenaAnterior=document.getElementsByName(nameC);
                      try{
                      var fr=cadenaAnterior[0].id;                      
                      var n=fr.replace('sc','');
                      cadena[c]=n.trim();
                      c++;
                      }catch(exception){
                      }
                    }
                    if(cadena.length!='0'){
                    var error;
                    var asunto;
                    var descripcion;
                    var fecha;
                    var actualizacion_id;
                    actualizacion_id = document.getElementById('actualizacion_id').value;
                    asunto = document.getElementById('asunto').value;
                    descripcion = tinyMCE.get('descripcion').getContent();
                    fecha = document.getElementById('caducidad').value;
                   for(var i=0;i<cadena.length;i++){
                      var idch='sc'+cadena[i];
                      check = document.getElementById(idch).checked;
                      if(check==false){
                      chequear[i]=0;
                      }else{
                      chequear[i]=1;
                      }
                    }
                    xajax_procesarformularioMof(asunto,descripcion,fecha,actualizacion_id,cadena,chequear);
                    }else{
                            alert('No se ha Ingresado el Destinatario');
                           }
                  }
                  var verifiAgre = new Array();
                  banexc=0;
                  function envios(cadenaer)
                  {
                    var cont=0;
                    var band=0;
                    var envioId = document.getElementById('cargo').value;
                    var envio = document.getElementById('cargo').selectedIndex;
                    var envioText = document.getElementById('cargo').options[envio].text;

                   for(var k=0;k<verifiAgre.length;k++){
                          if(verifiAgre[k]==envioId){
                             band=1;
                            }
                         }
                  for(var j=0;j<cadenaer;j++){
                      var nameC='campo'+j;
                      cadenaAnterior=document.getElementsByName(nameC);
                      try{
                              var fr=cadenaAnterior[0].id;
                              var n=fr.replace('sc','');
                               if(n.trim()==envioId){
                                band=1;
                               }
                          }catch(exception){
                           verifiAgre[banexc]=envioId;
                           banexc++;
                          }
                    }
                  if(band==0){
                        for(var i=0;i<cadena.length;i++){
                           if(cadena[i]==envioId){
                             cont+=1;
                            }
                          var idcarg='sc'+cadena[i];
                          check = document.getElementById(idcarg).checked;
                          if(check==false){
                          chequear[i]=0;
                          }else{
                          chequear[i]=1;
                          }
                         }
                         if(cont==0){
                         cadena[rec]=envioId;
                         xajax_envios(envioId,envioText,cadena,chequear);
                         rec+=1;
                         }
                     }
                    }

                   function eliminar(envio_id){
                     imagen = document.getElementById(envio_id);
                        if (!imagen){
                                alert('El elemento selecionado no existe');
                        } else {
                                padre = imagen.parentNode;
                                padre.removeChild(imagen);
                                var pos = cadena.indexOf(envio_id);
                                if(pos!=-1) cadena.splice(pos,1);
                                chequear.splice(pos, 1 );
                                //rec-=1;
                               }
                    }
                   
                </script>";//location.href='$url1'; alert('descripcion >> '+descripcion);descripcion = document.getElementById('descripcion').value;
/* xajax_eliminar(envio_id);
                      var pos = cadena.indexOf(envio_id);
                      cadena.splice( pos, 1 );*/

        $asunto=$_REQUEST['asunto'];
        $descripcion=$_REQUEST['descripcion'];
        $caducidad1=$_REQUEST['fecha_fin'];
        $actualizacion_id=$_REQUEST['actualizacion_id'];
        $fecha_ini=$_REQUEST['fecha_ini'];
        $fecha_fin=substr($caducidad1, 0, 11);
        $caducidad=$this->FechaStamp($caducidad1);
        $arayCarg=$_REQUEST['cargos'];
        $url2 = ModuloGetURL('app', 'Mensajeria', 'user', 'Menu');
        $htmldiv.="<table>";
     if($arayCarg[0][cargo_id]!=''){
         for ($i = 0; $i < sizeof($arayCarg); $i++) {
         $c=$arayCarg[$i][descripcion];
         $e[]=$arayCarg[0][cargo_id];
          if($arayCarg[$i][obligatorio]==1){
             $yesno="checked='yes'";
         }else{
             $yesno='';
         }
         $idcarg=$arayCarg[$i][cargo_id];
        $htmldiv.="<tr id=$idcarg>
                   <td height=10 width=200 align='top'><h5>$c</h5></td>";
        $htmldiv.="<td width=150><h6>Obligatorio
                   <input type='checkbox' name='campo$i' $yesno id='sc$idcarg' ></td>";
        $htmldiv.="<td><h6><img src=\"".GetThemePath()."/images/delete2.gif\">&nbsp;
                   <a href=\"javascript:eliminar('$idcarg');\" >ELIM</a></h6></td></tr>";
         }
       }
        $htmldiv.="</table>";
        $html.= ThemeAbrirTabla('INGRESO DE ACTUALIZACIONES');
        $html.="<table width='75%' align='center' border='0'>
                <tr>
                <td>
                <div id='mensaje'></div>
                </td>
                </tr>
                </table>";
        
        $html.= "<form name=\"forma\" id='forma' method=\"post\"/>\n";
        $html.="<table width='75%' align='center' border='0'>
                <br />
                <tr>
                 <td align='left' width='10%'><b>Tema:</b></th></td>
                 <td width='90%'><input type='text' style='width:600px;height:20px' value='$asunto' id='asunto' name='asunto'/>&nbsp;&nbsp;&nbsp;&nbsp;<b><a href=\"$url2\">MEN&Uacute;</a></b></td>
               </tr>
               ";

        $html.="<tr>
                <td colspan='2' align='center'>
			<textarea id='descripcion' name='descripcion' value='$descripcion' rows='15' cols='80' style='width: 100%'>
			$descripcion
                        </textarea>
                </td>
                </tr>";//
   $html.="<input type='hidden' id='actualizacion_id' name='actualizacion_id' value='$actualizacion_id'>";
       $html.=" <tr>
                <td colspan='2' ><H6><b>Vigente Hasta:</b><input type='text' value='$fecha_fin' id='caducidad' name='caducidad' size='11' maxlength='10' >";
                 $html.= ReturnOpenCalendario('forma','caducidad','/');
                 $html.="</H6>
                </td>
               </tr>";
              $html.=" <tr><td colspan='2'><H6><b>Enviar A:&nbsp;</b>";
                 $Cargos=$objSql->TipoCargos();
                 
                // $_SESSION['cargos']=$Cargos;
		if($Cargos){
                    $html.="<select name='cargo' id='cargo' class='select'>";
		   // $html.=" <option  value=-1>Todos</option>";
                        for($i=0;$i<sizeof($Cargos);$i++){
                            $ct[]=$Cargos[$i][cargo_id];
                            $re.=$Cargos[$i][cargo_id].',';
				if($_REQUEST['cargo']==$Cargos[$i][cargo_id]){
					$html.="<option value=".$Cargos[$i][cargo_id]." selected>".$Cargos[$i][descripcion]."</option>";
				}else{
					$html.="<option value=".$Cargos[$i][cargo_id].">".$Cargos[$i][descripcion]."</option>";
				}
			}
			$html.="</select>";
		}
                $rt = implode (',',$re);
                $tam= sizeof($arayCarg);
                 $html.="<input  type='button' style='height:23px; width:60px' value='Agregar' id='guardar' name='agregar' onClick =\"javascript:envios($tam);\" />";
                 $html.=" <div id='titulo'></div>";
                 $html.=" <table id='usuario'>";
                 //$html.=" <tr id='usuario'></tr>";
                 $html.=" </table>";
                 if($actualizacion_id!=''){
                 $html.=$htmldiv;
                 }
                 $html.=" </td></tr>";




       if($actualizacion_id==''){
               $html.="<tr>
                       <td colspan='2' align='center'><input  type='button'  style='height:30px; width:70px' value='Guardar' id='guardar' name='guardar' onClick =\"javascript:formulariol();\" />";
              
           }//action='$url1'
           if($actualizacion_id!=''){
                $tam= sizeof($arayCarg);
               $html.="<tr>
                       <td colspan='2' align='center'><input type='button'  style='height:30px; width:70px' value='Modificar' id='modificar' name='modificar' onClick =\"javascript:formularioModif($tam);\"/>";
              
           }//action='$url2'
        
        $html.="</td>";
        $html.="</tr>";
        $html.="</table>";
        $html.="</br>\n";
        $html.="</form>\n";
        $html.= $this->tabladeIngresos();
        $html.=ThemeCerrarTabla();

return $html;

}//<a href=\"$url2\">Menu</a>

function tabladeIngresos(){
      $objSql = AutoCarga::factory("ConsultasSql", "", "app", "Mensajeria");
      $datos=$objSql->ConsultarActulizacion();

        $html .= "<table align='center' border='0' class='hc_table_list' width='100%'>
                   <tr  class='modulo_table_list_title' style='font-size:16px'>
                    <td colspan='8'>REGISTROS DE ACTUALIZACIONES</td>
                   </tr>
                    <tr class='modulo_table_list_title' style=\"font-size:12px\">
                    <td width='1%'>ID</th>
                    <td width='5%'>Fecha Registro</th>
                    <td width='5%'>�ltima Actualizaci�n</th>
                    <td width='5%'>Fecha de Caducidad</th>
                    <td width='20%'>Asunto</th>
                    <td width='50%'>Descripci�n</th>
                    <td width='10%'>Receptores</br>(*lectura obligatoria)</th>
                    <td width='4%'>Modificar</th>
                    </tr>
                  ";

        for ($i = 0; $i < sizeof($datos); $i++) {
            if ($i % 2) {
                $estilo = 'modulo_list_claro';
            } else {
                $estilo = 'modulo_list_oscuro';
            }
            $actualizacion_id = $datos[$i][actualizacion_id];
            $cargos=$objSql->Consultarcontrolar_x_perfil($actualizacion_id);
            $fecha_ini = $datos[$i][fecha_ini];
            $fecha_fin = $datos[$i][fecha_fin];
            $fecha_act = $datos[$i][fecha_actu];
            $asunto = $datos[$i][asunto];
            $descripcion = $datos[$i][descripcion];
            $fecha_ini=substr($fecha_ini, 0, 16);
            $fecha_fin=substr($fecha_fin, 0, 11);
            $fecha_act=substr($fecha_act, 0, 16);
            $html.="<tr class='$estilo'>";
            $url = ModuloGetURL('app', 'Mensajeria', 'user', 'crear',array('actualizacion_id'=>$actualizacion_id,'fecha_ini'=>$fecha_ini,
                                'fecha_fin'=>$fecha_fin,'asunto'=>$asunto,'descripcion'=>$descripcion,'modif'=>'','cargos'=>$cargos));
           
            $html .= "<td class='$estilo'>$actualizacion_id</td>
                      <td class='$estilo'>$fecha_ini</td>
            <td class='$estilo'>$fecha_act</td>
                      <td class='$estilo'>$fecha_fin</td>
                      <td class='$estilo'>$asunto</td>
                      <td class='$estilo'>$descripcion</td>";
            $html .= "<td class='$estilo'>";
                for ($j = 0; $j < sizeof($cargos); $j++) {
                    $descrip=$cargos[$j][descripcion];
                    $lect='';
                    if($cargos[$j][obligatorio]==1){
                        $lect='*';
                    }
            $html .= "<p>-&nbsp;$descrip&nbsp;$lect</p>";
                }
            $html .= "</td>
                      <td class='$estilo' align='center'><a href='$url' >Modificar</a></td>
            </tr>";
        }
        $html .= "</table>";
        return $html;
}

function LeerActualizaciones(){
    $objSql = AutoCarga::factory("ConsultasSql", "", "app", "Mensajeria");
    $usuario_id=UserGetUID();
    $datos=$objSql->ConsultarControl($usuario_id);
    $url2 = ModuloGetURL('app', 'Mensajeria', 'user', 'Menu');
    $action1 = ModuloGetURL('system','Menu','user','main');
    $html.="<script>
                 function guardarlectura(actualizacion_id,s)
                  {
                    xajax_glectura(actualizacion_id,s,'');
                  }
                </script>";
     
        $html.= ThemeAbrirTabla('MENSAJES DE ACTUALIZACIONES');
         if($_REQUEST['vermenu']==1){
            $html.="<table align='center'>";
            $html.="<tr>";
            $html.="<td>";
            $html.="<b><a align='left' href=\"$url2\">MEN&Uacute;</a></b>";
            $html.="</td>";
            $html.="</tr>";
            $html.="</table>";
            }else{
                $html.="<table align='center'>";
                $html.="<tr>";
                $html.="<td>";
                $html.="<b><a align='left' href=\"$action1\">MEN&Uacute;</a></b>";
                $html.="</td>";
                $html.="</tr>";
                $html.="</table>";
            }
        $html .= "<table align='center' border='1' RULES='GROUPS' width='90%'>";
        for ($i = 0; $i < sizeof($datos); $i++) {
            $estilo1 = 'modulo_list_claro';
            $estilo = 'modulo_list_oscuro';
            $actualizacion_id =$datos[$i][actualizacion_id];
            $asunto = $datos[$i][asunto];
            $fecha_fin = $datos[$i][fecha_fin];
            $fecha=$this->FechaStamp($fecha_fin);
            $sw = $datos[$i][sw];
            $nombre = $datos[$i][nombre];
            $descripcion= $datos[$i][descripcion];
            $fecha_lectura=$this->FechaStamp($datos[$i][fecha_lectura]);
            $hora_lectura=$this->HoraStamp($datos[$i][fecha_lectura]);
            if ($sw==0) {
              $mensaje='�Mensaje Nuevo!';
              $chequearSI='';
              $chequearNO="CHECKED";
            } else {
              $mensaje='';
              $chequearSI="CHECKED";
              $chequearNO='';
            }
            $html.="
            <tbody>
            <tr class='$estilo1' >";
            $html .= "
            <td class='$estilo' width='40%'><h3><b>Publicaci�n N�.&nbsp;$actualizacion_id&nbsp;&nbsp;&nbsp;&nbsp;Fecha Caducidad:&nbsp;&nbsp;$fecha</b></h3></td>
            <td class='$estilo' width='30%'><h3><b>Autor:&nbsp;$nombre</b></h3></td>
            <td class='$estilo' width='15%'><div id='mensaje$actualizacion_id' ><h3><b><a style='text-decoration: blink;color:#C4051C'>$mensaje</a></b></h3></div></td>";
            if ($sw==0) {
            $s=1;
            $html .= " <td class='$estilo' width='15%' align='right'><div id='boton$actualizacion_id'><h3><b>Marcar como Leido<input type='checkbox'
                onClick =\"javascript:guardarlectura('$actualizacion_id','$s');\" name='chequear.$actualizacion_id' value='1'/> </b></h3></div></td>";
            }else{
           $html .= " <td class='$estilo' width='15%' align='right'><h3><b>Leido:&nbsp;$fecha_lectura&nbsp;$hora_lectura</b></h3> </td>";
            }
           $html .= " </tbody>
            <tbody>
            </tr>
            <tr>
            <td align='center' colspan='4'><h5><b>Asunto:&nbsp;$asunto</b></h5></td>
            </tr>
            <tr>
            <td colspan='4'>$descripcion</td>";
          $html .= "   </tr>";
          $html .= "  </tbody>";
        }
        $html .= "</table>";
        $html.=ThemeCerrarTabla();
         
    return $html;
}

function LeerActualizacionesObligatorias(){
    $objSql = AutoCarga::factory("ConsultasSql", "", "app", "Mensajeria");
    $usuario_id=UserGetUID();
    $datos=$objSql->ConsultarControlObligatorio($usuario_id);
    $url2 = ModuloGetURL('app', 'Mensajeria', 'user', 'Menu');
    $action1 = ModuloGetURL('system','Menu','user','main');
    $html.="<script>
                 function guardarlectura(actualizacion_id,s)
                  {
                    xajax_glectura(actualizacion_id,s,'');
                  }
                </script>";

        $html.= ThemeAbrirTabla('MENSAJES DE ACTUALIZACIONES');
         if($_REQUEST['vermenu']==1){
            $html.="<table align='center'>";
            $html.="<tr>";
            $html.="<td>";
            $html.="<b><a align='left' href=\"$url2\">MEN&Uacute;</a></b>";
            $html.="</td>";
            $html.="</tr>";
            $html.="</table>";
            }else{
                $html.="<table align='center'>";
                $html.="<tr>";
                $html.="<td>";
                $html.="<b><a align='left' href=\"$action1\">MEN&Uacute;</a></b>";
                $html.="</td>";
                $html.="</tr>";
                $html.="</table>";
            }
        $html .= "<table align='center' border='1' RULES='GROUPS' width='90%'>";
        for ($i = 0; $i < sizeof($datos); $i++) {
            $estilo1 = 'modulo_list_claro';
            $estilo = 'modulo_list_oscuro';
            $actualizacion_id =$datos[$i][actualizacion_id];
            $asunto = $datos[$i][asunto];
            $fecha_fin = $datos[$i][fecha_fin];
            $fecha=$this->FechaStamp($fecha_fin);
            $sw = $datos[$i][sw];
            $nombre = $datos[$i][nombre];
            $descripcion= $datos[$i][descripcion];
            $fecha_lectura=$this->FechaStamp($datos[$i][fecha_lectura]);
            $hora_lectura=$this->HoraStamp($datos[$i][fecha_lectura]);
            if ($sw==0) {
              $mensaje='�Mensaje Nuevo!';
              $chequearSI='';
              $chequearNO="CHECKED";
            } else {
              $mensaje='';
              $chequearSI="CHECKED";
              $chequearNO='';
            }
            $html.="
            <tbody>
            <tr class='$estilo1' >";
            $html .= "
            <td class='$estilo' width='40%'><h3><b>Publicaci�n N�.&nbsp;$actualizacion_id&nbsp;&nbsp;&nbsp;&nbsp;Fecha Caducidad:&nbsp;&nbsp;$fecha</b></h3></td>
            <td class='$estilo' width='30%'><h3><b>Autor:&nbsp;$nombre</b></h3></td>
            <td class='$estilo' width='15%'><div id='mensaje$actualizacion_id' ><h3><b><a style='text-decoration: blink;color:#C4051C'>$mensaje</a></b></h3></div></td>";
            if ($sw==0) {
            $s=1;
            $html .= " <td class='$estilo' width='15%' align='right'><div id='boton$actualizacion_id'><h3><b>Marcar como Leido<input type='checkbox'
                onClick =\"javascript:guardarlectura('$actualizacion_id','$s');\" name='chequear.$actualizacion_id' value='1'/> </b></h3></div></td>";
            }else{
           $html .= " <td class='$estilo' width='15%' align='right'><h3><b>Leido:&nbsp;$fecha_lectura&nbsp;$hora_lectura</b></h3> </td>";
            }
           $html .= " </tbody>
            <tbody>
            </tr>
            <tr>
            <td align='center' colspan='4'><h5><b>Asunto:&nbsp;$asunto</b></h5></td>
            </tr>
            <tr>
            <td colspan='4'>$descripcion</td>";
          $html .= "   </tr>";
          $html .= "  </tbody>";
        }
        $html .= "</table>";
        $html.=ThemeCerrarTabla();

    return $html;
}
 /**
      * Se encarga de separar la fecha del formato timestamp
      * @access private
      * @return string
      * @param date fecha
      */
     function FechaStamp($fecha)
     {
       if($fecha){
          $fech = strtok ($fecha,"-");
          for($l=0;$l<3;$l++)
          {
            $date[$l]=$fech;
            $fech = strtok ("-");
          }
          return  ceil($date[2])."/".str_pad(ceil($date[1]),2,0,STR_PAD_LEFT)."/".str_pad(ceil($date[0]),2,0,STR_PAD_LEFT);
       }
     }

     function HoraStamp($hora)
      {
        $hor = strtok ($hora," ");
        for($l=0;$l<4;$l++)
        {
          $time[$l]=$hor;
          $hor = strtok (":");
        }
            $x=explode('.',$time[3]);
        return  $time[1].":".$time[2].":".$x[0];
      }




}
?>