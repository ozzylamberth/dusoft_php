<?php

class app_AgendaBusqueda_userclasses_HTML extends app_AgendaBusqueda_user
{

    function app_AgendaBusqueda_user_HTML()
    {
        $this->app_AgendaBusqueda_user(); //Constructor del padre 'modulo'
        return true;
    }


    function BusquedaPermisos()
    {
        unset($_SESSION['BusquedaAgenda']);
        /*$this->salida = ThemeAbrirTabla('PARAMETROS DE BUSQUEDA','100%');*/
        if($this->BusquedaPermisosUsuarios()==false)
        {
            return false;
        }
        /*$tipo_id=$this->tipo_id_paciente();
        $this->salida .= "<BR>";
        $this->salida .= '<table width="50%" align="center" class="modulo_table_list">';
        $this->salida .= "<tr><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
        $this->BuscarIdPaciente($tipo_id,'False',$a);
        $this->salida .= "</select></td></tr>";
        $this->salida .= "<tr><td class=\"".$this->SetStyle("Documento")."\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value=\"".$a."\"></td></tr>";
        $this->salida .= "</table>";
        $this->salida .= "<BR>";
        $this->salida .= ThemeCerrarTabla();*/
        return true;
    }

    function SeleccionParametros()
    { unset($_SESSION['REPORTES_CONSULTA_EXTERNA']['RETORNO']);
        $_SESSION['BusquedaAgenda']['profesional']=$_REQUEST['profesional'];
        $_SESSION['BusquedaAgenda']['nompro']=$_REQUEST['nompro'];
        if(empty($_SESSION['BusquedaAgenda']['empresa']))
        {
            $_SESSION['BusquedaAgenda']['empresa']=$_REQUEST['BusquedaAgenda']['empresa_id'];
            $_SESSION['BusquedaAgenda']['departamento']=$_REQUEST['BusquedaAgenda']['departamento'];
            $_SESSION['BusquedaAgenda']['nomemp']=$_REQUEST['BusquedaAgenda']['descripcion1'];
            $_SESSION['BusquedaAgenda']['nomdep']=$_REQUEST['BusquedaAgenda']['descripcion2'];
            $_SESSION['BusquedaAgenda']['sw_mostrar_historia']=$_REQUEST['BusquedaAgenda']['sw_mostrar_historia'];
        }
        if($_REQUEST['DiaEspe']!=$_SESSION['BusquedaAgenda']['DiaEspe'])
        {
            $_SESSION['BusquedaAgenda']['DiaEspe']=$_REQUEST['DiaEspe'];
        }
        if(empty($_REQUEST['DiaEspe']))
        {
            $_SESSION['BusquedaAgenda']['DiaEspe']=date("Y-m-d");
        }
        $fechas=$this->DiasCitas();
        SessionSetVar('CITASMES',$fechas);
        $datosProf=$this->BusquedaProfesionalesConsulta();
        $this->salida = ThemeAbrirTabla('PARAMETROS DE SELECCION');
        $this->salida .= "<BR>";
        $this->salida .= '<table width="80%" align="center" class="modulo_table_list">';
        $this->salida .= '<tr align="center" class="modulo_table_title">';
        $this->salida .= '<td align="center">';
        $this->salida .= "Empresa";
        $this->salida .= "</td>";
        $this->salida .= '<td align="center">';
        $this->salida .= "Departamento";
        $this->salida .= "</td>";
        $this->salida .= "</tr>";
        $this->salida .= '<tr class="modulo_list_oscuro">';
        $this->salida .= '<td align="center">';
        $this->salida .= $_SESSION['BusquedaAgenda']['nomemp'];
        $this->salida .= "</td>";
        $this->salida .= '<td align="center">';
        $this->salida .= $_SESSION['BusquedaAgenda']['nomdep'];
        $this->salida .= "</td>";
        $this->salida .= "</tr>";
        $this->salida .= "</table>";
        $this->salida .= "<BR>";
        $this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "<tr>";
        $this->salida .= "<td width=\"62%\">";
        $this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">";
        $this->salida .= "<tr>";
        $this->salida .= "<td>";
        $this->salida .= "<fieldset>";
        $this->salida .= "<legend class=\"field\">";
        $this->salida .= "BUSQUEDA DE FECHA";
        $this->salida .= "</legend>";
        $this->salida .= "<table width=\"100%\" align=\"center\" border=\"0\">";
        $this->salida .= "<tr>";
        $this->salida .= "<td width=\"100%\">";
        $this->salida.="\n".'<script>'."\n";
        $this->salida.='function year1(t)'."\n";
        $this->salida.='{'."\n";
        $this->salida.='window.location.href="Contenido.php?year="+t.elements[0].value+"&meses="+t.elements[1].value+"';
        foreach($_REQUEST as $v=>$v1)
        {
            if($v!='year' and $v!='meses')
            {
                $this->salida.='&'.$v.'='.$v1;
            }
        }
        $this->salida.='";'."\n";
        $this->salida.='}'."\n";
        $this->salida.='function profe(t)'."\n";
        $this->salida.='{'."\n";
        //$this->salida.='var a=t.elements[1].value'."\n";
        $this->salida.="var b=t.split(',')"."\n";
        $this->salida.="if(b[0]!=undefined && b[1]!=undefined)"."\n";
        $this->salida.="{"."\n";
        //$this->salida.="alert(t);"."\n";
        $this->salida.='window.location.href="Contenido.php?profesional="+b[0]+","+b[1]+"&nompro="+b[2]+"';
        foreach($_REQUEST as $v=>$v1)
        {
            if($v!='profesional' and $v!='nompro')
            {
                $this->salida.='&'.$v.'='.$v1;
            }
        }
        $this->salida.='";'."\n";
        $this->salida.="}"."\n";
        $this->salida.="else"."\n";
        $this->salida.="{"."\n";
        //$this->salida.="alert(t.elements[2].value);"."\n";
        $this->salida.='window.location.href="Contenido.php?profesional="+b[0]+","+b[1]+"&nompro="+b[2]+"';
        foreach($_REQUEST as $v=>$v1)
        {
            if($v!='year' and $v!='meses' and $v!='profesional' and $v!='nompro')
            {
                $this->salida.='&'.$v.'='.$v1;
            }
        }
        $this->salida.='";'."\n";
        $this->salida.="}"."\n";
        $this->salida.='}'."\n";
        $this->salida.='</script>';
        $this->salida .='<form name="cosa">';
        $this->salida .= "<table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .='<tr align="center">';
        $this->salida .="<td class=\"label\">AÑO</td><td><select name=\"year\" onchange=\"year1(this.form)\" class=\"select\">";
        if(empty($_REQUEST['year']))
        {
            $_REQUEST['year']=date("Y");
            $this->AnosAgenda(True,$_REQUEST['year']);
        }
        else
        {
            $this->AnosAgenda(true,$_REQUEST['year']);
            $year=$_REQUEST['year'];
        }
        $this->salida .= "</select></td>";
        $this->salida .="<td class=\"label\">MES</td><td><select name=\"mes\" onchange=\"year1(this.form)\" class=\"select\">";
        if(empty($_REQUEST['meses']))
        {
            $mes=$_REQUEST['meses']=date("m");
            $this->MesesAgenda(True,$year,$mes);
        }
        else
        {
            $this->MesesAgenda(True,$year,$_REQUEST['meses']);
            $mes=$_REQUEST['meses'];
        }
        $this->salida .= "</select>";
        $this->salida .= "</td>";
        $this->salida .= "</tr>";
        $this->salida .= "</table>";
        $this->salida .='</form>';
        $this->ReturnMetodoExterno('app', 'Agenda', 'user', 'CalendarioEstandard');
        $this->salida .='<br>';
        $this->salida .= "</td>";
        $this->salida .= "</tr>";
        $this->salida .= "</fieldset>";
        $this->salida .= "</table>";
        $this->salida .= "</td>";
        $this->salida .= "</tr>";
        $this->salida .= "</table>";
        $this->salida .= "</td>";
        $this->salida .= "<td>";
        $this->salida .= "<BR>";
        $this->salida .= "<table border=\"0\" width=\"92%\" align=\"center\">";
        $accion=ModuloGetURL('app','AgendaBusqueda','user','SeleccionParametros',array('DiaEspe'=>$_REQUEST['DiaEspe'],'year'=>$_REQUEST['year'],'meses'=>$_REQUEST['meses']));
        $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "<tr>";
        $this->salida .= "<td>";
        $this->salida .= "<fieldset>";
        $this->salida .= "<legend class=\"field\">";
        $this->salida .= "BUSQUEDA AVANZADA";
        $this->salida .= "</legend>";
        $this->salida .= "<table width=\"90%\" align=\"center\">";
        $this->salida .= "<tr>";
        $this->salida .= "<br>";
        $this->salida .= "<td class=\"label\">";
        $this->salida .= "TIPO BUSQUEDA: ";
        $this->salida .= "</td>";
        $this->salida .= "<td>";
        $this->salida .= "<select name=\"TipoBusqueda\" class=\"select\">";
        if($_REQUEST['TipoBusqueda']==1)
        {
            $this->salida .= "<option value=\"1\" selected>NOMBRE MEDICO</option>";
        }
        else
        {
            $this->salida .= "<option value=\"1\">NOMBRE MEDICO</option>";
        }
        if($_REQUEST['TipoBusqueda']==2)
        {
            $this->salida .= "<option value=\"2\" selected>MEDICOS HOMBRES</option>";
        }
        else
        {
            $this->salida .= "<option value=\"2\">MEDICOS HOMBRES</option>";
        }
        if($_REQUEST['TipoBusqueda']==3)
        {
            $this->salida .= "<option value=\"3\" selected>MEDICOS MUJERES</option>";
        }
        else
        {
            $this->salida .= "<option value=\"3\">MEDICOS MUJERES</option>";
        }
        $this->salida .= "</select>";
        $this->salida .= "</td>";
        $this->salida .= "</tr>";
        if($_REQUEST['TipoBusqueda']==1 or $_REQUEST['TipoBusqueda']==2 or $_REQUEST['TipoBusqueda']==3)
        {
            $this->salida .= "<tr>";
            $this->salida .= "<td class=\"label\">";
            $this->salida .= "Medicos: ";
            $this->salida .= "</td>";
            $this->salida .= "<td>";
            $this->salida .= "<select name=\"Profesionales\" class=\"select\" onchange=\"profe(this.value)\">";
            if(empty($_SESSION['BusquedaAgenda']['profesional']))
            {
                $this->salida .= "<option value=\"\" selected>--Seleccione--</option>";
                $profesional=$this->Profesionales();
                $i=0;
                while($i<sizeof($profesional[0]))
                {
                    $this->salida .= "<option value=\"".$profesional[1][$i].",".urlencode($profesional[2][$i]).",".$profesional[0][$i]."\">".$profesional[0][$i]."</option>";
                    $i++;
                }
            }
            else
            {
                $this->salida .= "<option value=\"\">--Seleccione--</option>";
                $profesional=$this->Profesionales();
                $i=0;
                $a=explode(",",$_SESSION['BusquedaAgenda']['profesional']);
                while($i<sizeof($profesional[0]))
                {
                    if($a[0]==$profesional[1][$i] and $a[1]==$profesional[2][$i])
                    {
                        $this->salida .= "<option value=\"".$profesional[1][$i].",".$profesional[2][$i].",".$profesional[0][$i]."\" selected>".$profesional[0][$i]."</option>";
                    }
                    else
                    {
                        $this->salida .= "<option value=\"".$profesional[1][$i].",".$profesional[2][$i].",".$profesional[0][$i]."\">".$profesional[0][$i]."</option>";
                    }
                    $i++;
                }
            }
            $this->salida .= "</select>";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
        }
        $this->salida .= "<tr>";
        $this->salida .= "<td colspan=\"2\" align=\"center\">";
        $this->salida .= "<br>";
        $this->salida .= "<input class=\"input-submit\" type=\"submit\" name=\"Busc\" value=\"Buscar\">";
        $this->salida .= "<br>";
        $this->salida .= "<br>";
        $this->salida .= "</td>";
        $this->salida .= "</tr>";
        $this->salida .= "</form>";
        $this->salida .= "</table>";
        $this->salida .= "</fieldset>";
        $this->salida .= "</td>";
        $this->salida .= "</tr>";
        $this->salida .= "</table>";
        $this->salida .= "</td>";
        $this->salida .= "</tr>";
        $this->salida .= "</table>";
        $this->salida .='<br>';
        $this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
        $this->salida .='<tr>';
        $this->salida .='<td align="center">';
        $accion=ModuloGetURL('app','AgendaBusqueda','user','ListadoCitasDia',array('DiaEspe'=>$_REQUEST['DiaEspe'],'year'=>$_REQUEST['year'],'meses'=>$_REQUEST['meses']));
        $this->salida.="<form name=\"volver\" action=\"$accion\" method=\"post\">";
        $this->salida .='<a href="'.$accion.'"><input type="submit" name="BUSQUEDA" value="BUSQUEDA" class="input-submit"></a>';
        $this->salida.="</form>";
        $this->salida .='</td>';
        $this->salida .='<td align="center">';
        $accion=ModuloGetURL('app','AgendaBusqueda','user','main');
        $this->salida.="<form name=\"volver\" action=\"$accion\" method=\"post\">";
        $this->salida .='<a href="'.$accion.'"><input type="submit" name="VOLVER" value="VOLVER" class="input-submit"></a>';
        $this->salida.="</form>";
        $this->salida .='</td>';
        $this->salida .='</tr>';
        $this->salida .='</table>';
        $this->salida .= ThemeCerrarTabla();
        return true;
    }


    function ListadoCitasDia()
    {
    unset($_SESSION['REPORTES_CONSULTA_EXTERNA']['RETORNO']);
        unset($_SESSION['recone']);
        $_SESSION['HISTORIACLINICA']['RETORNO']['modulo']='AgendaBusqueda';
        $_SESSION['HISTORIACLINICA']['RETORNO']['metodo']='ListadoCitasDia';
        $_SESSION['HISTORIACLINICA']['RETORNO']['tipo']='user';
        $_SESSION['HISTORIACLINICA']['RETORNO']['contenedor']='app';
        if(empty($_REQUEST['DiaEspe']))
        {
            $_REQUEST['DiaEspe']=$_SESSION['BusquedaAgenda']['DiaEspe'];
        }
        unset($_SESSION['BusquedaAgenda']['datos_impresion']);
        if(!($_SESSION['BusquedaAgenda']['profesional']==""))
        {
            $this->SetJavaScripts('DatosPaciente');
            $dato=$this->CitasDia();
            $this->salida = ThemeAbrirTabla('CITAS DEL DIA: '.$_REQUEST['DiaEspe']);
            $this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
            $this->salida .='<tr class="modulo_table_title">';
            $this->salida .='<td align="center">';
            $this->salida.="PROFESIONAL: ".$_SESSION['BusquedaAgenda']['nompro'];
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
            $this->salida .= "</table>";
            $this->salida .='<br>';
            $this->salida.="<table width=\"80%\" border=\"0\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
            $this->salida .='<tr class="modulo_table_title">';
            $this->salida .='<td align="center">';
            $this->salida.="HORA";
            $this->salida .= "</td>";
            $this->salida .='<td align="center">';
            $this->salida.="TIPO CONSULTA";
            $this->salida .= "</td>";
            $this->salida .='<td align="center">';
            $this->salida.="IDENTIFICACION";
            $this->salida .= "</td>";
            $this->salida .='<td align="center">';
            $this->salida.="NOMBRE PACIENTE";
            $this->salida .= "</td>";
            $this->salida .='<td align="center">';
            $this->salida.="PLAN";
            $this->salida .= "</td>";
            $this->salida .='<td align="center">';
            $this->salida.="ESTADO";
            $this->salida .= "</td>";
            if($_SESSION['BusquedaAgenda']['sw_mostrar_historia']=='1')
            {
                $this->salida .='<td align="center">';
                $this->salida.="Ver Historia";
                $this->salida .= "</td>";
            }
            $this->salida .= "</tr>";
            foreach($dato as $k=>$v)
            {
                if($v[sw_atencion]==0 OR $v[sw_atencion]==3)
                {
                    if($spy==0)
                    {
                        $this->salida .='<tr class="modulo_list_claro">';
                        $spy=1;
                    }
                    else
                    {
                        $this->salida .='<tr class="modulo_list_oscuro">';
                        $spy=0;
                    }
                }
                else
                {
                    $this->salida .='<tr class="modulo_table_list_title">';
                }
                $this->salida .='<td align="center">';
                $this->salida .=$v[hora];
                $this->salida .= "</td>";
                $this->salida .='<td align="center">';
                $this->salida .=$v[descripcion];
                $this->salida .= "</td>";
                $this->salida .='<td align="center">';
                $this->salida .=$v[tipo_id_paciente].'-'.$v[paciente_id];
                $this->salida .= "</td>";
                $this->salida .='<td align="center">';
                $dato2=RetornarWinOpenDatosPaciente($v[tipo_id_paciente],$v[paciente_id],$v[nombre_completo]);
                $this->salida .=$dato2;
                $this->salida .= "</td>";
                $this->salida .='<td align="center">';
                $this->salida .=$v[plan_descripcion];
                $this->salida .= "</td>";
                $this->salida .='<td align="center">';
                if($v[sw_atencion]==1)
                {
                    $this->salida .="CANCELADA";
                }
                elseif($v[sw_atencion]==3)
                {
                    $this->salida .="ATENDIDA";
                }
                else
                {
                    if($v[sw_estado]==2)
                    {
                        $this->salida .="PAGA";
                    }
                    elseif($v[sw_estado]==3)
                    {
                        $this->salida .="CUMPLIDA";
                    }
                    else
                    {
                        $this->salida .="ACTIVA";
                    }
                }
                $this->salida .= "</td>";
                if($_SESSION['BusquedaAgenda']['sw_mostrar_historia']=='1')
                {
                    $this->salida .='<td align="center">';
                    if(!empty($v[tipo_id_paciente]) and !empty($v[paciente_id]))
                    {
                        $a=$this->BusquedaIngresoPaciente($v[tipo_id_paciente],$v[paciente_id]);
                        if($a=='Historia Vacia')
                        {
                            $this->salida.=$a;
                        }
                        else
                        {
                            $accion=ModuloHCGetURL($a,'',0,'','',array());
                            $this->salida.="<a href='$accion'>Ver Historia</a>";
                        }
                    }
                    $this->salida .= "</td>";
                }
                $this->salida .= "</tr>";
                $tipoConsulta=$v[descripcion];
            }
            $this->salida .= "</table>";
            $this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
            $this->salida .='<tr>';
            $this->salida .='<td align="center">';
            $accion=ModuloGetURL('app','AgendaBusqueda','user','BusquedaProfesionales',array('DiaEspe'=>$_REQUEST['DiaEspe']));
            $this->salida.="<form name=\"volver\" action=\"$accion\" method=\"post\">";
            $this->salida .='<a href="'.$accion.'"><input type="submit" name="VOLVER" value="VOLVER" class="input-submit"></a>';
            $this->salida.="</form>";
            $this->salida .='</td>';
            $this->salida .='</tr>';
            $_SESSION['BusquedaAgenda']['datos_impresion']=$dato;
            $dato1['DiaEspe']=$_REQUEST['DiaEspe'];
            $accion=ModuloGetURL('app','AgendaBusqueda','user','FuncionParaImprimir',$dato1);
            $this->salida .="<tr>";
            $this->salida .="<td>";
            $this->salida .="<table align=\"center\">";
            $this->salida .="<tr>";
            $this->salida .="<td align=\"center\">";
            $this->salida .="<a href=\"$accion\">Imprimir</a>";
            $this->salida .="</td>";
            $this->salida .="</tr>";
            $this->salida .="</table>";
            $this->salida .="</td>";
            $this->salida .="</tr>";
            $this->salida .='</table>';
            $_SESSION['REPORTES_CONSULTA_EXTERNA']['RETORNO']['modulo']='AgendaBusqueda';
            $_SESSION['REPORTES_CONSULTA_EXTERNA']['RETORNO']['metodo']='ListadoCitasDia';
            $_SESSION['REPORTES_CONSULTA_EXTERNA']['RETORNO']['tipo']='user';
            $_SESSION['REPORTES_CONSULTA_EXTERNA']['RETORNO']['contenedor']='app';
      $_SESSION['REPORTES_CONSULTA_EXTERNA']['RETORNO']['variables']=array("DiaEspe"=>$_REQUEST['DiaEspe'],"tipo_consulta"=>$_REQUEST['tipo_consulta'],"des_tipo_consulta"=>$_REQUEST['des_tipo_consulta'],"tipo_id_tercero"=>$_REQUEST['tipo_id_tercero'],"tercero_id"=>$_REQUEST['tercero_id'],"nombre"=>$_REQUEST['nombre']);
            if($_REQUEST['tipo_consulta']  && $_REQUEST['des_tipo_consulta']){$tipoConsulta=$_REQUEST['tipo_consulta'].','.$_REQUEST['des_tipo_consulta'];}else{$tipoConsulta='';}
            if($_SESSION['BusquedaAgenda']['departamento'] && $_SESSION['BusquedaAgenda']['nomdep']){$depto=$_SESSION['BusquedaAgenda']['departamento'].','.$_SESSION['BusquedaAgenda']['nomdep'];}else{$depto='';}
            if($_REQUEST['tipo_id_tercero'] && $_REQUEST['tercero_id'] && $_REQUEST['nombre']){$profesional=$_REQUEST['tipo_id_tercero'].','.$_REQUEST['tercero_id'].','.$_REQUEST['nombre'];}else{$profesional='';}
            if($_REQUEST['DiaEspe']){
        (list($ano,$mes,$dia)=explode('-',$_REQUEST['DiaEspe']));
                $feinictra=$dia.'/'.$mes.'/'.$ano;
                $fefinctra=$dia.'/'.$mes.'/'.$ano;
            }else{
        $feinictra='';
                $fefinctra='';
            }
            $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaReportesAgendaMedica',array("Empresa"=>$_SESSION['BusquedaAgenda']['empresa'],"DesEmpresa"=>$_SESSION['BusquedaAgenda']['nomemp'],"depto"=>$depto,"tipoconsul"=>$tipoConsulta,"profesional"=>$profesional,'feinictra'=>$feinictra,'fefinctra'=>$fefinctra,"filtro"=>1));
            $this->salida .=" <form name=\"volver\" action=\"$accion\" method=\"post\">";
            $this->salida .=" <table width=\"80%\" border=\"0\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\">";
            $this->salida .=" <tr><td align=\"right\">";
            $this->salida .=" <img border=\"0\" src=\"".GetThemePath()."/images/pconsultar.png\">&nbsp&nbsp;<a href=\"$accion\" class=\"link\">REPORTE</a>";
      $this->salida .=" </td></tr>";
            $this->salida.="  </form>";
            $this->salida.="  </td></tr>";
            $this->salida.="  </table>";
            $this->salida.= ThemeCerrarTabla();
        }
        else{
            $this->BusquedaProfesionales();
        }
        return true;
    }


    function BusquedaProfesionales()
    {
      unset($_SESSION['REPORTES_CONSULTA_EXTERNA']['RETORNO']);
        unset($_SESSION['recone']);
        $profesional=$this->Profesionales();
        if(!empty($profesional))
        {
            $tiposconsulta=$this->BusquedaTipoConsulta();
            $this->salida.='<script>';
            $this->salida.='function TipoConsulta(val)'."\n";
            $this->salida.='{'."\n";
            //$this->salida.='alert(t);'."\n";
      $this->salida.='cad=val.split("||//");'."\n";
      $this->salida.='t=cad[0];'."\n";
            $this->salida.='descripcion=cad[1];'."\n";
            $this->salida.='if(t!=-1);'."\n";
            $this->salida.='{'."\n";
            $this->salida.='window.location.href="'.ModuloGetURL('app','AgendaBusqueda','user','BusquedaProfesionales',array('DiaEspe'=>$_REQUEST['DiaEspe'])).'"+"&tipo_consulta="+t+"&des_tipo_consulta="+descripcion;'."\n";
            $this->salida.='}'."\n";
            $this->salida.='}'."\n";
            $this->salida.='</script>';
            $this->salida .= ThemeAbrirTabla('LISTADO DE PROFESIONALES: '.$_REQUEST['DiaEspe']);
            $this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
            $this->salida .='<tr class="modulo_table_title">';
            $this->salida .='<td align="center">';
            $this->salida.="PROFESIONALES";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
            $i=0;
            while($i<sizeof($profesional[0]))
            {
                if($spy==0)
                {
                    $this->salida.='<tr class="modulo_list_claro">';
                    $spy=1;
                }
                else
                {
                    $this->salida.='<tr class="modulo_list_oscuro">';
                    $spy=0;
                }

                $this->salida .='<td align="center">';
                $_SESSION['REPORTES_CONSULTA_EXTERNA']['RETORNO']['modulo']='AgendaBusqueda';
                $_SESSION['REPORTES_CONSULTA_EXTERNA']['RETORNO']['metodo']='BusquedaProfesionales';
                $_SESSION['REPORTES_CONSULTA_EXTERNA']['RETORNO']['tipo']='user';
                $_SESSION['REPORTES_CONSULTA_EXTERNA']['RETORNO']['contenedor']='app';
                $_SESSION['REPORTES_CONSULTA_EXTERNA']['RETORNO']['variables']=array("DiaEspe"=>$_REQUEST['DiaEspe']);
                if($_REQUEST['tipo_consulta']  && $_REQUEST['des_tipo_consulta']){$tipoConsulta=$_REQUEST['tipo_consulta'].','.$_REQUEST['des_tipo_consulta'];}else{$tipoConsulta='';}
                if($_SESSION['BusquedaAgenda']['departamento'] && $_SESSION['BusquedaAgenda']['nomdep']){$depto=$_SESSION['BusquedaAgenda']['departamento'].','.$_SESSION['BusquedaAgenda']['nomdep'];}else{$depto='';}
                if($profesional[2][$i] && $profesional[1][$i] && $profesional[0][$i]){$profesionalEnvio=$profesional[2][$i].','.$profesional[1][$i].','.$profesional[0][$i];}else{$profesionalEnvio='';}
                if($_REQUEST['DiaEspe']){
                    (list($ano,$mes,$dia)=explode('-',$_REQUEST['DiaEspe']));
                    $feinictra=$dia.'/'.$mes.'/'.$ano;
                    $fefinctra=$dia.'/'.$mes.'/'.$ano;
                }else{
                    $feinictra='';
                    $fefinctra='';
                }
                // Empresa Obligatorio(codigo dela empresa)
                //DesEmpresa Oblifatorioa(nombre dela empresa)
                //depto Opcional
                //deptoDisabled Si es -1 indica que aparezca desabilitado en el filtro
                //tipoconsul Opcional
                //tipoconsulDisabled Si es -1 indica que aparezca desabilitado en el filtro
                //profesional Opcional
                //profesionalDisabled Indica si es -1 que aparezca desabililitado
                //feinictra Obligatorio
                //fefinctra Obligatoria
                $accion=ModuloGetURL('app','Reportes_Consulta_Externa','user','LlamaReportesAgendaMedica',array("Empresa"=>$_SESSION['BusquedaAgenda']['empresa'],"DesEmpresa"=>$_SESSION['BusquedaAgenda']['nomemp'],"deptoDisabled"=>'',"depto"=>$depto,"tipoconsulDisabled"=>'',"tipoconsul"=>$tipoConsulta,"profesionalDisabled"=>'',"profesional"=>$profesionalEnvio,'feinictra'=>$feinictra,'fefinctra'=>$fefinctra,"filtro"=>0));
                //$accion=ModuloGetURL('app','AgendaBusqueda','user','ProfesionalesCitasDia',array('tercero_id'=>$profesional[1][$i],'tipo_id_tercero'=>$profesional[2][$i],'nombre'=>$profesional[0][$i],'DiaEspe'=>$_REQUEST['DiaEspe'],"tipo_consulta"=>$_REQUEST['tipo_consulta'],"des_tipo_consulta"=>$_REQUEST['des_tipo_consulta']));
                $this->salida.='<a href="'.$accion.'">'.$profesional[0][$i].'</a>';
                $this->salida .= "</td>";
                $this->salida .= "</tr>";
                $i=$i+1;
            }
            $this->salida.='<tr>';
            $this->salida .='<td align="center">';
            $this->salida.="<form name=\"volver\">";
            $this->salida.="<select name=\"tipo_consulta\" class=\"select\" onchange=\"TipoConsulta(this.value)\">";
            $this->salida.="<option value=\"-1\">--SELECCIONE--</option>";
            foreach($tiposconsulta as $k=>$v){
                if($_REQUEST['tipo_consulta']==$v[tipo_consulta_id]){
                    $this->salida.="<option value=\"".$v[tipo_consulta_id]."||//".$v[descripcion]."\" selected>".$v[descripcion]."</option>";
                }else{
                    $this->salida.="<option value=\"".$v[tipo_consulta_id]."||//".$v[descripcion]."\">".$v[descripcion]."</option>";
                }
            }
            $this->salida.="</select>";
            $this->salida.="</form>";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
            $this->salida .= "</table>";
            $this->salida .='<br>';
            $this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
            $this->salida .='<tr>';
            $this->salida .='<td align="center">';
            $accion=ModuloGetURL('app','AgendaBusqueda','user','SeleccionParametros');
            $this->salida.="<form name=\"volver\" action=\"$accion\" method=\"post\">";
            $this->salida .='<a href="'.$accion.'"><input type="submit" name="VOLVER" value="VOLVER" class="input-submit"></a>';
            $this->salida.="</form>";
            $this->salida .='</td>';
            $this->salida .='</tr>';
            $this->salida .='</table>';
            $this->salida .= ThemeCerrarTabla();
        }
        else
        {
            $this->salida = ThemeAbrirTabla('LISTADO DE PROFESIONALES: '.$_REQUEST['DiaEspe']);
            $this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\">";
            $this->salida .='<tr>';
            $this->salida .='<td align="center">';
            $this->salida .= "<label class=\"label_error\">NO HAY PROFESIONALES PARA ESTA FECHA</label>";
            $this->salida .='</td>';
            $this->salida .='</tr>';
            $this->salida .='</table>';
            $this->salida .='<br>';
            $this->salida .= "<table width=\"40%\" border=\"0\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
            $this->salida .='<tr>';
            $this->salida .='<td align="center">';
            $accion=ModuloGetURL('app','AgendaBusqueda','user','SeleccionParametros');
            $this->salida.="<form name=\"volver\" action=\"$accion\" method=\"post\">";
            $this->salida .='<a href="'.$accion.'"><input type="submit" name="VOLVER" value="VOLVER" class="input-submit"></a>';
            $this->salida.="</form>";
            $this->salida .='</td>';
            $this->salida .='</tr>';
            $this->salida .='</table>';
            $this->salida .= ThemeCerrarTabla();
        }
        return true;
    }


    function AnosAgenda($Seleccionado='False',$ano)
    {
        $anoActual=date("Y")-1;
        $anoActual1=$anoActual;
    for($i=0;$i<=10;$i++)
        {
      $vars[$i]=$anoActual1;
            $anoActual1=$anoActual1+1;
        }
        switch($Seleccionado)
        {
            case 'False':
            {
                foreach($vars as $value=>$titulo)
                {
          if($titulo==$ano)
                    {
                      $this->salida .="<option value=\"$titulo\" selected>$titulo</option>";
                  }else{
            $this->salida .=" <option value=\"$titulo\">$titulo</option>";
                  }
                }
                break;
          }case 'True':
            {
              foreach($vars as $value=>$titulo)
                {
                    if($titulo==$ano)
                    {
                    $this->salida .=" <option value=\"$titulo\" selected>$titulo</option>";
                  }else{echo $titulo.'--'.$ano;
                    $this->salida .=" <option value=\"$titulo\">$titulo</option>";
                    }
                }
                break;
          }
      }
    }




    function MesesAgenda($Seleccionado='False',$Año,$Defecto)
    {
        $anoActual=date("Y");
        $vars[1]='ENERO';
    $vars[2]='FEBRERO';
        $vars[3]='MARZO';
        $vars[4]='ABRIL';
        $vars[5]='MAYO';
        $vars[6]='JUNIO';
        $vars[7]='JULIO';
        $vars[8]='AGOSTO';
        $vars[9]='SEPTIEMBRE';
        $vars[10]='OCTUBRE';
        $vars[11]='NOVIEMBRE';
        $vars[12]='DICIEMBRE';
        //$mesActual=date("m");
        switch($Seleccionado)
        {
            case 'False':
            {
              if($anoActual==$Año)
                {
                foreach($vars as $value=>$titulo)
                    {
                    if($value>=$mesActual)
                        {
                          if($value==$Defecto)
                            {
                                $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
                            }else{
                                $this->salida .=" <option value=\"$value\">$titulo</option>";
                            }
                        }
                    }
                }
                else
                {
          foreach($vars as $value=>$titulo)
                    {
                        if($value==$Defecto)
                        {
                            $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
                        }else{
                                    $this->salida .=" <option value=\"$value\">$titulo</option>";
                        }
                    }
                }
                break;
            }
            case 'True':
            {
              if($anoActual==$Año)
                {
                  foreach($vars as $value=>$titulo)
                    {
                      if($value>=$mesActual)
                        {

                          if($value==$Defecto)
                            {
                                $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
                            }else
                            {
                                $this->salida .=" <option value=\"$value\">$titulo</option>";
                            }
                        }
                    }
                }
                else
                {
          foreach($vars as $value=>$titulo)
                    {
                        if($value==$Defecto)
                        {
                            $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
                        }else
                        {
                            $this->salida .=" <option value=\"$value\">$titulo</option>";
                        }
                    }
                }
                break;
            }
        }
    }


    function BuscarIdPaciente($tipo_id,$Seleccionado='False',$TipoId='')
    {
        switch($Seleccionado){
            case 'False':{
                foreach($tipo_id as $value=>$titulo){
                    if($value==$TipoId){
                        $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
                    }else{
                        $this->salida .=" <option value=\"$value\">$titulo</option>";
                    }
                }
                break;
            }
            case 'True':{
                foreach($tipo_id as $value=>$titulo){
                    if($value==$TipoId){
                        $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
                    }else{
                        $this->salida .=" <option value=\"$value\">$titulo</option>";
                    }
                }
                break;
            }
        }
    }
    
    
    function SetStyle($campo)
    {
                if ($this->frmError[$campo] || $campo=="MensajeError"){
                    if ($campo=="MensajeError"){
                        return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
                    }
                    else
                    {
                        return ("label_error");
                    }
                }
            return ("label");
    }


}
?>
