<?php

IncludeClass("ClaseHTML");

class Agregar_Actual_HTML extends app_Mensajeria_controller {

    function Agregar_Actual_HTML() {
        return true;
    }

    function Agregar() {
        $objSql = AutoCarga::factory("ConsultasSql", "classes", "app", "Mensajeria");
        $html.="
                    <script type='text/javascript' src='app_modules/Mensajeria/classes/tinymce/js/tinymce/tinymce.min.js'></script>  
                    
                    <script type='text/javascript'>
                        tinymce.init({
                            selector: 'textarea',
                            theme: 'modern',
                            plugins: [
                                'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                                'searchreplace wordcount visualblocks visualchars code fullscreen',
                                'insertdatetime media nonbreaking save table contextmenu directionality',
                                'emoticons template paste textcolor colorpicker textpattern imagetools'
                            ],
                            toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                            toolbar2: 'print preview media | forecolor backcolor emoticons',
                            image_advtab: true,
                            language : 'es',
                            templates: [
                                {title: 'Test template 1', content: 'Test 1'},
                                {title: 'Test template 2', content: 'Test 2'}
                            ]
                        });
                        </script>
                ";
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
                   function mostrar(actualizacion_id){
                        var oculto =  document.getElementById('UsuariosLectura'+actualizacion_id);
                        oculto.style.display = (oculto.style.display == 'none') ? 'block' : 'none';
                   }
                </script>";

        $asunto = $_REQUEST['asunto'];
        $descripcion = $_REQUEST['descripcion'];
        $caducidad1 = $_REQUEST['fecha_fin'];
        $actualizacion_id = $_REQUEST['actualizacion_id'];
        $fecha_ini = $_REQUEST['fecha_ini'];
        $fecha_fin = substr($caducidad1, 0, 11);
        $caducidad = $this->FechaStamp($caducidad1);
        $arayCarg = $_REQUEST['cargos'];
        $url2 = ModuloGetURL('app', 'Mensajeria', 'controller', 'Menu');
        $htmldiv.="<table>";
        if ($arayCarg[0]['perfil_id'] != '') {
            for ($i = 0; $i < sizeof($arayCarg); $i++) {
                $c = $arayCarg[$i][descripcion];
                $e[] = $arayCarg[0]['perfil_id'];
                if ($arayCarg[$i][obligatorio] == 1) {
                    $yesno = "checked='yes'";
                } else {
                    $yesno = '';
                }
                $idcarg = $arayCarg[$i]['perfil_id'];
                $htmldiv.="<tr id=$idcarg>
                   <td height='10' width='200' align='top'><h5>$c</h5></td>";
                $htmldiv.="<td width=150><h6>Obligatorio
                   <input type='checkbox' name='campo$i' $yesno id='sc$idcarg' ></td>";
                $htmldiv.="<td><h6><img src=\"" . GetThemePath() . "/images/delete2.gif\">&nbsp;
                   <a href=\"javascript:eliminar('$idcarg');\" >ELIM</a></h6></td></tr>";
            }
        }
        $htmldiv.="</table>";

        $html.= ThemeAbrirTabla('MENSAJERIA DEL SISTEMA', '70%');
        $html.= "<div  >"; 
        $html.="<table width='75%' align='center' border='0'>
                <tr>
                <td>
                <div id='mensaje'></div>
                </td>
                </tr>
                </table>";
        $html.= "<form name=\"forma\" id='forma' method=\"post\"/>\n";
        $html.="<input type='hidden' id='actualizacion_id' name='actualizacion_id' value='$actualizacion_id'>";
        $html.="<table width='100%' align='center' border='0'  >                    
                <tr style='border: green; 5px; solid;'>
                 <td align='left' width='10%'><b>Asunto:</b></td>
                 <td width='70%'><input type='text' style='width:600px;height:20px' value='$asunto' id='asunto' name='asunto'/></td>               
               ";
        $html.=" <td align='left'  width='10%' ><br><H6><b>Valido Hasta:</b></H6></td>
                  <td align='left' width='10%'><input type='text' value='$fecha_fin' id='caducidad' name='caducidad' size='11' maxlength='10' >";
        $html.= ReturnOpenCalendario('forma', 'caducidad', '/');
        $html.="  </td>
                  </tr>";
        $html.=" <tr>
                  <td align='left' width='10%' height='1%' ><br><H6><b>Perfil:</b></H6></br></td>";
        $Cargos = $objSql->TipoCargos();
        if ($Cargos) {
            $html.="<td width='90%' align='left'colspan='3'>
                      <select name='cargo' id='cargo' class='select'>";
            for ($i = 0; $i < sizeof($Cargos); $i++) {
                $ct[] = $Cargos[$i]['perfil_id'];
                $re.=$Cargos[$i]['perfil_id'] . ',';
                if ($_REQUEST['cargo'] == $Cargos[$i]['perfil_id']) {
                    $html.="<option value=" . $Cargos[$i]['perfil_id'] . " selected>" . $Cargos[$i]['descripcion'] . "</option>";
                } else {
                    $html.="<option value=" . $Cargos[$i]['perfil_id'] . ">" . $Cargos[$i]['descripcion'] . "</option>";
                }
            }
            $html.="</select>";
        }
        $rt = implode(',', $re);
        $tam = sizeof($arayCarg);
        $html.="&nbsp;&nbsp;&nbsp;&nbsp;<input  type='button' style='height:23px; width:60px' value='Agregar' id='guardar' name='agregar' onClick =\"javascript:envios($tam);\" /></td>";
        $html.="<tr>";
        $html.="<td>";
        $html.="</td>";
        $html.="<td>";
        $html.=" <div id='titulo'></div>";
        $html.=" <table id='usuario'>";
        $html.=" </table>";
        $html.=" </td>";
        $html.=" </tr>";
        if ($actualizacion_id != '') {
            $html.=$htmldiv;
        }

        $html.="<tr>
                <td colspan='4' align='center' STYLE='background-color:white'>
			<textarea id='descripcion' name='descripcion' value='$descripcion' rows='15' cols='80' style='width: 100%'>
			$descripcion
                        </textarea>
                </td>
             </tr>";


        if ($actualizacion_id == '') {
            $html.="<tr>                      
                       <td colspan='4' align='center'><input  type='button'  style='height:30px; width:70px' value='Enviar' id='guardar' name='guardar' onClick =\"javascript:formulariol();\" />
                       ";
        }
        if ($actualizacion_id != '') {
            $tam = sizeof($arayCarg);
            $html.="<tr><td colspan='4' align='center'><input type='button'  style='height:30px; width:70px' value='Modificar' id='modificar' name='modificar' onClick =\"javascript:formularioModif($tam);\"/>";
        }

        $html.="</td>";
        $html.="</tr>";
        $html.="<tr>";
        $html.="<td colspan='4' align='center'>";
        $html.="<b><a href=\"$url2\">volver</a></b>";
        $html.="</td>";
        $html.="</tr>";
        $html.="</table>";
        $html.="</br>\n";
        $html.="</form>\n";
        $html .= "</div>";
        $html.=ThemeCerrarTabla();
        $html.="<br>";
        $html.= $this->RegistrosSistemas();
        return $html;
    }

    function RegistrosSistemas() {
        $objSql = AutoCarga::factory("ConsultasSql", "classes", "app", "Mensajeria");
        $datos = $objSql->ConsultarMensajeria();
        $html = "<table align='center' border='1' style='border-color: #0000ff;' width='95%'>
                   <tr  class='modulo_table_list_title' style='font-size:16px' >
                    <td colspan='9' width='100%' >HISTORIAL DE MENSAJERIA</td>
                   </tr>
                    <tr class='modulo_table_list_title' style=\"font-size:12px\">
                    <td width='1%'>Item</td>
                    <td width='5%'>Fecha Mensaje</td>
                    <td width='5%'>Fecha Edici&oacute;n</td>
                    <td width='5%'>Validez del Mensaje</td>
                    <td width='15%'>Asunto</td>
                    <td width='40%'>Detalle</td>
                    <td width='10%'>Perfiles</br>(*Lectura obligatoria)</td>
                    <td width='15%'>Le&iacute;do</td>
                    <td width='4%'>Edictar</td>
                    </tr>
                  ";

        for ($i = 0; $i < sizeof($datos); $i++) {
            if ($i % 2) {
                $estilo = 'background-color:#C7DDFF;font-size:12px;font-weight: bold;float:center;';
                $estilo1 = 'background-color:#C7DDFF;font-size:10px;font-weight: bold;float:center;';
            } else {
                $estilo = 'background-color:#FFFFFF;font-size:12px;font-weight: bold;float:center;';
                $estilo1 = 'background-color:#FFFFFF;font-size:10px;font-weight: bold;float:center;';
            }
            $actualizacion_id = $datos[$i]['actualizacion_id'];

            $usuariolectura = $objSql->ConsultarLecturasMensajes($actualizacion_id);
            if (sizeof($usuariolectura) > 0) {
                $html_usulectura = $this->UsuariosLectura($actualizacion_id,$usuariolectura);
                $lectura = "<input type='button' value='SI' onclick=\"mostrar('$actualizacion_id')\" >  $html_usulectura";
            } else {
                $lectura = "<center>NO</center>";
            }

            $cargos = $objSql->Consultarcontrolar_x_perfil($actualizacion_id);            
            $fecha_ini = $datos[$i]['fecha_ini'];
            $fecha_fin = $datos[$i]['fecha_fin'];
            $fecha_act = $datos[$i]['fecha_actu'];
            $fecha_inia = $datos[$i]['fecha_inia'];
            $fecha_fina = $datos[$i]['fecha_fina'];
            $fecha_acta = $datos[$i]['fecha_actua'];
            $asunto = $datos[$i]['asunto'];
            $descripcion = $datos[$i]['descripcion'];
            $html.="<tr style='$estilo'>";
            $url = ModuloGetURL('app', 'Mensajeria', 'controller', 'crear', array('actualizacion_id' => $actualizacion_id, 'fecha_ini' => $fecha_ini,
                'fecha_fin' => $fecha_fin, 'asunto' => $asunto, 'descripcion' => $descripcion, 'modif' => '', 'cargos' => $cargos));
          

            $html .= "<td style='$estilo'>$actualizacion_id</td>
                      <td style='$estilo'>$fecha_inia</td>
                      <td style='$estilo'>$fecha_acta</td>
                      <td style='$estilo'>$fecha_fina</td>
                      <td style='$estilo'>$asunto</td>
                      <td style='$estilo'>$descripcion</td>";
            $html .= "<td style='$estilo1'>";
                                 
                    for ($j = 0; $j < sizeof($cargos); $j++) {
                        $descrip = $cargos[$j]['descripcion'];
                        $lect = '';
                        if ($cargos[$j]['obligatorio'] == 1) {
                            $lect = '*';
                        }
            $html .= "    -&nbsp;$descrip&nbsp;$lect </br>";
                       }
            $html .= " </td>
                      <td style='$estilo' align='center' >$lectura</td>
                      <td style='$estilo' align='center'><a href='$url' >Modificar</a></td>
                      </tr>";
        }
        $html .= "</table>";
        return $html;
    }

    function UsuariosLectura($actualizacion_id,$datos) {
        $html = "<div style='display:none;background-color:#FFFFFF;'  id='UsuariosLectura$actualizacion_id' >";
        $html .="<table align='center' border='0' class='hc_table_list' width='100%' >";
        $html .="<tr class='modulo_table_list_title' style='font-size:9px' >";
        $html .="<td colspan='2'>";
        $html .="Realizar&oacute;n Lectura";
        $html .="</td>";
        $html .="</tr>";
        $i=1;
        foreach ($datos as $key => $value) {
            $html .="<tr style=\"font-size:7px\">";
            $fecha = $value['fecha'];
            $html .="<td><p title='Fecha Lectura: $fecha'>";
            $html .=$i.") ".$value['nombre'];
            $html .="</p></td>";
            $html .="</tr>";
            $i++;
        }
        $html .="</table>";
        $html .="</div>";
        return $html;
    }

    function LeerActualizaciones() {
        $objSql = AutoCarga::factory("ConsultasSql", "", "app", "Mensajeria");
        $datos = $objSql->ConsultarControl(UserGetUID());
        $urlMenu = ModuloGetURL('app', 'Mensajeria', 'controller', 'Menu');
        $action1 = ModuloGetURL('system', 'Menu', 'user', 'main');
        $html.="<script>
                     function guardarlectura(actualizacion_id,s)
                      {
                        xajax_glectura(actualizacion_id,s,'');
                      }
                      function filtroMensaje(){
                      var desde = document.getElementById('desde').value;
                      var hasta = document.getElementById('hasta').value;
                      var user  = document.getElementById('user').value;
                        if(desde=='' && user=='' ){
                          alert('Debe Ingresar como Minimo el Usuario o la Fecha de Inicio');
                        }                        
                        xajax_filtro_mensaje(desde,hasta,user);
                        location.href='#consulta1';
                      }
                    </script>";

        $html.= ThemeAbrirTabla('MENSAJERIA DEL SISTEMA');
        $html.= "<table border=\"0\" width=\"50%\" align=\"center\">";
        $html.= "<form name=\"forma\" id='forma' method=\"post\"/>";
        $html.= "  <tr><td><fieldset><legend class=\"field\">BUSCADOR DE MENSAJES ANTIGUOS</legend>";
        $html.= " ";
        $html.= "<table border='0'>";
        $html.= "<tr>";
        $html.="<td align='left'  width='20%'><H6><b>Fecha de Publicaci&oacute;n: </b></H6></td>";
        $html.="<td align='left'  width='2%'><H6><b>Desde:</b></H6>";
        $html.="<td align='left'  width='28%'><input type='text' id='desde' name='desde' size='11' maxlength='10' >";
        $html.= " " . ReturnOpenCalendario('forma', 'desde', '/');
        $html.=" </td>";
        $html.="<td align='left'  width='2%'><H6><b>Hasta:</b></H6>";
        $html.="<td align='left'  width='28%'><input type='text' id='hasta' name='hasta' size='11' maxlength='10' >";
        $html.= " " . ReturnOpenCalendario('forma', 'hasta', '/');
        $html.=" </td>";
        $html.=" </tr>";
        $html.=" <tr>";
        $html.=" <td colspan='1'>";
        $html.="<H6><b>Enviado Por:</b></H6>";
        $html.=" </td>";
        $html.=" <td colspan='4'>";
        $html.="<select name=\"user\" id=\"user\" class=\"select\">";
        $usuariomensaje = $objSql->UsuariosEnvioMensajes();
        foreach ($usuariomensaje as $key => $value) {
            $ids = $value['usuario_id'];
            $nombres = $value['nombre'];
            $html.="<option value='$ids'>";
            $html.=$nombres;
            $html.="</option>";
        }
        $html.="</select>";
        $html.=" </td>";
        $html.=" </tr>";
        $html.= "<tr><td colspan='5' align='center'><input class=\"input-submit\" type=\"button\" name=\"Buscar\" value=\"Buscar\" onclick=\"javascript:filtroMensaje()\"></td></tr>";
        $html.= "</table>";
        $html.= "<fieldset></td></tr>";
        $html.= "</form>";
        $html.= "</table>";
        if ($_REQUEST['vermenu'] == 1) {
            $html.="<table align='center'>";
            $html.="<tr>";
            $html.="<td>";
            $html.="<b><a align='left' href=\"$urlMenu\">MEN&Uacute;</a></b>";
            $html.="</td>";
            $html.="</tr>";
            $html.="</table>";
        } else {
            $html.="<table align='center'>";
            $html.="<tr>";
            $html.="<td>";
            $html.="<b><a align='left' href=\"$action1\">volver</a></b>";
            $html.="</td>";
            $html.="</tr>";
            $html.="</table>";
        }

        $html .=$this->TablaMensajes($datos);
        $html .="<a name='consulta1'></a>";
        $html .=ThemeCerrarTabla();
        return $html;
    }

    function TablaMensajes($datos) {
        $html = "<div style='background-color:#FFFFFF'>";
        $html .= "<table align='center' border='0' class='hc_table_list' width='100%'>";

        for ($i = 0; $i < sizeof($datos); $i++) {
            $actualizacion_id = $datos[$i]['actualizacion_id'];
            $asunto = $datos[$i]['asunto'];
            $fecha_fin = $datos[$i][fecha_fin];
            $fecha = $this->FechaStamp($fecha_fin);
            $sw = $datos[$i]['sw'];
            $nombre = $datos[$i]['nombre'];
            $descripcion = $datos[$i]['descripcion'];
            $fecha_lectura = $this->FechaStamp($datos[$i]['fecha_lectura']);
            $hora_lectura = $this->HoraStamp($datos[$i]['fecha_lectura']);
            if ($sw == 0) {
                $mensaje = "<a style='text-decoration: blink;color:#C4051C'> !Mensaje Nuevo! </a>";
                $chequearSI = '';
                $chequearNO = "CHECKED";
            } else {
                $mensaje = "Le&iacute;do";
                $chequearSI = "CHECKED";
                $chequearNO = '';
            }
            $html.="
            <tbody>
            <tr class='modulo_table_list_title' style=\"font-size:12px\" >";
            $html .= "
            <td  width='40%' align='left'><h3><b>Mensaje No.&nbsp;$actualizacion_id&nbsp;&nbsp;&nbsp;&nbsp;Valido Hasta:&nbsp;&nbsp;$fecha</b></h3></td>
            <td  width='30%'><h3><b>Enviado Por:&nbsp;$nombre</b></h3></td>
            <td  width='15%'><div id='mensaje$actualizacion_id' ><h3><b>$mensaje</b></h3></div></td>";
            if ($sw == 0) {
                $s = 1;
                $html .= " <td  width='15%' align='right'><div id='boton$actualizacion_id'><h3><b>Marcar como Le&iacute;do<input type='checkbox'
                onClick =\"javascript:guardarlectura('$actualizacion_id','$s');\" name='chequear.$actualizacion_id' value='1'/> </b></h3></div></td>";
            } else {
                $html .= " <td  width='15%' align='right'><h3><b>Le&iacute;do:&nbsp;$fecha_lectura&nbsp;$hora_lectura</b></h3> </td>";
            }
            $html .= " </tbody>
            <tbody>
            </tr>
            <tr>
            <td align='center' colspan='4'><h4><b>Asunto:&nbsp;$asunto</b></h4></td>
            </tr>
            <tr>
            <td colspan='4'>$descripcion</td>";
            $html .= "   </tr>";
            $html .= "  </tbody>";
        }
        $html .= "</table>";
        $html .= "<div id='filtro'></div>";
        $html .= "</div>";
        return $html;
    }

    function LeerActualizacionesObligatorias() {
        $objSql = AutoCarga::factory("ConsultasSql", "", "app", "Mensajeria");
        $usuario_id = UserGetUID();
        $datos = $objSql->ConsultarControlObligatorio($usuario_id);
        $url2 = ModuloGetURL('app', 'Mensajeria', 'controller', 'Menu');
        $action1 = ModuloGetURL('system', 'Menu', 'user', 'main');
        $html.="<script>
                 function guardarlectura(actualizacion_id,s)
                  {
                    xajax_glectura(actualizacion_id,s,'');
                  }
                </script>";

        $html.= ThemeAbrirTabla('MENSAJERIA DEL SISTEMA');
        if ($_REQUEST['vermenu'] == 1) {
            $html.="<table align='center'>";
            $html.="<tr>";
            $html.="<td>";
            $html.="<b><a align='left' href=\"$url2\">MEN&Uacute;</a></b>";
            $html.="</td>";
            $html.="</tr>";
            $html.="</table>";
        } else {
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
            $actualizacion_id = $datos[$i][actualizacion_id];
            $asunto = $datos[$i][asunto];
            $fecha_fin = $datos[$i][fecha_fin];
            $fecha = $this->FechaStamp($fecha_fin);
            $sw = $datos[$i][sw];
            $nombre = $datos[$i][nombre];
            $descripcion = $datos[$i][descripcion];
            $fecha_lectura = $this->FechaStamp($datos[$i][fecha_lectura]);
            $hora_lectura = $this->HoraStamp($datos[$i][fecha_lectura]);
            if ($sw == 0) {
                $mensaje = '�Mensaje Nuevo!';
                $chequearSI = '';
                $chequearNO = "CHECKED";
            } else {
                $mensaje = '';
                $chequearSI = "CHECKED";
                $chequearNO = '';
            }
            $html.="
            <tbody>
            <tr class='$estilo1' >";
            $html .= "
            <td class='$estilo' width='40%'><h3><b>Mensaje No.&nbsp;$actualizacion_id&nbsp;&nbsp;&nbsp;&nbsp;Fecha Caducidad:&nbsp;&nbsp;$fecha</b></h3></td>
            <td class='$estilo' width='30%'><h3><b>Autor:&nbsp;$nombre</b></h3></td>
            <td class='$estilo' width='15%'><div id='mensaje$actualizacion_id' ><h3><b><a style='text-decoration: blink;color:#C4051C'>$mensaje</a></b></h3></div></td>";
            if ($sw == 0) {
                $s = 1;
                $html .= " <td class='$estilo' width='15%' align='right'><div id='boton$actualizacion_id'><h3><b>Marcar como Leido<input type='checkbox'
                onClick =\"javascript:guardarlectura('$actualizacion_id','$s');\" name='chequear.$actualizacion_id' value='1'/> </b></h3></div></td>";
            } else {
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
    function FechaStamp($fecha) {
        if ($fecha) {
            $fech = strtok($fecha, "-");
            for ($l = 0; $l < 3; $l++) {
                $date[$l] = $fech;
                $fech = strtok("-");
            }
            return ceil($date[2]) . "/" . str_pad(ceil($date[1]), 2, 0, STR_PAD_LEFT) . "/" . str_pad(ceil($date[0]), 2, 0, STR_PAD_LEFT);
        }
    }

    function HoraStamp($hora) {
        $hor = strtok($hora, " ");
        for ($l = 0; $l < 4; $l++) {
            $time[$l] = $hor;
            $hor = strtok(":");
        }
        $x = explode('.', $time[3]);
        return $time[1] . ":" . $time[2] . ":" . $x[0];
    }

}

?>