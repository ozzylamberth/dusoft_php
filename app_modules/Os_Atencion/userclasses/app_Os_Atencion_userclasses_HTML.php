<?php
  /**
   * $Id: app_Os_Atencion_userclasses_HTML.php,v 1.6 2010/02/26 12:36:19 sandra Exp $
   * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
   * @package IPSOFT-SIIS
   *
   * Modulo para el manejo de ordenes de servicio
   */
  /**
  *  app_Os_Atencion_userclasses_HTML.php
  *
  * Clase para procesar los datos del formulario mediante la operaciones de consulta ,captura y de insercion.
  * del modulo Os_Atencion , se extiende la clase Os_Atencion y asi pueden ser
  * utilizados los metodos de esta clase en la anterior. 
  */
  class app_Os_Atencion_userclasses_HTML extends app_Os_Atencion_user
  {
    /**
    * Constructor de la clase app_Os_Atencion_userclasses_HTML
    * El constructor de la clase aOs_Atencion_userclasses_HTML se encarga de llamar
    * a la clase app_Os_Atencion_user quien se encarga de el tratamiento
    * de la base de datos.
    * @return boolean
    */

        function app_Os_Atencion_userclasses_HTML()
        {
                    $this->salida='';
                    $this->app_Os_Atencion_user();
                    return true;
        }


        function SetStyle($campo)
        {
                    if ($this->frmError[$campo] || $campo=="MensajeError"){
                        if ($campo=="MensajeError"){
                $arreglo=array('numero'=>$numero,'prefijo'=>$prefijo);
                return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
                        }
                        return ("label_error");
                    }
                return ("label");
        }



function FrmMostrarDatos($secuencia_orden)
{
        $this->salida.= ThemeAbrirTabla('ORDEN');
        $this->Encabezado() ;
        $this->salida.="<br><table border=\"0\"  class=\"modulo_table_list\"  align=\"center\"   width=\"80%\" >";
        $this->salida.="<tr align=\"center\"> ";
        $this->salida.="<td colspan=\"2\"   align=\"center\" class=\"modulo_table_title\" >NUMERO ORDEN</td>";
        $this->salida.="</tr>";
        $this->salida.="<tr align=\"center\">";
        $this->salida.="<td colspan=\"2\"   align=\"center\" class=\"modulo_list_oscuro\" >$secuencia_orden</td>";
        $this->salida.="</tr>";
        //$ac=ModuloGetURL('app','Laboratorio','admin','RetornarPermisos');
        //$ax=ModuloGetURL('app','Laboratorio','user','BuscarPermisosUser');
        $this->salida.="</table>";

            $this->salida.="<table align=\"center\" width='40%' border=\"0\">";
            $action2=ModuloGetURL('app','Laboratorio','user','DecisionOrdenLab',array('nombre'=>$NombreUsuario));
            $this->salida .= "           <form name=\"forma\" action=\"$action2\" method=\"post\">";
            $this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
            $this->salida .= "</tr>";
            $this->salida.="</table><br>";
            $this->salida.= ThemeCerrarTabla();
        return true;

}

/**ESTE MENU YA NO ESTA HABILITADO .................OJO...........
* Funcion donde se visualiza el menu de usuario.
* @return boolean
*/
    function Menu()
    {
        $this->salida.= ThemeAbrirTabla('MENÚ LABORATORIO');
        $this->salida.="<br><table border=\"0\"  class=\"modulo_table_list\"  align=\"center\"   width=\"80%\" >";
        $this->salida.="<tr>";
        $this->salida.="<td colspan=\"2\"   align=\"center\" class=\"modulo_table_title\" >EVENTOS DE USUARIOS</td>";
        $this->salida.="</tr>";
        $ac=ModuloGetURL('app','Laboratorio','admin','RetornarPermisos');
        $ax=ModuloGetURL('app','Laboratorio','user','BuscarPermisosUser');
        $this->salida.="<tr>";
        $this->salida.="<td  colspan=\"2\"  class=\"modulo_list_oscuro\"  align=\"center\"><a href=\"$ax\">CREAR ORDEN MEDICA</a>";
        $this->salida.="</td>";
        $this->salida.="</tr>";
        $this->salida.="<tr>";
        $this->salida.="<td  colspan=\"2\"  class=\"modulo_list_claro\"  align=\"center\"><a href=\"$ac\">ADICIONAR EXAMEN MEDICO</a>";
        $this->salida.="</td>";
        $this->salida.="</tr>";
        $this->salida.="</table>";
        $this->salida.= ThemeCerrarTabla();
        return true;
    }
    /*
    * Funcion donde se visualiza el encabezado de la empresa.
    * @return boolean
    */
    function Encabezado()
    {
      $this->salida .= "<br><table  class=\"modulo_table_title\" border=\"0\" width=\"80%\" align=\"center\" >";
      $this->salida .= " <tr class=\"modulo_table_title\">";
      $this->salida .= " <td>EMPRESA</td>";
      $this->salida .= " <td>CENTRO UTILIDAD</td>";
      $this->salida .= " <td>DEPARTAMENTO</td>";
      $this->salida .= " </tr>";
      $this->salida .= " <tr align=\"center\">";
      $this->salida .= " <td class=\"modulo_list_claro\" >".$_SESSION['LABORATORIO']['NOM_EMP']."</td>";
      $this->salida .= " <td class=\"modulo_list_claro\">".$_SESSION['LABORATORIO']['NOM_CENTRO']."</td>";
      $this->salida .= " <td class=\"modulo_list_claro\" >".$_SESSION['LABORATORIO']['NOM_DPTO']."</td>";
      $this->salida .= " </tr>";
      $this->salida .= " </table>";
      return true;
    }
    /**
    * Se utilizada listar en el combo los diferentes tipo de identifiacion de los pacientes
    * @access private
    * @return void
    */
    function BuscarIdPaciente($tipo_id,$TipoId)
    {
        foreach($tipo_id as $value=>$titulo)
        {
            if($value==$TipoId){
                $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
            }else{
                $this->salida .=" <option value=\"$value\">$titulo</option>";
            }
        }
    }


    /**
    * Muestra el nombre del tercero con sus respectivos planes
    * @access private
    * @return string
    * @param array arreglor con los tipos de responsable
    * @param int el responsable que viene por defecto
    */
    function MostrarResponsable($responsables,$Responsable)
    {
        $this->salida .=" <option value=\"-1\">-------SELECCIONE-------</option>";
        for($i=0; $i<sizeof($responsables); $i++)
        {
            if($responsables[$i][plan_id]==$Responsable)
            {
                $this->salida .=" <option value=\"".$responsables[$i][plan_id]."\" selected>".$responsables[$i][plan_descripcion]."</option>";
            }
            else
            {
                $this->salida .=" <option value=\"".$responsables[$i][plan_id]."\">".$responsables[$i][plan_descripcion]."</option>";
            }
        }
    }


    /**
    *
    *  esta funcion resetea todas las variables de session de este modulo....
    * @return boolean
    */
    function ReseteraVariablesSession()
    {
        unset($_SESSION['LABORATORIO']['N_ORDEN']);
        unset($_SESSION['LABORATORIO']['INGRESO']);
        return true;
    }



    /**
    * Esta funcion realiza la busqueda de las ordenes de servicio según filtros como numero de orden
    * documento y plan
    * @return boolean
    */
    function FormaMetodoBuscar($Busqueda,$arr,$f,$url_full)
    {

            unset($_SESSION['SEGURIDAD']);
			
			IncludeFileModulo("OsAtencion", "RemoteXajax", "app", "Os_Atencion");
			$action = ModuloGetURL('app', 'Os_Atencion', 'user', 'FormaDatosPaciente');
			$this->SetXajax(array("UpdateEmailPaciente"), null, "ISO-8859-1");

            $this->salida.= ThemeAbrirTabla('ORDEN DE SERVICIOS MEDICOS');
            $this->Encabezado();
            $this->ReseteraVariablesSession();
            if(!$Busqueda)
            {
                $Busqueda = 1;
            }

            //ACCION DEL BUSCADOR
            $accion=ModuloGetURL('app','Os_Atencion','user','BuscarOrden');

            $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
            $this->salida .= "      <tr>";
            $this->salida .= "         <td width=\"70%\" >";
            $this->salida .= "      <br><table border=\"0\" width=\"80%\" align=\"center\">";
            $this->salida .= "          <tr><td><fieldset><legend class=\"field\">BUSCADOR AVANZADO</legend>";
            $this->salida .= "                <table width=\"95%\" align=\"center\" border=\"0\">";
            $this->salida .= "             <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
            $this->salida .= "                      <tr><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
            $this->salida .= "                <option value=-1 selected>--  SELECCIONE --</option>";

            if($_REQUEST['TipoDocumento']=='*')
            {
                $this->salida .= "                <option value=\"*\" selected>--  TODOS  --</option>";
            }
            else
            {
                $this->salida .= "                <option value=\"*\">--  TODOS  --</option>";
            }

            $tipo_id = $this->tipo_id_paciente();

            $this->BuscarIdPaciente($tipo_id,$_REQUEST['TipoDocumento']);

            $this->salida .= "                  </select></td></tr>";
            if($_REQUEST['distinct']==on){$check='checked';}else{$check='';}
            $this->salida .= "                      <tr><td class=\"label\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value=".$_REQUEST['Documento']."></td></tr>";
            $this->salida .= "                      <tr><td class=\"label\">NOMBRES</td><td><input type=\"text\" class=\"input-text\" name=\"nombres\" maxlength=\"32\" value=".$_REQUEST['nombres'].">&nbsp;&nbsp; &nbsp;</td></tr>";
            $this->salida .= "                      <tr><td class=\"label\">APELLIDOS</td><td><input type=\"text\" class=\"input-text\" name=\"apellidos\" maxlength=\"32\" value=".$_REQUEST['apellidos'].">&nbsp;&nbsp; &nbsp;</td></tr>";
            $this->salida .= "                     <tr><td class=\"".$this->SetStyle("Responsable")."\">PLAN: </td><td><select name=\"Responsable\" class=\"select\">";
            $responsables=$this->responsables();
            $this->MostrarResponsable($responsables,$_REQUEST['Responsable']);
            $this->salida .= "              </select></td></tr>";
            $this->salida .= "                      <tr><td class=\"".$this->SetStyle("IngresoId")."\">No. ORDEN</td><td><input type=\"text\" class=\"input-text\" name=\"NumIngreso\" maxlength=\"32\" value=".$_REQUEST['NumIngreso']."></td></tr>";
			
			//CAMPO CORREO ELECTRONICO E-MAIL DE PACIENTE
			//$emailpaciente=$this->TraerEmail('1022937280','CC');
			$emailpaciente=$this->TraerEmail($_REQUEST['Documento'],$_REQUEST['TipoDocumento']);
			
		if(empty($_REQUEST['Documento']) || $_REQUEST['TipoDocumento']==-1)
		{
			$this->salida.="<tr><td class='".$this->SetStyle("CorreoElectronico")."'></td>";
			$mensaje = "POR FAVOR INGRESE EL <i>TIPO DE DOCUMENTO</i> Y EL <i>DOCUMENTO</i> PARA VER/ACTUALIZAR EL E-MAIL.";
		}
		else
		{
			if(empty($emailpaciente[0]['email']))
			{
				$this->salida.="<tr><td class='".$this->SetStyle("CorreoElectronico")."'>CORREO ELECTRÓNICO:</td>";
				$this->salida.="	<td><input type='text' class='input-text' name='emailti' id='emailti' />
										<input type='button' name='actualizarEmailBtn' value='Actualizar E-Mail' class='input-submit' onClick=\"xajax_UpdateEmailPaciente('".$_REQUEST['Documento']."','".$_REQUEST['TipoDocumento']."',emailti.value)\"/>
									</td>
								</tr>";
			}
			else
			{
				$this->salida.="<tr><td class='".$this->SetStyle("CorreoElectronico")."'>CORREO ELECTRÓNICO:</td>
									<td><input type='text' class='input-text' name='emailti' id='emailti' value='".$emailpaciente[0]['email']."'/>
										<input type='button' name='actualizarEmailBtn' value='Actualizar E-Mail' class='input-submit' onClick=\"xajax_UpdateEmailPaciente('".$_REQUEST['Documento']."','".$_REQUEST['TipoDocumento']."',emailti.value)\"/></td>
								</tr>";
			}
		}
			//FIN CAMPO CORREO ELECTRONICO E-MAIL DE PACIENTE
			
            $this->salida .= "                <input type=\"hidden\" name=\"TipoBuscar\" value=\"$Busqueda\">";
            $this->salida .= "                 <input type=\"hidden\" name=\"LinkCargo\" value=\"$LinkCargo\">";

            $this->salida .= "               <tr><td align='center' colspan=\"$col\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSQUEDA\"></td>";
            $this->salida .= "                </form>";

            //FORMA VOLVER
            $actionM=ModuloGetURL('app','Os_Atencion','user','main');
            $this->salida .= "             <form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
            $this->salida .= "                     <td align=\"left\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"><br></td></form>";
            $this->salida .= "                     </tr>";

            //LINK SOLICITUD MANUAL
            $actionH=ModuloGetURL('app','Os_Atencion','user','LlamarFormaBuscar');
            $this->salida .= "                      <tr><td class=\"label\" coslpan=\"2\"><br><a href=\"$actionH\">SOLICITUD MANUAL</a></br></td></tr>";

            $this->salida .= "        </fieldset></td></tr></table>";
            $this->salida .= "  </table>";
            $this->salida .= "         </td>";

            $this->salida .= "      </tr>";
            $this->salida .= "  </table>";

            if($mensaje){
                $accionT=ModuloGetURL('app','Facturacion','user','main',array('TipoCuenta'=>$TipoCuenta));
                $this->salida .= "          <p class=\"label_error\" align=\"center\">$mensaje</p>";
                $this->salida .= "           <form name=\"formabuscar\" action=\"$accionT\" method=\"post\">";
            }

            if($_SESSION['OS_ATENCION']['CARGARFILTRO'])
            {
                unset($_SESSION['OS_ATENCION']['CARGARFILTRO']);
                $this->salida .= ThemeCerrarTabla();
                return true;
            }
            else
            {
                if(!$arr && $this->uno!=1)
                {
                        $arr=$this->BusquedaCompleta();
                        //$_SESSION['SPY']=$this->RecordSearch($Caja,$TipoCuenta,$Departamento);
                }
            }
      if (empty($this->dos))
      {
            $this->salida.="<table border=\"0\" align=\"center\"  width=\"100%\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida.="</table>";
      }
            if(!empty($arr) AND !empty($f))
            {
                    $mostrar ="\n<script language='javascript'>\n";
                    $mostrar.="function mOvr(src,clrOver) {;\n";
                    //$mostrar.=" if (!src.contains(event.fromElement)) {\n";
                    //$mostrar.="src.style.cursor = 'hand';\n";
                    //$mostrar.="src.bgColor = clrOver;\n";
                    $mostrar.="src.style.background = clrOver;\n";
                    //$mostrar.="}\n";
                    $mostrar.="}\n";

                    $mostrar.="function mOut(src,clrIn) {\n";
                    //$mostrar.="if (!src.contains(event.toElement)) {\n";
                    //$mostrar.="src.style.cursor = 'default';\n";
                    //$mostrar.="src.bgColor = clrIn;\n";
                    $mostrar.="src.style.background = clrIn;\n";
                    //$mostrar.="}\n";
                    $mostrar.="}\n";
                    $mostrar.="</script>\n";
                    $this->salida .="$mostrar";

                    $this->salida .= "      <table width=\"70%\" border=\"0\" cellspacing=\"3\" cellpadding=\"3\" align=\"center\" >";
                    $this->salida .= "          <tr align=\"center\" class=\"modulo_table_list_title\">";
                    $this->salida .= "              <td width=\"20%\">IDENTIFICACION</td>";
                    $this->salida .= "              <td width=\"58%\">DATOS DEL PACIENTE</td>";
                    //$this->salida .= "                <td width=\"15%\">RESPONSABLE</td>";
                    $this->salida .= "              <td colspan='3' width=\"8%\">ESTADOS</td>";
                    $this->salida .= "              <td width=\"5%\"></td>";
                    $this->salida .= "          </tr>";
                    //$arr=array_unique($arr);
                    $backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
                    for($i=0;$i<sizeof($arr);$i++)
                    {
                        if( $i % 2){ $estilo='modulo_list_claro';}
                        else {$estilo='modulo_list_oscuro';}

                        $this->salida.="<tr  class='$estilo' align='center'  onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>";
                        $this->salida.="  <td><label class='label_mark'>".$arr[$i][tipo_id_paciente]." &nbsp; - &nbsp;".$arr[$i][paciente_id]."</label></td>";
                        $this->salida.="  <td>".$arr[$i][nombre]."</td>";

                        unset($arreglo);
                        $arreglo=$this->Traer_Estados_Os_maestros($arr[$i][tipo_id_paciente],$arr[$i][paciente_id]);

                        if($arreglo[1]=='1')//1 son activas,para pagos //0
                        {$img1="cargar.png";$title1="Existen ordenes para Pagar";}else{$img1="checkN.gif";$title1="No hay ordenes para pagos";}

                        if($arreglo[2]=='2')//2 son pagas,para cumplimiento 0 ->cargos realizados en la atención no cargados a una cuenta quedando pendiente por cobrar
                        {$img2="cargos.png";$title2="Existen ordenes para Cumplimiento";}else{$img2="checkN.gif";$title2="No hay ordenes para Cumplimiento";}

                        if($arreglo[3]=='3')//3 son pagas,para atencion
                        {$img3="atencion_citas.png";$title3="Existen ordenes para Atencion";}else{$img3="checkN.gif";$title3="No hay ordenes para Atencion";}
                        $this->salida .= "              <td width=\"3%\"><img title='$title1' src=\"". GetThemePath() ."/images/$img1\" border='0'></td>";
                        $this->salida .= "              <td width=\"3%\"><img title='$title2' src=\"". GetThemePath() ."/images/$img2\" border='0'></td>";
                        $this->salida .= "              <td width=\"3%\"><img title='$title3' src=\"". GetThemePath() ."/images/$img3\" border='0'></td>";
                        //Esta linea era la q funcionaba actualmente no borrar
                        //$this->salida .= "<td width=\"10%\"onclick=mClk(this); onmouseout=mOut(this,'#CCCCCC'); onmouseover=mOvr(this,'#7A99BB');><a href=".ModuloGetURL('app','Os_Atencion','user','FrmOrdenar',array('tipoid'=>$arr[$i]['tipo_id_paciente'],'idp'=>$arr[$i]['paciente_id'],'nombre'=>urlencode($arr[$i]['nombre'])))."><img src=\"". GetThemePath() ."/images/flecha_der.gif\" border='0' width='10' height='10'>&nbsp;&nbsp;VER</a></td>";
                        //$this->salida .= "<td  width=\"10%\" ><a href=".ModuloGetURL('app','Os_Atencion','user','RevisarAuto',array('tipoid'=>$arr[$i]['tipo_id_paciente'],'idp'=>$arr[$i]['paciente_id'],'nombre'=>urlencode($arr[$i]['nombre']),'plan_id'=>$arr[$i]['plan_id'],'orden_servicio_id'=>$arr[$i]['orden_servicio_id']))."><img src=\"". GetThemePath() ."/images/flecha_der.gif\" border='0' width='10' height='10'>&nbsp;&nbsp;VER</a></td>";
                        //$this->salida .= "<td  width=\"10%\" ><a href=".ModuloGetURL('app','Os_Atencion','user','RevisarAuto',array('tipoid'=>$arr[$i]['tipo_id_paciente'],'idp'=>$arr[$i]['paciente_id'],'nombre'=>urlencode($arr[$i]['nombre']),'plan_id'=>$arr[$i]['plan_id'],'orden_servicio_id'=>$arr[$i]['orden_servicio_id'])).">VER</a></td>";
                        $this->salida .= "<td  width=\"10%\" ><a href=".ModuloGetURL('app','Os_Atencion','user','FrmOrdenar',array('tipoid'=>$arr[$i]['tipo_id_paciente'],'idp'=>$arr[$i]['paciente_id'],'nombre'=>urlencode($arr[$i]['nombre']))).">VER</a></td>";
                        $this->salida.="</tr>";
                    }
                    $this->salida.="</table>";
                    $this->conteo=$_SESSION['SPY'];
                    $this->salida .=$this->RetornarBarra();
            }
            $this->salida .= ThemeCerrarTabla();
        return true;
    }


    /**
    * Esta funcion calcula el numero de pasos que saldran en la barra de navegación.
    * @return boolean
    */
    function CalcularNumeroPasos($conteo)
    {
        $numpaso=ceil($conteo/$this->limit);
        return $numpaso;
    }

    /**
    * Esta funcion calcula la barra de navegación.
    * @return boolean
    */
    function CalcularBarra($paso)
    {
        $barra=floor($paso/10)*10;
        if(($paso%10)==0)
        {
            $barra=$barra-10;
        }
        return $barra;
    }

    /**
    * Esta funcion calcula los segmentos en que se desplaza el apuntador de los registros
    * de la base de datos.
    * @return boolean
    */
    function CalcularOffset($paso)
    {
        $offset=($paso*$this->limit)-$this->limit;
        return $offset;
    }


    /**
    * Esta funcion integra (CalcularNumeroPasos,CalcularOffset,CalcularBarra), para asi
    * crear una barra de navegacion, para los registros.
    * @return boolean
    */
    function RetornarBarra()
    {
        if($this->limit = $this->conteo)
        {
            return '';
        }

        $paso=$_REQUEST['paso'];

        if(is_null($paso))
        {
            $paso=1;
        }

        $vec='';

        foreach($_REQUEST as $v=>$v1)
        {
            if($v!='modulo' and $v!='metodo' and $v!='SIIS_SID' and  $v!='Of' and $v!='')
            {
                $vec[$v] = $v1;
            }
        }

        $accion = ModuloGetURL('app','Os_Atencion','user','BuscarOrden',$vec);

        $barra = $this->CalcularBarra($paso);

        $numpasos = $this->CalcularNumeroPasos($this->conteo);

        $colspan=1;

        $this->salida .= "<br><table border='1' align='center'  cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Páginas :</td>";
        if($paso > 1)
        {
            $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
            $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
            $colspan+=1;
        }
        else
        {
            // $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
            //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
        }
        $barra ++;
        if(($barra+10)<=$numpasos)
        {
            for($i=($barra);$i<($barra+10);$i++)
            {
                if($paso==$i)
                {
                        $this->salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
                }
                else
                {
                        $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
                }
                $colspan++;
            }
            $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
            $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
            $colspan+=2;
        }
        else
        {
            $diferencia=$numpasos-9;
            if($diferencia<=0)
            {
                $diferencia=1;
            }//cambiar en todas las barra
            for($i=($diferencia);$i<=$numpasos;$i++)
            {
                if($paso==$i)
                {
                    $this->salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
                }
                else
                {
                    $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
                }
                $colspan++;
            }
            if($paso!=$numpasos)
            {
                $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
                $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
                $colspan++;
            }
            else
            {
                // $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
                //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
            }
        }

        if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
        {
            if($numpasos>10)
            {
                $valor=10+3;
            }
            else
            {
                $valor=$numpasos+3;
            }
            $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
        }
        else
        {
            if($numpasos>10)
            {
                $valor=10+5;
            }
            else
            {
                $valor=$numpasos+5;
            }
            $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
        }
     }
    /**
    * Esta funcion te muestra en detalle las ordenes de servicio
    * filtrados por(tipo_afiliado_id,rango,orden_servicio_id),y separarados por plan.
    * @return boolean
    */
    function FrmOrdenar($nom,$tipo,$id,$seleccionados,$puntoatencion)
    {
      if(empty($nom))
      {
        $nom=$_REQUEST['nom'];
        $tipo=$_REQUEST['tipo_id'];
        $id=$_REQUEST['id'];
      }
      unset($_SESSION['citas']);
      unset($_SESSION['citas_profesional']);
      if(!$nom)
      {
        $nom=urldecode($_REQUEST['nombre']);
        $tipo=$_REQUEST['tipoid'];
        $id=$_REQUEST['idp'];
      }
      UNSET($_SESSION['OS_ATENCION']);
      UNSET($_SESSION['CAJA']['IMD_OS']);
      UNSET($_SESSION['CAJA']['IMD_CUENTA']);

      $this->salida.= ThemeAbrirTabla('ORDEN DE SERVICIOS MEDICOS');
      $this->salida .= "<SCRIPT language='javascript'>\n";
      $this->salida .= "  function Pintartd(clrIn,i,x)\n";
      $this->salida .= "  {\n";
      $this->salida .= "    if(x==true)\n";
      $this->salida .= "    {\n";
      $this->salida .= "      document.getElementById(i).style.background = '#7A99BB';\n";
      $this->salida .= "    }\n";
      $this->salida .= "    else\n";
      $this->salida .= "    {\n";
      $this->salida .= "      document.getElementById(i).style.background = clrIn;\n";
      $this->salida .= "    }\n";
      $this->salida .= "  }\n";

      //PARA CONSULTAR ORDENES VENCIDAS
      $RUTA = "app_modules/Os_Atencion/OrdenesVencidas.php?tipo_id=$tipo&paciente=$id&dpto=".$_SESSION['LABORATORIO']['DPTO']."";
      $this->salida .= "  function abreVentana()\n";
      $this->salida .= "  {\n";
      $this->salida .= "    var rem=\"\";\n";
      $this->salida .= "    var nombre=\"\"\n";
      $this->salida .= "    var url2=\"\"\n";
      $this->salida .= "    var width=\"700\"\n";
      $this->salida .= "    var height=\"400\"\n";
      $this->salida .= "    var winX=width;\n";
      $this->salida .= "    var winY=height;\n";
      $this->salida .= "    var nombre=\"Printer_Mananger\";\n";
      $this->salida .= "    var str =\"width=\"+width+\",height=\"+height+\",left=\"+winX+\",top=\"+winY+\",resizable=no,status=no,scrollbars=yes,location=no\";\n";
      $this->salida .= "    var url2 ='$RUTA';\n";
      $this->salida .= "    rem = window.open(url2, nombre, str);}\n";
      $this->salida .= "</SCRIPT>\n";
      $this->Encabezado();
      $this->salida .= "              <BR><table  cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"80%\" align=\"center\" >";
      $this->salida .= $this->SetStyle("MensajeError");
      $this->salida .= "                     <tr><td class=\"modulo_table_title\"  width=\"20%\">NOMBRE PACIENTE: </td><td class=\"modulo_list_claro\" align=\"left\">".$nom."</td></tr>";
      $this->salida .= "                     <tr><td class=\"modulo_table_title\"  class=\"label\" width=\"25%\" align=\"left\">IDENTIFICACION: </td><td class=\"modulo_list_claro\" align=\"left\">".$tipo."&nbsp;".$id."</td></tr>";
      $this->salida .= "                     <tr><td class=\"modulo_table_title\"  class=\"label\" width=\"25%\" align=\"left\">ORDENES VENCIDAS </td><td class=\"modulo_list_claro\" align=\"left\"><a href='javascript:abreVentana()'>VER ORDENES VENCIDAS</a></td></tr>";
      $this->salida .= "                     <tr><td class=\"modulo_table_title\"  class=\"label\" width=\"25%\" align=\"left\">SOLICITUDES POR AUTORIZAR </td>";
      //Ordenes de Servicio

      $conteo_os=$this->ConteoOrdenesPaciente($tipo,$id,$_SESSION['LABORATORIO']['DPTO']);
      if($conteo_os==1)
      {
        //AQUI ES PARA COMUNICARSE CON LA CENTRAL DE IMPRESION DE ORDENES DE DAR.
        $_SESSION['CENTRALHOSP']['RETORNO']['modulo']='Os_Atencion';
        $_SESSION['CENTRALHOSP']['RETORNO']['metodo']='Seleccion';//'FrmOrdenar';
        $_SESSION['CENTRALHOSP']['RETORNO']['tipo']='user';
        $_SESSION['CENTRALHOSP']['RETORNO']['contenedor']='app';
        $_SESSION['CENTRALHOSP']['RETORNO']['argumentos']=array('nom'=>$nom,'tipo_id'=>$tipo,'id'=>$id);

        $href=ModuloGetURL('app','CentralImpresionHospitalizacion','user','BuscarPorPaciente',array(
        "paciente_id"=>$id,"tipo_id_paciente"=>$tipo,'departamento'=>$_SESSION['LABORATORIO']['DPTO'],'empresa_id'=>$_SESSION['LABORATORIO']['EMPRESA_ID']));
        $this->salida .= "  <td align=\"left\" class=\"modulo_list_claro\"><a href=\"$href\"><img src=\"". GetThemePath() ."/images/pinactivo.png\" border='0'>&nbsp;Autorizar OS</a></td>\n";
      }
      else
      {
        $this->salida .= "  <td align=\"left\" class=\"modulo_list_claro\"><img src=\"". GetThemePath() ."/images/editar.png\" border='0'>&nbsp;Ninguna</td>\n";
      }
      
      $this->salida .= "</table><BR>";

      //CASO EN QUE LAS ORDENES TRAIDAS ESTAN PAGADAS O CARGADAS
      //A LA CUENTA Y ESTAN PENDIENTES POR SER CUMPLIDAS
      $vector2=$this->TraerOrdenesServicio_estado2($tipo,$id);
      if($vector2)
      {
          $this->salida .= "<form name=\"formo\" action=\"".ModuloGetURL('app','Os_Atencion','user','CambiarEstadoACumplimiento',array('nom'=>$nom,'id_tipo'=>$tipo,'id'=>$id,'vect'=>sizeof($vector2)))."\" method=\"post\">";
          $this->salida.="<BR><table  align=\"center\" border=\"0\" width=\"80%\">";
          $this->salida.="<tr class=\"modulo_table_title\">";
          if($_SESSION['LABORATORIO']['SW_HONORARIO']==1)
          {
              $this->salida.="  <td align=\"left\" colspan=\"9\">ORDENES PAGADAS</td>";
          }
          else
          {
              $this->salida.="  <td align=\"left\" colspan=\"8\">ORDENES PAGADAS</td>";
          }
          $this->salida.="</tr>";
          $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
          $this->salida.="  <td width=\"8%\">ORDEN ID</td>";
          $this->salida.="  <td width=\"10%\">CANTIDAD</td>";
          $this->salida.="  <td width=\"7%\">CARGO</td>";
          $this->salida.="  <td width=\"30%\">DESCRIPCION</td>";
		  $this->salida.="  <td width=\"10%\">FECHA ORDEN</td>";
		  $this->salida.="  <td width=\"13%\">DPTO TOMADO</td>";
          $this->salida.="  <td width=\"10%\">VENCIMIENTO</td>";
          if($_SESSION['LABORATORIO']['SW_HONORARIO']==1)
          {$this->salida.="  <td width=\"20%\">MEDICO</td>";}
          $this->salida.="  <td width=\"8%    \"></td>";

          $conteo=sizeof($vector2); //para saber si es uno solo y si viene vencido entonces no creamos el
          //boton cumplir...
          for($i=0;$i<sizeof($vector2);$i++)
          {
            if( $i % 2){ $estilo='modulo_list_claro';}
            else {$estilo='modulo_list_oscuro';}
			$dptoTomado2 = $this->TraerdptoTomado($vector2[$i][numero_orden_id]);
            $vecimiento=$vector2[$i][fecha_vencimiento];
            $arr_fecha=explode(" ",$vecimiento);
            $this->salida.="<tr class=$estilo>";
            $this->salida.="  <td align=\"center\" >".$vector2[$i][numero_orden_id]."</td>";
            $this->salida.="  <td align=\"center\" >".$vector2[$i][cantidad]."</td>";
            $this->salida.="  <td align=\"center\" >".$vector2[$i][cargoi]."</td>";
            $this->salida.="  <td align=\"center\" >".$vector2[$i][des1]."</td>";
			$this->salida.="  <td  align=\"center\" >".substr($vector2[$i][fecha_registro], 0, -7)."</td>";
			$this->salida.="  <td  align=\"center\" >".$dptoTomado2[0][descripcion]."</td>";
            if(strtotime($arr_fecha[0]) >= strtotime(date("Y-m-d")))
            {
                $this->salida.="  <td  align=\"center\" >$arr_fecha[0]</td>";
                if($_SESSION['LABORATORIO']['SW_HONORARIO']==1)
                {
                  $datos=$this->ComboProfesionales();
                  if(is_array($datos))
                  {
                              $this->salida.="<td align=\"center\" ><select name=profe[$i] class='select'>";
                              $this->salida.="<option value=-1>----Seleccione----</option>";
                              for($m=0;$m<sizeof($datos);$m++)
                              {

                                      $this->salida.="<option value=".$datos[$m][usuario_id].">".$datos[$m][nombre]."</option>";
                              }
                              $this->salida.="</select></td>";
                  }
                  else
                  {
                      $this->salida.="  <td  align=\"center\" ></td>";
                  }
                }
                if($vector2[$i][os_tipo_solicitud_id]=='CIT')//quiere decir q es una cita..
                {
                    $this->salida.="  <td><label class='label_mark'>PAGADO</label></td>";
                }
                else
                {
                    $this->salida.="  <td  align=\"center\"><input type=checkbox name=op[$i] value=".$vector2[$i][numero_orden_id].",".$vector2[$i][cargoi].",".$vector2[$i][tarifario_id].",".$vector2[$i][autorizacion_ext].",".$vector2[$i][autorizacion_int].",".$vector2[$i][cantidad].",".urlencode($vector2[$i][descargo]).",".$vector2[$i][servicio].",".str_replace(" ","_",$vector2[$i][serv_des]).",".$vector2[$i][orden_servicio_id]."></td>";
                }
                $sw_conteo=true;//esto se activa si no esta vencido y lo comparamos a ver si es un solo
                //registro para determinar que solo salga como informacion y no con boton de cumplir.
            }
            else
            {
                $this->salida.="  <td  align=\"center\" ><label class='label_mark'>VENCIDO</label></td>";
                $this->salida.="  <td align='center'><img src=\"".GetThemePath()."/images/delete.gif\"></td>";
                if(empty($sw_conteo))
                {$sw_conteo=false;}
                if($_SESSION['LABORATORIO']['SW_HONORARIO']==1)
                {
                    $this->salida.="  <td  align=\"center\" ></td>";
                }

            }

            $this->salida.="</tr>";
        }
        $this->salida.="</table>";
        
        if($conteo >=1 AND $sw_conteo ==true)
        {
                $this->salida.="<table align='center' width='80%'>";
                $this->salida.="<tr align='right' class=\"modulo_table_button\">";
                $this->salida.="<td><input class=\"input-submit\" type=submit name=mandar value=Cumplir-8></form></td>";
                $this->salida.="</tr>";
                $this->salida.="</table><br>";
        }
        $this->salida.="</form>";
      }

      unset($vector3);
      //CASO EN EL QUE SE TRAEN LAS ORDENES QUE FUERON CUMPLIDAS
      //Y QUE ESTAN PENDIENTES POR SER TOMADAS
      $vector3=$this->TraerOrdenesServicio_estado3($tipo,$id);

      if($vector3)
      {
      
              $this->salida.="<BR><table  align=\"center\" border=\"0\" width=\"80%\">";
              $this->salida.="<tr class=\"modulo_table_title\">";
              if($_SESSION['LABORATORIO']['SW_HONORARIO']==1)
              {   $this->salida.="  <td align=\"left\" colspan=\"9\">PARA ATENCION</td>";}
              else
              {$this->salida.="  <td align=\"left\" colspan=\"9\">PARA ATENCION</td>";}
              $this->salida.="</tr>";
              $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
              $this->salida.="  <td width=\"8%\">ITEM</td>";
              $this->salida.="  <td width=\"5%\">CANT</td>";
		  /* echo '<pre>Request';
		  print_r ($_REQUEST);
		  echo '</pre>';
		  echo '<pre>Session';
		  print_r ($_SESSION);
		  echo '</pre>';  */
              $this->salida.="  <td width=\"7%\">CARGO</td>";
              $this->salida.="  <td width=\"30%\">DESCRIPCION</td>";
			  $this->salida.="  <td width=\"10%\">FECHA ORDEN</td>";
			  $this->salida.="  <td width=\"13%\">DPTO TOMADO</td>";
              $this->salida.="  <td width=\"10%\">VENCIMIENTO</td>";
              if($_SESSION['LABORATORIO']['SW_HONORARIO']==1)
              {$this->salida.="  <td width=\"20%\">ASIGNADO A</td>";}
              $this->salida.="  <td width=\"8%\">CUMPLIMIENTO</td>";
              $this->salida.="  <td width=\"5%\">Sel</td>";
              unset($_SESSION['OS_ATENCION']['NUMERO_C']);
              for($i=0;$i<sizeof($vector3);$i++)
              {
                      if( $i % 2){ $estilo='modulo_list_claro';$color='#DDDDDD';}
                      else {$estilo='modulo_list_oscuro';$color='#CCCCCC';}

                      $numero=$this->TraerNumeroCumplimiento($vector3[$i][numero_orden_id]);
                      //si este $numero llega vacio es por q a la tabla os_cumplimiento_detalle
                      //le han borrado el numero_orden_id..

                      if(!$_SESSION['OS_ATENCION']['NUMERO_C'])
                      {
                          $_SESSION['OS_ATENCION']['NUMERO_C']=$numero['numero_cumplimiento'];
                          $sw_imprimir=0;
                          //aqui va el cambio <duvan>
                          $accion1=ModuloGetURL('app','Os_Atencion','user','ReporteFichaLaboratorio',array('numero'=>$numero['numero_cumplimiento'],'fecha_cumplimiento'=>$numero['fecha_cumplimiento'],'tipoid'=>$tipo,'id'=>$id,'nom'=>$nom));
                          $this->salida .= "<form name=\"formaimp\"  action=".$accion1." method=\"post\">";
                      }
                      else
                      {
                          if($_SESSION['OS_ATENCION']['NUMERO_C']==$numero['numero_cumplimiento'])
                          {
                                  $_SESSION['OS_ATENCION']['NUMERO_C']=$numero['numero_cumplimiento'];
                                  $sw_imprimir=0;
                          }
                          else
                          {
                                      if($_SESSION['LABORATORIO']['SW_HONORARIO']==1)
                                      {$nu=10;}else{$nu=9;}
                                      $sw_imprimir=0;
                                      $accion1=ModuloGetURL('app','Os_Atencion','user','ReporteFichaLaboratorio',array('numero'=>$numero['numero_cumplimiento'],'fecha_cumplimiento'=>$numero['fecha_cumplimiento'],'tipoid'=>$tipo,'id'=>$id,'nom'=>$nom));

                                      if($vector3[$i][os_tipo_solicitud_id]=='CIT')//quiere decir q es una cita..
                                      {
                                          $this->salida.="  <tr class='$estilo'><td  colspan='$nu' align=\"center\"><label class='label_mark'>CITA</label></td></tr>";
                                      }
                                      else
                                      {
                                          $this->salida.="  <tr class='$estilo'><td  colspan='$nu' align=\"center\"><input type='submit' class='input-submit' name='imp' value='imprimir'></td></tr>";
                                      }
                                      $_SESSION['OS_ATENCION']['NUMERO_C']=$numero['numero_cumplimiento'];
                                      $this->salida.="</form>";
                                      $this->salida .= "<form name=\"formaimp\"  action=".$accion1." method=\"post\">";
                          }
                      }
					  $dptoTomado = $this->TraerdptoTomado($vector3[$i][numero_orden_id]);
					  
                      $vecimiento=$vector3[$i][fecha_vencimiento];
                      $arr_fecha=explode(" ",$vecimiento);
                      //$this->salida.="<tr id=$i class='$estilo'>";
                      //$this->salida.="  <td  align=\"center\" >".$vector3[$i][numero_orden_id]."</td>";
					 

					//CANTIDADES CON CARGOS NUEVOS
					  $cantidad_cargo2=$this->MostrarCantidadCargoCuentasDetalle($vector3[$i][numero_orden_id],$vector3[$i][cargoi]);
					  if(empty($cantidad_cargo2))
					  {
						$this->salida.="<tr id=$i class='$estilo'>";
						$this->salida.="  <td  align=\"center\" >".$vector3[$i][numero_orden_id]."</td>";
						$this->salida.="  <td  align=\"center\" >".$vector3[$i][cantidad]."</td>";
						$this->salida.="  <td  align=\"center\" >".$vector3[$i][cargoi]."</td>";
						$this->salida.="  <td  align=\"center\" >".$vector3[$i][des1]."</td>";
						$this->salida.="  <td  align=\"center\" >".substr($vector3[$i][fecha_registro], 0, -7)."</td>";
						$this->salida.="  <td  align=\"center\" >".$dptoTomado[0][descripcion]."</td>";
						
						if(strtotime($arr_fecha[0]) >= strtotime(date("Y-m-d")))
						{
							$this->salida.="  <td   align=\"center\" >$arr_fecha[0]</td>";
							if($_SESSION['LABORATORIO']['SW_HONORARIO']==1)
							{
								$this->salida.="  <td  align=\"center\" >".$this->TraerEspecialista($vector3[$i][numero_orden_id])."</td>";
							}
							$num_cumplimiento=$this->ConvierteCumplimiento($vector3[$i][fecha_cumplimiento],$vector3[$i][numero_cumplimiento],$_SESSION['LABORATORIO']['DPTO']);
							$this->salida.="  <td  align=\"center\"><label class='label_mark'>$num_cumplimiento</td>";
						}
						else
						{
                          if($_SESSION['LABORATORIO']['SW_HONORARIO']==1)
                          {
							$this->salida.="  <td  align=\"center\" >".$this->TraerEspecialista($vector3[$i][numero_orden_id])."</td>";
						  }
                          $this->salida.="  <td   align=\"center\"><label class='label_mark'>VENCIDO</label></td>";
                          $this->salida.="  <td align='center'><img src=\"".GetThemePath()."/images/delete.gif\"></td>";
						}
                      
						if($vector3[$i][os_tipo_solicitud_id]=='CIT')//quiere decir q es una cita..
						{
						  $this->salida.="  <td><label class='label_mark'><label class='label_mark'>CITA</label></label></td>";
						}
						else
						{
						  $this->salida.="<td class='$estilo' align=\"center\"><input type=\"checkbox\" value=\"".$vector3[$i][numero_orden_id]."\" name=\"sel[]\" onclick=Pintartd('$color','$i',this.checked)></td>";
						}
						 $this->salida.="</tr>";
							
					  }
					  else
					  {
						$vacios = count($cantidad_cargo2);
						for($s=-1;$s<$vacios;$s++)
						{
							if($s==-1)
							{
								$this->salida.="<tr id=$i class='$estilo'>";
								$this->salida.="  <td  align=\"center\" >".$vector3[$i][numero_orden_id]."</td>";
								$this->salida.="  <td  align=\"center\" >".$vector3[$i][cantidad]."</td>";
								$this->salida.="  <td  align=\"center\" >".$vector3[$i][cargoi]."</td>";
								$this->salida.="  <td  align=\"center\" >".$vector3[$i][des1]."</td>";
								$this->salida.="  <td  align=\"center\" >".substr($vector3[$i][fecha_registro], 0, -7)."</td>";
								$this->salida.="  <td  align=\"center\" >".$dptoTomado[0][descripcion]."</td>";
								
								if(strtotime($arr_fecha[0]) >= strtotime(date("Y-m-d")))
								{
								  $this->salida.="  <td   align=\"center\" >$arr_fecha[0]</td>";
								  if($_SESSION['LABORATORIO']['SW_HONORARIO']==1)
								  {
									$this->salida.="  <td  align=\"center\" >".$this->TraerEspecialista($vector3[$i][numero_orden_id])."</td>";
								  }
								  $num_cumplimiento=$this->ConvierteCumplimiento($vector3[$i][fecha_cumplimiento],$vector3[$i][numero_cumplimiento],$_SESSION['LABORATORIO']['DPTO']);
								  $this->salida.="  <td  align=\"center\"><label class='label_mark'>$num_cumplimiento</td>";
								}
								else
								{
								  if($_SESSION['LABORATORIO']['SW_HONORARIO']==1)
								  {$this->salida.="  <td  align=\"center\" >".$this->TraerEspecialista($vector3[$i][numero_orden_id])."</td>";}
								  $this->salida.="  <td   align=\"center\"><label class='label_mark'>VENCIDO</label></td>";
								  $this->salida.="  <td align='center'><img src=\"".GetThemePath()."/images/delete.gif\"></td>";
								}
							  
								if($vector3[$i][os_tipo_solicitud_id]=='CIT')//quiere decir q es una cita..
							    {
									$this->salida.="  <td><label class='label_mark'><label class='label_mark'>CITA</label></label></td>";
							    }
							    else
							   {
								  $this->salida.="<td class='$estilo' align=\"center\"><input type=\"checkbox\" value=\"".$vector3[$i][numero_orden_id]."\" name=\"sel[]\" onclick=Pintartd('$color','$i',this.checked)></td>";
							   }
								$this->salida.="</tr>";
							}
							else
							{	
								$i_nuevo=$i+1+$s;
								if( $i_nuevo % 2){ $estilo='modulo_list_claro';$color='#DDDDDD';}
								else {$estilo='modulo_list_oscuro';$color='#CCCCCC';}

								$this->salida.="<tr id=$i_nuevo class='$estilo'>";
								$this->salida.="  <td  align=\"center\" >".$cantidad_cargo2[$s][numero_orden_id]."</td>";
								$this->salida.="  <td  align=\"center\" >".$cantidad_cargo2[$s][cantidad]."</td>";
								$this->salida.="  <td  align=\"center\" >".$cantidad_cargo2[$s][cargo_cups]."</td>";
								$this->salida.="  <td  align=\"center\" >".$cantidad_cargo2[$s][descripcion]."</td>";
								$this->salida.="  <td  align=\"center\" >".substr($vector3[$i][fecha_registro], 0, -7)."</td>";
								$this->salida.="  <td  align=\"center\" >".$dptoTomado[0][descripcion]."</td>";
								
								if(strtotime($arr_fecha[0]) >= strtotime(date("Y-m-d")))
								{
									$this->salida.="  <td   align=\"center\" >$arr_fecha[0]</td>";
									if($_SESSION['LABORATORIO']['SW_HONORARIO']==1)
									{	
										$this->salida.="  <td  align=\"center\" >".$this->TraerEspecialista($vector3[$i][numero_orden_id])."</td>";
									}
									$num_cumplimiento=$this->ConvierteCumplimiento($vector3[$i][fecha_cumplimiento],$vector3[$i][numero_cumplimiento],$_SESSION['LABORATORIO']['DPTO']);
									$this->salida.="  <td  align=\"center\"><label class='label_mark'>$num_cumplimiento</td>";
								}
								else
							   {
								  if($_SESSION['LABORATORIO']['SW_HONORARIO']==1)
								  {$this->salida.="  <td  align=\"center\" >".$this->TraerEspecialista($vector3[$i][numero_orden_id])."</td>";}
								  $this->salida.="  <td   align=\"center\"><label class='label_mark'>VENCIDO</label></td>";
								  $this->salida.="  <td align='center'><img src=\"".GetThemePath()."/images/delete.gif\"></td>";
							   }
                      
								if($vector3[$i][os_tipo_solicitud_id]=='CIT')//quiere decir q es una cita..
								{
									$this->salida.="  <td><label class='label_mark'><label class='label_mark'>CITA</label></label></td>";
								}
								else
								{
									$this->salida.="<td class='$estilo' align=\"center\"><input type=\"checkbox\" value=\"".$vector3[$i][numero_orden_id]."\" name=\"sel[]\" onclick=Pintartd('$color','$i_nuevo',this.checked)></td>";
								}
								$this->salida.="</tr>";
							}
						}
					  }
					  /*echo '<pre>';
					  print_r ($cantidad_cargo2);
					  echo '</pre>';*/
					  
					  //CANTIDAD 2
                      //$this->salida.="  <td  align=\"center\" >".$vector3[$i][cantidad]."</td>";
					  /* if(count($cantidad_cargo2[$i][cantidad])<1)
					  {
						$this->salida.="  <td  align=\"center\" >".$vector3[$i][numero_orden_id]."</td>";
						$this->salida.="  <td  align=\"center\" >".$vector3[$i][cantidad]."</td>";
						$this->salida.="  <td  align=\"center\" >".$vector3[$i][cargoi]."</td>";
						$this->salida.="  <td  align=\"center\" >".$vector3[$i][des1]."</td>";
						$this->salida.="  <td  align=\"center\" >".substr($vector3[$i][fecha_registro], 0, -7)."</td>";
						$this->salida.="  <td  align=\"center\" >".$dptoTomado[0][descripcion]."</td>";
					  }
					  else
					  {
						$this->salida.="  <td  align=\"center\" >".$cantidad_cargo2[$i][cantidad]."</td>";
						$this->salida.="  <td  align=\"center\" >".$cantidad_cargo2[$i][cantidad]."</td>";
						$this->salida.="  <td  align=\"center\" >".$cantidad_cargo2[$i][cantidad]."</td>";
						$this->salida.="  <td  align=\"center\" >".$cantidad_cargo2[$i][cantidad]."</td>";
						$this->salida.="  <td  align=\"center\" >".$cantidad_cargo2[$i][cantidad]."</td>";
						$this->salida.="  <td  align=\"center\" >".$cantidad_cargo2[$i][cantidad]."</td>";
					  } */
					  //$this->salida.="  <td  align=\"center\" >".$vector3[$i][cantidad]."</td>";
					  
                      /* $this->salida.="  <td  align=\"center\" >".$vector3[$i][cargoi]."</td>";
                      $this->salida.="  <td  align=\"center\" >".$vector3[$i][des1]."</td>";
					  $this->salida.="  <td  align=\"center\" >".substr($vector3[$i][fecha_registro], 0, -7)."</td>";
					  $this->salida.="  <td  align=\"center\" >".$dptoTomado[0][descripcion]."</td>"; */

                      /* if(strtotime($arr_fecha[0]) >= strtotime(date("Y-m-d")))
                      {
                          $this->salida.="  <td   align=\"center\" >$arr_fecha[0]</td>";
                          if($_SESSION['LABORATORIO']['SW_HONORARIO']==1)
                          {	$this->salida.="  <td  align=\"center\" >".$this->TraerEspecialista($vector3[$i][numero_orden_id])."</td>";}
                            $num_cumplimiento=$this->ConvierteCumplimiento($vector3[$i][fecha_cumplimiento],$vector3[$i][numero_cumplimiento],$_SESSION['LABORATORIO']['DPTO']);
                            $this->salida.="  <td  align=\"center\"><label class='label_mark'>$num_cumplimiento</td>";
                      }
                      else
                      {
                          if($_SESSION['LABORATORIO']['SW_HONORARIO']==1)
                          {$this->salida.="  <td  align=\"center\" >".$this->TraerEspecialista($vector3[$i][numero_orden_id])."</td>";}
                          $this->salida.="  <td   align=\"center\"><label class='label_mark'>VENCIDO</label></td>";
                          $this->salida.="  <td align='center'><img src=\"".GetThemePath()."/images/delete.gif\"></td>";
                      }
                      
                      if($vector3[$i][os_tipo_solicitud_id]=='CIT')//quiere decir q es una cita..
                      {
                          $this->salida.="  <td><label class='label_mark'><label class='label_mark'>CITA</label></label></td>";
                      }
                      else
                      {
                          $this->salida.="<td class='$estilo' align=\"center\"><input type=\"checkbox\" value=\"".$vector3[$i][numero_orden_id]."\" name=\"sel[]\" onclick=Pintartd('$color','$i',this.checked)></td>";
                      }
                          $this->salida.="</tr>"; */


                      if($sw_imprimir==1)
                      {
                          $accion1=ModuloGetURL('app','Os_Atencion','user','ReporteFichaLaboratorio',array('numero'=>$numero['numero_cumplimiento'],'fecha_cumplimiento'=>$numero['fecha_cumplimiento'],'numero_orden_id'=>$vector3[$i][numero_orden_id],'tipoid'=>$tipo,'id'=>$id,'nom'=>$nom));
                          if($_SESSION['LABORATORIO']['SW_HONORARIO']==1)
                          {$numero=10;}else{$numero=9;}

                          if($vector3[$i][os_tipo_solicitud_id]=='CIT')//quiere decir q es una cita..
                          {
                                  $this->salida.="  <tr class='$estilo'><td  colspan='$numero' align=\"center\"><label class='label_mark'>CITA</label></td></tr>";
                          }
                          else
                          {
                              $this->salida.="  <tr class='$estilo'><td  colspan='$numero' align=\"center\"><input type='submit' class='input-submit' name='imp' value='imprimir'></td></tr>";
                          }
                      }

                      if($i==sizeof($vector3)-1)
                      {
                          if($_SESSION['LABORATORIO']['SW_HONORARIO']==1)
                          {$numero=10;}else{$numero=9;}
                          if($vector3[$i][os_tipo_solicitud_id]=='CIT')//quiere decir q es una cita..
                          {
                              $this->salida.="  <tr class='$estilo'><td  colspan='$numero' align=\"center\"><label class='label_mark'>CITA</label></td></tr>";
                          }
                          else
                          {
                              $this->salida.="  <tr class='$estilo'><td  colspan='$numero' align=\"center\"><input type='submit' class='input-submit' name='imp' value='imprimir'></td></tr>";
                          }
                          $this->salida.="</form>";

                      }

              }
              $this->salida.="</table>";
              unset($_SESSION['OS_ATENCION']['NUMERO_C']);
      }
      //nueva tabla de cargos realizados en la atención no cargados a una cuenta quedando pendiente por cobrar

        echo "SESSION**:<pre>";
        print_r($_SESSION);
        echo "</pre>";

						//CASO ESPECIAL DE LAS ORDENES DE SERVICIO
						$vector=$this->TraerOrdenesServicio_Especiales($tipo,$id); //sacamos las ordenes de sevicio que desea pagar.
						for($i=0;$i<sizeof($vector);)
						{
										$k=$i;
										if($vector[$i][plan_id]==$vector[$k][plan_id]
										AND $vector[$i][tipo_afiliado_id]==$vector[$k][tipo_afiliado_id]
										AND $vector[$i][rango]==$vector[$k][rango]
										AND $vector[$i][orden_servicio_id]==$vector[$k][orden_servicio_id])
										{
										$this->salida.="<BR><table  align=\"center\" border=\"0\" width=\"80%\">";
										$this->salida.="<tr class=\"modulo_table_title\">";
										$this->salida.="  <td align=\"left\" colspan=\"7\">CARGOS REALIZADOS EN CONSULTA PENDIENTES POR FACTURAR</td>";
										$this->salida.="</tr>";
										$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
										$this->salida.="  <td width=\"7%\">ORDEN</td>";
										$this->salida.="  <td width=\"8%\">ITEM</td>";
										$this->salida.="  <td width=\"10%\">CANTIDAD</td>";
										$this->salida.="  <td width=\"10%\">CARGO</td>";
										$this->salida.="  <td width=\"40%\">DESCRIPCION</td>";
										$this->salida.="  <td width=\"20%\">VENCIMIENTO</td>";
										$this->salida.="  <td width=\"8%    \"></td>";
										$this->salida .= "           <form name=\"formdita\" action=\"".ModuloGetURL('app','Os_Atencion','user','FormaCargosEquivalentes',array('id_tipo'=>$tipo,'nom'=>urlencode($nom),'id'=>$id,'plan_id'=>$vector[$k][plan_id]))."\" method=\"post\">";
										$this->salida.="</tr>";
										}
										while($vector[$i][plan_id]==$vector[$k][plan_id]
										AND $vector[$i][tipo_afiliado_id]==$vector[$k][tipo_afiliado_id]
										AND $vector[$i][rango]==$vector[$k][rango]
										AND $vector[$i][servicio]==$vector[$k][servicio])
										{
                        $color="#DDDDDD";
                        $this->salida.="<tr class='modulo_list_claro'>";
                        $this->salida.="  <td  class=\"hc_table_submodulo_list_title\" width=\"7%\">".$vector[$k][orden_servicio_id]."</td>";
                        $this->salida.="  <td colspan=\"6\">";
                        $this->salida.="  <table align=\"center\" border=\"1\" width=\"100%\">";
                        $l=$k;
                        while($vector[$k][orden_servicio_id]==$vector[$l][orden_servicio_id]
                        AND $vector[$k][plan_id]==$vector[$l][plan_id]
                        AND $vector[$k][tipo_afiliado_id]==$vector[$l][tipo_afiliado_id]
                        AND $vector[$k][rango]==$vector[$l][rango]
                        AND $vector[$k][servicio]==$vector[$l][servicio])
												{
																		$vecimiento=$vector[$l][fecha_vencimiento];
																		$arr_fecha=explode(" ",$vecimiento);
																		if( $l % 2){ $estilo='modulo_list_claro';}
																		else {$estilo='modulo_list_oscuro';}
																		$this->salida.="<tr align='center' class=$estilo >";
																		$this->salida.="  <td align='center'   width=\"8%\"<label class='label_mark'>".$vector[$l][numero_orden_id]."</label></td>";
																		$this->salida.="  <td colspan=5>";
																		$this->salida.="  <table align=\"center\" border=\"0\" width=\"100%\">";
																		$m=$l;
																		while($vector[$l][numero_orden_id]==$vector[$m][numero_orden_id]
																		AND $vector[$l][orden_servicio_id]==$vector[$m][orden_servicio_id]
																		AND $vector[$l][plan_id]==$vector[$m][plan_id]
																		AND $vector[$l][tipo_afiliado_id]==$vector[$m][tipo_afiliado_id]
																		AND $vector[$l][rango]==$vector[$m][rango]
																		AND $vector[$l][servicio]==$vector[$m][servicio])
																		{
																						$this->salida.="<tr class=$estilo id=l$k>";
																						$this->salida.="  <td width=\"10%\" align=\"center\" >".$vector[$m][cantidad]."</td>";
																						$this->salida.="  <td width=\"14%\" align=\"center\" >".$vector[$m][cargoi]."</td>";
																						$this->salida.="  <td width=\"42%\">".$vector[$m][des1]."</td>";

																						if(strtotime($arr_fecha[0]) >= strtotime(date("Y-m-d")))
																						{
																								$color="#CCCCCC";
                                                $this->salida.="<td width=\"26%\" align=\"center\" >$arr_fecha[0]</td>";

                                                $this->salida.="<td width=\"15%\" align=\"center\" >";
                                                $this->salida.="<table align=\"center\" border=\"1\" width=\"10%\" height=\"10%\">";
                                                $this->salida.="<tr class=$estilo>";
                                                $citas = $this->Revision_Cita($vector[$m][numero_orden_id],$vector[$m][cargoi]);
                                                $this->salida.="<td colspan='2' width=\"15%\" align=\"center\"><input type=checkbox name=op[$m] value=".$vector[$m][numero_orden_id].",".$vector[$m][cargo].",".$vector[$m][tarifario_id].",".$vector[$m][autorizacion_ext].",".$vector[$m][autorizacion_int].",".$vector[$m][cantidad].",".urlencode($vector[$m][descargo]).",".$vector[$m][servicio].",".$vector[$m][serv_des].",".$vector[$k][orden_servicio_id]." onclick=Pintartd('$color','l$k',this.checked)></td>";
                                                $this->salida.="</tr>";
                                                $this->salida.="</table>";
																								$this->salida.="</td>";
																						}
																						else
																						{
																								$this->salida.="  <td width=\"26%\" align=\"center\" ><label class='label_mark'>VENCIDO</label></td>";
																								$this->salida.="  <td><img src=\"".GetThemePath()."/images/delete.gif\"></td>";
																						}
																						$this->salida.="</tr>";
																						$m++;
																		}
																		$this->salida.="</table>";
																		$this->salida.="</td>";
																		$this->salida.="</tr>";
																		$l=$m;
												}
												$info=$this->TraerInformacion_Medico($vector[$k][hc_os_solicitud_id]);

												if(!is_array($info))
												{
														$info='NO SE ENCONTRO INFORMACIÓN DE LA ATENCIÓN';
														$this->salida.="<tr><td colspan='8' align=\"center\">";
														$this->salida.="<table width='100%' border='0' cellpadding='2' align=\"center\">";
														$this->salida.="<tr rowspan='4'><td width='20%' class=\"hc_table_submodulo_list_title\" >OBSERVACION</td><td class='modulo_list_claro'><label class='label_mark'>$info</label></td></tr>";
														$this->salida.="</table>";
														$this->salida.="</td></tr>";
												}
												else
												{
														$fecha_x=explode(".",$info[0][fecha]);
														$this->salida.="<tr><td colspan='8' align=\"center\">";
														$this->salida.="<table width='100%' border='1' cellpadding='2' align=\"center\">";
														$this->salida.="<tr><td width='20%' class=\"hc_table_submodulo_list_title\" >No EVOLUCION :</td><td width='80%' class='modulo_list_oscuro'>".$info[0][evolucion_id]."</td></tr>";
														$this->salida.="<tr><td width='20%' class=\"hc_table_submodulo_list_title\" >FECHA EVOLUCION : </td><td width='80%' class='modulo_list_claro'>".$fecha_x[0]."</td></tr>";
														$this->salida.="<tr><td width='20%' class=\"hc_table_submodulo_list_title\" >MEDICO :</td><td width='80%' class='modulo_list_oscuro'><label class='label_mark'>".$info[0][nombre]."</label></td></tr>";
														$this->salida.="<tr><td width='20%' class=\"hc_table_submodulo_list_title\" >DEPARTAMENTO :</td><td width='80%' class='modulo_list_oscuro'>".$info[0][descripcion]."</td></tr>";
														$this->salida.="</table>";
														$this->salida.="</td></tr>";
												}
												$this->salida.="</table>";
												$this->salida.="</td>";
												$this->salida.="</tr>";
												$k=$l;
								}
								$this->salida.="</table>";
								$this->salida.="<table align='center' width='80%'>";
								$this->salida.="<tr align='right' class=\"modulo_table_button\">";

								//este switche $_SESSION['LABORATORIO']['SW_ESTADO'] si esta en 1 es por que solo
								//esta habilitado para cumplimiento...
								if($_SESSION['LABORATORIO']['SW_ESTADO']==1)
								{
												//este switche $vector[$i][sw_cuenta]>=1
												//permite ir a cargar la cuenta en caso de
												//q venga en 1 o > 1, mejor dicho es por q tiene una cuenta....
												if($vector[$i][sw_cuenta]>=1 AND $vector[$i][sw_cargo_multidpto]=='1')
												{

														$this->salida.="<td><input class=\"input-submit\" type=submit name=mandar$l value=Cumplir-1></td>";
												}
												else
												{
														$this->salida.="<td>&nbsp;</td>";
												}
								}
								//este switche $_SESSION['LABORATORIO']['SW_ESTADO'] si esta en 0 es por
								//q esta habilitado para que se pague en caja ...

								elseif($_SESSION['LABORATORIO']['SW_ESTADO']==0)
								{
												if($vector[$i][sw_cuenta]>=1 AND $vector[$i][sw_cargo_multidpto]=='1')
												{
														$this->salida.="<td><input class=\"input-submit\" type=submit name=mandar$l value=Cumplir-2></td>";
												}
												else
												{
														$this->salida.="<td><input class=\"input-submit\" type=submit name=mandar$l value=Cumplir-3></td>";
												}
								}

								$this->salida.="</form>";
								$this->salida.="</tr>";
								$this->salida.="</table>";
								$i=$k;
						}

			//sacamos las ordenes de sevicio que desea pagar.
			//EL SIGUIENTE CICLO FUNCIONA CUANDO TRAE LAS ORDENES DE SERVICIO QUE
			//ESTAN PENDIENTES DE PAGO POR LA CAJA O PARA CARGAR A LA CUENTA
      $vector=$this->TraerOrdenesServicio($tipo,$id); 
			for($i=0;$i<sizeof($vector);)
			{
        $k=$i;
        if($vector[$i][plan_id]==$vector[$k][plan_id]
        AND $vector[$i][tipo_afiliado_id]==$vector[$k][tipo_afiliado_id]
        AND $vector[$i][rango]==$vector[$k][rango]
        AND $vector[$i][orden_servicio_id]==$vector[$k][orden_servicio_id])
        {
          $dptos = $this->ObtenerDepartamentosPuntoTomado($vector[$k]['orden_servicio_id']);
          $this->salida .= "<br>\n";
          $this->salida .= "<form name=\"formita\" action=\"".ModuloGetURL('app','Os_Atencion','user','FormaCargosEquivalentes',array('id_tipo'=>$tipo,'nom'=>urlencode($nom),'id'=>$id,'plan_id'=>$vector[$k][plan_id]))."\" method=\"post\">";
          if(empty($dptos))
          {
            $this->salida .= "  <input type=\"hidden\" name=\"departamento_pt\" value=\"".$_SESSION['LABORATORIO']['DPTO']."\">\n";
            $this->salida .= "  <input type=\"hidden\" name=\"id_orden_servicio\" value=\"".$vector[$k]['orden_servicio_id']."\">\n";
          }
          $this->salida .= "  <table  align=\"center\" border=\"0\" width=\"80%\">";
          $this->salida .= "    <tr class=\"modulo_table_list_title\">";
          $this->salida .= "      <td align=\"left\" colspan=\"9\">\n";
          $this->salida .= "        PLAN&nbsp;&nbsp;".$vector[$i][descripcion]."&nbsp;&nbsp;".$vector[$i][plan_descripcion]."\n";
          $this->salida .= "      </td>\n";
          $this->salida .= "    </tr>\n";
          if(!empty($dptos))
          {
            //$seleccionados
            $this->salida .= "    <tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "      <td colspan=\"5\" align=\"left\">PUNTO DE TOMADO</td>\n";
            $this->salida .= "      <td colspan=\"5\" align=\"left\" class=\"modulo_list_claro\">\n";
            $this->salida .= "        <input type=\"hidden\" name=\"id_orden_servicio\" value=\"".$vector[$k]['orden_servicio_id']."\">\n";
            $this->salida .= "        <select name=\"departamento_pt\" class=\"select\">\n";
            $this->salida .= "          <option value=\"-1\">---SELECCIONAR---</option>\n";
            foreach($dptos as $key => $dtl)
            {
              $chk = "";
              if($puntoatencion == $dtl['departamento'])
                $chk = "selected";
              else if(!$puntoatencion && $dtl['sw_defecto'] == '1')
                $chk = "selected";
              $this->salida .= "          <option value=\"".$dtl['departamento']."\" ".$chk.">".$dtl['descripcion']."</option>\n";
            }
            $this->salida .= "        </select>\n";
            $this->salida .= "      </td>\n";
            $this->salida .= "    </tr>\n";
          }
          $this->salida .= "    <tr class=\"hc_table_submodulo_list_title\">";
          $this->salida .= "      <td width=\"5%\">ORDEN</td>";
          $this->salida .= "      <td width=\"5%\">ITEM</td>";
          $this->salida .= "      <td width=\"5%\">CANT.</td>";
          $this->salida .= "      <td width=\"5%\">CARGO</td>";
		  $this->salida .= "      <td width=\"10%\">EQ. DATALAB</td>";
          $this->salida .= "      <td width=\"30%\">DESCRIPCION</td>";
		  $this->salida .= "      <td width=\"22%\">OBSERVACIONES</td>";
          $this->salida .= "      <td width=\"10%\">VENCIMIENTO</td>";
          $this->salida .= "      <td width=\"8%\">OPCION</td>";
          $this->salida .= "    </tr>\n";
        }
        while($vector[$i][plan_id]==$vector[$k][plan_id]
        AND $vector[$i][tipo_afiliado_id]==$vector[$k][tipo_afiliado_id]
        AND $vector[$i][rango]==$vector[$k][rango]
        AND $vector[$i][servicio]==$vector[$k][servicio])
        {
          $this->salida .= "    <tr class='modulo_list_claro'>";
          $this->salida .= "      <td  class=\"hc_table_submodulo_list_title\" width=\"5%\">".$vector[$k][orden_servicio_id]."</td>";
          $this->salida .= "      <td colspan=\"8\">";
          $this->salida .= "        <table align=\"center\" border=\"1\" width=\"100%\">";
          
		  
          $l=$k;
          while($vector[$k][orden_servicio_id]==$vector[$l][orden_servicio_id]
                    AND $vector[$k][plan_id]==$vector[$l][plan_id]
                    AND $vector[$k][tipo_afiliado_id]==$vector[$l][tipo_afiliado_id]
                    AND $vector[$k][rango]==$vector[$l][rango]
                    AND $vector[$k][servicio]==$vector[$l][servicio])
          {
            $vecimiento=$vector[$l][fecha_vencimiento];
            $arr_fecha=explode(" ",$vecimiento);
            if( $l % 2){ $estilo='modulo_list_claro';}
            else {$estilo='modulo_list_oscuro';}
            $this->salida .= "          <tr align='center'>";
            $this->salida .= "            <td align='center' class=$estilo width=\"8%\"<label class='label_mark'>".$vector[$l][numero_orden_id]."</label></td>";
            $this->salida .= "              <td colspan=5>";
            $this->salida .= "                <table align=\"center\" border=\"0\" width=\"100%\">";
            $m=$l;
			
			//TIPOS SOLICITUD y OBSERVACIONES ITEM
			$tiposolicitud= $this->TraerOsTipoSolicitud($vector[$l][numero_orden_id]);
			/* echo'<pre>';
			print_r($l." numero_orden_id: ".$tiposolicitud[0][numero_orden_id]."<br> os_tipo_solicitud_id:".$tiposolicitud[0][os_tipo_solicitud_id]."<br>");
			echo'</pre>'; */
			$observaciones_item= $this->TraerObservaciones($tiposolicitud[0][os_tipo_solicitud_id],$tiposolicitud[0][hc_os_solicitud_id]);
			//print_r ("Observacion: ".$observaciones_item[0][observacion]."<br>");
			
            while($vector[$l][numero_orden_id]==$vector[$m][numero_orden_id]
            AND $vector[$l][orden_servicio_id]==$vector[$m][orden_servicio_id]
            AND $vector[$l][plan_id]==$vector[$m][plan_id]
            AND $vector[$l][tipo_afiliado_id]==$vector[$m][tipo_afiliado_id]
            AND $vector[$l][rango]==$vector[$m][rango]
            AND $vector[$l][servicio]==$vector[$m][servicio])
            {
			  $equivalencia = $this->TraerEquivalencia($vector[$m][cargoi]);
              $this->salida.="<tr class=$estilo>";
              $this->salida.="  <td width=\"5%\" align=\"center\" >".$vector[$m][cantidad]."</td>";
              $this->salida.="  <td width=\"5%\" align=\"center\" >".$vector[$m][cargoi]."</td>";
			  
			  if(!empty($equivalencia[0][codigo_datalab]))
			  {
				$this->salida.="  <td width=\"10%\" align=\"center\" >".$equivalencia[0][codigo_datalab]."</td>";
			  }
              else
              {
                  $this->salida.="  <td width=\"10%\" align=\"center\" bgcolor=\"RED\"> <B>NO ESTA</B></td>";
              }

			  
              $this->salida.="  <td width=\"30%\">".$vector[$m][des1]."</td>";
			  $this->salida.="  <td width=\"22%\"> ".$observaciones_item[0][observacion]."</td>";
			  
              if(strtotime($arr_fecha[0]) >= strtotime(date("Y-m-d")))
              {
                $ck = (!empty($seleccionados[$m]))? "checked":"";
                
                $this->salida.="<td width=\"10%\" align=\"center\" >$arr_fecha[0]</td>";
                $this->salida.="<td width=\"8%\" align=\"center\" >";
				
                $this->salida.="<table align=\"center\" border=\"0\" width=\"100%\">";
                $this->salida.="<tr class=$estilo>";
                $this->salida.="<td width=\"8%\" align=\"center\"><input type=checkbox name=op[$m] ".$ck." value=".$vector[$m][numero_orden_id].",".$vector[$m][cargo].",".$vector[$m][tarifario_id].",".$vector[$m][autorizacion_ext].",".$vector[$m][autorizacion_int].",".$vector[$m][cantidad].",".urlencode($vector[$m][descargo]).",".$vector[$m][servicio].",".$vector[$m][serv_des].",".$vector[$k][orden_servicio_id]."></td>";

                $this->salida.="</tr>";
                $this->salida.="</table>";
                $this->salida.="</td>";
              }
              else
              {
                  $this->salida.="  <td width=\"26%\" align=\"center\" ><label class='label_mark'>VENCIDO</label></td>";
                  $this->salida.="  <td><img src=\"".GetThemePath()."/images/delete.gif\"></td>";
              }
              $this->salida.="</tr>";
              $m++;
            }
            $this->salida.="</table>";
            $this->salida.="</td>";
            $this->salida.="</tr>";
            $l=$m;
          }
                //parte de alex.
                $this->salida.="<tr><td colspan='8' align=\"center\">";
                $this->salida.="<table width='100%' border='0' cellpadding='2' align=\"center\">";
                $this->salida.="<tr><td width='20%' class=\"hc_table_submodulo_list_title\" >OBSERVACION</td><td class='modulo_list_claro'>".$vector[$k][observacion]."</td></tr>";
                $this->salida.="<tr><td width='20%' class=\"hc_table_submodulo_list_title\" >SERVICIO</td><td width='80%' class='modulo_list_oscuro'>".$vector[$k][serv_des]."</td></tr>";
                $this->salida.="<tr><td width='20%' class=\"hc_table_submodulo_list_title\" >AUT. INT.</td><td width='80%' class='modulo_list_claro'>".$vector[$k][autorizacion_int]."</td></tr>";
                $this->salida.="<tr><td width='20%' class=\"hc_table_submodulo_list_title\" >AUT. EXT.</td><td width='80%' class='modulo_list_oscuro'>".$vector[$k][autorizacion_ext]."</td></tr>";
                $this->salida.="<tr><td width='20%' class=\"hc_table_submodulo_list_title\" >AFILIACION</td><td width='80%' class='modulo_list_oscuro'>".$vector[$k][tipo_afiliado_nombre]."</td></tr>";
				$this->salida.="<tr><td width='20%' class=\"hc_table_submodulo_list_title\" >FECHA DE LA ORDEN</td><td width='80%' class='modulo_list_oscuro'>".substr($vector[$k][fecha_registro], 0, -7)."</td></tr>";

                $this->salida.="</table>";
                $this->salida.="</td></tr>";
                //parte de alex.

                $this->salida.="</table>";
                $this->salida.="</td>";
                $this->salida.="</tr>";
                $k=$l;
        }
        $this->salida.="</table>";
        $this->salida.="<table align='center' width='80%'>";
        $this->salida.="<tr align='right' class=\"modulo_table_button\">";

        //este switche $_SESSION['LABORATORIO']['SW_ESTADO'] si esta en 1 es por que solo
        //esta habilitado para realizar el cumplimiento de la orden...
        
        if($_SESSION['LABORATORIO']['SW_ESTADO']==1)
        {
          //este switche $vector[$i][sw_cuenta]>=1
          //permite ir a cargar la cuenta en caso de
          //q venga en 1 o > 1, mejor dicho es por q tiene una cuenta....
          //$vector[$i][sw_cargo_multidpto]=='1' es por si es de 'servicio' hospitalario..
          if($vector[$i][sw_cuenta]>=1 AND $vector[$i][sw_cargo_multidpto]=='1')
          {
              $this->salida.="<td><input class=\"input-submit\" type=submit name=mandar$l value=Cumplir-4></td>";
          }
          else
          {
              $this->salida.="<td>&nbsp;</td>";
          }
        }
        //este switche $_SESSION['LABORATORIO']['SW_ESTADO'] si esta en 0 es por
        //q esta habilitado para que se pague en caja ...

        elseif($_SESSION['LABORATORIO']['SW_ESTADO']==0)
        {
                if($vector[$i][sw_cuenta]>=1 AND $vector[$i][sw_cargo_multidpto]=='1')
                {
                    $this->salida.="<td><input class=\"input-submit\" type=submit name=mandar$l value=Cumplir-5></td>";
                }
                else
                {
                    $this->salida.="<td><input class=\"input-submit\" type=submit name=mandar$l value=Cumplir-6></td>";
                }
        }

        $this->salida.="</form>";
        $this->salida.="</tr>";
        $this->salida.="</table>";
        $i=$k;
			}

            if(EMPTY($_SESSION['LABORATORIO']['CAJARAPIDA']))
            {
                    $_SESSION['OS_ATENCION']['CARGARFILTRO']=TRUE;
                    $this->salida .= "<br><br><table align=\"center\" width='20%' border=\"0\">";
                    $action2=ModuloGetURL('app','Os_Atencion','user','FormaMetodoBuscar',array("uid"=>$uid,'nombre'=>urldecode($NombreUsuario),'usuario'=>$Usuario,"empID"=>$empresa,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of']));
                    $this->salida .= "           <form name=\"formados\" action=\"$action2\" method=\"post\">";
                    $this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
                    $this->salida .= "</tr>";
                    $this->salida .= "</table><br>";
            }
            $this->salida .= ThemeCerrarTabla();
            return true;
    }
    /**
    * Esta funcion permite al usuario verificar los cargos liquidados y revisar
    * si tiene una cuenta activa o no para tener la opcion de cargarla a la cuenta.
    * @return boolean
    */
    function LiquidacionOrden($vector,$nom,$tipo,$id,$op,$PlanId,$vector_des='',$descuentos_cargos='',$dpto)
    {
      if(!empty($nom))
      {
        $_SESSION['OS_ATENCION']['vector']=$vector;
        $_SESSION['OS_ATENCION']['nom']=$nom;
        $_SESSION['OS_ATENCION']['tipo']=$tipo;
        $_SESSION['OS_ATENCION']['id']=$id;
        $_SESSION['OS_ATENCION']['op']=$op;
        $_SESSION['OS_ATENCION']['PlanId']=$PlanId;
        $_SESSION['OS_ATENCION']['departamento_pt'] = $dpto;
        $_SESSION['OS_ATENCION']['vector_des']=$vector_des;
        $_SESSION['OS_ATENCION']['descuentos_cargos']=$descuentos_cargos;
        $_SESSION['OS_ATENCION']['arr']=$_REQUEST['arr'];
      }
      else
      {
        $vector=$_SESSION['OS_ATENCION']['vector'];
        $nom=$_SESSION['OS_ATENCION']['nom'];
        $tipo=$_SESSION['OS_ATENCION']['tipo'];
        $id=$_SESSION['OS_ATENCION']['id'];
        $op=$_SESSION['OS_ATENCION']['op'];
        $PlanId=$_SESSION['OS_ATENCION']['PlanId'];
        $dpto = $_SESSION['OS_ATENCION']['departamento_pt'];
        $vector_des=$_SESSION['OS_ATENCION']['vector_des'];
        $descuentos_cargos = $_SESSION['OS_ATENCION']['descuentos_cargos'];
        $_REQUEST['arr']=$_SESSION['OS_ATENCION']['arr'];
      }
      unset($_SESSION['CAJA']['OTRAVEZ']);//variable q coloca el valor por defecto q tiene q pagar a
      unset($_SESSION['CAJA']['ARRAY_PAGO']);//arreglo q contiene los  cargos..
      unset($_SESSION['CAJA']['liq']);
      unset($_SESSION['CAJA']['datos']);
      unset($_SESSION['CAJA']['vector']);
      unset($_SESSION['CAJA']['AUX']['vector']);
      unset($_SESSION['CAJA']['AUX']['liq']);
      unset($_SESSION['CAJA']['AUX']['datos']);
      unset($_SESSION['ARREGLO_CITAS_INCUMPLIDAS']);//vector q  contiene el listado de las citas incumplidas....
      unset($_SESSION['VECTOR_DESC']);//vector que tiene los descuentos de cuota moderadora o copago.
      unset($_SESSION['ARREGLO_IYM']);//vector con los cargos de inventarios agregados
      unset($_SESSION['CAJA']['VALORCOPAGO']);
      unset($_SESSION['CAJA']['VALORCUOTAMODERADORA']);

      IncludeLib("tarifario_cargos");
      IncludeLib('funciones_facturacion');
      $Cuenta=0;
      $nom=urldecode($nom);
      $this->salida.= ThemeAbrirTabla('LIQUIDACION ORDEN DE SERVICIOS MEDICOS ');
      $this->Encabezado($width='98%');
      $this->salida .= "<BR>\n";
      $this->salida .= "<table  cellspacing=\"2\"  cellpadding=\"3\"border=\"0\" width=\"80%\" align=\"center\" >";
      $this->salida .= "  <tr>\n";
      $this->salida .= "    <td class=\"modulo_table_title\" width=\"20%\">NOMBRE PACIENTE: </td>\n";
      $this->salida .= "    <td class=\"modulo_list_claro\" align=\"left\">".$nom."</td>\n";
      $this->salida .= "  </tr>";
      $this->salida .= "  <tr>\n";
      $this->salida .= "    <td class=\"modulo_table_title\" class=\"label\" width=\"25%\" align=\"left\">IDENTIFICACION: </td>\n";
      $this->salida .= "    <td class=\"modulo_list_claro\" align=\"left\">".$tipo."&nbsp;".$id."</td>\n";
      $this->salida .= "  </tr>";
      $this->salida .= "</table>\n";
      $this->salida .= "<BR>";
      //$this->salida.="<BR><table  align=\"center\" border=\"2\"  width=\"90%\">";
      //ENTRA CUANDO EL PACIENTE TIENE CUENTA CREADA
      if($vector)
      {
        $sw_hay_cuenta=true;//este swiche me indica si hubo  no cuenta, asi determino como liquido
        //el cargo con cuenta o sin cuenta.
        $this->salida .= "<BR>\n";
        $this->salida .= "<table align=\"center\" bordercolor='#4D6EAB' border=\"1\"  width=\"80%\">";
        $this->salida .= "  <tr class=\"modulo_table_list_title\">";
        $this->salida .= "    <td align=\"left\" colspan=\"5\">CUENTA&nbsp;No.&nbsp;".$vector[0][numerodecuenta]."&nbsp;&nbsp;".$vector[$i][plan_descripcion]."</td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr class=\"modulo_table_list_title\">";
        $this->salida .= "    <td width=\"20%\">PLAN</td>";
        $this->salida .= "    <td width=\"10%\">TOTAL CUENTA</td>";
        $this->salida .= "    <td width=\"20%\">SERVICIO</td>";
        $this->salida .= "    <td width=\"10%\">SALDO</td>";
        $this->salida .= "    <td width=\"20%\"></td>";
        $this->salida .= "  </tr>";
        $Cuenta=$vector[0][numerodecuenta];
        $Ingreso=$vector[0][ingreso];
        $_SESSION['OS_ATENCION']['cuenta']=$Cuenta;
        $_SESSION['OS_ATENCION']['ingreso']=$Ingreso;
        for($i=0;$i<sizeof($vector);$i++)
        {
          //**************************
          $accion=ModuloGetURL("app","Os_Atencion","user","InsertarCargoCuenta",array("cuenta"=>$Cuenta,"op"=>$op,"plan2"=>$PlanId,"tipo_id"=>$tipo,"pac"=>$id,"nom"=>$nom,"departamento_pt"=>$dpto));
          $js = "<SCRIPT>";
          $js .= "  function OcultarCargarCuenta()\n";
          $js .= "  { \n";
          $js .= "    e = document.getElementById('cargarcuenta');\n";
          $js .= "    e.style.display = \"none\";\n";
          $js .= "    window.location = \"$accion\";";
          $js .= "    document.getElementById('mensaje').innerHTML = \"<center><font color ='blue'>Un momento...</font></center>\";\n";
          $js .= "    e = document.getElementById('mensaje');\n";
          $js .= "    e.style.display = \"block\";\n";
          $js .= "  }\n";
          $js .= "</SCRIPT>";
          $this->salida.= "$js";
          //**************************
          $this->salida .= "  <tr class='modulo_list_claro' align='center'>";
          $this->salida .= "    <td >".$vector[$i][tercero]."&nbsp; - &nbsp;".$vector[$i][plan_descripcion]."</td>";
          $this->salida .= "    <td >".$vector[$i][total_cuenta]."</td>";
          $this->salida .= "    <td >".$vector[$i][descripcion]."</td>";
          $this->salida .= "    <td >".$vector[$i][saldo]."</td>";
          $this->salida .= "    <td >";
          $this->salida .= "      <div id='cargarcuenta' style=\"display:block\">";                                    
          $this->salida .= "        <a href='javascript:OcultarCargarCuenta();'>[&nbsp;CUMPLIR&nbsp;]</a>";                                    
          $this->salida .= "      </div>";
          $this->salida .= "      <div id='mensaje' style=\"display:none\"></div>";
          $this->salida .= "    </td>";                                    
          $this->salida .= "  </tr>";
        }
        $this->salida .= "</table><BR>";
      }
      else
      {
        $sw_hay_cuenta=false;
        //este swiche $sw_hay_cuenta  se pone false cuando no existe cuenta.
        $this->salida.="<p class='label_error' align=\"center\" >   EL PACIENTE NO TIENE UNA CUENTA CREADA</p>";
      }

      $this->salida .= "<table  align=\"center\" border=\"0\"  width=\"98%\">";
      $this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">";
      $this->salida .= "    <td width=\"5%\">ITEM</td>";
      $this->salida .= "    <td width=\"5%\">CARGO</td>";
      $this->salida .= "    <td width=\"5%\">TARIF.</td>";
      $this->salida .= "    <td width=\"20%\">DESCRIPCION</td>";
      $this->salida .= "    <td width=\"15%\">SERVICIO</td>";
      $this->salida .= "    <td width=\"5%\">CANT.</td>";
	  $this->salida .= "    <td width=\"10%\">OBSERVACION</td>";
      $this->salida .= "    <td width=\"10%\" colspan='2'>VAL. NO CUBIERTO</td>";
      $this->salida .= "    <td width=\"10%\" colspan='2'>VAL. EMPRESA</td>";
      $this->salida .= "    <td width=\"10%\">VALOR CARGO</td>";
      $this->salida .= "    <td width=\"5%\">ELIM</td>";
      $this->salida .= "  </tr>";
      $j=0;

      if($sw_hay_cuenta==false)
      {
        $total_cargo=$total_paciente=$total_empresa=0;
        $cargo_liq = array(); //arreglo que contiene los cargos y demas datos para liquidarlos.
        $Arr_Descripcion[]=array();//arreglo para guardar la descripcion y los servicios.
        //$i=0;
        foreach($op as $index=>$codigo)
        {
          $valores=explode(",",$codigo);
          $_SESSION['OS_ATENCION']['ORDEN']=$valores[0];
          $datos=$this->DatosOs($valores[0]);
          $_SESSION['OS_ATENCION']['SERVICIO_ID']=$valores[7];
          $emp='';
          $emp = BuscarEmpleadorOrden($_REQUEST['arr'][0][numero_orden_id]);

          for($i=0;$i<sizeof($datos);$i++)
          {
              $dat[$j]['cargo']=$datos[$i]['cargo'];
              $dat[$j]['tarifario_id']=$datos[$i]['tarifario_id'];
              $dat[$j][descripcion]=$datos[$i]['descripcion'];
              $dat[$j][numero_orden_id]=$datos[$i]['numero_orden_id'];
              $dat[$j][os_maestro_cargos_id]=$datos[$i]['os_maestro_cargos_id'];
              $Arr_Descripcion[$j]=array('des_cargo'=>$valores[6],'servicio'=>$valores[7],'des_servicio'=>$valores[8],'numero_orden_id'=>$valores[0],'cargo'=>$valores[1]);
              $cargo_liq[$j]=array('tarifario_id'=>$datos[$i]['tarifario_id'],'cargo'=>$datos[$i]['cargo'],'cantidad'=>$datos[$i]['cantidad'],'autorizacion_int'=>$datos[$i]['autorizacion_int'],'autorizacion_ext'=>$datos[$i]['autorizacion_ext'],'sw_factura'=>$datos[$i]['sw_factura'],'descuento_empresa'=> 0,'descuento_paciente'=> 0);
              $j++;
          }
        }
          //carga los descuentos
        if(!empty($descuentos_cargos))
        {
            foreach($descuentos_cargos as $desc_ind => $descrip_des)
            {
                $cargo_liq[$desc_ind]['descuento_empresa'] = $descrip_des['descuento_empresa'];
                $cargo_liq[$desc_ind]['descuento_paciente'] = $descrip_des['descuento_paciente'];
            }
        }
        
        $imd['IYM']=$this->TraerIMDAdicionados('',$valores[0]);
        $cargo_fact=LiquidarCargosCuentaVirtual($cargo_liq,'',$imd,$vector_des, $datos[0][plan_id] ,$datos[0][tipo_afiliado_id] ,$datos[0][rango] ,$datos[0][semanas_cotizacion],$datos[0][servicio],$tipo,$id,$emp['tipo_id_empleador'],$emp['empleador_id']);
        $afiliado=$datos[0][tipo_afiliado_id];
        $rango=$datos[0][rango];
        $sem=$datos[0][semanas_cotizacion];
        $auto=$datos[0][autorizacion_int];
        $serv=$datos[0][servicio];
        $k=0;
        
        foreach($cargo_fact[cargos] as $w=>$v)
        {
          if( $k % 2){ $estilo='modulo_list_claro';}
          else {$estilo='modulo_list_oscuro';}
          $des_empresa=ModuloGetURL('app','Os_Atencion','user','GetDescuento',array('sw_tipo'=>1,'indice'=>$k,'valor_cargo' => $v[valor_cargo]));
          $des_cliente=ModuloGetURL('app','Os_Atencion','user','GetDescuento',array('sw_tipo'=>2,'indice'=>$k,'valor_cargo' => $v[valor_cargo]));
          $this->salida .= "<tr class='$estilo' align='center'>";
          $this->salida .= "  <td >".$Arr_Descripcion[$k][numero_orden_id]."</td>";
		  
		  $cantidad_cargo=$this->MostrarCantidadCargo($Arr_Descripcion[$k][numero_orden_id]);
		  
          $this->salida .= "  <td >".$v[cargo]."</td>";
          $this->salida .= "  <td >".$v[tarifario_id]."</td>";
          $this->salida .= "  <td >".$v[descripcion]."</td>";
          $this->salida .= "  <td >".$Arr_Descripcion[$k][des_servicio]."</td>";
          //$this->salida .= "  <td >".$v[cantidad]."</td>";
		  if($cantidad_cargo[$k][cantidad]==0)
		  {
			$this->salida .= "  <td >".$v[cantidad]."</td>";
		  }
		  else
		  {
			$this->salida .= "  <td >".$cantidad_cargo[$k][cantidad]."</td>";
		  }
		  //$this->salida .= "  <td >".$v[cantidad]."-".$cantidad_cargo[$k][cantidad]."</td>";
			$tiposolicitud= $this->TraerOsTipoSolicitud($Arr_Descripcion[$k][numero_orden_id]);
			$observaciones_item= $this->TraerObservaciones($tiposolicitud[0][os_tipo_solicitud_id],$tiposolicitud[0][hc_os_solicitud_id]);
		  $this->salida.="  <td >".$observaciones_item[0][observacion]."</td>";
          $this->salida .= "  <td width=\"\" align=\"center\">".$v[valor_no_cubierto]." </td>\n";
          $this->salida .= "  <td width=\"\">\n";
          $this->salida .= "    <a href='$des_cliente'>\n";
          $this->salida .= "      <img title='MODIFICA VALOR NO CUBIERTO' src=\"". GetThemePath() ."/images/modificar.png\" border='0'>\n";
          $this->salida .= "    </a>\n";
          $this->salida .= "  </td>\n";
          $this->salida .= "  <td width=\"\" align=\"center\">".$v[valor_cubierto]."</td>\n";
          $this->salida .= "  <td width=\"\">\n";
          $this->salida .= "    <a href='$des_empresa'>\n";
          $this->salida .= "      <img title='MODIFICA VALOR CUBIERTO' src=\"". GetThemePath() ."/images/modificar.png\" border='0'>\n";
          $this->salida .= "    </a>\n";
          $this->salida .= "  </td>";
          $this->salida .= "  <td >".$v[valor_cargo]."</td>";										
          $cargo=false;
          $cargosadicionados=$this->TraerCargosAdicionados($Arr_Descripcion[$k][numero_orden_id]);										
          
          for($j=0; $j<sizeof($cargosadicionados); $j++)
          {
            if($cargosadicionados[$j][numero_orden_id]==$Arr_Descripcion[$k][numero_orden_id]
              AND $cargosadicionados[$j][tarifario_id]==$v[tarifario_id] AND $cargosadicionados[$j][cargo]==$v[cargo])
            {
              $cargo=true;
              $j=sizeof($cargosadicionados); 
            }
          }
          if(($v[cargo]=='IMD' AND $v[tarifario_id]=='SYS') OR $cargo)
          {
            $action=ModuloGetURL('app','Os_Atencion','user','EliminarCargoAdicionado',array('numero_orden_id'=>$valores[0],'cargo'=>$v[cargo],'tarifario_id'=>$v[tarifario_id],'tmp_cuenta_insumos_id'=>$v[tmp_cuenta_insumos_id]));
            $this->salida.="  <td align=\"center\"><a href=\"$action\"><img src=\"".GetThemePath()."/images/elimina.png\"  border='0' title=\"ELIMINAR CARGO Y/O INSUMO\"></a></td>";
          }
          else
          {
            $this->salida.="  <td >&nbsp;</td>";
          }
          $total_cargo=$total_cargo+$v[valor_cargo];
          $valpac=$v[copago]+$v[cuota_moderadora]+$v[valor_no_cubierto];
          //$this->salida.="  <td >".$cargo_fact[$k][total_paciente]."</td>";
          $total_paciente=$total_paciente + $valpac;
          $total_empresa=$total_empresa + $v[valor_empresa];
          $this->salida.="</tr>";
          $cargo_arr[]=array('tarifario_id'=>$v['tarifario_id'],'descripcion'=>$v[descripcion],'os_maestro_cargos_id'=>$dat[$k]['os_maestro_cargos_id'],'numero_orden_id'=>$dat[$k]['numero_orden_id'],'cargo'=>$v['cargo'],'des_servicio'=>$Arr_Descripcion[$k][des_servicio],'cantidad'=>$v['cantidad'],'valor_cargo'=>$v[valor_cargo],'valor_no_cubierto'=>$v[valor_no_cubierto],'autorizacion_int'=>$v['autorizacion_int'],'autorizacion_ext'=>$v['autorizacion_ext']);
          $k++;
        }
        $sw_link=true;
        //esta variable permite q salga el link de pago en caja..
      }
      else
      {
        $total_cargo=$total_paciente=$total_empresa=0;
		$s=0;
        foreach($op as $index=>$codigo)
        {
          $valores=explode(",",$codigo);
          $_SESSION['OS_ATENCION']['ORDEN']=$valores[0];
          $_SESSION['OS_ATENCION']['SERVICIO_ID']=$valores[7];
          $datos=$this->DatosOs($valores[0]);
          list($dbconn) = GetDBconn();
          $query="(SELECT tarifario_id,cargo,NULL as adicionado,NULL as transaccion,cantidad FROM os_maestro_cargos
                          WHERE numero_orden_id=".$valores[0]."
                  UNION 

                  SELECT tarifario_id,cargo,'1' as adicionado,transaccion,cantidad  FROM tmp_cuentas_cargos 
                          WHERE numero_orden_id=".$valores[0].")";
          $resulta=$dbconn->execute($query);
          if ($dbconn->ErrorNo() != 0) {
            $this->error = "Error al Cargar el Modulo";
            $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
            return false;
          }
          while(!$resulta->EOF)
          {
			$cantidad_cargo=$this->MostrarCantidadCargo($valores[0]);
			 //$Liq=LiquidarCargoCuenta($Cuenta,$resulta->fields[0],$resulta->fields[1],$valores[5],0,0,false,false,0,$valores[7],$PlanId,$datos[tipo_afiliado_id],$datos[rango],$datos[semanas_cotizadas],false);
			
			$Liq=LiquidarCargoCuenta($Cuenta,$resulta->fields[0],$resulta->fields[1],$resulta->fields[4],0,0,false,false,0,$valores[7],$PlanId,$datos[tipo_afiliado_id],$datos[rango],$datos[semanas_cotizadas],$valores[0],false);
			//$Liq=LiquidarCargoCuenta($Cuenta,$resulta->fields[0],$resulta->fields[1],$cantidad_cargo[$s][cantidad],0,0,false,false,0,$valores[7],$PlanId,$datos[tipo_afiliado_id],$datos[rango],$datos[semanas_cotizadas],false);
            $afiliado=$datos[tipo_afiliado_id];
            $rango=$datos[rango];
            $sem=$datos[semanas_cotizacion];
            $auto=$datos[autorizacion_int];
            $serv=$datos[servicio];
            if( $i % 2){ $estilo='modulo_list_claro';}
            else {$estilo='modulo_list_oscuro';}
            $this->salida.="<tr class='$estilo' align='center'>";
            $this->salida.="  <td >".$valores[0]."</td>";
            $this->salida.="  <td >".$resulta->fields[1]."</td>";
            $this->salida.="  <td >".$resulta->fields[0]."</td>";
				$desc=$this->TraerNombreTarifario($resulta->fields[0],$resulta->fields[1]);
            $this->salida.="  <td >".$desc."</td>";
            $this->salida.="  <td>".$valores[8]."</td>";
			
//			$cantidad_cargo=$this->MostrarCantidadCargo($valores[0]);
			
            //$this->salida.="  <td >".$Liq[cantidad]."</td>";
			if($cantidad_cargo[$s][cantidad]==0)
			{
				$this->salida.="  <td >".$Liq[cantidad]."</td>";
			}
			else
			{
				$this->salida.="  <td >".$cantidad_cargo[$s][cantidad]."</td>";
			}
			$s++;
				$tiposolicitud= $this->TraerOsTipoSolicitud($valores[0]);
				$observaciones_item= $this->TraerObservaciones($tiposolicitud[0][os_tipo_solicitud_id],$tiposolicitud[0][hc_os_solicitud_id]);
			$this->salida.="  <td >".$observaciones_item[0][observacion]."</td>";
            $this->salida.="  <td>".$Liq[valor_no_cubierto]."</td>";
			$this->salida.="  <td> &nbsp;</td>";
            $this->salida.="  <td  align=\"center\">".$Liq[valor_cubierto]."</td>";
			$this->salida.="  <td > &nbsp;</td>";
            $this->salida.="  <td >".$Liq[valor_cargo]."</td>";
            if($resulta->fields[2]=='1'){
            $action=ModuloGetURL('app','Os_Atencion','user','EliminarCargoAdicionado',array('numero_orden_id'=>$valores[0],'cargo'=>$resulta->fields[1],'tarifario_id'=>$resulta->fields[0]));
            $this->salida.="  <td align=\"center\"><a href=\"$action\"><img src=\"".GetThemePath()."/images/elimina.png\"  border='0' title=\"ELIMINAR CARGO Y/O INSUMO\"></a></td>";
            }else{
            $this->salida.="  <td >&nbsp;</td>";	
            }
            $total_cargo=$total_cargo+$Liq[valor_cargo];
            //$this->salida.="  <td >".$Liq[total_paciente]."</td>";
            $total_paciente=$total_paciente + $Liq[total_paciente];

            $total_empresa=$total_empresa + $Liq[valor_empresa];
            $this->salida.="</tr>";
            $cargo_arr[]=array('tarifario_id'=>$resulta->fields[0],'numero_orden_id'=>$valores[0],'descripcion'=>$desc,'cargo'=>$resulta->fields[1],'des_servicio'=>$valores[8],'cantidad'=>$Liq[cantidad],'valor_cargo'=>$Liq[valor_cargo]);
            $i++;
            $resulta->MoveNext();
          }
        }
      }

      $this->salida.="<tr class='$estilo' align='center'>";
      $this->salida.="  <td colspan='14'>&nbsp;&nbsp;</td>";
      $this->salida.="</tr>";
      $nombres=$this->BuscarNombreCop($datos[0][plan_id]);

      //tipo_liquidacion_cargo != "NO APLICA"
      
      if($nombres[tipo_liquidacion_cargo] != 3)
      {
        $valpac=$cargo_fact[cuota_moderadora];
        if($cargo_fact[valor_cuota_moderadora]>0)
        {
          //$vector,$nom,$tipo,$id,$op,$PlanId
          $this->salida.="<tr align='right'>";
          $this->salida.="  <td class=\"modulo_table_list_title\" colspan='11' style='text-align:right'>".$nombres[nombre_cuota_moderadora]."</td>";
          $this->salida.="  <td class=\"modulo_table_list_title\" colspan='3'>".FormatoValor($cargo_fact[valor_cuota_moderadora])."</td>";
          $this->salida.="</tr>";
        }

        if($cargo_fact[valor_cuota_paciente]>0)
        {
          $this->salida.="<tr align='right'>";
          $this->salida.="  <td class=\"modulo_table_list_title\" colspan='11' style='text-align:right'>".$nombres[nombre_copago]."</td>";
          $this->salida.="  <td class=\"modulo_table_list_title\" colspan='3'>".FormatoValor($cargo_fact[valor_cuota_paciente])."</td>";
          $this->salida.="</tr>";
        }
      }

      if($cargo_fact[valor_no_cubierto]>0)
      {
        $this->salida.="<tr align='right'>";
        $this->salida.="  <td class=\"modulo_table_list_title\" colspan='11' style='text-align:right'>Valor No Cubierto</td>";
        $this->salida.="  <td class=\"modulo_table_list_title\" colspan='3'>".FormatoValor($cargo_fact[valor_no_cubierto])."</td>";
        $this->salida.="</tr>";
      }

      if($cargo_fact[valor_gravamen_paciente]>0)
      {
        $this->salida.="<tr align='right'>";
        $this->salida.="  <td class=\"modulo_table_list_title\" colspan='11' style='text-align:right'>IVA Paciente</td>";
        $this->salida.="  <td class=\"modulo_table_list_title\" colspan='3'>".FormatoValor($cargo_fact[valor_gravamen_paciente])."</td>";
        $this->salida.="</tr>";
      }
      
      $this->salida.="<tr align='right'>";
      $this->salida.="  <td class=\"modulo_table_list_title\" colspan='11' style='text-align:right'>TOTAL</td>";
      $this->salida.="  <td colspan='3' class=\"modulo_table_list_title\">".FormatoValor($cargo_fact[valor_total_paciente])."</td>";
      $this->salida.="</tr>";

      $this->salida.="<tr align='right'>";
      $this->salida.="  <td colspan='3'><label class='label_mark'><a href='".ModuloGetURL('app','Os_Atencion','user','FrmAgregarCargos',array('nom'=>$nom,'op'=>$op,'tipoid'=>$tipo,'id'=>$id,'afiliado'=>$afiliado,'rango'=>$rango,'sem'=>$sem,'plan'=>$PlanId,'auto'=>$auto,'servicio'=>$serv,'numero_orden'=>$valores[0],'sw_hay_cuenta'=>$sw_hay_cuenta))."'>&nbsp;&nbsp;AGREGAR CARGOS</a></label></td>";
      $this->salida.="  <td colspan='3' align='left'><label class='label_mark'><a href='".ModuloGetURL('app','Os_Atencion','user','FrmAgregarIMD',array('nom'=>$nom,'op'=>$op,'tipoid'=>$tipo,'id'=>$id,'afiliado'=>$afiliado,'rango'=>$rango,'sem'=>$sem,'plan'=>$PlanId,'auto'=>$auto,'servicio'=>$serv,'numero_orden'=>$valores[0],'sw_hay_cuenta'=>$sw_hay_cuenta))."'>&nbsp;&nbsp;AGREGAR INSUMOS Y MED</a></label></td>";
      $this->BuscarPermiso();
      unset($_SESSION['CAJA']['liq']['caja_os']);
      //si no hay cuenta aparece este link
      if($sw_link==true and !empty($_SESSION['SEGURIDAD']['CAJARAPIDA']['caja']))
      {
        if(!EMPTY($_SESSION['LABORATORIO']['CAJARAPIDA']))
        { //LiquidacionOrden($vector,$nom,$tipo,$id,$op,$PlanId)
          $this->salida.="  <td colspan='3'><img src=\"".GetThemePath()."/images/informacion.png\"<label class='label_mark'><a href='".ModuloGetURL('app','CajaGeneral','user','CajaRapida',array('nom'=>$nom,'op'=>$op,'tipoid'=>$tipo,'id'=>$id,'afiliado'=>$afiliado,'rango'=>$rango,'sem'=>$sem,'plan'=>$PlanId,'auto'=>$auto,'servicio'=>$serv))."'>&nbsp;&nbsp;CUMPLIMIENTO*1</a></label></td >";
          $_SESSION['CAJA']['ARRAY_PAGO']=$cargo_arr;
          $_SESSION['CAJA']['AUX']['liq']=$cargo_fact;
          $_SESSION['CAJA']['AUX']['datos']=$dat;
          $_SESSION['CAJA']['AUX']['vector']=$vector;
        }
        else
        {
          //esta variable de session nos guarda cuntas cajas tiene permiso el cajero
          //aqui preguntamos si es una de una vez saldra derecho el link
          if($_SESSION['cuantascajas']==1)
          {
            foreach($_SESSION['SEGURIDAD']['CAJARAPIDA']['caja'] as $k=>$v)
            {
              foreach($v as $t=>$s)
              {
                foreach($s as $m=>$n)
                {
                  $datos1=$n;
                  break;
                }
                break;
              }
              break;
            }

            $_SESSION['CAJA']['ARRAY_PAGO']=$cargo_arr;
            //$_SESSION['CAJA']['liq']=$cargo_fact;
            $_SESSION['CAJA']['datos']=$dat;
            $_SESSION['CAJA']['caja_os']['liq']=$cargo_fact;
            $_SESSION['CAJA']['vector']=$vector;
            $_SESSION['ARREGLO_IYM']['IYM'] = $this->TraerIMDAdicionados('',$valores[0]);

            $this->salida.="  <td colspan='3'><img src=\"".GetThemePath()."/images/informacion.png\"<label class='label_mark'><a href='".ModuloGetURL('app','CajaGeneral','user','CajaRapida',array('nom'=>$nom,'op'=>$op,'tipoid'=>$tipo,'id'=>$id,'afiliado'=>$afiliado,'rango'=>$rango,'sem'=>$sem,'plan'=>$PlanId,'auto'=>$auto,'servicio'=>$serv,$_SESSION['SEGURIDAD']['CAJARAPIDA']['url'][4]=>$datos1))."'>&nbsp;&nbsp;CUMPLIMIENTO-2</a></label></td >";
          }
          else
          {
            $_SESSION['CAJA']['ARRAY_PAGO']=$cargo_arr;
            //$_SESSION['CAJA']['liq']=$cargo_fact;
            $_SESSION['CAJA']['caja_os']['liq']=$cargo_fact;
            $_SESSION['CAJA']['datos']=$dat;
            $_SESSION['CAJA']['vector']=$vector;
            $_SESSION['ARREGLO_IYM']['IYM'] = $this->TraerIMDAdicionados('',$valores[0]);
            $this->salida.="  <td colspan='3'><img src=\"".GetThemePath()."/images/informacion.png\"<label class='label_mark'><a href='".ModuloGetURL('app','Os_Atencion','user','MenuCaja',array('nom'=>$nom,'op'=>$op,'tipoid'=>$tipo,'id'=>$id,'afiliado'=>$afiliado,'rango'=>$rango,'sem'=>$sem,'plan'=>$PlanId,'auto'=>$auto,'servicio'=>$serv))."'>&nbsp;&nbsp;CUMPLIMIENTO-3</a></label></td >";
          }
        }

      }
      else
      {
          $this->salida.="  <td colspan='3'>&nbsp;</td>";
      }
      $this->salida.="</tr>";
      $this->salida.="</table>";
      //***************************************************
      //INSUMOS Y MEDICAMENTOS TEMPORALMENTE ADICIONADADOS
      //***************************************************
      
      if($sw_hay_cuenta)
      {
        $imd=$this->TraerIMDAdicionados('',$valores[0]);
        
        //$imd=$this->TraerIMDAdicionados($_SESSION['OS_ATENCION']['cuenta'],'');
        if(is_array($imd))
        {
            $this->salida.="<BR><BR><table  align=\"center\" border=\"0\"  width=\"90%\">";
            $this->salida.="  <td class=\"modulo_table_list_title\" colspan='10'>INSUMOS Y MEDICAMENTOS ADICIONADOS</td>";
            $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
            $this->salida.="  <td width=\"5%\">INSUMO/MED</td>";
            $this->salida.="  <td width=\"35%\">DESCRIPCION</td>";
            $this->salida.="  <td width=\"10%\">LOTE</td>";
            $this->salida.="  <td width=\"20%\">BODEGA</td>";
            $this->salida.="  <td width=\"5%\">CANT.</td>";
            $this->salida.="  <td width=\"10%\">VAL. NO CUBIERTO</td>";
            $this->salida.="  <td width=\"8%\">VAL. EMPRESA</td>";
            $this->salida.="  <td width=\"15%\">VALOR CARGO</td>";
            $this->salida.="  <td width=\"15%\">PRECIO PLAN</td>";
            $this->salida.="  <td width=\"15%\">TOTAL</td>";
            $this->salida.="  <td width=\"5%\">&nbsp;</td>";
            $this->salida.="</tr>";
//            $traerbodega=$this->TraerBodega($_SESSION['CAJA']['IMD_CUENTA'][0]);

            $total=$subtotal=0;
            for($i=0; $i<sizeof($imd); $i++)
            {
                $traerbodega=$this->TraerBodega($_SESSION['CAJA']['IMD_CUENTA'][$i]);
                $this->salida.="<tr class='$estilo' align='center'>";
                $this->salida.="  <td >".$imd[$i][codigo_producto]."</td>";
                $this->salida.="  <td align=\"left\">".$imd[$i][descripcion]."</td>";
                $this->salida.="  <td align=\"left\">".$imd[$i][lote]."</td>";
                $this->salida.="  <td align=\"left\">".$traerbodega[descripcion]."</td>";
                $this->salida.="  <td >".$imd[$i][cantidad]."</td>";
                $this->salida.="  <td align=\"right\">$".FormatoValor($imd[$i][valor_nocubierto])."</td>";
                $this->salida.="  <td align=\"right\">$".FormatoValor($imd[$i][valor_cubierto])."</td>";
                $this->salida.="  <td align=\"right\">$".FormatoValor($imd[$i][valor_cargo])."</td>";
                $this->salida.="  <td align=\"right\">$".FormatoValor($imd[$i][precio_plan])."</td>";
                $subtotal=$imd[$i][valor_cargo]*$imd[$i][cantidad];
                $this->salida.="  <td align=\"right\">$".FormatoValor($subtotal)."</td>";
                $total+=$subtotal;
                $action=ModuloGetURL('app','Os_Atencion','user','EliminarCargoAdicionado',array('tmp_cuenta_insumos_id'=>$imd[$i][tmp_cuenta_insumos_id],"cargo"=>'IMD',"tarifario_id"=>'SYS'));
                $this->salida.="  <td align=\"center\"><a href=\"$action\"><img src=\"".GetThemePath()."/images/elimina.png\"  border='0' title=\"ELIMINAR CARGO Y/O INSUMO\"></a></td>";
                $this->salida.="</tr>";
            }
            /*$this->salida.="<tr class=\"modulo_table_list_title\" align=\"right\">";
            $this->salida.="  <td align=\"right\" colspan='5'>TOTAL</td>";
            $this->salida.="  <td class=\"modulo_table_list_title\">$".FormatoValor($total)."</td>";
            $this->salida.="</tr>";*/
            $this->salida.="<tr class=\"modulo_table_list\" align=\"right\">";
            
            $accion=ModuloGetURL('app','Os_Atencion','user','GuardarTodosCargosIyM',array("orden"=>$valores[0],'cuenta'=>$Cuenta,'op'=>$op,'plan'=>$PlanId,"tipo_id"=>$tipo,"pac"=>$id,'nom'=>$nom));
            $this->salida.="  <td align=\"right\" colspan='10'><a href='$accion'>[&nbsp;CUMPLIR&nbsp;]</a></td>";
            $this->salida.="</tr>";
            $this->salida.="</table>";
            if(!empty($_SESSION['CAJA']['IMD_CUENTA']))
            {
              $datos=$_SESSION['CAJA']['IMD_CUENTA'];
            }
            $_SESSION['CAJA']['caja_os']['imd_liq']=$imd;
        }
      }

      //$action2=ModuloGetURL('app','Os_Atencion','user','FormaMetodoBuscar',array('paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of']));
      $this->salida.="<br><br><table align=\"center\" width='40%' border=\"0\">";
      $action2=ModuloGetURL('app','Os_Atencion','user','FrmOrdenar',array('nombre'=>$nom,'tipoid'=>$tipo,'idp'=>$id,'paso'=>$_REQUEST['paso'],'Of'=>$_REQUEST['Of']));
      $this->salida .= "           <form name=\"formados\" action=\"$action2\" method=\"post\">";
      $this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
      $this->salida .= "</tr>";
      $this->salida.="</table><br>";

      $this->salida .= ThemeCerrarTabla();
      return true;
    }

    function FrmAgregarCargos($arr)
    {
            if(!empty($_REQUEST['numero_orden']))
                $_SESSION['OS_ATENCION']['numero_orden']=$_REQUEST['numero_orden'];
            $this->salida= ThemeAbrirTablaSubModulo('ADICIONAR CARGOS');
            $this->DatosCompletos();
            $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida.="</table><br>";
    /*      $arreglo=$this->BuscarDatosCargos();
            echo sizeof($arreglo);
            //print_r();
            if(!empty($arreglo))
            {
                    $accion1=ModuloGetURL('app','Os_Atencion','user','CrearOS');
                    $this->salida .= "<form name=\"formadesapoyo\" action=\"$accion1\" method=\"post\">";
                    $this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida.="  <td width=\"7%\">CUPS</td>";
                    $this->salida.="  <td width=\"42%\">DESCRIPCION</td>";
                    $this->salida.="  <td width=\"5%\">CANT.</td>";
                    $this->salida.="  <td width=\"10%\">VAL. NO CUBIERTO</td>";
                    $this->salida.="  <td width=\"10%\">VAL. EMPRESA</td>";
                    $this->salida.="  <td width=\"10%\">VALOR CARGO</td>";
                    $this->salida.="  <td width=\"5%\"></td>";
                    //$this->salida.="  <td width=\"5%\" colspan=\"2\"></td>";
                    $this->salida.="</tr>";
                    for($i=0; $i<sizeof($arreglo);$i++)
                    {
                        $this->salida.="<tr class='modulo_list_oscuro'>";
                        $this->salida .= "        <td align=\"center\" width=\"7%\">".$arreglo[$i][cargo]."</td>";
                        $this->salida .= "        <td width=\"42%\">".$arreglo[$i][descripcion]."</td>";
                        $this->salida.="</tr>";
                    }
                    $this->salida .= "  </table><br>";
            }*/
            $accion1=ModuloGetURL('app','Os_Atencion','user','Busqueda_AvanzadaCargos');
            $this->salida .= "<form name=\"formadesapoyo\" action=\"$accion1\" method=\"post\">";
            $this->salida.="<table  align=\"center\" border=\"0\"  width=\"75%\">";
            $this->salida.="<tr class=\"modulo_table_title\">";
            $this->salida.="  <td align=\"center\" colspan=\"7\">ADICION DE CARGOS - BUSQUEDA AVANZADA </td>";
            $this->salida.="</tr>";
            $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
            $this->salida.="<td width=\"5%\">TIPO</td>";
            $this->salida.="<td width=\"10%\" align = left >";
            $this->salida.="<select size = 1 name = 'criterio1apoyo'  class =\"select\">";
            $this->salida.="<option value = '001' selected>Todos</option>";
            if (($_REQUEST['criterio1apoyo'])  == '002')
            {  $this->salida.="<option value = '002' selected>Frecuentes</option>";   }
            else
            {  $this->salida.="<option value = '002' >Frecuentes</option>";  }
            $this->salida.="</select>";
            $this->salida.="</td>";
            $this->salida.="<td width=\"6%\">CARGO:</td>";
            $this->salida .="<td width=\"11%\" align='center'><input type='text' size =10 class='input-text' size = 10 maxlength = 10   name = 'cargoapoyo'  value =\"".$_REQUEST['cargoapoyo']."\"    ></td>" ;
            $this->salida.="<td width=\"10%\">DESCRIPCION:</td>";
            $this->salida .="<td width=\"25%\" align='center'><input type='text' class='input-text'     name = 'descripcionapoyo'   value =\"".$_REQUEST['descripcionapoyo']."\"        ></td>" ;
            $this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name=\"buscarapoyo\" type=\"submit\" value=\"BUSCAR\"></td>";
            $this->salida.="</tr>";
            $this->salida.="</table><br>";
            $this->salida.="</form>";
            if(!empty($arr))
            {
                //$_SESSION['OS_ATENCION']['CARGOS_AGREGADOS']=$arr;
                $this->FormaResultadosCargos($arr,$_SESSION['OS_ATENCION']['numero_orden']);
            }
            $accionV=ModuloGetURL('app','Os_Atencion','user','LiquidacionOrden');
            $this->salida .= "<form name=\"formaapoyo\" action=\"$accionV\" method=\"post\">";
            $this->salida .= "<p align=\"center\"><input class=\"input-submit\" name=\"volverapoyo\" type=\"submit\" value=\"CANCELAR\"></form></p>";
            $this->salida .= themeCerrarTabla();
            return true;
    }

    function FormaResultadosCargos($vectorA,$numero_orden)
    {
            if ($vectorA)
            {
                    $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
                    $this->salida.="<tr class=\"modulo_table_title\">";
                    $this->salida.="  <td align=\"center\" colspan=\"4\">RESULTADO DE LA BUSQUEDA</td>";
                    $this->salida.="</tr>";
                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida.="  <td width=\"15%\">TIPO</td>";
                    $this->salida.="  <td width=\"10%\">CARGO</td>";
                    $this->salida.="  <td width=\"50%\">DESCRIPCION</td>";
                    $this->salida.="  <td width=\"5%\">OPCION</td>";
                    $this->salida.="</tr>";
                    for($i=0;$i<sizeof($vectorA);$i++)
                    {
                            if( $i % 2){ $estilo='modulo_list_claro';}
                            else {$estilo='modulo_list_oscuro';}
                            $this->salida.="<tr class=\"$estilo\">";
                            $this->salida.="  <td align=\"center\" width=\"15%\">".$vectorA[$i][tipo]."</td>";
                            $this->salida.="  <td align=\"center\" width=\"10%\">".$vectorA[$i][cargo]."</td>";
                            $this->salida.="  <td align=\"left\" width=\"50%\">".$vectorA[$i][descripcion]."</td>";
                            $accion=ModuloGetURL('app','Os_Atencion','user','FrmVerEquivalencias', array('cargo'=>$vectorA[$i][cargo],'apoyod_tipo_id'=>$vectorA[$i][cargo],'descripcion'=>$vectorA[$i][descripcion],'numero_orden'=>$numero_orden));
                            $this->salida.="  <td align=\"center\" width=\"5%\"><a href=\"$accion\">Adicionar</a></td>";
                            $this->salida.="</tr>";
                    }
                    $this->salida.="</table><br>";
                    $var=$this->RetornarBarraExamenes_AvanzadaCargos();
                    if(!empty($var))
                    {
                        $this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";// class=\"modulo_table\"
                        $this->salida .= "  <tr>";
                        $this->salida .= "  <td width=\"100%\" align=\"center\">";
                        $this->salida .=$var;
                        $this->salida .= "  </td>";
                        $this->salida .= "  </tr>";
                        $this->salida .= "  </table><br>";
                    }
            }
    }

    function FrmVerEquivalencias($cargobase,$descripcion)
    {
        $numero_orden=$_REQUEST['numero_orden'];
        if(!empty($_SESSION['DATOS_PACIENTE']['plan_id']))
            $plan=$_SESSION['DATOS_PACIENTE']['plan_id'];
        elseif(!empty($_SESSION['OS_ATENCION']['PlanId']))
            $plan=$_SESSION['OS_ATENCION']['PlanId'];
        if(empty($cargobase))
        {
            $cargobase=$_REQUEST['cargo'];
            $descripcion=$_REQUEST['descripcion'];
        }
        $this->salida .= ThemeAbrirTabla('TARIFARIOS EQUIVALENTES DE LOS PROCEDIMIENTOS');
        $action=ModuloGetURL('app','Os_Atencion','user','Actualizar_Tmp_cargos',array("cargobase"=>$cargobase,"descripcion"=>$descripcion,"plan"=>$plan,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso,
        "TipoDocumentoFil"=>$TipoDocumentoFil,"DocumentoFil"=>$DocumentoFil,"NoIngresoFil"=>$NoIngresoFil,"NoCuentaFil"=>$NoCuentaFil,"EstadoFil"=>$EstadoFil,"FechaCirugiaFil"=>$FechaCirugiaFil,'numero_orden'=>$numero_orden));
        /*$this->salida .="<script>\n\n";
        $this->salida .=" function VerificacionEquivalentes(frm){";
        $dat=$this->TraeProcedimientosCirugia($NoLiquidacion);
        for($i=0;$i<sizeof($dat);$i++){
            $this->salida .="   if(frm.SeleccionId".$dat[$i]['consecutivo_procedimiento'].".value==''){";
            $this->salida .="     alert('Por cada Procedimiento debe Realizar la Seleccion del Tarifario con el que desea Liquidar');";
            $this->salida .="     return false;";
            $this->salida .="   }";
        }
        $this->salida .=" }";
        $this->salida .="</script>\n\n";*/
        $this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
        $this->Encabezado();
        //$this->salida .= "  <input type=\"hidden\" name=\"cah\" value=\"c_a_h_q\">";
        $this->salida .= "  <table width=\"40%\" border=\"0\" align=\"center\">";
        $this->salida .= "  <tr><td align=\"center\">";
        $this->salida .=    $this->SetStyle("MensajeError");
        $this->salida .= "  </td></tr>";
        $this->salida .= "   </table>";

        $procedimientos=$this->GetEquivalenciasCargos($plan,$cargobase);
        $this->salida .= "    <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"2\">PROCEDIMIENTOS Y SELECCION DE EQUIVALENCIAS&nbsp&nbsp&nbsp&nbsp; - &nbsp&nbsp&nbsp&nbsp PLAN: ".$_SESSION['DATOS_PACIENTE']['plan_descripcion']."</td></tr>";
        $this->salida .= "    <tr class=\"modulo_list_oscuro\">";
        $this->salida .= "    <td class=\"label\"  colspan=\"2\">".$cargobase." - ".$descripcion."</td>";
        $this->salida .= "    </tr>";
        if(is_array($procedimientos))
        {
            $_SESSION['OS_ATENCION']['PROCEDIMIENTOS']=$procedimientos;
            for($i=0;$i<sizeof($procedimientos);$i++)
            {
                $this->salida .= "    <tr class=\"modulo_list_oscuro\"><td colspan=\"2\">";
                $this->salida .= "    <table border=\"0\" width=\"98%\" align=\"center\" class=\"normal_10\">";
                $this->salida .= "        <tr class=\"modulo_list_claro\">";
                $this->salida .= "        <td width=\"10%\">".$procedimientos[$i]['nomtarifario']."</td>";
                $this->salida .= "        <td width=\"10%\">".$procedimientos[$i]['cargo']."</td>";
                $this->salida .= "        <td>".$procedimientos[$i]['descripcion']."</td>";
                //$this->salida .= "        <td align=\"center\" width=\"5%\">&nbsp;</td>";
        $this->salida.="<td width=\"5%\"> <input name=\"cargo".$procedimientos[$i]['cargo']."\" maxlength='5'  size='3' class=\"input-text\" type=\"text\"></td>";
                $this->salida .= "        <td align=\"center\" width=\"5%\"><input title=\"Seleccion\" type=\"checkbox\" name=\"seleccion$i\" value=\"".$procedimientos[$i]['tarifario_id']."||//".$procedimientos[$i]['cargo']."||//".$procedimientos[$i]['precio']."||//".$procedimientos[$i]['grupo_tarifario_id'].
                "||//".$procedimientos[$i]['subgrupo_tarifario_id']."||//".$procedimientos[$i]['tipo_cargo']."||//".$procedimientos[$i]['grupo_tipo_cargo']."||//".$procedimientos[$i]['nivel']."||//".$procedimientos[$i]['tipo_unidad_id']."||//".$procedimientos[$i]['sw_honorarios']."||//".$procedimientos[$i]['concepto_rips']."\"></td>";
                $this->salida .= "        </tr>";
                $this->salida .= "        </table>";
                $this->salida .= "    </td></tr>";
                
            }
						$this->salida .= "    <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
						$this->salida .= "    <tr><td align=\"right\"><input type=\"submit\" name=\"Liquidar\" value=\"Agregar\" class=\"input-submit\"></td></tr>";
						$this->salida .= "    </table><BR>";						
        }
        else
        {
                $this->salida .= "    <tr class=\"modulo_list_oscuro\"><td colspan=\"2\">";
                $this->salida .= "       <table border=\"0\" width=\"98%\" align=\"center\" class=\"normal_10\">";
                $this->salida .= "        <tr class=\"modulo_list_claro\">";
                $this->salida .= "        <td class=\"label_error\" align=\"center\">NO SE ENCONTRARON EQUIVALENCIAS</td>";
                $this->salida .= "        </tr>";
                $this->salida .= "      </table>";
                $this->salida .= "    </td></tr>";
        }
				$this->salida .= "      </form>";
        $action=ModuloGetURL('app','Os_Atencion','user','FrmAgregarCargos');
        $this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
        $this->salida .= "    <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "    <tr><td align=\"center\"><input type=\"submit\" name=\"Volver\" value=\"VOLVER\" class=\"input-submit\"></td></tr>";
        $this->salida .= "    </table>";
        $this->salida .= "      </form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }


function FrmAgregarIMD($arr)
{
        $this->salida .= ThemeAbrirTabla('ELEGIR BODEGAS DE INSUMOS O MEDICAMENTOS');
        $accion=ModuloGetURL('app','Os_Atencion','user','BuscadorProductoInv',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
        $this->salida .= "    <form name=\"formaborrar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "            <table width=\"40%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "               <tr>";
        $tipo=$this->Bodegas();
        $this->salida .= "       <td class=\"label\">BODEGAS: </td>";
        $this->salida .= "                 <td colspan=\"2\"><select name=\"bodegas\" class=\"select\">";
        $this->salida .= "                     <option value=\"-1\">----------BODEGAS----------</option>";
        for($i=0; $i<sizeof($tipo); $i++)
        {
                $this->salida .= "                     <option value=\"".$tipo[$i][bodega].",".$tipo[$i][empresa_id].",".$tipo[$i][centro_utilidad]."\">".$tipo[$i][descripcion]."</option>";
        }
        $this->salida .= "                 </select></td>";
        $this->salida .= "               </tr>";
        $this->salida .= "           </table>";
        $this->salida .= " <table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"45%\" align=\"center\"  >";
        $this->salida .= "    <tr align=\"center\">";
        $this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Cambiar\" value=\"ACEPTAR\"></td>";
        $this->salida .= "    </form>";
        $accionCancelar=ModuloGetURL('app','Os_Atencion','user','LiquidacionOrden',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Pieza'=>$Pieza,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
        $this->salida .= "    <form name=\"formaborrar\" action=\"$accionCancelar\" method=\"post\">";
        $this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Consultar\" value=\"CANCELAR\"></td>";
        $this->salida .= "    </form>";
        $this->salida .= "    </tr>";
        $this->salida .= " </table>";
        $this->salida .= "</form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
}


    //FORMA PARA LA BUSQUEDA DE PRODUCTOS DE INENTARIO
  /**
    *       BuscadorProductoInv
    *
  *   Funcion que muestra la consulta de los productos en el inventario
    *       @Author Lorena Aragón G.
    *       @access Private
    *       @return boolean
    */

  function BuscadorProductoInv($NoLiquidacion,$TipoDocumento,$Documento,$nombrePaciente,$cuenta,$ingreso,$codigoBus,$DescripcionBus,$bodega,$ProductosBodega)
    {
        if(!empty($_REQUEST['bodegas']))
            $_SESSION['OS_ATENCION']['bodega']=$_REQUEST['bodegas'];
        else
            $_REQUEST['bodegas']=$_SESSION['OS_ATENCION']['bodega'];
        $this->salida .= ThemeAbrirTabla('BUSCADOR PRODUCTOS INVENTARIOS');
        $action=ModuloGetURL('app','Os_Atencion','user','ProductosInventariosBodega',array("NoLiquidacion"=>$NoLiquidacion,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso,"bodega"=>$_REQUEST['bodegas']));
        $this->salida .= "    <form name=\"forma\" action=\"$action\" method=\"post\">";
        $this->Encabezado();
        $this->salida .= "    <BR><table width=\"80%\" border=\"0\" align=\"center\">";
        $this->salida .= "    <tr class=\"modulo_table_list_title\"><td colspan=\"4\">PARAMENTROS DE BUSQUEDA</td></tr>";
        $this->salida .= "    <tr class=\"modulo_list_claro\">";
        $this->salida .= "     <td class=\"label\">CODIGO</td>";
        $this->salida .= "        <td><input type=\"text\" class=\"input-text\" name=\"codigoBus\" size=\"10\" value=\"$codigoBus\"></td>";
        $this->salida .= "     <td class=\"label\">DESCRIPCION</td>";
        $this->salida .= "     <td><input size=\"70\" type=\"text\" name=\"DescripcionBus\" value=\"".$DescripcionBus."\" class=\"input-submit\"></td>";
        $this->salida .= "    </tr>";
        $this->salida .= "    <tr class=\"modulo_list_claro\"><td align=\"center\" colspan=\"4\">";
        $this->salida .= "     <input class=\"input-submit\" type=\"submit\" name=\"Filtrar\" value=\"FILTRAR\" class=\"input-submit\">";
        $this->salida .= "     <input class=\"input-submit\" type=\"submit\" name=\"Volver\" value=\"VOLVER\" class=\"input-submit\">";
        $this->salida .= "    </td></tr>";
        $this->salida .= "    </table><BR>";
        $this->salida .= "      </form>";
        //$ProductosBodega=$this->ProductosInventariosBodega($codigoBus,$DescripcionBus,$bodega );
        if($ProductosBodega)
        {
            $_SESSION['OS_ATENCION']['PRODUCTOS']=$ProductosBodega;
            $actionSelect=ModuloGetURL('app','Os_Atencion','user','InsertarProductoTmpInventario',array("producto"=>$ProductosBodega[$i]['codigo_producto'],"descripcion"=>$ProductosBodega[$i]['descripcion'],"existencia"=>$ProductosBodega[$i]['existencia'],
            "NoLiquidacion"=>$NoLiquidacion,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso));
            $this->salida .= "    <form name=\"forma\" action=\"$actionSelect\" method=\"post\">";
            $this->salida .= "    <table width=\"80%\" border=\"0\" align=\"center\">";
            $this->salida .= "    <tr class=\"modulo_table_list_title\">";
            $this->salida .= "    <td width=\"20%\">CODIGO</td>";
            $this->salida .= "    <td width=\"50%\">DESCRIPCION</td>";
            $this->salida .= "    <td width=\"50%\">LOTE</td>";
            $this->salida .= "    <td width=\"50%\">FECHA VENCIMIENTO</td>";
            $this->salida .= "    <td width=\"15%\">EXISTENCIAS</td>";
            $this->salida .= "    <td width=\"14%\">PRECIO VENTA/UNIDAD</td>";
            $this->salida .= "    <td width=\"1%\">CANT.</td>";
            //$this->salida .= "    <td width=\"5%\">&nbsp;</td>";
            $this->salida .= "    </tr>";
            for($i=0;$i<sizeof($ProductosBodega);$i++)
            {
                if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
                    $this->salida .= "    <tr class=\"$estilo\">";
                    $this->salida .= "    <td>".$ProductosBodega[$i]['codigo_producto']."</td>";
                    $this->salida .= "    <td>".$ProductosBodega[$i]['descripcion']."</td>";
                    $this->salida .= "    <td>".$ProductosBodega[$i]['lote']."</td>";
                    $this->salida .= "    <td>".$ProductosBodega[$i]['fecha_vencimiento']."</td>";
                    $this->salida .= "    <td>".$ProductosBodega[$i]['existencia']."</td>";
                    $this->salida .= "    <td align=\"right\">$".FormatoValor($ProductosBodega[$i]['precio_venta'])."</td>";
                    $this->salida.="<td width=\"1%\"> <input name=\"producto".$ProductosBodega[$i]['codigo_producto'].$i."\" maxlength='5'  size='3' class=\"input-text\" type=\"text\"></td>";
                  //$this->salida .= "    <td align=\"center\" width=\"5%\"><a href=\"$actionSelect\"><img title=\"Seleccionar Producto\" border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"></a></td>";
                    $this->salida .= "    </tr>";
                    $y++;
            }
            $this->salida .= "    <tr>";
            $this->salida .= "    <td align=\"center\" colspan=\"7\">";
            $this->salida .= "     <input class=\"input-submit\" type=\"submit\" name=\"adicionar\" value=\"ADICIONAR\" class=\"input-submit\">";
            $this->salida .= "    </td>";
            $this->salida .= "   </tr>";
            $this->salida .= "    </table><BR>";
            $this->salida .= "      </form>";
            //$Paginador = new ClaseHTML();
      $this->actionPaginador=ModuloGetURL('app','CajaGeneral','user','LlamaBuscadorProductoInv',array("NoLiquidacion"=>$NoLiquidacion,"TipoDocumento"=>$TipoDocumento,"Documento"=>$Documento,"nombrePaciente"=>$nombrePaciente,"cuenta"=>$cuenta,"ingreso"=>$ingreso,"codigoBus"=>$codigoBus,"DescripcionBus"=>$DescripcionBus));
            //$this->salida .= "        ".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionPaginador);
        }else{
      $this->salida .= "    <table width=\"60%\" border=\"0\" align=\"center\">";
      $this->salida .= "    <tr><td align=\"center\" class=\"label_error\">NO SE ENCONTRARON REGISTROS CON ESTOS PARAMETROS</td></tr>";
      $this->salida .= "      </table><BR>";
        }
    //$this->salida .= "        </form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
  }
//FIN FORMA PARA LA BUSQUEDA DE PRODUCTOS DE INENTARIO


/**
    *       FormaMensaje => muestra mensajes al usuario
    *
    *       @Author DRA.
    *       @access Private
    *       @param string => mensaje a mostrar
    *       @param string => titulo de la tabla
    *       @param string => action del form
    *       @param string => value del input-submit
    *       @return boolean
    */
    function FormaMensaje($mensaje,$titulo,$accion,$boton)
    {
        $this->salida .= ThemeAbrirTabla($titulo)."<br>";
        $this->salida .= "<table width=\"60%\" align=\"center\" class=\"normal_10\" border='0'>\n";
        $this->salida .= "  <form name=\"formaMensaje\" action=\"$accion\" method=\"post\">\n";
        $this->salida .= "      <tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>\n";
        if(!empty($boton)){
            $this->salida .= "  <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"$boton\"></td></tr>\n";
        }
        else{
            $this->salida .= "  <tr><td colspan=\"2\" align=\"center\">&nbsp;</td></tr>\n";
        }
        $this->salida .= "  </form>\n";
        $this->salida .= "</table>\n";
        $this->salida .= themeCerrarTabla();
        return true;
    }














    //lo de lorena q  ue esta inseerttando claudia

    function ProgramacionCitasImagen($numerOrdenId,$tipoIdPaciente,$PacienteId)
    {//echo '==>>>'.$numerOrdenId;
        $this->salida .= ThemeAbrirTabla('CITAS IMAGENOLOGIA');
        $accion=ModuloGetURL('app','Os_Atencion','user','SeleccionEquiposImagen',array("numerOrdenId"=>$numerOrdenId,"tipoIdPaciente"=>$tipoIdPaciente,"PacienteId"=>$PacienteId));
        $this->salida .= "    <form name=\"forma\" action=\"$accion\" method=\"post\">";
        $TiposEquipos=$this->TiposEquiposImagen();
        if($TiposEquipos)
        {
                $this->salida .= "       <table class=\"normal_10\" border=\"0\" width=\"50%\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
                $this->salida .= "       <tr><td width=\"100%\">";
                $this->salida .= "       <fieldset><legend class=\"field\">SELECCION TIPO EQUIPO</legend>";
                $this->salida .= "       <br><table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"70%\" align=\"center\">";
                for($i=0;$i<sizeof($TiposEquipos);$i++)
                {
                $this->salida .= "       <tr class=\"modulo_list_claro\">";
                $this->salida .= "       <td class=\"label\">".$TiposEquipos[$i]['descripcion']."</td>";
                if ($TiposEquipos[$i]['tipo_equipo_imagen_id']==$_SESSION['citas']['tipo_equipo'])
                {
          $this->salida .= "       <td width=\"5%\"><input type=\"radio\" checked name=\"tipoEquipo\" value=\"".$TiposEquipos[$i]['tipo_equipo_imagen_id']."\"></td>";
                }
                else
                {
          $this->salida .= "       <td width=\"5%\"><input type=\"radio\" name=\"tipoEquipo\" value=\"".$TiposEquipos[$i]['tipo_equipo_imagen_id']."\"></td>";
                }
                $this->salida .= "       </tr>";
                }
                $this->salida .= "       </table><br>";
                $this->salida .= "         </fieldset>";
                $this->salida .= "       </td></tr>";
                $this->salida .= "       </table><BR>";

                $this->salida .= "       <table class=\"normal_10\" border=\"0\" width=\"60%\" align=\"center\">";
                $this->salida .= "       <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\">";
                $this->salida .= "       <input class=\"input-submit\" type=\"submit\" name=\"Salir\" value=\"SALIR\"></td></tr>";
                $this->salida .= "       </table>";
        }
        else
        {
            $this->salida .= "       <table class=\"normal_10\" border=\"0\" width=\"60%\" align=\"center\">";
            $this->salida .= "       <tr><td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Salir\" value=\"SALIR\"></td></tr>";
            $this->salida .= "       </table>";
        }
        $this->salida .= "    </form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

        function ReservaEquipoTurno($tipoEquipo,$numerOrdenId,$tipoIdPaciente,$PacienteId)
    {

        $this->salida .= ThemeAbrirTabla('CITAS IMAGENOLOGIA');
        $this->salida .= "<SCRIPT>";
        $this->salida .= "function IntervalosCheck(frm,valor,interval){";
        $this->salida .= "  ArrayElements= new Array();";
        $this->salida .= "  ArrayValores= new Array();";
    $this->salida .= "  var j=0;";
        $this->salida .= "  var numElements=0;";
        $this->salida .= "  vector=valor.split('/');";
        $this->salida .= "  equipvalor=vector[0];";
    $this->salida .= "  fechavalor=vector[1];";
        $this->salida .= "  for(i=0;i<frm.elements.length;i++){";
    $this->salida .= "    if(frm.elements[i].type=='checkbox'){";
        $this->salida .= "      cadena=frm.elements[i].value;";
        $this->salida .= "      vector=cadena.split('/');";
        $this->salida .= "      equip=vector[0];";
        $this->salida .= "      fecha=vector[1];";
    $this->salida .= "      if(equipvalor==equip){";
        $this->salida .= "        if(frm.elements[i].checked){";
        $this->salida .= "          numElements=numElements+1;";
        $this->salida .= "          ArrayElements[j]=i;";
    $this->salida .= "          ArrayValores[j]=frm.elements[i].value;";
    $this->salida .= "          j++;";
    $this->salida .= "        }";
        $this->salida .= "      }else{";
    $this->salida .= "        frm.elements[i].checked=false";
        $this->salida .= "      }";
    $this->salida .= "    }";
    $this->salida .= "  }";
    $this->salida .= "  var valorcheck=ArrayValores[0];";
        $this->salida .= "  vector=valorcheck.split(' ');";
        $this->salida .= "  fechaTot=vector[0];";
        $this->salida .= "  HoraTot=vector[1];";
        $this->salida .= "  vector=HoraTot.split(':');";
        $this->salida .= "  HoraCom=vector[0];";
        $this->salida .= "  MinutosCom=vector[1];";
    $this->salida .= "  for(i=ArrayElements[0];i<=ArrayElements[j-1];i++){";
    $this->salida .= "    cadena=frm.elements[i].value;";
        $this->salida .= "    vector=cadena.split('/');";
        $this->salida .= "    equip=vector[0];";
        $this->salida .= "    fecha=vector[1];";
        $this->salida .= "    if(equip==equipvalor){";
        $this->salida .= "      vector=fecha.split(' ');";
        $this->salida .= "      fechaTot=vector[0];";
        $this->salida .= "      HoraTot=vector[1];";
        $this->salida .= "      vector=HoraTot.split(':');";
        $this->salida .= "      HoraAct=vector[0];";
        $this->salida .= "      MinutosAct=vector[1];";
        $this->salida .= "      if(HoraAct == HoraCom && MinutosAct == MinutosCom){";
        $this->salida .= "        frm.elements[i].checked=true;";
    $this->salida .= "      }else{";
        $this->salida .= "        alert ('no es Posible Seleccionar este Intervalo');";
        $this->salida .= "        for(i=0;i<frm.elements.length;i++){";
    $this->salida .= "          frm.elements[i].checked=false;";
        $this->salida .= "        }";
    $this->salida .= "      }";
        $this->salida .= "      MinutosCom=Number(MinutosCom)+Number(interval);";
        $this->salida .= "      if(MinutosCom==60){";
        $this->salida .= "        HoraCom=Number(HoraCom)+Number(1);";
    $this->salida .= "        if(HoraCom==24){";
    $this->salida .= "          HoraCom=00;";
        $this->salida .= "        }";
    $this->salida .= "        MinutosCom=00;";
    $this->salida .= "      }";
        $this->salida .= "    }";
    $this->salida .= "  }";
    $this->salida .= "}";
        $this->salida .= "</SCRIPT>";
        $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "  <tr><td align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td align=\"center\">&nbsp;</td></tr>";
        $this->salida .= "  </table>";
        $this->salida .= "   <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
    $this->salida .= "   <tr><td>";
        $_REQUEST['DiaEspe'];
        $this->salida.="\n".'<script>'."\n";
        $this->salida.='function year1(t)'."\n";
        $this->salida.='{'."\n";
        $this->salida.='window.location.href="Contenido.php?year="+t.elements[0].value+"&meses="+t.elements[1].value+"';
        foreach($_REQUEST as $v=>$v1)
        {
            if($v!='year' and $v!='meses' and $v!='DiaEspe')
            {
                if (is_array($v1)) {
                        foreach($v1 as $k2=>$v2) {
                            if (is_array($v2)) {
                                foreach($v2 as $k3=>$v3) {
                                    if (is_array($v3)) {
                                        foreach($v3 as $k4=>$v4) {
                                            $this->salida .= "&$v" . "[$k2][$k3][$k4]=$v4";
                                        }
                                    }else{
                                        $this->salida .= "&$v" . "[$k2][$k3]=$v3";
                                    }
                                }
                            }else{
                                $this->salida .= "&$v" . "[$k2]=$v2";
                            }
                        }
                    } else {
                        $this->salida .= "&$v=$v1";
                    }
            }
        }
        $this->salida.='";'."\n";
        $this->salida.='}'."\n";
        $this->salida.='</script>';
        $this->salida .='<form name="cosa">';
        $this->salida .= "<table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .='<tr align="center">';
        $this->salida .="<td class=\"label\">AÑO</td><td><select name=\"year\" onchange=\"year1(this.form)\" class=\"select\">";
        if(empty($_REQUEST['year']))
        {
            $a=explode("-",$_SESSION['CITASMES'][0]);
            $year=$_REQUEST['year']=$a[0];
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
            $a=explode("-",$_SESSION['CITASMES'][0]);
            if(empty($a[0]))
            {
                $mes=$_REQUEST['meses']=date("m");
                $year=date("Y");
            }
            else
            {
                $mes=$_REQUEST['meses']=$a[1];
            }
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
        $_REQUEST['metodo']='SeleccionEquiposImagen';
    $_REQUEST['modulo']='Os_Atencion';
        $_REQUEST['contenedor']='app';
        $_REQUEST['tipo']='user';
        $this->ReturnMetodoExterno('app', 'Agenda', 'user', 'CalendarioEstandard');
        //$this->salida .= CalendarioTodos();
        $this->salida .= "   </td></tr>";
        $this->salida .= "   </table><BR><BR>";
        if(empty($_REQUEST['DiaEspe'])){
      $_REQUEST['DiaEspe']=date("Y-m-d");
        }
        $cadena=explode('-',$_REQUEST['DiaEspe']);
        $anoP=$cadena[0];
        $mesP=$cadena[1];
        $diaP=$cadena[2];
        if(date("Y-m-d",mktime(0,0,0,$mesP,$diaP,$anoP))<date("Y-m-d",mktime(0,0,0,date("mes"),date("d"),date("Y")))){
      $_REQUEST['DiaEspe']=date('Y-m-d');
        }
    $accion=ModuloGetURL('app','Os_Atencion','user','CrearReservaCitaImagen');
        $this->salida .= "   <form name=\"forma\" action=\"$accion\" method=\"post\">";

    $rangoInterval=ModuloGetVar('app', 'Os_Atencion','RangoTurnosEquiposImagen');
        $rangoInicio=ModuloGetVar('app', 'Os_Atencion','InicioTurnoSalaImagen');
        $rangoDuracion=ModuloGetVar('app', 'Os_Atencion','DuracionTurnoSalaImagen');
        $FechaConver=mktime(0,0,0,$mesP,$diaP,$anoP);
        $Equipos=$this->SeleccionEquiposTipo($tipoEquipo);
    if($Equipos){
        $this->salida .= "   <table border=\"0\" width=\"95%\" align=\"center\" >";
    $this->salida .= "   <input type=\"hidden\" name=\"tipoEquipo\" value=\"$tipoEquipo\">";
        $this->salida .= "   <input type=\"hidden\" name=\"numerOrdenId\" value=\"$numerOrdenId\">";
        $this->salida .= "   <input type=\"hidden\" name=\"tipoIdPaciente\" value=\"$tipoIdPaciente\">";
        $this->salida .= "   <input type=\"hidden\" name=\"PacienteId\" value=\"$PacienteId\">";
        $this->salida .= "   <input type=\"hidden\" name=\"rangoInterval\" value=\"$rangoInterval\">";
    $this->salida .= "   <tr><td class=\"modulo_table_list_title\" align=\"center\" colspan=\"".(sizeof($Equipos) * 3)."\">PROGRAMACION DE EQUIPOS PARA EL DIA&nbsp;&nbsp;".strftime("%A %d de  %B de %Y",$FechaConver)."</td></tr>";
        $this->salida .= "   <tr>";
        for($i=0;$i<sizeof($Equipos);$i++){
      $this->salida .= " <td align=\"center\" colspan=\"3\" class=\"modulo_table_list_title\">".$Equipos[$i]['descripcion']."</td>";
        }
        $infoCadena=explode(':',$rangoInicio);
        $Rango=date("Y-m-d H:i:s",mktime($infoCadena[0],$infoCadena[1],0,$mesP,$diaP,$anoP));
    $RangoFinal=date("Y-m-d H:i:s",mktime(($infoCadena[0]+$rangoDuracion),$infoCadena[1],0,$mesP,$diaP,$anoP));
        while($Rango < $RangoFinal){
            $HoraMos=$this->HoraStamp($Rango);
            $HoraMos = explode (':',$HoraMos);
      $this->salida .= " <tr>";
            for($i=0;$i<sizeof($Equipos);$i++){
              $disponibilidadEquipo=$this->VerificarDisponibilidadEquipo($Rango,$Equipos[$i]['equipo_imagen_id'],$rangoInterval);
                $disponibilidadEquipoCitas=$this->VerificarDisponibilidadEquipoCitas($Rango,$Equipos[$i]['equipo_imagen_id'],$rangoInterval);
                if($disponibilidadEquipoCitas)
                {
          $this->salida .= " <td align=\"center\" width=\"5%\" class=\"modulo_list_claro\">".$HoraMos[0]." : ".$HoraMos[1]."</td>";
          $this->salida .= " <td align=\"left\" width=\"40%\" class=\"modulo_list_claro\">
                    <b><font color='#4D6FAD'>IDENTIFICACION:</font></b> ".$disponibilidadEquipoCitas['tipo_id_paciente']." - ".$disponibilidadEquipoCitas['paciente_id']." <BR>
                    <b><font color='#4D6FAD'>NOMBRE:</font></b> ".$disponibilidadEquipoCitas['primer_nombre']." ".$disponibilidadEquipoCitas['segundo_nombre']." ".$disponibilidadEquipoCitas['primer_apellido']." ".$disponibilidadEquipoCitas['segundo_apellido']."<BR>
                    <b><font color='#4D6FAD'>TELEFONO:</font></b> ".$disponibilidadEquipoCitas['residencia_telefono']."</td>";
                    $this->salida .= " <td align=\"center\" width=\"5%\" class=\"modulo_list_claro\">&nbsp;</td>";
                }
                elseif($disponibilidadEquipo==1 AND $Rango>=date('Y-m-d H:i:s')){
                  $this->salida .= " <td align=\"center\" width=\"5%\" class=\"modulo_list_oscuro\">".$HoraMos[0]." : ".$HoraMos[1]."</td>";
                    $this->salida .= " <td align=\"center\" class=\"modulo_list_oscuro\">&nbsp;</td>";
          $this->salida .= " <td align=\"center\" width=\"5%\" class=\"modulo_list_oscuro\"><input type=\"checkbox\" name=\"HoraProgram[]\" onclick=\"IntervalosCheck(this.form,this.value,'$rangoInterval')\"  value=\"".$Equipos[$i]['equipo_imagen_id']."/$Rango\"></td>";
                }else{
          $this->salida .= " <td align=\"center\" width=\"5%\" class=\"modulo_list_claro\">".$HoraMos[0]." : ".$HoraMos[1]."</td>";
          $this->salida .= " <td align=\"center\"  class=\"modulo_list_claro\">&nbsp;</td>";
                    $this->salida .= " <td align=\"center\" width=\"5%\" class=\"modulo_list_claro\">&nbsp;</td>";
                }
            }
          $this->salida .= " </tr>";
      $Rango=date('Y-m-d H:i:s',mktime($HoraMos[0],($HoraMos[1]+$rangoInterval),0,$mesP,$diaP,$anoP));
        }
        $this->salida .= "   </tr>";
    $this->salida .= "   </table>";
        }
    $this->salida .= "   <table border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "   <tr><td align=\"center\"><input class= input-submit type=\"submit\" name = ASIGNAR value = ASIGNAR>";
        $this->salida .= "    <input class= input-submit type=\"submit\" name = VOLVER value = VOLVER></td></tr>";
    $this->salida .= "   </table>";
    $this->salida .= "   </form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
* Funcion que Saca los años para el calendario a partir del año actual
* @return array
*/
  function AnosAgenda($Seleccionado='False',$ano)
    {

        $anoActual=date("Y");
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
                      $this->salida .=" <option value=\"$titulo\" selected>$titulo</option>";
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
                  }else{
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
        $mesActual=date("m");
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

    function FormaBusquedaCitaImagen()
    {
    $this->salida .= ThemeAbrirTabla('ASIGNACION PROFESIONAL CITAS IMAGENOLOGIA');
    $this->salida .= "  <table border=\"0\" width=\"60%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "  <tr><td align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "  </td></tr>";
        $this->salida .= "  <tr><td align=\"center\">&nbsp;</td></tr>";
        $this->salida .= "  </table>";
        $this->salida .= "   <table border=\"0\" width=\"60%\" align=\"center\" class=\"modulo_table_list\">";
    $this->salida .= "   <tr><td>";
        $_REQUEST['DiaEspe'];
        $this->salida.="\n".'<script>'."\n";
        $this->salida.='function year1(t)'."\n";
        $this->salida.='{'."\n";
        $this->salida.='window.location.href="Contenido.php?year="+t.elements[0].value+"&meses="+t.elements[1].value+"';
        foreach($_REQUEST as $v=>$v1)
        {
            if($v!='year' and $v!='meses' and $v!='DiaEspe')
            {
                if (is_array($v1)) {
                        foreach($v1 as $k2=>$v2) {
                            if (is_array($v2)) {
                                foreach($v2 as $k3=>$v3) {
                                    if (is_array($v3)) {
                                        foreach($v3 as $k4=>$v4) {
                                            $this->salida .= "&$v" . "[$k2][$k3][$k4]=$v4";
                                        }
                                    }else{
                                        $this->salida .= "&$v" . "[$k2][$k3]=$v3";
                                    }
                                }
                            }else{
                                $this->salida .= "&$v" . "[$k2]=$v2";
                            }
                        }
                    } else {
                        $this->salida .= "&$v=$v1";
                    }
            }
        }
        $this->salida.='";'."\n";
        $this->salida.='}'."\n";
        $this->salida.='</script>';
        $this->salida .='<form name="cosa">';
        $this->salida .= "<table border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .='<tr align="center">';
        $this->salida .="<td class=\"label\">AÑO</td><td><select name=\"year\" onchange=\"year1(this.form)\" class=\"select\">";
        if(empty($_REQUEST['year']))
        {
            $a=explode("-",$_SESSION['CITASMES'][0]);
            $year=$_REQUEST['year']=$a[0];
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
            $a=explode("-",$_SESSION['CITASMES'][0]);
            if(empty($a[0]))
            {
                $mes=$_REQUEST['meses']=date("m");
                $year=date("Y");
            }
            else
            {
                $mes=$_REQUEST['meses']=$a[1];
            }
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
        $_REQUEST['metodo']='AsignacionProfCitasImagen';
    $_REQUEST['modulo']='Os_Atencion';
        $_REQUEST['contenedor']='app';
        $_REQUEST['tipo']='user';
        $this->ReturnMetodoExterno('app', 'Agenda', 'user', 'CalendarioEstandard');
        //$this->salida .= CalendarioTodos();
        $this->salida .= "   </td></tr>";
        $this->salida .= "   </table><BR><BR>";
        if(empty($_REQUEST['DiaEspe'])){
      $_REQUEST['DiaEspe']=date("Y-m-d");
        }
        $cadena=explode('-',$_REQUEST['DiaEspe']);
        $anoP=$cadena[0];
        $mesP=$cadena[1];
        $diaP=$cadena[2];
        /*if(date("Y-m-d",mktime(0,0,0,$mesP,$diaP,$anoP))<date("Y-m-d",mktime(0,0,0,date("mes"),date("d"),date("Y")))){
      $_REQUEST['DiaEspe']=date('Y-m-d');
        }*/
        //echo ($_REQUEST['DiaEspe']);
        $accion=ModuloGetURL('app','Os_Atencion','user','Llamado_Os_Atencion',array('nombre'=>$_SESSION['citas_profesional']['nombre'],'tipo_id_paciente'=>$_SESSION['citas_profesional']['tipo'],'paciente_id'=>$_SESSION['citas_profesional']['id']));
        $this->salida .= "   <form name=\"forma\" action=\"$accion\" method=\"post\">";
        $Citas=$this->SeleccionCitasPendientesImagen($_REQUEST['DiaEspe']);
    if($Citas){
          $this->salida .= "   <table border=\"0\" width=\"95%\" align=\"center\" class=\"modulo_table_list\">";
            $FechaConver=mktime(0,0,0,$mesP,$diaP,$anoP);
      $this->salida .= "   <tr><td class=\"modulo_table_list_title\" colspan=\"8\" align=\"center\">PROGRAMACION DE CITAS PARA EL DIA&nbsp;&nbsp;".strftime("%A %d de  %B de %Y",$FechaConver)."</td></tr>";
      $this->salida .= "   <tr>";
            $this->salida .= "   <td class=\"modulo_table_list_title\" align=\"center\">IDENTIFICACION</td>";
            $this->salida .= "   <td class=\"modulo_table_list_title\" align=\"center\">NOMBRES</td>";
            $this->salida .= "   <td class=\"modulo_table_list_title\" align=\"center\">EQUIPO</td>";
            $this->salida .= "   <td class=\"modulo_table_list_title\" align=\"center\">FECHA</td>";
            $this->salida .= "   <td class=\"modulo_table_list_title\" align=\"center\">DURACION</td>";
      $this->salida .= "   <td class=\"modulo_table_list_title\" align=\"center\">No. ORDEN</td>";
      if($_REQUEST['DiaEspe']<date("Y-m-d"))
            {
              $this->salida .= "   <td colspan = 2 class=\"modulo_table_list_title\" align=\"center\">PROFESIONAL</td>";
            }
            else
            {
        $this->salida .= "   <td class=\"modulo_table_list_title\" align=\"center\">PROFESIONAL</td>";
                $this->salida .= "   <td class=\"modulo_table_list_title\" align=\"center\">OPCION</td>";
            }
      $this->salida .= "   </tr>";
            $y=0;
            for($i=0;$i<sizeof($Citas);$i++){
              if($y % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
        $this->salida .= "   <tr class=\"$estilo\">";
        $this->salida .= "   <td>".$Citas[$i]['tipo_id_paciente']." - ".$Citas[$i]['paciente_id']."</td>";
                $this->salida .= "   <td>".$Citas[$i]['primer_nombre']." ".$Citas[$i]['segundo_nombre']." ".$Citas[$i]['primer_apellido']." ".$Citas[$i]['segundo_apellido']."</td>";
                $this->salida .= "   <td>".$Citas[$i]['equipo']."</td>";
                $this->salida .= "   <td>".$Citas[$i]['fecha_hora_cita']."</td>";
                $this->salida .= "   <td>".$Citas[$i]['duracion']."</td>";
                $this->salida .= "   <td>".$Citas[$i]['numero_orden_id']."</td>";

                if($_REQUEST['DiaEspe']<date("Y-m-d"))
                {
            $this->salida .= "   <td colspan = 2>".$Citas[$i]['nombre']."</td>";
                }
                else
                {
                    $this->salida .= "   <td>".$Citas[$i]['nombre']."</td>";
                    $action=ModuloGetURL('app','Os_Atencion','user','AsignacionProfesionalCita',array("citaId"=>$Citas[$i]['os_imagen_cita_id'],"identificacion"=>$Citas[$i]['tipo_id_paciente'].' - '.$Citas[$i]['paciente_id'],
                    "nombre"=>$Citas[$i]['primer_nombre'].' '.$Citas[$i]['segundo_nombre'].' '.$Citas[$i]['primer_apellido'].' '.$Citas[$i]['segundo_apellido'],"equipo"=>$Citas[$i]['equipo'],
                    "duracion"=>$Citas[$i]['duracion'],"numeroOrden"=>$Citas[$i]['numero_orden_id'],"fechaInicio"=>$Citas[$i]['fecha_hora_cita']));
                    $this->salida .= "    <td align=\"center\"><a href=\"$action\" class=\"link\"><b>ASIGNAR</b></a></td></tr>";
                }

        $this->salida .= "   </tr>";
                $y++;
            }
      $this->salida .= "   </table>";
        }
        else
        {
        $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
        $this->salida.="<tr>";
        $this->salida.="  <td class=\"label_error\" align=\"center\" colspan=\"2\">NO HAY PROGRAMACION PARA ESTE DIA</td>";
        $this->salida.="</tr>";
      $this->salida.="</table>";
        }
        $this->salida .= "   <BR><table border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "   <tr><td align=\"center\"><input type=\"submit\" class=\"input-submit\" value=\"REGRESAR\" name=\"regresar\"></td></tr>";
        $this->salida .= "   </table>";
    $this->salida .= "   </form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    function FormaAsignacionProfesionalCita($citaId,$identificacion,$nombre,$equipo,$duracion,$numeroOrden,$fechaInicio){



        $this->salida .= ThemeAbrirTabla('ASIGNACION PROFESIONAL CITA IMAGENOLOGIA');
        $accion=ModuloGetURL('app','Os_Atencion','user','GuardarAsignacionProf',array("citaId"=>$citaId,"identificacion"=>$identificacion,"nombre"=>$nombre,"equipo"=>$equipo,"duracion"=>$duracion,"numeroOrden"=>$numeroOrden,"fechaInicio"=>$fechaInicio));
        $this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";
        $this->salida .= "       <table class=\"normal_10\" border=\"0\" width=\"50%\" align=\"center\">";
        $this->salida .= "       <tr><td width=\"100%\">";
        $this->salida .= "       <fieldset><legend class=\"field\">DATOS DE LA CITA</legend>";
        $this->salida .= "       <br><table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "       <tr class=\"modulo_list_claro\"><td class=\"label\">IDENTIFICACION PACIENTE</td><td>$identificacion</td></tr>";
        $this->salida .= "       <tr class=\"modulo_list_claro\"><td class=\"label\">NOMBRE PACIENTE</td><td>$nombre</td></tr>";
        $this->salida .= "       <tr class=\"modulo_list_claro\"><td class=\"label\">EQUIPO</td><td>$equipo</td></tr>";
        $this->salida .= "       <tr class=\"modulo_list_claro\"><td class=\"label\">DURACION CITA</td><td>$duracion</td></tr>";
        $this->salida .= "       <tr class=\"modulo_list_claro\"><td class=\"label\">No. ORDEN</td><td>$numeroOrden</td></tr>";
    $this->salida .= "       </table><br>";
        $this->salida .= "         </fieldset>";
        $this->salida .= "       </td></tr>";
        $this->salida .= "       </table>";
        $this->salida .= "       <table class=\"normal_10\" border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "<tr><td colspan=\"4\" align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "</td></tr>";
        $this->salida .= "       <tr><td width=\"100%\">";
        $this->salida .= "       <fieldset><legend class=\"field\">SELECCION PROFESIONAL QUE ATIENDE LA CITA</legend>";
        $this->salida .= "       <br><table cellspacing=\"2\" cellpadding=\"3\"border=\"0\" width=\"95%\" align=\"center\">";
    $this->salida .= "       <tr class=\"modulo_list_claro\">";
        $this->salida .= "       <td class=\"label\">PROFESIONAL</td>";
        $this->salida .= "       <td align=\"center\"><select name=\"profesional\" class=\"select\">\n";
        $profesionales=$this->profesionalesEspecialistaTurnosImagen($fechaInicio,$duracion);
        $this->BuscarProfesionlesEspecialistas($profesionales,'False',$profesional);
        $this->salida .= "      </select></td>";
    $this->salida .= "       </tr>";
    $this->salida .= "       </table><br>";
        $this->salida .= "         </fieldset>";
        $this->salida .= "       </td></tr>";
        $this->salida .= "       </table>";

        $this->salida .= "       <table class=\"normal_10\" border=\"0\" width=\"60%\" align=\"center\">";
        $this->salida .= "       <tr><td align=\"center\"><input type=\"submit\" class=\"input-submit\" value=\"ASIGNAR\" name=\"asignar\">";
        $this->salida .= "       <input type=\"submit\" class=\"input-submit\" value=\"VOLVER\" name=\"volver\"></td></tr>";
    $this->salida .= "       </table>";
        $this->salida .= "</form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    function BuscarProfesionlesEspecialistas($profesionales,$Seleccionado='False',$Profesionales=''){

        switch($Seleccionado){
            case 'False':{
        $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
                for($i=0;$i<sizeof($profesionales);$i++){
                  $value=$profesionales[$i]['tercero_id'].'/'.$profesionales[$i]['tipo_id_tercero'];
                    $titulo=$profesionales[$i]['nombre_tercero'];
                    if($value==$Profesionales){
                    $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
                  }else{
            $this->salida .=" <option value=\"$value\">$titulo</option>";
                  }
              }
              break;
          }case 'True':{
              for($i=0;$i<sizeof($profesionales);$i++){
                $value=$profesionales[$i]['tercero_id'].'/'.$profesionales[$i]['tipo_id_tercero'];
                    $titulo=$profesionales[$i]['nombre'];
                  if($value==$Profesionales){
                    $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
                  }
                  $this->salida .=" <option value=\"$value\">$titulo</option>";
              }
              break;
          }
      }
    }
    /**
    *
    */
    function FormaBuscar()
    {
      IncludeFileModulo("OsAtencion","RemoteXajax", "app", "Os_Atencion");
      $action=ModuloGetURL('app','Os_Atencion','user','FormaDatosPaciente');

      $this->SetXajax(array("ValidarPlanAtencion"),null,"ISO-8859-1");
      
      $ctl = AutoCarga::factory("ClaseUtil");
      $this->salida .= $ctl->AcceptNum(false);

      $empresa=$_SESSION['LABORATORIO']['EMPRESA_ID'];
      $centro_utilidad=$_SESSION['LABORATORIO']['CENTROUTILIDAD'];

      $tipo_dato=$this->Consulta_tipo_dato($empresa,$centro_utilidad);

      $campo=$this->BuscarCamposObligatorios();

      $this->salida .= "<script>\n";
      $this->salida .= "  function EvaluarDatos(objeto)\n";
      $this->salida .= "  {\n";
      $this->salida .= "    e = document.getElementById('error');\n";
      $this->salida .= "    if(objeto.plan.value == '-1')\n";
      $this->salida .= "    {\n";
      $this->salida .= "      e.innerHTML = 'SE DEBE SELECCIONAR EL PLAN'\n";
      $this->salida .= "      return;\n";
      $this->salida .= "    }\n";
      $this->salida .= "    if(objeto.Documento.value == '')\n";
      $this->salida .= "    {\n";
      $this->salida .= "      e.innerHTML = 'DEBE INGRESAR EL NUMERO DEL DOCUMENTO'\n";
      $this->salida .= "      return;\n";
      $this->salida .= "    }\n";
      if($campo['historia_prefijo']['sw_mostrar']==1)
      {
        $this->salida .= "    if(objeto.prefijo.value == '')\n";
        $this->salida .= "    {\n";
        $this->salida .= "      e.innerHTML = 'DEBE INGRESAR EL PREFIJO DE LA HISTORIA'\n";
        $this->salida .= "      return;\n";
        $this->salida .= "    }\n";
      }
      if($campo['historia_numero']['sw_mostrar']==1)
      {
        $this->salida .= "    if(objeto.historia.value == '')\n";
        $this->salida .= "    {\n";
        $this->salida .= "      e.innerHTML = 'DEBE INGRESAR EL NÚMERO DE LA HISTORIA'\n";
        $this->salida .= "      return;\n";
        $this->salida .= "    }\n";
      }
      $this->salida .= "    xajax_ValidarPlanAtencion(xajax.getFormValues('formabuscar'));\n";
      $this->salida .= "  }\n";
      $this->salida .= "  function Continuar(objeto)\n";
      $this->salida .= "  {\n";
      $this->salida .= "    objeto.action = '".$action."';\n";
      $this->salida .= "    objeto.submit();\n";
      $this->salida .= "  }\n";
      $this->salida .= "</script>\n";
      
      $this->salida .= ThemeAbrirTabla('SOLICITUD MANUAL - BUSCAR PACIENTE ( '.$_SESSION['LABORATORIO']['NOM_DPTO'].' )');
      $this->salida .= "<form name=\"formabuscar\" id=\"formabuscar\" action=\"javascript:EvaluarDatos(document.formabuscar)\" method=\"post\">\n";
      $this->salida .= "  <table width=\"50%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
      //$this->salida .= "  <form name=\"formabuscar\" action=\"$action\" method=\"post\">\n";
      $this->salida .= $this->SetStyle("MensajeError");
      $responsables = $this->responsables();
      $this->salida .= "    <tr class=\"formulacion_table_list\">\n";
      $this->salida .= "      <td align=\"left\">* PLAN: </td>\n";
      $this->salida .= "      <td class=\"modulo_list_claro\" align=\"left\">\n";
      if(!empty($responsables))
      {
        $this->salida .= "        <select name=\"plan\" class=\"select\">\n";
        $this->salida .= "          <option value=\"-1\">-------SELECCIONE-------</option>\n";
        $s = "";
        foreach($responsables as $key => $dtl)
        {
          ($dtl['plan_id']==$_REQUEST['plan'])? $s = "selected": $s = "";
          $this->salida .= "          <option value=\"".$dtl['plan_id']."\" ".$s.">".$dtl['plan_descripcion']."</option>\n";
        }
        $this->salida .= "        </select>\n";
      }
      else
      {
        $this->salida .= "        NO HAY PLANES ACTIVOS PARA LA EMPRESA\n";
      }
      $this->salida .= "      </td>\n";
      $this->salida .= "    </tr>\n";
      $this->salida .= "    <tr class=\"formulacion_table_list\">\n";
      $this->salida .= "      <td align=\"left\">* TIPO DOCUMENTO: </td>\n";
      $this->salida .= "      <td class=\"modulo_list_claro\" align=\"left\">\n";
      $this->salida .= "        <select name=\"Tipo\" class=\"select\">";
      $tipo_id=$this->tipo_id_paciente();
      foreach($tipo_id as $value=>$titulo)
      {
        ($value==$_REQUEST['Tipo'])? $s = "selected": $s = "";
        $this->salida .= "        <option value=\"$value\" ".$s.">$titulo</option>";
      }
      $this->salida .= "        </select>\n";
      $this->salida .= "      </td>\n";
      $this->salida .= "    </tr>\n";
      $this->salida .= "    <tr class=\"formulacion_table_list\">\n";
      $this->salida .= "      <td align=\"left\" >* DOCUMENTO: </td>\n";
      
       if(!empty($tipo_dato))
       {
                 if($tipo_dato['sw_alfanumerico']=='0')
               {
                   
                        $this->salida .= "      <td class=\"modulo_list_claro\" align=\"left\">\n";
                        $this->salida .= "        <input type=\"text\" class=\"input-text\" name=\"Documento\"  onkeypress=\"return acceptNum(event)\"   maxlength=\"32\" value=\"".$_REQUEST['Documento']."\">\n";
                        $this->salida .= "      </td>\n";
                   
                   
                }else
                {
                        $this->salida .= "      <td class=\"modulo_list_claro\" align=\"left\">\n";
                        $this->salida .= "        <input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value=\"".$_REQUEST['Documento']."\">\n";
                        $this->salida .= "      </td>\n";

                }
                
      
        }else
        {
        
              $this->salida .= "      <td class=\"modulo_list_claro\" align=\"left\">\n";
              $this->salida .= "        <input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value=\"".$_REQUEST['Documento']."\">\n";
              $this->salida .= "      </td>\n";
        
        
        }
    
      $this->salida .= "    </tr>\n";
      
      if($campo[historia_prefijo][sw_mostrar]==1)
      {
        $this->salida .= "    <tr class=\"formulacion_table_list\">\n";
        $this->salida .= "      <td align=\"left\" >* PREFIJO: </td>";
        $this->salida .= "      <td class=\"modulo_list_claro\" align=\"left\">\n";
        $this->salida .= "        <input type=\"text\" maxlength=\"4\" name=\"prefijo\" value=\"".$_REQUEST['prefijo']."\" class=\"input-text\">\n";
        $this->salida .= "      </td>";
        $this->salida .= "    </tr>";
      }
      if($campo[historia_numero][sw_mostrar]==1)
      {
        $this->salida .= "    <tr class=\"formulacion_table_list\">\n";
        $this->salida .= "      <td align=\"left\" >* No. HISTORIA: </td>";
        $this->salida .= "      <td class=\"modulo_list_claro\" align=\"left\">\n";
        $this->salida .= "        <input type=\"text\" maxlength=\"50\" name=\"historia\" value=\"".$_REQUEST['historia']."\" class=\"input-text\">\n";
        $this->salida .= "      </td>\n";
        $this->salida .= "    </tr>\n";
      }
      $this->salida .= "  </table>\n";
      $this->salida .= "  <center>\n";
      $this->salida .= "    <div id=\"error\" class=\"label_error\"></div>\n";
      $this->salida .= "  </center>\n";
      $this->salida .= "  <table align=\"center\" width=\"60%\">\n";
      $this->salida .= "    <tr>\n";
      $this->salida .= "      <td align=\"center\">\n";
      $this->salida .= "        <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\">\n";
      $this->salida .= "      </td>\n";
      $this->salida .= " </form>\n";
      $actionM=ModuloGetURL('app','Os_Atencion','user','FormaMetodoBuscar');
      $this->salida .= " <form name=\"formavolver\" action=\"$actionM\" method=\"post\">";
      $this->salida .= "      <td align=\"center\">\n";
      $this->salida .= "        <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\">\n";
      $this->salida .= "      </td>\n";
      $this->salida .= "  </form>\n";
      $this->salida .= "    </tr>\n";
      $this->salida .= "  </table>\n";
      $this->salida .= ThemeCerrarTabla();
      return true;
    }
    /**
    *
    *
    function FormaBuscar()
    {
      IncludeFileModulo("OsAtencion","RemoteXajax", "app", "Os_Atencion");

      $this->SetXajax(array("ValidarPlanAtencion"),null,"ISO-8859-1");
      $action=ModuloGetURL('app','Os_Atencion','user','BuscarPaciente');
      
      $campo=$this->BuscarCamposObligatorios();

      $this->salida .= "<script>\n";
      $this->salida .= "  function EvaluarDatos(objeto)\n";
      $this->salida .= "  {\n";
      $this->salida .= "    e = document.getElementById('error');\n";
      $this->salida .= "    if(objeto.plan.value == '-1')\n";
      $this->salida .= "    {\n";
      $this->salida .= "      e.innerHTML = 'SE DEBE SELECCIONAR EL PLAN'\n";
      $this->salida .= "      return;\n";
      $this->salida .= "    }\n";
      $this->salida .= "    if(objeto.Documento.value == '')\n";
      $this->salida .= "    {\n";
      $this->salida .= "      e.innerHTML = 'DEBE INGRESAR EL NUMERO DEL DOCUMENTO'\n";
      $this->salida .= "      return;\n";
      $this->salida .= "    }\n";
      if($campo['historia_prefijo']['sw_mostrar']==1)
      {
        $this->salida .= "    if(objeto.prefijo.value == '')\n";
        $this->salida .= "    {\n";
        $this->salida .= "      e.innerHTML = 'DEBE INGRESAR EL PREFIJO DE LA HISTORIA'\n";
        $this->salida .= "      return;\n";
        $this->salida .= "    }\n";
      }
      if($campo['historia_numero']['sw_mostrar']==1)
      {
        $this->salida .= "    if(objeto.historia.value == '')\n";
        $this->salida .= "    {\n";
        $this->salida .= "      e.innerHTML = 'DEBE INGRESAR EL NÚMERO DE LA HISTORIA'\n";
        $this->salida .= "      return;\n";
        $this->salida .= "    }\n";
      }
      $this->salida .= "    xajax_ValidarPlanAtencion(xajax.getFormValues('formabuscar'));\n";
      $this->salida .= "  }\n";
      $this->salida .= "  function Continuar(objeto)\n";
      $this->salida .= "  {\n";
      $this->salida .= "    objeto.action = '".$action."';\n";
      $this->salida .= "    objeto.submit();\n";
      $this->salida .= "  }\n";
      $this->salida .= "</script>\n";
      
      $this->salida .= ThemeAbrirTabla('SOLICITUD MANUAL - BUSCAR PACIENTE ( '.$_SESSION['LABORATORIO']['NOM_DPTO'].' )');
      $this->salida .= "<form name=\"formabuscar\" id=\"formabuscar\" action=\"javascript:EvaluarDatos(document.formabuscar)\" method=\"post\">\n";
      $this->salida .= "  <table width=\"50%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
      //$this->salida .= "  <form name=\"formabuscar\" action=\"$action\" method=\"post\">\n";
      $this->salida .= $this->SetStyle("MensajeError");
      $responsables = $this->responsables();
      $this->salida .= "    <tr class=\"formulacion_table_list\">\n";
      $this->salida .= "      <td align=\"left\">* PLAN: </td>\n";
      $this->salida .= "      <td class=\"modulo_list_claro\" align=\"left\">\n";
      if(!empty($responsables))
      {
        $this->salida .= "        <select name=\"plan\" class=\"select\">\n";
        $this->salida .= "          <option value=\"-1\">-------SELECCIONE-------</option>\n";
        $s = "";
        foreach($responsables as $key => $dtl)
        {
          ($dtl['plan_id']==$_REQUEST['plan'])? $s = "selected": $s = "";
          $this->salida .= "          <option value=\"".$dtl['plan_id']."\" ".$s.">".$dtl['plan_descripcion']."</option>\n";
        }
        $this->salida .= "        </select>\n";
      }
      else
      {
        $this->salida .= "        NO HAY PLANES ACTIVOS PARA LA EMPRESA\n";
      }
      $this->salida .= "      </td>\n";
      $this->salida .= "    </tr>\n";
      $this->salida .= "    <tr class=\"formulacion_table_list\">\n";
      $this->salida .= "      <td align=\"left\">* TIPO DOCUMENTO: </td>\n";
      $this->salida .= "      <td class=\"modulo_list_claro\" align=\"left\">\n";
      $this->salida .= "        <select name=\"Tipo\" class=\"select\">";
      $tipo_id=$this->tipo_id_paciente();
      foreach($tipo_id as $value=>$titulo)
      {
        ($value==$_REQUEST['Tipo'])? $s = "selected": $s = "";
        $this->salida .= "        <option value=\"$value\" ".$s.">$titulo</option>";
      }
      $this->salida .= "        </select>\n";
      $this->salida .= "      </td>\n";
      $this->salida .= "    </tr>\n";
      $this->salida .= "    <tr class=\"formulacion_table_list\">\n";
      $this->salida .= "      <td align=\"left\" >* DOCUMENTO: </td>\n";
      $this->salida .= "      <td class=\"modulo_list_claro\" align=\"left\">\n";
      $this->salida .= "        <input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value=\"".$_REQUEST['Documento']."\">\n";
      $this->salida .= "      </td>\n";
      $this->salida .= "    </tr>\n";
      
      if($campo[historia_prefijo][sw_mostrar]==1)
      {
        $this->salida .= "    <tr class=\"formulacion_table_list\">\n";
        $this->salida .= "      <td align=\"left\" >* PREFIJO: </td>";
        $this->salida .= "      <td class=\"modulo_list_claro\" align=\"left\">\n";
        $this->salida .= "        <input type=\"text\" maxlength=\"4\" name=\"prefijo\" value=\"".$_REQUEST['prefijo']."\" class=\"input-text\">\n";
        $this->salida .= "      </td>";
        $this->salida .= "    </tr>";
      }
      if($campo[historia_numero][sw_mostrar]==1)
      {
        $this->salida .= "    <tr class=\"formulacion_table_list\">\n";
        $this->salida .= "      <td align=\"left\" >* No. HISTORIA: </td>";
        $this->salida .= "      <td class=\"modulo_list_claro\" align=\"left\">\n";
        $this->salida .= "        <input type=\"text\" maxlength=\"50\" name=\"historia\" value=\"".$_REQUEST['historia']."\" class=\"input-text\">\n";
        $this->salida .= "      </td>\n";
        $this->salida .= "    </tr>\n";
      }
      $this->salida .= "  </table>\n";
      $this->salida .= "  <center>\n";
      $this->salida .= "    <div id=\"error\" class=\"label_error\"></div>\n";
      $this->salida .= "  </center>\n";
      $this->salida .= "  <table align=\"center\" width=\"60%\">\n";
      $this->salida .= "    <tr>\n";
      $this->salida .= "      <td align=\"center\">\n";
      $this->salida .= "        <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\">\n";
      $this->salida .= "      </td>\n";
      $this->salida .= " </form>\n";
      $actionM=ModuloGetURL('app','Os_Atencion','user','FormaMetodoBuscar');
      $this->salida .= " <form name=\"formavolver\" action=\"$actionM\" method=\"post\">";
      $this->salida .= "      <td align=\"center\">\n";
      $this->salida .= "        <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\">\n";
      $this->salida .= "      </td>\n";
      $this->salida .= "  </form>\n";
      $this->salida .= "    </tr>\n";
      $this->salida .= "  </table>\n";
      $this->salida .= ThemeCerrarTabla();
      return true;
    }*/

    function DatosPaciente()
    {
                if(empty($_SESSION['SOLICITUD']['PACIENTE']['nombre']))
                {
                        $nom=$this->NombrePaciente($_SESSION['DATOS_PACIENTE']['tipo_id'],$_SESSION['DATOS_PACIENTE']['paciente_id']);
                        $_SESSION['DATOS_PACIENTE']['nombre']=$nom['nombre'];
                }
                $this->salida .= "       <table width=\"80%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">";
                $this->salida .= "          <tr>";
                $this->salida .= "              <td class=\"modulo_table_list_title\" colspan=\"6\" align=\"left\">DATOS PACIENTE </td>";
                $this->salida .= "          </tr>";
                $this->salida .= "          <tr>";
                $this->salida .= "              <td width=\"10%\" class=\"modulo_table_list_title\" width=\"15%\">IDENTIFICACION: </td><td width=\"15%\" class=\"modulo_list_claro\">".$_SESSION['DATOS_PACIENTE']['tipo_id_paciente']." ".$_SESSION['DATOS_PACIENTE']['paciente_id']."</td>";
                $this->salida .= "              <td width=\"10%\" class=\"modulo_table_list_title\" width=\"15%\">PACIENTE:</td><td width=\"30%\" class=\"modulo_list_claro\">".$_SESSION['DATOS_PACIENTE']['nombre']."</td>";
                $this->salida .= "              <td width=\"10%\" class=\"modulo_table_list_title\" width=\"10%\">PLAN:</td><td width=\"30%\" class=\"modulo_list_claro\">".$_SESSION['DATOS_PACIENTE']['plan_descripcion']."</td>";
                $this->salida .= "          </tr>";
                $this->salida .= "          </table><BR>";
    }

    function FormaDatosSolicitud()
    {
                $this->salida .= ThemeAbrirTabla('SOLICITUD MANUAL ( '.$_SESSION['LABORATORIO']['NOM_DPTO'].' )');
                $this->DatosPaciente();
                $accion=ModuloGetURL('app','Os_Atencion','user','GuardarDatosSolicitud');
                $this->salida .= "             <form name=\"forma\" action=\"$accion\" method=\"post\">";
                $this->salida .= "                <table width=\"60%\" align=\"center\" border=\"0\">";
                $this->salida .= $this->SetStyle("MensajeError");
                if(empty($_REQUEST['Fecha']))
                {  $_REQUEST['Fecha']=date("d/m/Y");  }
                $this->salida .= "  <tr>";
                $this->salida .= "  <td class=\"".$this->SetStyle("Fecha")."\">FECHA: </td>";
                $this->salida .= "  <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"Fecha\" size=\"12\" value=\"".$_REQUEST['Fecha']."\">";
                $this->salida .= "&nbsp;&nbsp;".ReturnOpenCalendario('forma','Fecha','/')."</td>";
                $this->salida .= "  </tr>";
                $this->salida .= "  <tr>";
                $this->salida .= "                  <td class=\"".$this->SetStyle("Serv")."\">SERVICIO: </td>";
                $this->salida .= "                    <td colspan=\"2\"><select name=\"Serv\" class=\"select\">";
                $ser=$this->TiposServicios();
                $this->salida .=" <option value=\"-1\">-------SELECCIONE-------</option>";
                for($i=0; $i<sizeof($ser); $i++)
                {
                        if($ser[$i][servicio]==$_REQUEST['Serv'])
                        {  $this->salida .=" <option value=\"".$ser[$i][servicio]."\" selected>".$ser[$i][descripcion]."</option>";  }
                        else
                        {  $this->salida .=" <option value=\"".$ser[$i][servicio]."\">".$ser[$i][descripcion]."</option>";  }
                }
                $this->salida .= "              </select></td></tr>";
                $this->salida .= "               <tr>";
                //proveedores
                $this->salida .= "                  <td class=\"".$this->SetStyle("Origen1")."\">ENTIDAD SOLICITA: </td>";
                $this->salida .= "                    <td colspan=\"2\"><select name=\"Origen1\" class=\"select\">";
                $pla=$this->PlanesProveedores();
                $this->salida .=" <option value=\"\">-------SELECCIONE-------</option>";
                for($i=0; $i<sizeof($pla); $i++)
                {
                  if($pla[$i][plan_proveedor_id]==$_REQUEST['Origen1'])
                  {  
                    $this->salida .=" <option value=\"".$pla[$i][plan_proveedor_id]."\" selected>".$pla[$i][plan_descripcion]."</option>";  
                  }
                  else
                  {  
                    $this->salida .=" <option value=\"".$pla[$i][plan_proveedor_id]."\">".$pla[$i][plan_descripcion]."</option>";  
                  }
                }
                $this->salida .= "              </select></td></tr>";
                //fin proeveedores
                $this->salida .= "                  <td class=\"".$this->SetStyle("Origen")."\">ENTIDAD SOLICITA: </td>";
                $this->salida .= "                  <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"Origen\" value=\"".$_REQUEST['Origen']."\" size=\"40\" maxlength=\"50\"></td>";
                $this->salida .= "               </tr>";
                $this->salida .= "               <tr>";
                $this->salida .= "                  <td class=\"".$this->SetStyle("Medico")."\">MEDICO EXT: </td>";
                $this->salida .= "                  <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"Medico\" value=\"".$_REQUEST['Medico']."\" size=\"40\" maxlength=\"50\"></td>";
                $this->salida .= "               </tr>";
//------------------------------------------------
                $this->salida .= "  <tr>";
                $this->salida .= "                  <td class=\"".$this->SetStyle("MedInt")."\">MEDICO INT: </td>";
                $this->salida .= "                    <td colspan=\"2\"><select name=\"MedInt\" class=\"select\">";
                $pro=$this->Profesionales();
                $this->salida .=" <option value=\"\">-------SELECCIONE-------</option>";
                for($i=0; $i<sizeof($pro); $i++)
                {
                        if($pro[$i][tipo_id_tecero]."||".$pro[$i][tecero_id]==$_REQUEST['MedInt'])
                        {  $this->salida .=" <option value=\"".$pro[$i][nombre]."\" selected>".$pro[$i][nombre]."</option>";  }
                        else
                        {  $this->salida .=" <option value=\"".$pro[$i][nombre]."\">".$pro[$i][nombre]."</option>";  }
                }
                $this->salida .= "              </select></td></tr>";

                $this->salida .= "                  <td class=\"".$this->SetStyle("departamento")."\">DEPARTAMENTO: </td>";
                $this->salida .= "                    <td colspan=\"2\"><select name=\"departamento\" class=\"select\">";
                $dpto=$this->BuscarDepartamento();
                $this->salida .=" <option value=\"\">-------SELECCIONE-------</option>";
                $dptos=explode('||',$_REQUEST['departamento']);
                for($i=0; $i<sizeof($dpto); $i++)
                {
                        if($dpto[$i][departamento]==$dptos[0])
                        {  $this->salida .=" <option value=\"".$dpto[$i][departamento]."||".$dpto[$i][descripcion]."\" selected>".$dpto[$i][descripcion]."</option>";  }
                        else
                        {  $this->salida .=" <option value=\"".$dpto[$i][departamento]."||".$dpto[$i][descripcion]."\">".$dpto[$i][descripcion]."</option>";  }
                }
                $this->salida .= "              </select></td></tr>";
                $this->salida .= "               </tr>";
                $this->salida .= "               <tr>";
                $this->salida .= "                  <td class=\"".$this->SetStyle("cama")."\">CAMA: </td>";
                $this->salida .= "                  <td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"cama\" value=\"".$_REQUEST['Medico']."\" size=\"40\" maxlength=\"50\"></td>";
                $this->salida .= "               </tr>";
//------------------------------------------------
                $this->salida .= "               <tr>";
                $this->salida .= "                  <td class=\"".$this->SetStyle("Observacion")."\">OBSERVACIONES: </td>";
                $this->salida .= "                  <td><textarea cols=\"75\" rows=\"3\" class=\"textarea\"name=\"Observacion\">$observacion</textarea></td>";
                $this->salida .= "               </tr>";
                $this->salida .= "               </table>";
                $this->salida .= "       <table width=\"50%\" border=\"0\" align=\"center\">";
                $this->salida .= "                     <tr>";
                $this->salida .= "                                  <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ACEPTAR\"></td>";
                $this->salida .= "                                  </form>";
                $actionM=ModuloGetURL('app','Os_Atencion','user','FormaMetodoBuscar');
                $this->salida .= "             <form name=\"forma2\" action=\"$actionM\" method=\"post\">";
                $this->salida .= "                                  <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"></td>";
                $this->salida .= "                                  </form>";
                $this->salida .= "                     </tr>";
                $this->salida .= "  </table>";
                $this->salida .= "               </form>";        $this->salida .= ThemeCerrarTabla();
                return true;
    }

    function frmForma($arr)
    {
      IncludeLib("tarifario_cargos");
      
      IncludeFileModulo("OsAtencion","RemoteXajax", "app", "Os_Atencion");
      $this->SetXajax(array("BuscarDiagnosticos","IngresarDiagnosticos","EliminarDiagnostico"),null,"ISO-8859-1");
      
      $this->IncludeJS("CrossBrowser");
      $this->IncludeJS("CrossBrowserDrag");
      $this->IncludeJS("CrossBrowserEvent");

      $this->salida= ThemeAbrirTablaSubModulo('SOLICITUD DE APOYOS DIAGNOSTICOS MANUALES  ( '.$_SESSION['LABORATORIO']['NOM_DPTO'].' )');
      $this->DatosCompletos();
      $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
      $this->salida .= $this->SetStyle("MensajeError");
      $this->salida.="</table><br>";

      $mdl = AutoCarga::factory("OS_AtencionSQL","classes","app","Os_Atencion");

      $arreglo = $mdl->ObtenerCargosAdicionados($_SESSION['LABORATORIO']['SERIAL'],$_SESSION['DATOS_PACIENTE'],UserGetUID());
      if(!empty($arreglo))
      {
        $diagnosticos = $mdl->ObtenerDiagnosticosIngresados(null,null,$_SESSION['LABORATORIO']['SERIAL'],$_SESSION['DATOS_PACIENTE'],UserGetUID());
        $this->salida .= "<form name=\"form_ordenes\" action=\"javascript:Continuar()\" method=\"post\">";
        $this->salida .= "  <table  align=\"center\" class=\"modulo_table_list\"  width=\"90%\">\n";
        $this->salida .= "    <tr class=\"formulacion_table_list\">\n";
        $this->salida .= "      <td width=\"7%\">CUPS</td>";
        $this->salida .= "      <td width=\"7%\">CARGO</td>";
        $this->salida .= "      <td width=\"5%\">TARIF.</td>";
        $this->salida .= "      <td width=\"40%\">DESCRIPCION</td>";
        $this->salida .= "      <td width=\"5%\">CANT.</td>";
        $this->salida .= "      <td width=\"10%\">VAL. NO CUBIERTO</td>";
        $this->salida .= "      <td width=\"10%\">VAL. EMPRESA</td>";
        $this->salida .= "      <td width=\"10%\">VALOR CARGO</td>";
        $this->salida .= "      <td width=\"6%\" colspan=\"2\"></td>";
        $this->salida .= "    </tr>\n";
        $est = "";
        foreach($arreglo as $key => $dtl1)
        {
          $est = ($est == "modulo_list_claro")? "modulo_list_oscuro":"modulo_list_claro";
          
          $this->salida .= "    <tr class=\"".$est."\" >\n";
          $this->salida .= "      <td align=\"center\" rowspan=\"".sizeof($dtl1)."\">".$key."</td>";
          $d=$i;
          $flag = false;
          foreach($dtl1 as $k2 => $dtl2)
          {
            $cargos = array();
            $cargos[]=array('tarifario_id'=>$dtl2['tarifario_id'],'cargo'=>$dtl2['cargo'],'cantidad'=>1,'autorizacion_int'=>'','autorizacion_ext'=>'');
            $liq=LiquidarCargosCuentaVirtual($cargos,'','','',$_SESSION['DATOS_PACIENTE']['plan_id'],$_SESSION['DATOS_PACIENTE']['PACIENTE']['tipo_afiliado_id'],$_SESSION['DATOS_PACIENTE']['PACIENTE']['rango'],$_SESSION['DATOS_PACIENTE']['PACIENTE']['semanas'],$_SESSION['OS_PACIENTES']['SERVICIO'],$_SESSION['DATOS_PACIENTE']['tipo_id'],$_SESSION['DATOS_PACIENTE']['paciente_id'],'');
            $accion = ModuloGetURL('app','Os_Atencion','user','EliminarCargo',array('id'=>$dtl2['tmp_solicitud_manual_id'],'idDetalle'=>$dtl2['tmp_solicitud_manual_detalle_id']));
            
            if($flag)
              $this->salida .= "    <tr class=\"".$est."\" >\n";
              
            $this->salida .= "      <td align=\"center\" >".$dtl2['cargo']."</td>";
            $this->salida .= "      <td align=\"center\" >".$dtl2['tarifario_id']."</td>";
            $this->salida .= "      <td >".$dtl2['descripcion']."</td>";
            $this->salida .= "      <td align=\"center\">".$liq[cargos][0][cantidad]."</td>";
            $this->salida .= "      <td align=\"center\">".$liq[cargos][0][valor_no_cubierto]."</td>";
            $this->salida .= "      <td align=\"center\">".$liq[cargos][0][valor_cubierto]."</td>";
            $this->salida .= "      <td align=\"center\">".$liq[cargos][0][valor_cargo]."</td>";
            $this->salida .= "      <td align=\"center\" width=\"3%\">\n";
            $this->salida .= "        <a href=\"".$accion."\" title=\"ELIMINAR CARGO\">\n";
            $this->salida .= "          <img src=\"".GetThemePath()."/images/elimina.png\"  border='0'>\n";
            $this->salida .= "        </a>\n";
            $this->salida .= "      </td>\n";
            if(!$flag)
            {
              $this->salida .= "      <td align=\"center\" width=\"3%\" rowspan=\"".sizeof($dtl1)."\">\n";
              $this->salida .= "        <a href=\"javascript:BuscarDiagnosticos('".$key."','".$dtl2['tmp_solicitud_manual_id']."')\" title=\"ADICIONAR DIAGNOSTICOS AL CARGO\">\n";
              $this->salida .= "          <img src=\"".GetThemePath()."/images/diagnosticos.png\"  border='0'>\n";
              $this->salida .= "        </a>\n";
              $this->salida .= "      </td>\n";
            }

            $this->salida .= "    </tr>\n";

            
            $copago += $liq[valor_cuota_paciente];
            $moderadora += $liq[valor_cuota_moderadora];
            $nocub += $liq[valor_no_cubierto];
            $total += $copago + $moderadora + $nocub;
            $flag = true;
          }
          $this->salida .= "    <tr>\n";
          $this->salida .= "      <td colspan=\"10\" class=\"formulacion_table_list\">\n";
          $this->salida .= "        <div id=\"diagnosticos_cargo_".$key."\">\n";
          if(!empty($diagnosticos[$key]))
          {
            $this->salida .= "<table width=\"100%\" align=\"center\" >\n";
            $this->salida .= "  <tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "    <td colspan=\"5\">DIAGNOSTICOS ASOCIADOS AL CARGO ".$key."</td>\n";
            $this->salida .= "  </tr>\n";
            $this->salida .= "  <tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "    <td width=\"10%\">CODIGO</td>\n";
            $this->salida .= "    <td width=\"70%\">DESCRIPCION</td>\n";
            $this->salida .= "    <td width=\"8%\">TIPO DX</td>\n";
            $this->salida .= "    <td width=\"4%\">PR</td>\n";
            $this->salida .= "    <td width=\"8%\">OP</td>\n";
            $this->salida .= "  </tr>\n";

            foreach($diagnosticos[$key] as $key => $dtl)
            {
              $est = ($est == "modulo_list_claro")? "modulo_list_oscuro":"modulo_list_claro";
            
              $this->salida .= "  <tr class=\"".$est."\" >\n";
              $this->salida .= "    <td>".$dtl['diagnostico_id']."</td>\n";
              $this->salida .= "    <td>".$dtl['diagnostico_nombre']."</td>\n";
              $this->salida .= "    <td align=\"center\">\n";
              if($dtl['tipo_diagnostico'] == '1')
                $this->salida .= "      <img src=\"".GetThemePath()."/images/id.png\" border=\"0\">\n";
              elseif($dtl['tipo_diagnostico'] == '2')
                $this->salida .= "      <img src=\"".GetThemePath()."/images/cn.png\" border=\"0\">\n";
              elseif($dtl['tipo_diagnostico'] == '3')
                $this->salida .= "      <img src=\"".GetThemePath()."/images/cr.png\" border=\"0\">\n";
              $this->salida .= "    </td>\n";
              $this->salida .= "    <td align=\"center\">\n";
              if($dtl['sw_principal'] == '1')
                $this->salida .= "      <img src=\"".GetThemePath()."/images/checksi.png\" border=\"0\">\n";
              $this->salida .= "    </td>\n";
              $this->salida .= "    <td align=\"center\">\n";
              $this->salida .= "      <a title=\"ELIMINAR DIAGNOSTICO\" href=\"javascript:EliminarDiagnostico('".$dtl['tmp_solicitud_manual_id']."','".$dtl['cargo_cups']."','".$dtl['diagnostico_id']."')\">\n";
              $this->salida .= "        <img src=\"".GetThemePath()."/images/elimina.png\" border=\"0\">\n";
              $this->salida .= "      </a>\n";
              $this->salida .= "    </td>\n";
              $this->salida .= "  </tr>\n";
            }
            $this->salida .= "</table>\n";
          }

          $this->salida .= "        </div>\n";
          $this->salida .= "      </td>\n";
          $this->salida .= "    </tr>\n";
        }
        $this->salida .= "    <tr class=\"modulo_table_list_title\">\n";
        $this->salida .= "      <td colspan=\"4\" >TOTAL</td>";
        $this->salida .= "      <td colspan=\"4\" >".$total."</td>";
        $this->salida .= "      <td colspan=\"2\" class=\"modulo_list_claro\">\n";
        $this->salida .= "        <input class=\"input-submit\" type=submit name=mandar value=Cumplir-7>\n";
        $this->salida .= "      </form></td>";
        $this->salida .= "    </tr>\n";
        $this->salida .= "  </table><br>";
      }
      $accion1=ModuloGetURL('app','Os_Atencion','user','Busqueda_Avanzada');
      $this->salida .= "<form name=\"formadesapoyo\" action=\"$accion1\" method=\"post\">";
      $this->salida .= "<table  align=\"center\" border=\"0\"  width=\"75%\">";
      $this->salida .= "<tr class=\"modulo_table_title\">";
      $this->salida .= "  <td align=\"center\" colspan=\"7\">ADICION DE APOYOS DIAGNOSTICOS - BUSQUEDA AVANZADA </td>";
      $this->salida .= "</tr>";
      $this->salida .= "<tr class=\"hc_table_submodulo_list_title\">";
      $this->salida .= "<td width=\"5%\">TIPO</td>";
      $this->salida .= "<td width=\"10%\" align = left >";
      $this->salida .= "<select size = 1 name = 'criterio1apoyo'  class =\"select\">";
      $this->salida .= "<option value = '001' selected>Todos</option>";
      if (($_REQUEST['criterio1apoyo'])  == '002')
      {  $this->salida .= "<option value = '002' selected>Frecuentes</option>";   }
      else
      {  $this->salida .= "<option value = '002' >Frecuentes</option>";  }
      $this->salida .= "</select>";
      $this->salida .= "</td>";
      $this->salida .= "<td width=\"6%\">CARGO:</td>";
      $this->salida .= "<td width=\"11%\" align='center'><input type='text' size =10 class='input-text' size = 10 maxlength = 10   name = 'cargoapoyo'  value =\"".$_REQUEST['cargoapoyo']."\"    ></td>" ;
      $this->salida .= "<td width=\"10%\">DESCRIPCION:</td>";
      $this->salida .= "<td width=\"25%\" align='center'><input type='text' class='input-text'     name = 'descripcionapoyo'   value =\"".$_REQUEST['descripcionapoyo']."\"        ></td>" ;
      $this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name=\"buscarapoyo\" type=\"submit\" value=\"BUSCAR\"></td>";
      $this->salida .= "</tr>";
      $this->salida .= "</table><br>";
      $this->salida .= "</form>";
      if(!empty($arr))
      {
          $this->FormaResultados($arr);
      }
      $accionV=ModuloGetURL('app','Os_Atencion','user','LlamarFormaBuscar');
      $this->salida .= "<form name=\"formaapoyo\" action=\"$accionV\" method=\"post\">";
      $this->salida .= "<p align=\"center\"><input class=\"input-submit\" name=\"volverapoyo\" type=\"submit\" value=\"CANCELAR\"></form></p>";
      $ctl = Autocarga::factory("ClaseUtil");
      $this->salida .= $ctl->LimpiarCampos();
      $this->salida .= "<script>\n";
      $this->salida .= "  function BuscarDiagnosticos(cups,tmp_solicitud_manual_id,offset)\n";
      $this->salida .= "  {\n";
      $this->salida .= "    xajax_BuscarDiagnosticos(xajax.getFormValues('buscador_diagnosticos'),cups,tmp_solicitud_manual_id,offset)\n";
      $this->salida .= "  }\n";      
      $this->salida .= "  function ValidarSeleccionDiagnostica()\n";
      $this->salida .= "  {\n";
      $this->salida .= "    xajax_IngresarDiagnosticos(xajax.getFormValues('seleccion_diagnostica'));\n";
      $this->salida .= "  }\n";
      $this->salida .= "  function EliminarDiagnostico(tmp_solicitud_manual_id,cups,diagnostico_id)\n";
      $this->salida .= "  {\n";
      $this->salida .= "    xajax_EliminarDiagnostico(tmp_solicitud_manual_id,cups,diagnostico_id);\n";
      $this->salida .= "  }\n";
      
      $accion1=ModuloGetURL('app','Os_Atencion','user','CrearOS');
      $this->salida .= "  function Continuar()\n";
      $this->salida .= "  {\n";
      $this->salida .= "    document.form_ordenes.action = \"".$accion1."\";\n";
      $this->salida .= "    document.form_ordenes.submit();\n";
      $this->salida .= "  }\n";
      $this->salida .= "</script>\n";
      
      $html  = "<form name=\"buscador_diagnosticos\" id=\"buscador_diagnosticos\" action=\"\">\n";
      $html .= "  <table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td colspan=\"4\">BUSCADOR DE DIAGNOSTICOS</td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr class=\"formulacion_table_list\">\n";
      $html .= "      <td width=\"20%\">CODIGO</td>\n";
      $html .= "      <td width=\"20%\" class=\"modulo_list_claro\">\n";
      $html .= "        <input type=\"text\" name=\"codigo\" class=\"input-text\" style=\"width:100%\">\n";
      $html .= "      </td>\n";      
      $html .= "      <td width=\"20%\">DIAGNOSTICO</td>\n";
      $html .= "      <td width=\"40%\" class=\"modulo_list_claro\">\n";
      $html .= "        <input type=\"text\" name=\"diagnostico\" class=\"input-text\" style=\"width:100%\">\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "    <tr>\n";
      $html .= "      <td colspan=\"4\">\n";
      $html .= "        <table align=\"center\">\n";
      $html .= "          <tr>\n";
      $html .= "            <td>\n";
      $html .= "              <input type=\"submit\" name=\"buscar\" value=\"Buscar\" class=\"input-submit\">\n";
      $html .= "            </td>\n";      
      $html .= "            <td>\n";
      $html .= "              <input type=\"button\" name=\"limpiar\" value=\"Limpiar Campos\" class=\"input-submit\" onclick=\"LimpiarCampos(document.buscador_diagnosticos)\">\n";
      $html .= "            </td>\n";
      $html .= "          </tr>\n";
      $html .= "        </table>\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "  </table>\n";
      $html .= "</form>\n";
      $html .= "<form name=\"seleccion_diagnostica\" id=\"seleccion_diagnostica\" action=\"javascript:ValidarSeleccionDiagnostica()\">\n";
      $html .= "  <div id=\"resultado_busqueda\"></div>\n";
      $html .= "</form>\n";
      $this->salida .= $this->CrearVentana(600,400,$html);
      $this->salida .= ThemeCerrarTablaSubModulo();
      return true;
    }

    function DatosCompletos()
    {
            if(empty($_SESSION['DATOS_PACIENTE']['nombre']))
            {
                if(!empty($_SESSION['DATOS_PACIENTE']['tipo_id']))
                     $nom=$this->NombrePaciente($_SESSION['DATOS_PACIENTE']['tipo_id'],$_SESSION['DATOS_PACIENTE']['paciente_id']);
                else
                    if(!empty($_SESSION['OS_ATENCION']['tipo']))
                    $nom=$this->NombrePaciente($_SESSION['OS_ATENCION']['tipo'],$_SESSION['OS_ATENCION']['id']);
                $_SESSION['DATOS_PACIENTE']['nombre']=$nom['nombre'];
            }

            if(!empty($_SESSION['DATOS_PACIENTE']['tipo_id']))
            {
                $this->salida .= "       <table width=\"90%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">";
                $this->salida .= "          <tr>";
                $this->salida .= "              <td class=\"modulo_table_list_title\" colspan=\"6\" align=\"left\">DATOS PACIENTE </td>";
                $this->salida .= "          </tr>";
                $this->salida .= "          <tr>";
                $this->salida .= "              <td width=\"10%\" class=\"modulo_table_list_title\" width=\"20%\">IDENTIFICACION: </td><td width=\"15%\" class=\"modulo_list_claro\">".$_SESSION['DATOS_PACIENTE']['tipo_id']." ".$_SESSION['DATOS_PACIENTE']['paciente_id']."</td>";
                $this->salida .= "              <td width=\"10%\" class=\"modulo_table_list_title\" width=\"15%\">PACIENTE:</td><td width=\"30%\" class=\"modulo_list_claro\">".$_SESSION['DATOS_PACIENTE']['nombre']."</td>";
                $this->salida .= "              <td width=\"10%\" class=\"modulo_table_list_title\" width=\"5%\">PLAN:</td><td width=\"40%\" class=\"modulo_list_claro\">".$_SESSION['DATOS_PACIENTE']['plan_descripcion']."</td>";
                $this->salida .= "          </tr>";
                $this->salida .= "          <tr>";
                $this->salida .= "              <td width=\"10%\" class=\"modulo_table_list_title\" width=\"20%\">ENTIDAD: </td><td width=\"15%\" class=\"modulo_list_claro\">".$_SESSION['LABORATORIO']['DATOS']['ENTIDAD']."</td>";
                $this->salida .= "              <td width=\"10%\" class=\"modulo_table_list_title\" width=\"20%\">MEDICO:</td><td  class=\"modulo_list_claro\">".$_SESSION['LABORATORIO']['DATOS']['MEDICO']."</td>";
                $this->salida .= "              <td width=\"10%\" class=\"modulo_table_list_title\" >FECHA:</td><td  class=\"modulo_list_claro\">".$_SESSION['LABORATORIO']['DATOS']['FECHA']."</td>";
                $this->salida .= "          </tr>";
                $this->salida .= "          <tr>";
                $this->salida .= "              <td width=\"10%\" class=\"modulo_table_list_title\" width=\"20%\">OBSERVACION: </td><td class=\"modulo_list_claro\" colspan=\"5\">".$_SESSION['LABORATORIO']['DATOS']['OBSERVACION']."</td>";
                $this->salida .= "          </tr>";
                $this->salida .= "          </table><BR>";
            }
            elseif(!empty($_SESSION['OS_ATENCION']['tipo']))
            {
                $this->salida .= "       <table width=\"90%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">";
                $this->salida .= "          <tr>";
                $this->salida .= "              <td class=\"modulo_table_list_title\" colspan=\"6\" align=\"left\">DATOS PACIENTE </td>";
                $this->salida .= "          </tr>";
                $this->salida .= "          <tr>";
                $this->salida .= "              <td width=\"10%\" class=\"modulo_table_list_title\" width=\"20%\">IDENTIFICACION: </td><td width=\"15%\" class=\"modulo_list_claro\">".$_SESSION['OS_ATENCION']['tipo']." ".$_SESSION['OS_ATENCION']['id']."</td>";
                $this->salida .= "              <td width=\"10%\" class=\"modulo_table_list_title\" width=\"15%\">PACIENTE:</td><td width=\"30%\" class=\"modulo_list_claro\">".$_SESSION['OS_ATENCION']['nom']."</td>";
                $this->salida .= "              <td width=\"10%\" class=\"modulo_table_list_title\" width=\"5%\">PLAN:</td><td width=\"40%\" class=\"modulo_list_claro\">".$_SESSION['DATOS_PACIENTE']['plan_descripcion']."</td>";
                $this->salida .= "          </tr>";
                $this->salida .= "          <tr>";
                $this->salida .= "              <td width=\"10%\" class=\"modulo_table_list_title\" width=\"20%\">ENTIDAD: </td><td width=\"15%\" class=\"modulo_list_claro\">".$_SESSION['LABORATORIO']['DATOS']['ENTIDAD']."</td>";
                $this->salida .= "              <td width=\"10%\" class=\"modulo_table_list_title\" width=\"20%\">MEDICO:</td><td  class=\"modulo_list_claro\">".$_SESSION['LABORATORIO']['DATOS']['MEDICO']."</td>";
                $this->salida .= "              <td width=\"10%\" class=\"modulo_table_list_title\" >FECHA:</td><td  class=\"modulo_list_claro\">".$_SESSION['LABORATORIO']['DATOS']['FECHA']."</td>";
                $this->salida .= "          </tr>";
                $this->salida .= "          <tr>";
                $this->salida .= "              <td width=\"10%\" class=\"modulo_table_list_title\" width=\"20%\">OBSERVACION: </td><td class=\"modulo_list_claro\" colspan=\"5\">".$_SESSION['LABORATORIO']['DATOS']['OBSERVACION']."</td>";
                $this->salida .= "          </tr>";
                $this->salida .= "          </table><BR>";
            }

    }

    function FormaResultados($vectorA)
    {
            if ($vectorA)
            {
                    $accionG=ModuloGetURL('app','Os_Atencion','user','GuardarApoyo');
                    $this->salida .= "<form name=\"formadesapoyo\" action=\"$accionG\" method=\"post\">";
                    $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
                    $this->salida.="<tr class=\"modulo_table_title\">";
                    $this->salida.="  <td align=\"center\" colspan=\"4\">RESULTADO DE LA BUSQUEDA</td>";
                    $this->salida.="</tr>";
                    $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
                    $this->salida.="  <td width=\"15%\">TIPO</td>";
                    $this->salida.="  <td width=\"10%\">CARGO</td>";
                    $this->salida.="  <td width=\"50%\">DESCRIPCION</td>";
                    $this->salida.="  <td width=\"5%\">OPCION</td>";
                    $this->salida.="</tr>";
                    for($i=0;$i<sizeof($vectorA);$i++)
                    {
                            if( $i % 2){ $estilo='modulo_list_claro';}
                            else {$estilo='modulo_list_oscuro';}
                            $this->salida.="<tr class=\"$estilo\">";
                            $this->salida.="  <td align=\"center\" width=\"15%\">".$vectorA[$i][tipo]."</td>";
                            $this->salida.="  <td align=\"center\" width=\"10%\">".$vectorA[$i][cargo]."</td>";
                            $this->salida.="  <td align=\"left\" width=\"50%\">".$vectorA[$i][descripcion]."</td>";
                            $this->salida.="  <td align=\"center\" width=\"5%\"><input type=\"checkbox\" name=\"SeleccionCargos[".$vectorA[$i][cargo]."]\" value=\"".$vectorA[$i][apoyod_tipo_id]."||//".$vectorA[$i][descripcion]."\"></td>";
                            //Cambio Optimizacion de la forma Lorena
                            //$accion=ModuloGetURL('app','Os_Atencion','user','GuardarApoyo', array('cargo'=>$vectorA[$i][cargo],'apoyod_tipo_id'=>$vectorA[$i][apoyod_tipo_id],'descripcion'=>$vectorA[$i][descripcion]));
                            //$this->salida.="  <td align=\"center\" width=\"5%\"><a href=\"$accion\">CARGAR</a></td>";
                            //fin cambio
                            $this->salida.="</tr>";
                    }
                    $this->salida.="</table>";
                    $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
                    $this->salida.="<tr><td align=\"right\" class=\"input-submit\"><input type=\"submit\" name=\"Guardar\" value=\"GUARDAR\"></td></tr>";
                    $this->salida.="</table><br>";
                    $this->salida.="</form>";
                    $var=$this->RetornarBarraExamenes_Avanzada();
                    if(!empty($var))
                    {
                        $this->salida .= "  <br><table border=\"0\" width=\"60%\" align=\"center\">";// class=\"modulo_table\"
                        $this->salida .= "  <tr>";
                        $this->salida .= "  <td width=\"100%\" align=\"center\">";
                        $this->salida .=$var;
                        $this->salida .= "  </td>";
                        $this->salida .= "  </tr>";
                        $this->salida .= "  </table><br>";
                    }
            }
    }

    function RetornarBarraExamenes_Avanzada()//Barra paginadora de los planes clientes
    {
        if($this->limit>=$this->conteo)
        {
            return '';
        }
        $paso=$_REQUEST['paso1apoyo'];
        if(empty($paso))
        {
            $paso=1;
        }
        $accion=ModuloGetURL('app','Os_Atencion','user','Busqueda_Avanzada',array('conteoapoyo'=>$this->conteo,'criterio1apoyo'=>$_REQUEST['criterio1apoyo'],'descripcionapoyo'=>$_REQUEST['descripcionapoyo'],'cargoapoyo'=>$_REQUEST['cargoapoyo']));

        $barra=$this->CalcularBarra($paso);
        $numpasos=$this->CalcularNumeroPasos($this->conteo);
        $colspan=1;
        $salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
        if($paso > 1)
        {
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset(1)."&paso1apoyo=1'>&lt;</a></td>";
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($paso-1)."&paso1apoyo=".($paso-1)."'>&lt;&lt;</a></td>";
            $colspan+=2;
        }
        $barra++;
        if(($barra+10)<=$numpasos)
        {
            for($i=($barra);$i<($barra+10);$i++)
            {
                if($paso==$i)
                {
                    $salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
                }
                else
                {
                    $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($i)."&paso1apoyo=$i' >$i</a></td>";
                }
                $colspan++;
            }
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($paso+1)."&paso1apoyo=".($paso+1)."' >&gt;&gt;</a></td>";
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($numpasos)."&paso1apoyo=$numpasos'>&gt;</a></td>";
            $colspan+=2;
        }
        else
        {
            $diferencia=$numpasos-9;
            if($diferencia<=0)
            {
                $diferencia=1;
            }
            for($i=($diferencia);$i<=$numpasos;$i++)
            {
                if($paso==$i)
                {
                    $salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
                }
                else
                {
                    $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($i)."&paso1apoyo=$i'>$i</a></td>";
                }
                $colspan++;
            }
            if($paso!=$numpasos)
            {
                $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($paso+1)."&paso1apoyo=".($paso+1)."' >&gt;&gt;</a></td>";
                $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($numpasos)."&paso1apoyo=$numpasos'>&gt;</a></td>";
                $colspan++;
            }
        }
        if(($_REQUEST['Ofapoyo'])==0 OR ($paso==$numpasos))
        {
            if($numpasos>10)
            {
                $valor=10+3;
            }
            else
            {
                $valor=$numpasos+3;
            }
            $salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
        }
        else
        {
            if($numpasos>10)
            {
                $valor=10+5;
            }
            else
            {
                $valor=$numpasos+5;
            }
            $salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
        }
        return $salida;
    }

    function RetornarBarraExamenes_AvanzadaCargos()//Barra paginadora de los planes clientes
    {
        if($this->limit>=$this->conteo)
        {
            return '';
        }
        $paso=$_REQUEST['paso1apoyo'];
        if(empty($paso))
        {
            $paso=1;
        }
        $accion=ModuloGetURL('app','Os_Atencion','user','Busqueda_AvanzadaCargos',array('conteoapoyo'=>$this->conteo,'criterio1apoyo'=>$_REQUEST['criterio1apoyo']));

        $barra=$this->CalcularBarra($paso);
        $numpasos=$this->CalcularNumeroPasos($this->conteo);
        $colspan=1;
        $salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
        if($paso > 1)
        {
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset(1)."&paso1apoyo=1'>&lt;</a></td>";
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($paso-1)."&paso1apoyo=".($paso-1)."'>&lt;&lt;</a></td>";
            $colspan+=2;
        }
        $barra++;
        if(($barra+10)<=$numpasos)
        {
            for($i=($barra);$i<($barra+10);$i++)
            {
                if($paso==$i)
                {
                    $salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
                }
                else
                {
                    $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($i)."&paso1apoyo=$i' >$i</a></td>";
                }
                $colspan++;
            }
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($paso+1)."&paso1apoyo=".($paso+1)."' >&gt;&gt;</a></td>";
            $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($numpasos)."&paso1apoyo=$numpasos'>&gt;</a></td>";
            $colspan+=2;
        }
        else
        {
            $diferencia=$numpasos-9;
            if($diferencia<=0)
            {
                $diferencia=1;
            }
            for($i=($diferencia);$i<=$numpasos;$i++)
            {
                if($paso==$i)
                {
                    $salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
                }
                else
                {
                    $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($i)."&paso1apoyo=$i'>$i</a></td>";
                }
                $colspan++;
            }
            if($paso!=$numpasos)
            {
                $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($paso+1)."&paso1apoyo=".($paso+1)."' >&gt;&gt;</a></td>";
                $salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Ofapoyo=".$this->CalcularOffset($numpasos)."&paso1apoyo=$numpasos'>&gt;</a></td>";
                $colspan++;
            }
        }
        if(($_REQUEST['Ofapoyo'])==0 OR ($paso==$numpasos))
        {
            if($numpasos>10)
            {
                $valor=10+3;
            }
            else
            {
                $valor=$numpasos+3;
            }
            $salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
        }
        else
        {
            if($numpasos>10)
            {
                $valor=10+5;
            }
            else
            {
                $valor=$numpasos+5;
            }
            $salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
        }
        return $salida;
    }

    function FormaVariasEquivalencias($cargo,$apoyod_tipo_id)
    {
            IncludeLib("tarifario_cargos");
            IncludeLib("funciones_facturacion");
            $this->salida .= ThemeAbrirTabla('CARGOS EQUIVALENTES');
            $this->DatosCompletos();
            //$v=explode('//',$_REQUEST['apoyo']);
            //mensaje
            $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
            $this->salida .= $this->SetStyle("MensajeError");
            $this->salida .= "  </table><br>";
            $this->salida .= "     <br><table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
            $this->salida .= "          <tr><td colspan=\"5\">EL CARGO CUPS (".$cargo.")<b> ".$_REQUEST['descripcion']." </b>TIENE VARIAS EQUIVALENCIAS:</td></tr>";
            $this->salida .= "          <tr><td colspan=\"5\">&nbsp;</td></tr>";
            $this->salida .= "  </table><br>";
            $accion=ModuloGetURL('app','Os_Atencion','user','GuardarEquivalencias',array('cups'=>$cargo,'apoyod_tipo_id'=>$apoyod_tipo_id,'apoyo'=>$_REQUEST['apoyo']));
            $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
            $equi=ValdiarEquivalencias($_SESSION['DATOS_PACIENTE']['plan_id'],$cargo);
            $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
            $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
            $this->salida.="  <td width=\"5%\">CARGO</td>";
            $this->salida.="  <td width=\"5%\">TARIF.</td>";
            $this->salida.="  <td width=\"50%\">DESCRIPCION</td>";
            $this->salida.="  <td width=\"5%\">CANT.</td>";
            $this->salida.="  <td width=\"10%\">VAL. NO CUBIERTO</td>";
            $this->salida.="  <td width=\"12%\">VAL. EMPRESA</td>";
            $this->salida.="  <td width=\"15%\">VALOR CARGO</td>";
            $this->salida.="  <td></td>";
            $this->salida.="</tr>";
            for($i=0; $i<sizeof($equi); $i++)
            {
                $this->salida .= "<tr>";
                if( $k % 2){ $estilo='modulo_list_claro';}
                else {$estilo='modulo_list_oscuro';}
                $this->salida.="<tr class='$estilo' align='center'>";
                $this->salida .= "        <td align=\"center\">".$equi[$i][tarifario_id]."</td>";
                $this->salida .= "        <td align=\"center\">".$equi[$i][cargo]."</td>";
                $this->salida .= "        <td>".$equi[$i][descripcion]."</td>";
                $cargos='';
                $cargos[]=array('tarifario_id'=>$equi[$i][tarifario_id],'cargo'=>$equi[$i][cargo],'cantidad'=>1,'autorizacion_int'=>'','autorizacion_ext'=>'');
                $liq=LiquidarCargosCuentaVirtual($cargos,'','','',$_SESSION['DATOS_PACIENTE']['plan_id'],$_SESSION['DATOS_PACIENTE']['PACIENTE']['tipo_afiliado_id'],$_SESSION['DATOS_PACIENTE']['PACIENTE']['rango'],$_SESSION['DATOS_PACIENTE']['PACIENTE']['semanas'],$_SESSION['OS_PACIENTES']['SERVICIO'],$_SESSION['DATOS_PACIENTE']['tipo_id'],$_SESSION['DATOS_PACIENTE']['paciente_id'],'');
                $this->salida.="  <td >".$liq[cargos][0][cantidad]."</td>";
                $this->salida.="  <td >".$liq[cargos][0][valor_no_cubierto]."</td>";
                $this->salida.="  <td >".$liq[cargos][0][valor_cubierto]."</td>";
                $this->salida.="  <td >".$liq[cargos][0][valor_cargo]."</td>";
                $this->salida .= "        <td align=\"center\"><input type = checkbox name= Equi".$equi[$i][tarifario_id]."".$equi[$i][cargo]." value=\"".$equi[$i][tarifario_id]."//".$equi[$i][cargo]."//".$equi[$i][descripcion]."//".$liq[cargos][0][cantidad]."\"></td>";
                $this->salida.="</tr>";
            }
            $this->salida .= "  </table><br>";
            $this->salida .= "     <table border=\"0\" width=\"50%\" align=\"center\">";
            $this->salida .= "          <tr>";
            $this->salida .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ACEPTAR\"></td>";
            $this->salida .= "</form>";
            $accion=ModuloGetURL('app','Os_Atencion','user','frmForma');
            $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
            $this->salida .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"></td>";
            $this->salida .= "</form>";
            $this->salida .= "          </tr>";
            $this->salida .= "     </table>";
            $this->salida .= ThemeCerrarTabla();
            return true;
    }


//--------------fin cambio dar

//MauroB
//PIDE EL DESCUENTO PARA LA EMPRESA O EL CLIENTE
        function GetDescuento($mensaje = '')
     {

            $indice = $_REQUEST['indice'];
            $valor_cargo = $_REQUEST['valor_cargo'];
            if ($_REQUEST['sw_tipo']==1)
            {
                $this->salida .= ThemeAbrirTabla("MODIFICAR VALOR CUBIERTO POR LA ENTIDAD");
                $vari = "MODIFICAR VALOR CUBIERTO POR LA ENTIDAD";
                $monto = "VALOR MAXIMO PERMITIDO PARA EL DESCUENTO : $ $valor_cargo<br>".$mensaje;
                $campo = "descuento_empresa";
								$msg_campo = "VALOR CUBIERTO";
            }
            elseif($_REQUEST['sw_tipo']==2)
            {
                $this->salida .= ThemeAbrirTabla("MODIFICAR VALOR A CARGO DEL PACIENTE");
                $vari = "MODIFICAR VALOR A CARGO DEL PACIENTE";
                $monto = "VALOR MAXIMO PERMITIDO PARA EL DESCUENTO : $ $valor_cargo<br>".$mensaje;
                $campo = "descuento_paciente";
								$msg_campo = "VALOR NO CUBIERTO";
            }


            if(empty($tipo) or empty($id))
            {
                $vector=$_SESSION['OS_ATENCION']['vector'];
                $nom=$_SESSION['OS_ATENCION']['nom'];
                $tipo=$_SESSION['OS_ATENCION']['tipo'];
                $id=$_SESSION['OS_ATENCION']['id'];
                $op=$_SESSION['OS_ATENCION']['op'];
                $PlanId=$_SESSION['OS_ATENCION']['PlanId'];
                $vector_des=$_SESSION['OS_ATENCION']['vector_des'];
                $descuentos_cargos = $_SESSION['OS_ATENCION']['descuentos_cargos'];
            }



            $liqOrden=ModuloGetURL('app','Os_Atencion','user','ValidaDescuentoLiquidacionOrden',array('sw_tipo' => $_REQUEST['sw_tipo'],'indice' => $_REQUEST['indice']));
            $this->salida .='<form name="forma" action='.$liqOrden.' method="post">';

            $this->salida.="    <SCRIPT language='javascript'>";
            $this->salida.="        function acceptNum(evt)\n";
            $this->salida.="        {\n";
            $this->salida.="            var nav4 = window.Event ? true : false;\n";
            $this->salida.="            var key = nav4 ? evt.which : evt.keyCode;\n";
            $this->salida.="            return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
            $this->salida.="        }\n";
            $this->salida.="    </SCRIPT>";
            $this->salida .='<table align="center" width="45%" border="0">';
            $this->salida .='<tr>';
            $this->salida .= "<td  align=\"center\"><label class='label_mark'>$vari</label></td>";
            $this->salida .='</tr>';
            $this->salida .='<tr>';
            $this->salida .= "<td  align=\"center\"><label class='label_mark'>$monto</label></td>";
            $this->salida .='</tr>';
            $this->salida .='</table>';

            //$datos=$this->GetTiposDescuento();
            $this->salida.="<br><table border=\"0\"  align=\"center\"   width=\"75%\" >";
            $this->salida .="".$this->SetStyle("MensajeError")."";
            $this->salida.="<tr>";
            $this->salida .= "<td  colspan=\"2\"  align=\"center\" class=\"modulo_table_title\" >Seleccion Descuento</td>";
            $this->salida.="</tr>";

            $this->salida.="<tr  class=\"modulo_list_claro\">";
            $this->salida .= "  <td width=\"35%\" >CONCEPTO DEL DESCUENTO</td><td class=\"modulo_list_oscuro\">";
            $this->salida .= "   <select name=\"tipodescuento\" class=\"select\">";

//          if($datos)
//          {
//              for($i=0;$i<sizeof($datos);$i++)
//              {
//                      $this->salida .=" <option value=\"".."\">".."</option>";
//              }
//          }
//
            $this->salida .= "       </select></td></tr>";
						
						if($descuentos_cargos[$indice][$campo] == 0 )
							$descuento = 0.0;
						else
							$descuento = $descuentos_cargos[$indice][$campo];
						
            $this->salida.="<tr  class=\"modulo_list_claro\">";
            $this->salida.=" <td>".$msg_campo.": </td>";

            $this->salida .=" <td align=\"left\">";
            $this->salida .="  <input name=\"valordescuento\" type=\"text\" class=\"input\" value='".$descuento."' onKeyPress='return acceptNum(event)'>";
            $this->salida .=" </td>";
            $this->salida.="</tr>";

            $this->salida.="<tr class=\"modulo_list_claro\">";
            $this->salida .= "<td   width=\"35%\"  >OBSERVACION :</td>";
            $this->salida .= "<td  align=\"left\"><TEXTAREA name=obs cols=50 rows=8 readonly></TEXTAREA></td>";
            $this->salida.="</tr>";
            $this->salida.="</table>";

            $this->salida.="<input type = \"hidden\" name = \"valor_cargo\" value = \"$valor_cargo\"";
            $this->salida.="<br><table align=\"center\">";
            $this->salida.="<tr>";
            $this->salida .= "<td><input type=\"submit\" align=\"center\" name=\"Guardar\" value=\"Guardar\" class=\"input-submit\"></form></td>";
            $this->salida .='<td>&nbsp;</td>';
            $this->salida .= "<td>";
            $accion=ModuloGetURL('app','Os_Atencion','user','LiquidacionOrden',array());
            $this->salida .='<form name="forma" action="'.$accion.'" method="post">';
            $this->salida .=" <input type=\"submit\" align=\"center\" name=\"Volver\" value=\"Volver\" class=\"input-submit\">";
            $this->salida .="</form></td>";
            $this->salida .="</tr>";
            $this->salida .="</table>";
            $this->salida.= ThemeCerrarTabla();
            return true;
    }
    /**
    *
    */
    function FormaCargosEquivalentes()
    {
      $request = $_REQUEST;
      $datos['id_tipo'] = $request['id_tipo'];
      $datos['plan_id'] = $request['plan_id'];
      $datos['nom'] = $request['nom'];
      $datos['id'] = $request['id'];
      $datos['op'] = $request['op'];
      $datos['departamento_pt'] = $request['departamento_pt'];
      $datos['id_orden_servicio'] = $request['id_orden_servicio'];
      $dtSession = SessionGetVar("LABORATORIO");
      
      $ordenes = array();
      $ordeninsumos = array();
      foreach($request['op'] as $k1 => $dtl1)
      {
        $val = explode(",",$dtl1);
        $adicionales = $this->ParagrafadosCargos($val[0],$val[7],$request['plan_id'],$request['id_tipo'],$request['id']);
        if(!empty($adicionales))
          $ordenes[$val[0]] = $adicionales;
        
        $insumos = $this->ParagrafadosInsumos($val[0],$val[7],$request['plan_id'],$request['id_tipo'],$request['id'],$dtSession['EMPRESA_ID'],$dtSession['CENTROUTILIDAD'],$dtSession['DPTO']);
        if(!empty($insumos))
          $ordeninsumos[$val[0]] = $insumos;
      }
     
      $action['aceptar'] = ModuloGetURL('app','Os_Atencion','user','FormaAdicionarCargos',$datos);
      $action['continuar'] = ModuloGetURL('app','Os_Atencion','user','BuscarCuentaActiva',$datos);
      
      $datos1['tipoid'] = $request['id_tipo'];
      $datos1['nombre'] = $request['nom'];
      $datos1['idp'] = $request['id'];
      $action['volver'] = ModuloGetURL('app','Os_Atencion','user','FrmOrdenar',$datos1);

      if(!empty($ordeninsumos))
      {
        $rst = $this->AgregarProductoTmpInventario($ordeninsumos,$datos['plan_id']);
        if(!$rst)
        {
          $datos['tipoid'] = $request['id_tipo'];
          $datos['nombre'] = $request['nom'];
          $datos['idp'] = $request['id'];

          $action['volver'] = ModuloGetURL('app','Os_Atencion','user','FrmOrdenar',$datos);

          $html  = ThemeAbrirTabla('MENSAJE');
    			$html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
    			$html .= "	<tr>\n";
    			$html .= "		<td>\n";
    			$html .= "		  <table width=\"100%\" class=\"modulo_table_list\">\n";
    			$html .= "		    <tr class=\"normal_10AN\">\n";
    			$html .= "		      <td align=\"center\">\n".$this->mensajeDeError."</td>\n";
    			$html .= "		    </tr>\n";
    			$html .= "		  </table>\n";
    			$html .= "		</td>\n";
    			$html .= "	</tr>\n";
    			$html .= "	<tr>\n";
    			$html .= "		<td align=\"center\"><br>\n";
    			$html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
    			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">";
    			$html .= "			</form>";
    			$html .= "		</td>";
    			$html .= "	</tr>";
    			$html .= "</table>";
    			$html .= ThemeCerrarTabla();
          $this->salida = $html;
          
          return true;
        }
      }
      
      if(!empty($ordenes))
      {
        $ctl = AutoCarga::factory("ClaseUtil");
        $this->salida .= $ctl->AcceptNum(false);
        $this->salida .= ThemeAbrirTabla('TARIFARIOS EQUIVALENTES DE LOS PROCEDIMIENTOS');
        
        $cargobase = $_REQUEST['cargo'];
        $descripcion=$_REQUEST['descripcion'];
        
        $planes = $this->responsables($request['plan_id']);
        $this->Encabezado();
        $this->salida .= "<br>\n";
        $this->salida .= "<script>\n";
        $this->salida .= "  function EvaluarDatos(frm)\n";
        $this->salida .= "  {\n";
        $this->salida .= "    e = document.getElementById('error');\n";
        $this->salida .= "    flag = false;\n";
  			$this->salida .= "		for(i=0; i<frm.length; i++)\n";
  			$this->salida .= "		{\n";
  			$this->salida .= "			if(frm[i].type == 'checkbox')\n";
  			$this->salida .= "			{\n";
        $this->salida .= "			  if(frm[i].checked)\n";
  			$this->salida .= "			  {\n";
  			$this->salida .= "			    flag = true;\n";
  			$this->salida .= "			    if(frm[i-1].value == '')\n";
  			$this->salida .= "			    {\n";
  			$this->salida .= "			      e.innerHTML = 'POR FAVOR INGRESAR LA CANTIDAD, PARA LOS CARGOS SELECCIONADOS ';\n";
  			$this->salida .= "			      return;\n";
  			$this->salida .= "			    }\n";
  			$this->salida .= "			  }\n";
  			$this->salida .= "			}\n";
  			$this->salida .= "		}\n";
  			$this->salida .= "		e.innerHTML = '';\n";
        $this->salida .= "    if(!flag)\n";
  			$this->salida .= "	  {\n";
  			$this->salida .= "		  e.innerHTML = 'POR FAVOR SELECCIONAR ALGUNO DE LOS CARGOS MOSTRADOS';\n";
  			$this->salida .= "		  return;\n";
  			$this->salida .= "		}\n";
  			$this->salida .= "		frm.submit();\n";
        $this->salida .= "  }\n";
        $this->salida .= "</script>\n";
        $this->salida .= "<form name=\"forma_datos\" action=\"".$action['aceptar']."\" method=\"post\">\n";
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
        foreach($ordenes as $kI => $dtI)
        {
          foreach($dtI as $kII => $dtII)
          {
            $procedimientos = $this->GetEquivalenciasCargos($request['plan_id'],$dtII['cargo_relacionado']);
            $this->salida .= "    <tr class=\"formulacion_table_list\">\n";
            $this->salida .= "      <td colspan=\"2\">\n";
            $this->salida .= "        PROCEDIMIENTOS Y SELECCION DE EQUIVALENCIAS&nbsp&nbsp&nbsp&nbsp; - &nbsp&nbsp&nbsp&nbsp PLAN: ".$planes[0]['plan_descripcion']."\n";
            $this->salida .= "      </td>\n";
            $this->salida .= "    </tr>\n";
            $this->salida .= "    <tr class=\"modulo_table_list_title\">\n";
            $this->salida .= "      <td colspan=\"2\">".$dtII['cargo']." - ".$dtII['decripcion_base']."</td>\n";
            $this->salida .= "    </tr>\n";
            if(is_array($procedimientos))
            {
              foreach($procedimientos as $kII => $dtIII)
              {
                $this->salida .= "    <tr class=\"modulo_list_oscuro\">\n";
                $this->salida .= "      <td colspan=\"2\">";
                $this->salida .= "        <table border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10AN\">\n";
                $this->salida .= "          <tr class=\"modulo_list_claro\">\n";
                $this->salida .= "            <td width=\"10%\">".$dtIII['nomtarifario']."</td>\n";
                $this->salida .= "            <td width=\"10%\">".$dtIII['cargo']."</td>\n";
                $this->salida .= "            <td > ".$dtIII['descripcion']."</td>\n";
                $this->salida .= "            <td width=\"5%\">\n";
                $this->salida .= "              <input name=\"seleccion[".$kI."][".$dtIII['tarifario_id']."][".$dtIII['cargo']."][cantidad]\" maxlength='5'  size='3' class=\"input-text\" type=\"text\" onkeypress=\"return acceptNum(event)\" value=\"".$dtII['cantidad']."\">\n";
                $this->salida .= "            </td>\n";
                $this->salida .= "            <td align=\"center\" width=\"5%\">\n";
                $this->salida .= "              <input title=\"Seleccion\" type=\"checkbox\" name=\"seleccion[".$kI."][".$dtIII['tarifario_id']."][".$dtIII['cargo']."][cargo]\" value=\"".$dtIII['cargo']."\">\n";
                $this->salida .= "              <input type=\"hidden\" name=\"seleccion[".$kI."][".$dtIII['tarifario_id']."][".$dtIII['cargo']."][nivel]\" value=\"".$dtIII['nivel']."\">\n";
                $this->salida .= "              <input type=\"hidden\" name=\"seleccion[".$kI."][".$dtIII['tarifario_id']."][".$dtIII['cargo']."][precio]\" value=\"".$dtIII['precio']."\">\n";
                $this->salida .= "              <input type=\"hidden\" name=\"seleccion[".$kI."][".$dtIII['tarifario_id']."][".$dtIII['cargo']."][servicio]\" value=\"".$dtII['servicio']."\">\n";
                $this->salida .= "              <input type=\"hidden\" name=\"seleccion[".$kI."][".$dtIII['tarifario_id']."][".$dtIII['cargo']."][cargo_cups]\" value=\"".$dtII['cargo']."\">\n";
                $this->salida .= "              <input type=\"hidden\" name=\"seleccion[".$kI."][".$dtIII['tarifario_id']."][".$dtIII['cargo']."][sw_factura]\" value=\"".$dtII['sw_factura']."\">\n";
                $this->salida .= "              <input type=\"hidden\" name=\"seleccion[".$kI."][".$dtIII['tarifario_id']."][".$dtIII['cargo']."][tipo_cargo]\" value=\"".$dtIII['tipo_cargo']."\">\n";
                $this->salida .= "              <input type=\"hidden\" name=\"seleccion[".$kI."][".$dtIII['tarifario_id']."][".$dtIII['cargo']."][tarifario_id]\" value=\"".$dtIII['tarifario_id']."\">\n";
                $this->salida .= "              <input type=\"hidden\" name=\"seleccion[".$kI."][".$dtIII['tarifario_id']."][".$dtIII['cargo']."][sw_honorarios]\" value=\"".$dtIII['sw_honorarios']."\">\n";
                $this->salida .= "              <input type=\"hidden\" name=\"seleccion[".$kI."][".$dtIII['tarifario_id']."][".$dtIII['cargo']."][concepto_rips]\" value=\"".$dtIII['concepto_rips']."\">\n";
                $this->salida .= "              <input type=\"hidden\" name=\"seleccion[".$kI."][".$dtIII['tarifario_id']."][".$dtIII['cargo']."][numerodecuenta]\" value=\"".$dtII['numerodecuenta']."\">\n";
                $this->salida .= "              <input type=\"hidden\" name=\"seleccion[".$kI."][".$dtIII['tarifario_id']."][".$dtIII['cargo']."][tipo_unidad_id]\" value=\"".$dtIII['tipo_unidad_id']."\">\n";
                $this->salida .= "              <input type=\"hidden\" name=\"seleccion[".$kI."][".$dtIII['tarifario_id']."][".$dtIII['cargo']."][decripcion_base]\" value=\"".$dtIII['decripcion_base']."\">\n";
                $this->salida .= "              <input type=\"hidden\" name=\"seleccion[".$kI."][".$dtIII['tarifario_id']."][".$dtIII['cargo']."][grupo_tipo_cargo]\" value=\"".$dtIII['grupo_tipo_cargo']."\">\n";
                $this->salida .= "              <input type=\"hidden\" name=\"seleccion[".$kI."][".$dtIII['tarifario_id']."][".$dtIII['cargo']."][grupo_tarifario_id]\" value=\"".$dtIII['grupo_tarifario_id']."\">\n";
                $this->salida .= "              <input type=\"hidden\" name=\"seleccion[".$kI."][".$dtIII['tarifario_id']."][".$dtIII['cargo']."][subgrupo_tarifario_id]\" value=\"".$dtIII['subgrupo_tarifario_id']."\">\n";
                $this->salida .= "            </td>\n";
                $this->salida .= "          </tr>\n";
                $this->salida .= "        </table>\n";
                $this->salida .= "      </td>\n";
                $this->salida .= "    </tr>\n";
              }
            }
          }
        }
        $this->salida .= "  </table>\n";
        $this->salida .= "  <table border=\"0\" width=\"100%\" align=\"center\" class=\"normal_10\">\n";
  			$this->salida .= "    <tr>\n";
        $this->salida .= "      <td align=\"right\">\n";
        $this->salida .= "        <input type=\"button\" onClick=\"EvaluarDatos(document.forma_datos)\" name=\"Liquidar\" value=\"Agregar\" class=\"input-submit\">\n";
        $this->salida .= "      </td>\n";
        $this->salida .= "    </tr>\n";
        $this->salida .= "    <tr>\n";
        $this->salida .= "      <td class=\"label_error\" align=\"center\"><div id=\"error\"></div></td>\n";
        $this->salida .= "    </tr>\n";
  			$this->salida .= "  </table>\n";
				$this->salida .= "</form>\n";
        $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">\n";
        $this->salida .= "    <tr>\n";
        $this->salida .= "      <td align=\"center\">\n";
        $this->salida .= "        <form name=\"forma\" action=\"".$action['continuar']."\" method=\"post\">\n";
        $this->salida .= "          <input type=\"submit\" name=\"continuar\" value=\"Continuar\" class=\"input-submit\">\n";
        $this->salida .= "        </form>\n";
        $this->salida .= "      </td>\n";
        $this->salida .= "      <td align=\"center\">\n";
        $this->salida .= "        <form name=\"forma\" action=\"".$action['volver']."\" method=\"post\">\n";
        $this->salida .= "          <input type=\"submit\" name=\"volver\" value=\"Volver\" class=\"input-submit\">\n";
        $this->salida .= "        </form>\n";
        $this->salida .= "      </td>\n";
        $this->salida .= "    </tr>\n";
        $this->salida .= "  </table>\n";
        $this->salida .= ThemeCerrarTabla();
      }
      else
      {
        $this->salida .= "<script>\n";
        $this->salida .= "	location.href = \"".$action['continuar']."\"\n";
        $this->salida .= "</script>\n";
      }
      return true;
    }
    /**
    *
    */
    function FormaAdicionarCargos()
    {
      $request = $_REQUEST;
      
      $rst = $this->AgregarCargosTmp($request['seleccion'],$request);
      if(!$rst)
      {
        $datos['tipoid'] = $request['id_tipo'];
        $datos['nombre'] = $request['nom'];
        $datos['idp'] = $request['id'];

        $action['volver'] = ModuloGetURL('app','Os_Atencion','user','FrmOrdenar',$datos);

        $html  = ThemeAbrirTabla('MENSAJE');
  			$html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
  			$html .= "	<tr>\n";
  			$html .= "		<td>\n";
  			$html .= "		  <table width=\"100%\" class=\"modulo_table_list\">\n";
  			$html .= "		    <tr class=\"normal_10AN\">\n";
  			$html .= "		      <td align=\"center\">\n".$this->mensajeDeError."</td>\n";
  			$html .= "		    </tr>\n";
  			$html .= "		  </table>\n";
  			$html .= "		</td>\n";
  			$html .= "	</tr>\n";
  			$html .= "	<tr>\n";
  			$html .= "		<td align=\"center\"><br>\n";
  			$html .= "			<form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
  			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"aceptar\" value=\"Aceptar\">";
  			$html .= "			</form>";
  			$html .= "		</td>";
  			$html .= "	</tr>";
  			$html .= "</table>";
  			$html .= ThemeCerrarTabla();
        $this->salida = $html;
      }
      else
        $this->BuscarCuentaActiva($request['id'], $request['id_tipo'],$request['nom'],$request['op'],$request['plan_id']);
      
      return true;
    }
    /**
    * Metodo donde se crea la forma para el ingreso de datos del paciente
    *
    * @return boolean
    */
    function FormaDatosPaciente()
		{
			$rst = $this->DatosPacienteModulo();
			
			if($rst === false)
			{
				$this->FormaBuscar();
				return true;
			}
			
			$pct = $this->ReturnModuloExterno('app','DatosPaciente','user');
			
			$pct->SetActionVolver($this->action['volver']);
			$pct->FormaDatosPaciente($this->action);
			
			$this->SetJavaScripts("Ocupaciones");
			$this->salida = $pct->salida;
			return true;
		}
    /**
		* Funcion donde se crea una forma con una ventana con capas para mostrar informacion
    * en pantalle
    *
    * @param int $tmn Tamaño en x que tendra la ventana
    * @param int $tmny Tamaño en y que tendra la ventana
    * @param int $contenido Contenido a mostrar en la ventana
    *
    * @return string
		*/
		function CrearVentana($tmn = 370, $tmny = "'auto'",$contenido)
		{
			$html .= "<script>\n";
			$html .= "	var contenedor = 'Contenedor';\n";
			$html .= "	var titulo = 'titulo';\n";
			$html .= "	var hiZ = 4;\n";
			$html .= "	function OcultarSpan(capa)\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
 			$html .= "			e = xGetElementById(capa);\n";
			$html .= "			e.style.display = \"none\";\n";
			$html .= "		}\n";
			$html .= "		catch(error){}\n";
			$html .= "	}\n";
			$html .= "	function MostrarSpan(capa)\n";
			$html .= "	{ \n";
			$html .= "		try\n";
			$html .= "		{\n";
 			$html .= "			e = xGetElementById(capa);\n";
			$html .= "			e.style.display = \"\";\n";
			$html .= "		  Iniciar();\n";
			$html .= "		}\n";
			$html .= "		catch(error){alert(error)}\n";
			$html .= "	}\n";			
      
      $html .= "	function MostrarTitle(Seccion)\n";
			$html .= "	{\n";
			$html .= "		xShow(Seccion);\n";
			$html .= "	}\n";
			$html .= "	function OcultarTitle(Seccion)\n";
			$html .= "	{\n";
			$html .= "		xHide(Seccion);\n";
			$html .= "	}\n";

			$html .= "	function Iniciar()\n";
			$html .= "	{\n";
			$html .= "		contenedor = 'Contenedor';\n";
			$html .= "		titulo = 'titulo';\n";
      $html .= "		ele = xGetElementById('Contenido');\n";
			$html .= "	  xResizeTo(ele,".$tmn.", ".$tmny.");\n";	
			$html .= "		ele = xGetElementById(contenedor);\n";
			$html .= "	  xResizeTo(ele,".$tmn.", ".$tmny.");\n";
			$html .= "	  xMoveTo(ele, xClientWidth()/4, xScrollTop()+20);\n";
			$html .= "		ele = xGetElementById(titulo);\n";
			$html .= "	  xResizeTo(ele,".($tmn - 20).", 20);\n";
			$html .= "		xMoveTo(ele, 0, 0);\n";
			$html .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$html .= "		ele = xGetElementById('cerrar');\n";
			$html .= "	  xResizeTo(ele,20, 20);\n";
			$html .= "		xMoveTo(ele,".($tmn - 20).", 0);\n";
			$html .= "	}\n";
      
			$html .= "	function myOnDragStart(ele, mx, my)\n";
			$html .= "	{\n";
			$html .= "	  window.status = '';\n";
			$html .= "	  if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
			$html .= "	  else xZIndex(ele, hiZ++);\n";
			$html .= "	  ele.myTotalMX = 0;\n";
			$html .= "	  ele.myTotalMY = 0;\n";
			$html .= "	}\n";
			$html .= "	function myOnDrag(ele, mdx, mdy)\n";
			$html .= "	{\n";
			$html .= "	  if (ele.id == titulo) {\n";
			$html .= "	    xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
			$html .= "	  }\n";
			$html .= "	  else {\n";
			$html .= "	    xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
			$html .= "	  }  \n";
			$html .= "	  ele.myTotalMX += mdx;\n";
			$html .= "	  ele.myTotalMY += mdy;\n";
			$html .= "	}\n";
			$html .= "	function myOnDragEnd(ele, mx, my)\n";
			$html .= "	{\n";
			$html .= "	}\n";
			$html .= "</script>\n";
			$html .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
			$html .= "	<div id='titulo' class='draggable' style=\"	text-transform: uppercase;text-align:center;\"></div>\n";
			$html .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('Contenedor')\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
			$html .= "	<div id='Contenido' class='d2Content'>\n";
			$html .= "	".$contenido;
			$html .= "	</div>\n";
			$html .= "</div>\n";
			return $html;
		}
  }
?>