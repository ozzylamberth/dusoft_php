<?php

/**
 * $Id: app_CentroAutorizacionQx_userclasses_HTML.php,v 1.2 2005/06/27 14:14:41 darling Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Manejo visual de las autorizaciones.
 */

/**
*Contiene los metodos visuales para realizar las autorizaciones.
*/

class app_CentroAutorizacionQx_userclasses_HTML extends app_CentroAutorizacionQx_user
{
  /**
  *Constructor de la clase app_CentroAutorizacionQx_user_HTML
  *El constructor de la clase app_CentroAutorizacionQx_user_HTML se encarga de llamar
  *a la clase app_CentroAutorizacionQx_user quien se encarga de el tratamiento
  * de la base de datos.
  */

  function app_CentroAutorizacionQx_user_HTML()
  {
        $this->salida='';
        $this->app_CentroAutorizacionQx_user();
        return true;
  }


  function SetStyle($campo)
  {
        if ($this->frmError[$campo] || $campo=="MensajeError"){
          if ($campo=="MensajeError"){
            return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
          }
          return ("label_error");
        }
      return ("label");
  }

  /**
  *
  */
  function ComboJustificacion()
  {
      $this->salida .= "<SCRIPT>\n";
      $this->salida .= "function ComboJustificacion(valor,forma){\n";
       $this->salida .= "  if(valor!=-1){;\n";
      $this->salida .= "     forma.Observaciones.value=valor;\n";
      $this->salida .= "  }\n";
      $this->salida .= "}\n";
      $this->salida .= "</SCRIPT>\n";
  }




  /**
  * Forma del menu de admisiones
  * @access private
  * @return boolean
  */
  function FormaMenus()
  {
        $this->salida .= ThemeAbrirTabla('MENU AUTORIZACION PROCEDIMENTOS QX');
        $this->salida .= "            <br>";
        $this->salida .= "            <table width=\"40%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
        $this->salida .= "               <tr>";
        $this->salida .= "                  <td align=\"center\" class=\"modulo_table_list_title\">MENU AUTORIZACION QX</td>";
        $this->salida .= "               </tr>";
        $this->salida .= "               <tr>";
        $accionC=ModuloGetURL('app','CentroAutorizacionQx','user','LlamarBuscar');
        $this->salida .= "                  <td align=\"center\" class=\"modulo_list_oscuro\"><a href=\"$accionC\">Solicitudes Por Autorizar</a></td>";
        $this->salida .= "               </tr>";
				$this->salida .= "               <tr>";
				$accionA=ModuloGetURL('app','CentroAutorizacionQx','user','LlamarBuscarOS');
				$this->salida .= "                  <td align=\"center\" class=\"modulo_list_claro\"><a href=\"$accionA\">Listado De OS</a></td>";
				$this->salida .= "               </tr>";
        $this->salida .= "           </table>";
        if(empty($_SESSION['CentroAutorizacionQx']['TODOS']))
        {  $accion=ModuloGetURL('app','CentroAutorizacionQx','user','main');  }
        else
        {  $accion=ModuloGetURL('system','Menu','user','main');  }
        $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "<br><p align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"></p>";
        $this->salida .= "</form>";
        $this->salida .= ThemeCerrarTabla();
        return true;
  }


  /**
  *
  */
  function Encabezado()
  {
      $datos=$this->DatosEncabezado();
      $this->salida .= "<table  border=\"0\" class=\"modulo_table_list\" width=\"70%\" align=\"center\" >";
      $this->salida .= " <tr class=\"modulo_table_title\">";
      $this->salida .= " <td>EMPRESA</td>";
      $this->salida .= " <td>RESPONSABLE</td>";
      $this->salida .= " <td>PLAN</td>";
      $this->salida .= " </tr>";
      $this->salida .= " <tr align=\"center\">";
      $this->salida .= " <td class=\"modulo_list_claro\" >".$datos[razon_social]."</td>";
      $this->salida .= " <td class=\"modulo_list_claro\" >".$_SESSION['CentroAutorizacionQx']['RESPONSABLE']."</td>";
      $this->salida .= " <td class=\"modulo_list_claro\">".$datos[plan_descripcion]."</td>";
      $this->salida .= " </tr>";
      $this->salida .= " </table><br>";
  }


  /**
  *
  */
  function FormaMetodoBuscar($arr)
  {
			IncludeLib('funciones_admision');

      $this->salida.= ThemeAbrirTabla('BUSCAR SOLICITUDES QX');
  		if(empty($_SESSION['CentroAutorizacionQx']['TODOS']))
			{   $accion=ModuloGetURL('app','CentroAutorizacionQx','user','Buscar');  }
			else
			{   $accion=ModuloGetURL('app','CentroAutorizacionQx','user','BuscarSolicitud');  }
      $this->salida .= "<table class=\"modulo_table_list\" border=\"0\" width=\"80%\" align=\"center\">";
      $this->salida .= "<tr class=\"modulo_table_list_title\">";
      $this->salida .= "<td align = left >CRITERIOS DE BUSQUEDA:</td>";
      $this->salida .= "</tr>";
      $this->salida .= "<tr class=\"modulo_list_claro\" >";
      $this->salida .= "<td width=\"40%\" >";
      $this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\">";
      $this->salida .= "<tr><td>";
      $this->salida .= "<table width=\"80%\" align=\"center\" border=\"0\">";
      $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
      $this->salida .= "<tr><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
      $this->salida .=" <option value=\"\">-------SELECCIONE-------</option>";
      $tipo_id=TiposDocumentosPacientes();
			for($i=0; $i<sizeof($tipo_id); $i++)
			{
					$this->salida .=" <option value=\"".$tipo_id[$i][tipo_id_paciente]."\">".$tipo_id[$i][descripcion]."</option>";
			}
      $this->salida .= "</select></td></tr>";
      $this->salida .= "<tr><td class=\"label\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\"></td></tr>";
      $this->salida .= "<tr><td class=\"label\">NOMBRES</td><td><input type=\"text\" class=\"input-text\" name=\"Nombres\" maxlength=\"64\"></td></tr>";
			$this->salida .= "<tr><td class=\"label\">No. SOLICITUD: </td><td><input type=\"text\" class=\"input-text\" name=\"Solicitud\" maxlength=\"32\"></td></tr>";
      $this->salida .= "                <tr><td class=\"label\">TIPO SERVICIO: </td><td><select name=\"Servicio\" class=\"select\">";
      $tipo=TiposServicios();
      $this->salida .=" <option value=\"-1\">-------SELECCIONE-------</option>";
      for($i=0; $i<sizeof($tipo); $i++)
      {
          if($tipo[$i][servicio]==$_REQUEST[Servicio])
          {  $this->salida .=" <option value=\"".$tipo[$i][servicio]."\" selected>".$tipo[$i][descripcion]."</option>";  }
          else
          {  $this->salida .=" <option value=\"".$tipo[$i][servicio]."\">".$tipo[$i][descripcion]."</option>";  }
      }
      $this->salida .= "                  </select></td></tr>";
      $this->salida .= "<tr><td colspan = 2 align=\"center\" ><table>";
      $this->salida .= "<tr><td align=\"right\" ><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"BUSCAR\"></td>";
      $this->salida .= "</form>";
      $actionM=ModuloGetURL('app','CentroAutorizacionQx','user','TiposPlanes');
      $this->salida .= "<form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
      $this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"><br></td></form>";
      $this->salida .= "</tr>";
      $this->salida .= "</table></td></tr>";
      $this->salida .= "</td></tr></table>";
      $this->salida .= "</td>";
      $this->salida .= "</tr>";
      $this->salida .= "</table>";
      $this->salida .= "       </td>";
      $this->salida .= "    </tr>";
      $this->salida .= "  </table>";
      //mensaje
      $this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
      $this->salida .= $this->SetStyle("MensajeError");
      $this->salida .= "  </table>";
      if(!empty($arr))
      {
            $d=0;
            $this->salida .= "     <br><table width=\"80%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
            $this->salida .= "        <td width=\"25%\">IDENTIFICACION</td>";
            $this->salida .= "        <td width=\"45%\">PACIENTE</td>";
            $this->salida .= "        <td width=\"50%\">PROCESO AUTORIZACION</td>";
            $this->salida .= "        <td width=\"10%\"></td>";
            $this->salida .= "      </tr>";
            for($i=$d; $i<sizeof($arr); $i++)
            {
                  if($i % 2) {  $estilo="modulo_list_claro";  }
                  else {  $estilo="modulo_list_oscuro";   }
                  $this->salida .= "      <tr class=\"$estilo\">";
                  $this->salida .= "        <td>".$arr[$i][tipo_id_paciente]." ".$arr[$i][paciente_id]."</td>";
                  $this->salida .= "        <td>".$arr[$i][nombres]."".$arr[$i][evolucion_id]."</td>";
                  if($arr[$i][usuario_id]!=NULL)
                  {
                        $this->salida .= "        <td align=\"center\" class=\"label_error\">En Proceso</td>";
                  }
                  else
                  {  $this->salida .= "        <td align=\"center\"></td>";  }
                  if($arr[$i][usuario_id]==NULL)
                  {
  										if(empty($_SESSION['CentroAutorizacionQx']['TODOS']))
                      {  $accion=ModuloGetURL('app','CentroAutorizacionQx','user','DetalleSolicitud',array('tipoid'=>$arr[$i][tipo_id_paciente],'nombre'=>$arr[$i][nombres],'paciente'=>$arr[$i][paciente_id]));  }
											else
											{  $accion=ModuloGetURL('app','CentroAutorizacionQx','user','DetalleSolicituTodos',array('tipoid'=>$arr[$i][tipo_id_paciente],'nombre'=>$arr[$i][nombres],'paciente'=>$arr[$i][paciente_id]));   }
                      $this->salida .= "        <td align=\"center\"><a href=\"$accion\">VER</a></td>";
                  }
                  else
                  {  $this->salida .= "        <td align=\"center\"></td>";  }
                  $this->salida .= "      </tr>";
            }
            $this->salida .= " </table>";
            $this->conteo=$_SESSION['SPY2'];
            $this->salida .=$this->RetornarBarrad();
      }
      $this->salida .= ThemeCerrarTabla();
      return true;

  }

  /**
  *
  */
  function CalcularNumeroPasos($conteo){
    $numpaso=ceil($conteo/$this->limit);
    return $numpaso;
  }

  function CalcularBarra($paso){
    $barra=floor($paso/10)*10;
    if(($paso%10)==0){
      $barra=$barra-10;
    }
    return $barra;
  }

  function CalcularOffset($paso){
    $offset=($paso*$this->limit)-$this->limit;
    return $offset;
  }


  function RetornarBarrad(){
    $this->conteo;
    $this->limit;

    if($this->limit>=$this->conteo){
        return '';
    }
    $paso=$_REQUEST['paso'];
    if(is_null($paso)){
      $paso=1;
    }
    $vec='';
    foreach($_REQUEST as $v=>$v1)
    {
      if($v!='modulo' and $v!='metodo' and $v!='SIIS_SID' and  $v!='Of')
      {   $vec[$v]=$v1;   }
    }
    if(empty($vec)) {  $vec=array(); }
    $accion=ModuloGetURL('app','CentroAutorizacionQx','user','Buscar',$vec);
    $barra=$this->CalcularBarra($paso);
    $numpasos=$this->CalcularNumeroPasos($this->conteo);
    $colspan=1;

    $this->salida .= "<br><table border='1' align='center'  cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
    if($paso > 1){
      $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
      $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
      $colspan+=1;
    }else{
     // $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
      //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
    }
    $barra ++;
    if(($barra+10)<=$numpasos){
      for($i=($barra);$i<($barra+10);$i++){
        if($paso==$i){
            $this->salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
        }else{
            $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
        }
        $colspan++;
      }
      $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
      $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
      $colspan+=2;
    }else{
      $diferencia=$numpasos-9;
      if($diferencia<=0){$diferencia=1;}//cambiar en todas las barra
      for($i=($diferencia);$i<=$numpasos;$i++){
        if($paso==$i){
          $this->salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
        }else{
          $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
        }
        $colspan++;
      }
      if($paso!=$numpasos){
        $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
        $this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
        $colspan++;
      }else{
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
  *
  */
  function Todos()
  {
      $this->salida .= "<SCRIPT>";
      $this->salida .= "function Todos(frm,x){";
      $this->salida .= "  if(x==true){";
      $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
      $this->salida .= "      if(frm.elements[i].type=='checkbox'){";
      $this->salida .= "        frm.elements[i].checked=true";
      $this->salida .= "      }";
      $this->salida .= "    }";
      $this->salida .= "  }else{";
      $this->salida .= "    for(i=0;i<frm.elements.length;i++){";
      $this->salida .= "      if(frm.elements[i].type=='checkbox'){";
      $this->salida .= "        frm.elements[i].checked=false";
      $this->salida .= "      }";
      $this->salida .= "    }";
      $this->salida .= "  }";
      $this->salida .= "}";
      $this->salida .= "</SCRIPT>";
  }


 /**
  *
  */
  function FormaDetalleSolicitud($datos='',$control='')
  {
			IncludeLib('funciones_admision');
			if(!empty($datos))
			{
				if($control==3)
				{
					$RUTA = $_ROOT ."cache/ordenservicio.pdf";
				}
				$mostrar ="\n<script language='javascript'>\n";
				$mostrar.="var rem=\"\";\n";
				$mostrar.="  function abreVentana(){\n";
				$mostrar.="    var nombre=\"\"\n";
				$mostrar.="    var url2=\"\"\n";
				$mostrar.="    var str=\"\"\n";
				$mostrar.="    var ALTO=screen.height\n";
				$mostrar.="    var ANCHO=screen.width\n";
				$mostrar.="    var nombre=\"REPORTE\";\n";
				$mostrar.="    var str =\"ANCHO,ALTO,resizable=no,location=yes, status=no,scrollbars=yes\";\n";
				$mostrar.="    var url2 ='$RUTA';\n";
				$mostrar.="    rem = window.open(url2, nombre, str)};\n";
				$mostrar.="</script>\n";
				$this->salida.="$mostrar";
				$this->salida.="<BODY onload=abreVentana();>";
			}

			$reporte= new GetReports();
			$arr=$_SESSION['CentroAutorizacionQx']['ARREGLO']['DETALLE'];
			$this->salida .= ThemeAbrirTabla('DETALLE SOLICITUDES PROCEDIMIENTOS QX');
			$this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "  </table>";
			$this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
			$this->salida .= "      <tr align=\"center\">";
			$this->salida .= "        <td colspan=\"8\">";
			$accion=ModuloGetURL('app','CentroAutorizacionQx','user','PedirAutorizacion');
			$this->salida .= "     <table width=\"70%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">";
			$this->salida .= "      <tr>";
			$this->salida .= "        <td class=\"modulo_table_list_title\" colspan=\"6\" align=\"left\">DATOS PACIENTE </td>";
			$this->salida .= "      </tr>";
			$this->salida .= "      <tr>";
			$this->salida .= "        <td width=\"10%\" class=\"modulo_table_list_title\" width=\"20%\">IDENTIFICACION: </td><td width=\"20%\" class=\"modulo_list_claro\">".$arr[0][tipo_id_paciente]." ".$arr[0][paciente_id]."</td>";
			$this->salida .= "        <td width=\"10%\" class=\"modulo_table_list_title\" width=\"20%\">PACIENTE:</td><td width=\"40%\" class=\"modulo_list_claro\" colspan=\"3\">".$arr[0][nombres]."</td>";
			//$this->salida .= "        <td width=\"10%\" class=\"modulo_table_list_title\" width=\"20%\">INGRESO:</td><td width=\"60%\" class=\"modulo_list_claro\">".$arr[0][ingreso]."</td>";
			$this->salida .= "      </tr>";
			$this->salida .= "       </table>";
			$this->salida .= "        </td>";
			$this->salida .= "      </tr>";
      //links bd
      $plan=$this->Planes();
      for($i=0; $i<sizeof($plan); $i++)
      {
          $p=$this->ClasificarPlan($plan[$i][plan_id]);
          if(($p[sw_tipo_plan]==0 AND $p[sw_afiliacion]==1) OR ($p[sw_tipo_plan]==3))
          {
              $bd='';
              $bd=$this->DatosBD($arr[0][tipo_id_paciente],$arr[0][paciente_id],$plan[$i][plan_id]);
              if(!empty($bd))
              {
                  $this->salida .= "      <tr><td colspan=\"8\">";
                  $this->SetJavaScripts('DatosBD');
                  $this->SetJavaScripts('DatosBDAnteriores');
                  $this->SetJavaScripts('DatosEvolucionInactiva');
                  $this->salida .= "<br><table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"50%\" align=\"center\" class=\"normal_10\">";
                  $this->salida .= "  <tr class=\"modulo_list_claro\">";
                  $this->salida .= "   <td align=\"center\" colspan=\"2\" class=\"label\">".$plan[$i][plan_descripcion]."</td>";
                  $this->salida .= "  </tr>";          
                  $this->salida .= "  <tr class=\"modulo_list_claro\">";
                  $this->salida .= "   <td align=\"center\">".RetornarWinOpenDatosBD($arr[0][tipo_id_paciente],$arr[0][paciente_id],$plan[$i][plan_id])."</td>";
                  $x=$this->CantidadMeses($plan[$i][plan_id]);
                  if($x>1)
                  {
                      $this->salida .= "   <td align=\"center\">".RetornarWinOpenDatosBDAnteriores($arr[0][tipo_id_paciente],$arr[0][paciente_id],$plan[$i][plan_id],$x)."</td>";
                  }
                  $this->salida .= "  </tr>";
                  $this->salida .= "</table>";
                  $sw=$this->BuscarSwHc();
                  if(!empty($sw))
                  {
                      $_SESSION['AUTORIZACIONES']['AUTORIZAR']['ingreso']=$arr[0][ingreso];
                      $dat=$this->BuscarEvolucion();
                      if($dat)
                      {
                          $this->salida .= "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"30%\" align=\"center\" class=\"normal_10\">";
                          $this->salida .= "  <tr class=\"modulo_list_claro\">";
                          $_SESSION['HISTORIACLINICA']['RETORNO']['modulo']='CentroAutorizacionQx';
                          $_SESSION['HISTORIACLINICA']['RETORNO']['metodo']='FormaDetalleSolicitud';
                          $_SESSION['HISTORIACLINICA']['RETORNO']['tipo']='user';
                          $_SESSION['HISTORIACLINICA']['RETORNO']['contenedor']='app';
                          $accion=ModuloHCGetURL($dat,'','','','');
                          $this->salida .= "   <td align=\"center\"><a href=\"$accion\">HISTORIA CLINICA</a></td>";
                          $this->salida .= "  </tr>";
                          $this->salida .= "</table><BR>";
                      }
                  }      
                  $this->salida .= "      </td></tr>";
              }
          }
      }
      //fin links bd
      
      for($i=0; $i<sizeof($arr);)
      {
          $f=0;
          $accion=ModuloGetURL('app','CentroAutorizacionQx','user','PedirAutorizacion',array('plan'=>$arr[$i][plan_id],'empresa'=>$arr[$i][empresa_id],'servicio'=>$arr[$i][servicio]));
          $this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
          $d=$i;
          if($arr[$i][plan_id]==$arr[$d][plan_id]
            AND $arr[$i][servicio]==$arr[$d][servicio])
          {
                  $this->salida .= "      <tr><td colspan=\"8\"><br></td></tr>";
                  $this->salida .= "      <tr><td colspan=\"8\" class=\"modulo_table_list_title\">PLAN:".$arr[$i][plan_descripcion]."</td></tr>";
                  $this->salida .= "      <tr>";
                  $this->salida .= "        <td class=\"modulo_table_list_title\" width=\"12%\">SERVICIO: </td>";
                  $this->salida .= "        <td class=\"modulo_list_claro\" width=\"13%\" colspan=\"2\">".$arr[$i][desserv]."</td>";
                  $this->salida .= "        <td class=\"modulo_table_list_title\" width=\"11%\">DEPARTAMENTO: </td>";
                  $this->salida .= "        <td class=\"modulo_list_claro\" align=\"left\" colspan=\"4\">".$arr[$i][despto]."</td>";
                  $this->salida .= "      </tr>";
                  $this->salida .= "      <tr class=\"modulo_table_list_title\">";
                  $this->salida .= "        <td>FECHA</td>";
                  $this->salida .= "        <td width=\"10%\">SOLICITUD</td>";                  
                  $this->salida .= "        <td width=\"10%\">CARGO</td>";
                  $this->salida .= "        <td colspan=\"2\" width=\"50%\">DESCRIPCION</td>";
                  $this->salida .= "        <td width=\"7%\">CANTIDAD</td>";
                  $this->salida .= "        <td width=\"10%\">NIVEL</td>";
                  $this->salida .= "        <td width=\"10%\"></td>";
                  $this->salida .= "      </tr>";
          }

          while($arr[$i][plan_id]==$arr[$d][plan_id]
           AND $arr[$i][servicio]==$arr[$d][servicio])
          {
              if($d % 2) {  $estilo="modulo_list_claro";  }
              else {  $estilo="modulo_list_oscuro";   }
              $this->salida .= "      <tr class=\"$estilo\">";
              $this->salida .= "        <td>".FechaStamp($arr[$d][fecha])." ".HoraStamp($arr[$d][fecha])."</td>";
              $this->salida .= "        <td align=\"center\">".$arr[$d][hc_os_solicitud_id]."</td>";
              $this->salida .= "        <td align=\"center\">".$arr[$d][cargos]."</td>";
              $this->salida .= "        <td colspan=\"2\">".$arr[$d][descar]."</td>";
              $this->salida .= "        <td align=\"center\">".$arr[$d][cantidad]."</td>";
              $this->salida .= "        <td align=\"center\" class=\"label_mark\">".$arr[$d][descripcion]."</td>";
              $equi=$this->ValidarEquivalencias($arr[$d][cargos]);
              $cont=$this->ValidarContrato($arr[$d][cargos],$arr[$d][plan_id]);
							$this->salida .= "<td align=\"center\" class=\"label_error\">";
              if( $arr[$d][nivel] > $_SESSION['CentroAutorizacionQx']['NIVEL'])
              {    $this->salida .= "Necesita Nivel ".$arr[$d][nivel]."";  }
              elseif($equi >= 1 AND $cont > 0
                  AND $_SESSION['CentroAutorizacionQx']['NIVEL']>=$arr[$d][nivel])
              {
                    $s='';
                    $de=$this->ComboDepartamento($arr[$d][cargos]);
                    if(empty($de))
                    {
                        $p=$this->ComboProveedor($arr[$d][cargos]);
                        if(empty($p))
                        { $s='NO PROVEEDOR <BR>';  }
                    }
										$this->salida .= "$s<input type=\"radio\" value=\"".$arr[$d][cargos].",".$arr[$d][tarifario_id].",".$arr[$d][ingreso].",".$arr[$d][servicio].",".$arr[$d][hc_os_solicitud_id].",".$arr[$d][cargos].",".$arr[0][nombres].",".$arr[$d][plan_id].",".$arr[$d][usuario_id].",".$arr[$d][profesional]."\" name=\"Auto\">";
                    $f++;
              }
              elseif($cont==0)
              {
                  $this->salida .= "NO ESTA CONTRATADO";
              }
              elseif($equi==0)
              {
                  $this->salida .= "NO TIENE EQUIVALENCIAS";
              }
							$accionhref=ModuloGetURL('app','CentroAutorizacionQx','user','FormaAnularSolicitud',array('solicitud'=>$arr[$d][hc_os_solicitud_id],'descripcion'=>$arr[$d][descripcion]));
              $this->salida .= "<a class=\"label_mark\" href=\"$accionhref\" class=\"label_mark\"><BR>ANULAR</a>";
              $this->salida .= "      </td>";
              $this->salida .= "      </tr>";
              $d++;
          }
          $i=$d;
          if($f == 0)
          {
              $this->salida .= "      <tr class=\"$estilo\">";
              $this->salida .= "        <td class=\"label_error\" align=\"center\" colspan=\"8\">NINGUN CARGO PUEDER SER AUTORIZADO</td>";
              $this->salida .= "      </tr>";
          }
          if($f > 0)
          {
              $this->salida .= "      <tr class=\"$estilo\">";
              $this->salida .= "        <td align=\"right\" colspan=\"8\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"AUTORIZAR\"></td>";
              $this->salida .= "      </tr>";
          }
          $this->salida .= "                       </form>";
      }
      $this->salida .= "      <tr><td colspan=\"7\"><br></td></tr>";
      $this->salida .= " </table>";
     /* if(!empty($_SESSION['CentroAutorizacionQx']['ARREGLO']['DETALLE3']))
      {
			  $this->ListadoOsAuto('FormaDetalleSolicitud',&$reporte);
			}
      if(!empty($_SESSION['CentroAutorizacionQx']['ARREGLO']['DETALLE4']))
      {
			  $this->ListadoOsNoAuto('FormaDetalleSolicitud',&$reporte);
			}*/
			unset($reporte);
      $this->salida .= "     <table width=\"50%\" border=\"0\" align=\"center\">";
      $this->salida .= "               <tr>";
      $actionM=ModuloGetURL('app','CentroAutorizacionQx','user','LlamarBuscar');
      $this->salida .= "             <form name=\"forma1\" action=\"$actionM\" method=\"post\">";
      $this->salida .= "                       <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"></td>";
      $this->salida .= "                       </form>";
      $actionM=ModuloGetURL('app','CentroAutorizacionQx','user','main2');
      $this->salida .= "             <form name=\"forma2\" action=\"$actionM\" method=\"post\">";
      $this->salida .= "                       <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"></td>";
      $this->salida .= "                       </form>";
      $this->salida .= "               </tr>";
      $this->salida .= "  </table>";
      $this->salida .= ThemeCerrarTabla();
      return true;
  }

	function FormaAnularSolicitud()
	{
			$this->salida .= ThemeAbrirTabla('ANULAR SOLICITUD No. '.$_REQUEST['solicitud']);
			$accion=ModuloGetURL('app','CentroAutorizacionQx','user','AnularSolicitud',array('solicitud'=>$_REQUEST['solicitud'],'descripcion'=>$_REQUEST['descripcion']));
			$this->salida .= "       <form name=\"forma\" action=\"$accion\" method=\"post\">";
			$this->salida .= "      <br><table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "     </table>";
			$this->salida .= "       <table border=\"0\" width=\"70%\" align=\"center\">";
			$this->salida .= "           <tr>";
			$this->salida .= "    					<td  align=\"center\" colspan=\"2\" class=\"label_mark\">".$_REQUEST['descripcion']."</td>";
			$this->salida .= "            </tr>";
			$this->salida .= "           <tr>";
			$this->salida .= "              <td  width=\"30%\" class=\"".$this->SetStyle("Observaciones")."\">OBSERVACIONES ANULACION: </td>";
			$this->salida .= "              <td><textarea  cols=\"80\" rows=\"4\" class=\"textarea\" name=\"observacion\"></textarea></td>";
			$this->salida .= "            </tr>";
			$this->salida .= "       </table>";
			$this->salida .= "       <table align=\"center\" border=\"0\" width=\"50%\">";
			$this->salida .= "    <tr>";
			$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"ACEPTAR\"></form></td>";
			if(empty($_SESSION['CentroAutorizacionQx']['TODOS']))
			{ $accion=ModuloGetURL('app','CentroAutorizacionQx','user','DetalleSolicitud');  }
			else
			{ $accion=ModuloGetURL('app','CentroAutorizacionQx','user','DetalleSolicituTodos');  }
			$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
			$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"Cancelar\" type=\"submit\" value=\"CANCELAR\"></form></td>";
			$this->salida .= "    </tr>";
			$this->salida .= "       </table>";
			$this->salida .= ThemeCerrarTabla();
			return true;
	}

//-----------------------------------------------------------------------------------
}//fin clase

?>


