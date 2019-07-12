<?php

/**
 * $Id: app_Autorizacion_userclasses_HTML.php,v 1.13 2007/01/29 19:30:15 carlos Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Manejo visual de las autorizaciones.
 */

class app_Autorizacion_userclasses_HTML extends app_Autorizacion_user
{
  /**
  *Constructor de la clase app_Autorizacion_user_HTML
  *El constructor de la clase app_Autorizacion_user_HTML se encarga de llamar
  *a la clase app_Autorizacion_user quien se encarga de el tratamiento
  * de la base de datos.
  */

  function app_Autorizacion_user_HTML()
  {
        $this->salida='';
        $this->app_Autorizacion_user();
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
  * Forma para mensajes.
  * @access private
  * @return boolean
  * @param string mensaje
  * @param string nombre de la ventana
  * @param string accion de la forma
  * @param string nombre del boton
  */
  function FormaMensaje($mensaje,$titulo,$accion)
  {
        $this->salida .= ThemeAbrirTabla($titulo);
        $this->salida .= "            <table width=\"60%\" align=\"center\">";
        $this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "               <tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>";
        $this->salida .= "               <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td></tr>";
        $this->salida .= "           </form>";
        $this->salida .= "           </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
  }



 /**
 *
 */
 function SolicitudServicios($grupo,$tipo,$data,$nivel)
 {
        $this->salida .= ThemeAbrirTabla('AUTORIZACIONES - PANTALLA AUTORIZACION PACIENTE ');
        //tabla de autorizaciones del plan
        $this->salida .= "      <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "     </table>";
        $accion=ModuloGetURL('app','Autorizacion','user','InsertarServicio');
        $this->salida .= "      <form name=\"solicitud\" action=\"$accion\" method=\"post\">";
        $this->salida .= "     <br><table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "            <tr class=\"modulo_table_list_title\">";
        $this->salida .= "              <td align=\"center\" width=\"25%\">GRUPO</td>";
        $this->salida .= "              <td align=\"center\" width=\"35%\">TIPO</td>";
        $ser=$this->CallMetodoExterno('app','Autorizacion_Solicitud','user','NivelesAtencion');
        for($i=0; $i<sizeof($ser); $i++)
        { $this->salida .= "              <td width=\"8%\" align=\"center\">".$ser[$i][descripcion_corta]."</td>"; }
        $this->salida .= "            </tr>";
        $j=0;
        $d=0;
        foreach($nivel as $g => $t)
        {
              if($j % 2) {  $estilo="modulo_list_claro";  }
              else {  $estilo="modulo_list_oscuro";   }
              $this->salida .= "            <tr>";
              $this->salida .= "              <td colspan\"".$grupo[$g]."\" align=\"center\" class=\"$estilo\">$g</td>";
              $this->salida .= "              <td colspan=\"5\">";
              $f=0;
              foreach($t as $destipo => $desnivel)
              {
                  if($f % 2) {  $estilo="modulo_list_claro";  }
                  else {  $estilo="modulo_list_oscuro";   }
                  $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" cellspacing=\"1\" cellpadding=\"1\">";
                  $this->salida .= "            <tr class=\"$estilo\">";
                  $this->salida .= "              <td  width=\"35%\" colspan\"".$tipo[$destipo]."\"  align=\"center\">$destipo</td>";
                  $z=$j;
                  $sw=0;
                  for($i=0; $i<sizeof($ser); $i++)
                  {
                      if(empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['TODO']))
                      {
                          if(!empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['CARGOS']['VECT'][$data[$d][grupo_tipo_cargo]][$data[$d][tipo_cargo]][$ser[$i][nivel]])
                              AND $desnivel[$ser[$i][descripcion_corta]])
                          {
                              $this->salida .= "<td width=\"8%\" align=\"center\"><input type=\"checkbox\" value=\"".$data[$d][grupo_tipo_cargo].",".$data[$d][tipo_cargo].",".$ser[$i][nivel]."\" name=\"Nivel".$data[$d][grupo_tipo_cargo].$data[$d][tipo_cargo].$ser[$i][nivel]."\" checked></td>";
                              $d++;
                          }
                          elseif(empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['CARGOS']['VECT'][$data[$d][grupo_tipo_cargo]][$data[$d][tipo_cargo]][$ser[$i][nivel]])
                                 AND $desnivel[$ser[$i][descripcion_corta]])
                          {
                              $this->salida .= "<td width=\"8%\" align=\"center\"><input type=\"checkbox\" value=\"".$data[$d][grupo_tipo_cargo].",".$data[$d][tipo_cargo].",".$ser[$i][nivel]."\" name=\"Nivel".$data[$d][grupo_tipo_cargo].$data[$d][tipo_cargo].$ser[$i][nivel]."\" ></td>";
                              $d++;
                          }
                          else
                          {    $this->salida .= "<td width=\"8%\" align=\"center\"></td>";  }
                      }
                      else
                      {
                          if(!empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['CARGOS']['VECT'][$data[$d][grupo_tipo_cargo]][$data[$d][tipo_cargo]][$ser[$i][nivel]])
                              AND $ser[$i][nivel]==$data[$d][nivel])
                          {
                              $this->salida .= "<td width=\"8%\" align=\"center\"><input type=\"checkbox\" value=\"".$data[$d][grupo_tipo_cargo].",".$data[$d][tipo_cargo].",".$ser[$i][nivel]."\" name=\"Nivel".$data[$d][grupo_tipo_cargo].$data[$d][tipo_cargo].$ser[$i][nivel]."\" checked></td>";
                          }
                          else
                          {
                              $this->salida .= "<td width=\"8%\" align=\"center\"><input type=\"checkbox\" value=\"".$data[$d][grupo_tipo_cargo].",".$data[$d][tipo_cargo].",".$ser[$i][nivel]."\" name=\"Nivel".$data[$d][grupo_tipo_cargo].$data[$d][tipo_cargo].$ser[$i][nivel]."\" ></td>";
                          }
                          $d++;
                      }
                  }
                  $this->salida .= "            </tr>";
                  $this->salida .= "            </table>";
                  $f++;
              }
              $this->salida .= "              </td>";
              $this->salida .= "            </tr>";
        }
        $this->salida .= "            </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"50%\" align=\"center\">";
        $this->salida .= "          <tr align=\"center\"><td><input class=\"input-submit\" type=\"submit\" name=\"Guardar\" value=\"GUARDAR\"></td>";
        $this->salida .= "      </form>";
        $accion=ModuloGetURL('app','Autorizacion','user','LlamarFormaAutorizacion');
        $this->salida .= "      <form name=\"sol\" action=\"$accion\" method=\"post\">";
        $this->salida .= "          <td><input class=\"input-submit\" type=\"submit\" name=\"Guardar\" value=\"CANCELAR\"></td><tr>";
        $this->salida .= "      </form>";
        $this->salida .= "       </table>";
				$this->salida .= ThemeCerrarTabla();
        return true;
 }

	/**
	*
	*/
	function FormaCargos()
  {
        $this->salida .= ThemeAbrirTabla('AUTORIZACIONES - PANTALLA AUTORIZACION PACIENTE');
        $accion=ModuloGetURL('app','Autorizacion','user','InsertarCargo');
        $this->salida .= "      <form name=\"solicitud\" action=\"$accion\" method=\"post\">";
        $this->salida .= "      <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "     </table>";
        //tabla cargos autorizar
        $this->salida .= "         <br> <table width=\"90%\" align=\"center\" border=\"0\" class=\"modulo_table_list\"  cellpadding=\"4\" cellpadding=\"4\">";
        $this->salida .= "             <tr class=\"modulo_table_list_title\" align=\"center\">";
        $this->salida .= "                 <td colspan=\"5\">AUTORIZACION CARGOS</td>";
        $this->salida .= "             </tr>";
        $this->salida .= "             <tr align=\"center\" class=\"modulo_list_oscuro\">";
        $this->salida .= "                 <td class=\"label\">CARGOS</td>";
        $this->salida .= "                 <td colspan=\"4\">";
        $this->salida .= "            <table width=\"100%\" align=\"center\" border=\"0\"  cellpadding=\"3\">";
        foreach($_SESSION['AUTORIZACIONES']['AUTORIZAR']['VECTOR'] as $k => $v)
        {
            if(!empty($v))
            {
                $this->salida .= "             <tr align=\"center\" class=\"modulo_table_list_title\">";
                $this->salida .= "                 <td width=\"10%\">CODIGO</td>";
                $this->salida .= "                 <td>CARGO</td>";
                $this->salida .= "                 <td width=\"5%\">CANT</td>";
                $this->salida .= "                 <td width=\"5%\"></td>";
                $this->salida .= "             </tr>";
                foreach($v as $cod => $cant)
                {
                    foreach($cant as $cantidad => $cargo)
                    {
                            $this->salida .= "             <tr class=\"modulo_list_claro\">";
                            $this->salida .= "                 <td align=\"center\">$cod</td>";
                            $this->salida .= "                 <td>$cargo</td>";
                            $this->salida .= "                 <td align=\"center\">$cantidad</td>";
                            $accion=ModuloGetURL('app','Autorizacion','user','EliminarCargo',array('TarifarioId'=>$k,'Codigo'=>$cod,'Cantidad'=>$cantidad,'Cargo'=>$cargo));
                            $this->salida .= "                 <td align=\"center\"><a href=\"$accion\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
                            $this->salida .= "             </tr>";
                    }
                }
            }
        }
        $this->salida .= "            </table>";
        $this->salida .= "                 </td>";
        $this->salida .= "             </tr>";
        global $_ROOT;
        $PlanId=$_SESSION['SOLICITUDAUTORIZACION']['plan_id'];
        $this->salida .= "\n<script language='javascript'>\n";
        $this->salida .= "var rem=\"\";\n";
        $this->salida .= "  function abrirVentana(){\n";
        $this->salida .= "    var nombre='';\n";
        $this->salida .= "      var url2='';\n";
        $this->salida .= "      var str='';\n";
        $this->salida .= "      var ALTO=screen.height;\n";
        $this->salida .= "      var ANCHO=screen.width;\n";
        $this->salida .= "      nombre=\"buscador_General\";\n";
        $this->salida .= "      str =\"ANCHO,ALTO,resizable=no,status=no,scrollbars=yes\";\n";
        $this->salida .= "      url2 ='".$_ROOT."classes/classbuscador/buscador.php?tipo=BuscarCargo&forma=solicitud&plan='+'".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']."';\n";
        $this->salida .= "      rem = window.open(url2, nombre, str);\n";
				$this->salida .= "  }\n";
        $this->salida .= " </script>\n";
        $this->salida .= "    <input type=\"hidden\" name=\"TarifarioId\">";
        $this->salida .= "             <tr class=\"modulo_table_list_title\" align=\"center\">";
        $this->salida .= "                 <td>CODIGO</td>";
        $this->salida .= "                 <td>CARGO</td>";
        $this->salida .= "                 <td>CANT.</td>";
        $this->salida .= "                 <td></td>";
        $this->salida .= "                 <td></td>";
        $this->salida .= "             </tr>";
        $Cantidad=1;
        $this->salida .= "             <tr align=\"center\" class=\"modulo_list_claro\">";
        $this->salida .= "                 <td><input type=\"text\" class=\"input-text\" name=\"Codigo\" size=\"10\"></td>";
        $this->salida .= "                 <td><input type=\"text\" class=\"input-text\" name=\"Cargo\" size=\"86\" readonly></td>";
        $this->salida .= "                 <td><input type=\"text\" class=\"input-text\" name=\"Cantidad\" size=\"3\" value=\"$Cantidad\"></td>";
        $this->salida .= "                 <td><input type=\"button\" class=\"input-submit\" name=\"Buscar\" value=\"Buscar\" onclick=abrirVentana()></td>";
        $this->salida .= "                 <td width=\"5%\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Insertar\"></td>";
        $this->salida .= "             </tr>";
        $this->salida .= "            </table><br>";
        $this->salida .= "      <table border=\"0\" width=\"50%\" align=\"center\">";
        $this->salida .= "          <tr align=\"center\"><td><input class=\"input-submit\" type=\"submit\" name=\"Guardar\" value=\"GUARDAR\"></td>";
        $this->salida .= "      </form>";
        $accion=ModuloGetURL('app','Autorizacion','user','LlamarFormaAutorizacion');
        $this->salida .= "      <form name=\"sol\" action=\"$accion\" method=\"post\">";
        $this->salida .= "          <td><input class=\"input-submit\" type=\"submit\" name=\"Guardar\" value=\"CANCELAR\"></td><tr>";
        $this->salida .= "      </form>";
        $this->salida .= "       </table>";
				$this->salida .= ThemeCerrarTabla();
        return true;
  }


  /**
  *
  */
  function FormaAutorizacion()
  { 
        $this->SetJavaScripts('DatosBD');
        $this->SetJavaScripts('DatosBDAnteriores');
				//cambio sos
				$this->SetJavaScripts('EmpleadorSOS');
				$sw_ocultar=$this->BuscarPlanOcultar();
        if($_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO']!='CENTROAUTORIZACION'
						AND empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION']))
        {  $this->InsertarAutorizacionInicial();   }
        $this->salida = ThemeAbrirTabla('AUTORIZACION PACIENTE');
        if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']))
        {
            $this->salida .= "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"50%\" align=\"center\" class=\"normal_10\">";
            $this->salida .= "  <tr>";
            $this->salida .= "   <td align=\"center\">".RetornarWinOpenDatosBD($_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente'],$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id'],$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'])."</td>";
            $this->salida .= "  </tr>";
            /*$x=$this->MultiplesBD();
            if($x>1)
            {
                $this->salida .= "  <tr>";
                $this->salida .= "   <td align=\"center\">".RetornarWinOpenDatosBDAnteriores($_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente'],$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id'],$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'],$x)."</td>";
                $this->salida .= "  </tr>";
            }*/
            $this->salida .= "</table><BR>";
        }
        $sw=$this->BuscarSwHc();
        if(!empty($sw) AND !empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['ingreso']))
        {
            $dat=$this->BuscarEvolucion();
            if($dat)//1139
            {
                $this->salida .= "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"30%\" align=\"center\" class=\"normal_10\">";
                $this->salida .= "  <tr class=\"modulo_list_claro\">";
                $_SESSION['HISTORIACLINICA']['RETORNO']['modulo']='CentroAutorizacion';
                $_SESSION['HISTORIACLINICA']['RETORNO']['metodo']='FormaAutorizacion';
                $_SESSION['HISTORIACLINICA']['RETORNO']['tipo']='user';
                $_SESSION['HISTORIACLINICA']['RETORNO']['contenedor']='app';
                $accion=ModuloHCGetURL($dat,'','','','');
                $this->salida .= "   <td align=\"center\"><a href=\"$accion\">HISTORIA CLINICA</a></td>";
                $this->salida .= "  </tr>";
                $this->salida .= "</table><BR>";
            }
        }

        $m=$this->CantidadMeses($_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']);
        if($m>1)
        {
            $this->salida .= "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"30%\" align=\"center\" class=\"normal_10\">";
            $this->salida .= "  <tr class=\"modulo_list_claro\">";
            $this->salida .= "   <td align=\"center\">".RetornarWinOpenDatosBDAnteriores($_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente'],$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id'],$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'],$m)."</td>";
            $this->salida .= "  </tr>";
            $this->salida .= "</table><BR>";
        }
        //mensaje
        $this->salida .= "<div align=\"center\" class=\"label_mark\">".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['msg']."</div><br>";
        $this->salida .= "          <table width=\"90%\" align=\"center\" border=\"0\" cellpadding=\"3\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "          </table>";
//
if(empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']) 
AND empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id'])
AND empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente']))
{
	$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']=$_SESSION['FACTURACION']['plan_id'];
	$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente']=$_SESSION['FACTURACION']['tipo_id_paciente'];
	$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id']=$_SESSION['FACTURACION']['paciente_id'];
}
//
        //llamar en encabezado datos paciente
        $this->FormaDatosPaciente();
        $accion=ModuloGetURL('app','Autorizacion','user','InsertarAutorizacion');
        $this->salida .= "      <form name=\"forma\" action=\"$accion\" method=\"post\">";
        if($_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO']!='FACTURACION')
        {   //tipo afiliado y rango
            $this->FormaDatosAfiliado();
        }
        //otros datos de la bd
         if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']))
        {
              $this->salida .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
              $this->salida .= "          <tr>";
              $this->salida .= "            <td  width=\"10%\" class=\"".$this->SetStyle("TipoAfiliado")."\">EMPLEADOR: </td>";
								//cambio sos
								if(is_array($_SESSION['DATOSAFILIADOEMPLEADOR'][0])==1)
								{
									GLOBAL $VISTA;
									$prueba="<a href=\"javascript:EmpleadorSOS('0');\">";
									$prueba1="</a>";
								}
								$this->salida .= "<td align=\"left\" width=\"35%\">$prueba".$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_empleador']."$prueba1";
								$i=1;
								while(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_empleador'.$i]))
								{
									if(is_array($_SESSION['DATOSAFILIADOEMPLEADOR'][$i])==1)
									{
										$prueba="<a href=\"javascript:EmpleadorSOS('$i');\">";
										$prueba1="</a>";
									}
									$this->salida.=" - $prueba".$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_empleador'.$i].$prueba1;
									$i++;
								}
								$this->salida .="</td>";
								//fin cambio sos
								//$this->salida .= "            <td align=\"left\" width=\"35%\">".$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_empleador']."</td>";
								$this->salida .= "            <td></td>";
								$this->salida .= "             <td width=\"7%\" class=\"".$this->SetStyle("Nivel")."\">EDAD: </td>";
								$this->salida .= "            <td align=\"left\" width=\"5%\">".$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_edad']."</td>";
								$this->salida .= "            <td></td>";
								$this->salida .= "            <td width=\"10%\" class=\"".$this->SetStyle("Semanas")."\" width=\"23%\">ESTADO: </td>";
								$this->salida .= "            <td align=\"left\" width=\"10%\">".$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_estado_bd']."</td>";
								$this->salida .= "            <td width=\"12%\" class=\"".$this->SetStyle("Semanas")."\" width=\"23%\">URGENCIAS: </td>";
								if($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_urgencias']==1)
								{  $ur='MES URG'; }
								$this->salida .= "            <td align=\"left\" width=\"10%\">".$ur."</td>";
								$this->salida .= "          </tr>";
								//cambio sos
								if($sw_ocultar!=1)
								{
									$this->salida .= "          <tr>";
									$this->salida .= "            <td  width=\"10%\" class=\"".$this->SetStyle("TipoAfiliado")."\">RADICACION BD: </td>";
									$this->salida .= "            <td align=\"left\" width=\"35%\">".$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['fecha_radicacion']."</td>";
									$this->salida .= "            <td></td>";
									$this->salida .= "             <td width=\"7%\" class=\"".$this->SetStyle("Nivel")."\">VENCIMIENTO BD: </td>";
									$this->salida .= "            <td align=\"left\" width=\"5%\">".$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['fecha_vencimiento']."</td>";
									$this->salida .= "            <td></td>";
								}
								//fin cambio sos
								$this->salida .= "            <td width=\"10%\" class=\"".$this->SetStyle("Semanas")."\" width=\"23%\"></td>";
								$this->salida .= "            <td align=\"left\" width=\"10%\"></td>";
								$this->salida .= "            <td width=\"12%\" class=\"".$this->SetStyle("Semanas")."\" width=\"23%\"></td>";
								$this->salida .= "            <td align=\"left\" width=\"10%\"></td>";
								$this->salida .= "          </tr>";
								$this->salida .= "       </table>";
        }
				//si no es la 1 del sistema
				if($_SESSION['AUTORIZACIONES']['AUTORIZAR']['AUTORIZACION'] > 100)
        {   $this->CargosSolicitadosAutorizacion();   }
				else
				{ $this->FormaCargoCups();  }
				//comentaria porque no se si se va ha dejar
        /*if($_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO']!='CONSULTAEXTERNA'
          AND $_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO']!='FACTURACION'
          AND $_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO']!='CENTROAUTORIZACION')
        {      //menu adicionar
              $this->salida .= "     <br><table border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
              $this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
              $this->salida .= "      <td colspan=\"2\">ADICIONAR AUTORIZACIONES PARA:</td>";
              $this->salida .= "       </tr>";
              $this->salida .= "      <tr align=\"center\" class=\"modulo_list_claro\">";
              //$accionS=ModuloGetURL('app','Autorizacion','user','AdicionarServicio');
              //$this->salida .= "            <td width=\"50%\"><a href=\"$accionS\">GRUPOS CARGOS</a></td>";
              $this->salida .= "            <td width=\"50%\"><input class=\"input-submit\" type=\"submit\" name=\"Grupos\" value=\"GRUPOS CARGOS\"></td>";
              //$accionC=ModuloGetURL('app','Autorizacion','user','AdicionarCargo');
              //$this->salida .= "            <td width=\"50%\"><a href=\"$accionC\">CARGOS</a></td>";
              $this->salida .= "            <td width=\"50%\"><input class=\"input-submit\" type=\"submit\" name=\"Cargos\" value=\"CARGOS\"></td>";
              $this->salida .= "          </tr>";
              $this->salida .= "       </table>";
        }*/
        //TIPO AUTORIZACION
				$this->salida .= "     <br><table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
        if($sw_ocultar!=1)
				{
        	$this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
        	$this->salida .= "      <td width=\"33%\">SELECCIONE TIPO AUTORIZACION: </td>";
        	$this->salida .= "      <td class=\"modulo_list_claro\"><select name=\"TipoAutorizacion\" class=\"select\">";
        	$TiposAuto=$this->TiposAuto();
        	$this->BuscarTipoAutorizacion($TiposAuto,$_REQUEST['TipoAutorizacion']);
        	$this->salida .= "      </select></td>";
        	//$accion=ModuloGetURL('app','Autorizacion','user','PedirAutorizacion');
        	$this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td>";
        	$this->salida .= "      </tr>";
				}
				$this->salida .= "     </table><BR>";
				//AQUI ESTA EL CAMBIO DE LO DE TIPOS, ESTO NO DEBERIA CAMBIAR EL PROCESO NORMAL DE AUTORIZACION
        /*$this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"33%\">SELECCIONE TIPO AUTORIZACION: </td>";
        $this->salida .= "      <td class=\"modulo_list_claro\"><select name=\"TipoAutorizacion\" class=\"select\">";
        $TiposAuto=$this->CallMetodoExterno('app','Autorizacion','user','TiposAuto');
        $this->BuscarTipoAutorizacion($TiposAuto,$_REQUEST['TipoAutorizacion']);
        $this->salida .= "      </select></td>";
        //$accion=ModuloGetURL('app','Autorizacion','user','PedirAutorizacion');
        $this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td>";
        $this->salida .= "      </tr>";
        $this->salida .= "     </table><BR>";*/
        //fecha de la autorizacion
        $this->salida .= "      <table border=\"0\" width=\"70%\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\" class=\"normal_10\">";
        $this->salida .= "      </tr>";
        $this->salida .= "  <td class=\"".$this->SetStyle("FechaAuto")."\">FECHA AUTORIZACION: </td>";
        if(!$FechaAuto){ $FechaAuto=date("d/m/Y"); }
        $this->salida .= "  <td><input type=\"text\" class=\"input-text\" name=\"FechaAuto\" size=\"12\" value=\"".$FechaAuto."\" onFocus=\"this.select();\"  onBlur=\"IsValidDate(this,'DD/MM/YYYY')\" onKeyUp=\"setDate(this,'DD/MM/YYYY','es')\">";
        $this->salida .= "&nbsp;&nbsp;".ReturnOpenCalendario('forma','FechaAuto','/')."</td>";
        if(!$HoraAuto){ $HoraAuto=date('H'); }
        if(!$MinAuto){ $MinAuto=date('i'); }
        $this->salida .= "  <td class=\"".$this->SetStyle("HoraAuto")."\">HORA AUTORIZACION: </td>";
        $this->salida .= "  <td><input type=\"text\" class=\"input-text\" name=\"HoraAuto\" size=\"4\" value=\"".$HoraAuto."\" maxlength=\"2\">&nbsp;:&nbsp;<input type=\"text\" class=\"input-text\" name=\"MinAuto\" size=\"4\" value=\"".$MinAuto."\" maxlength=\"2\"></td>";
        $this->salida .= "      </tr>";
        $this->salida .= "     </table>";
        //OBSERVACIONES
        $this->salida .= " <table border=\"0\" width=\"80%\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
        $observacion=$this->Observaciones();
        //if(!empty($observacion))
        if($observacion!=' ' AND $observacion!='')
        {
            $this->salida .= "  <tr>";
            $this->salida .= "  <td  width=\"30%\" class=\"".$this->SetStyle("Observaciones")."\">OBSERVACIONES DE LAS AUTORIZACION REALIZADAS: </td>";
            $this->salida .= "  <td><textarea  cols=\"80\" rows=\"4\" class=\"textarea\" name=\"ObservacionesT\" readonly>$observacion</textarea></td>";
            $this->salida .= "  </tr><br>";
        }
        $this->salida .= "  <tr>";
        $this->salida .= "  <td  width=\"30%\" class=\"".$this->SetStyle("Observaciones")."\">OBSERVACIONES AUTORIZACION: </td>";
        $obs='';
        if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_urgencias'])
         AND empty($_SESSION['AUTORIZACIONES']['ObservacionesA']))
        { $obs='PACIENTE EN MES DE URGENCIAS. '; }
        elseif(!empty($_SESSION['AUTORIZACIONES']['ObservacionesA']))
        { $obs=$_SESSION['AUTORIZACIONES']['ObservacionesA'];}
        $this->salida .= "  <td><textarea  cols=\"80\" rows=\"4\" class=\"textarea\" name=\"ObservacionesA\">$obs</textarea></td>";

        $this->salida .= "  </tr>";
        $this->salida .= "     </table><BR>";
        if($_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO']!='CONSULTAEXTERNA'
            AND $_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO']!='FACTURACION'
            AND $_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO']!='CENTROAUTORIZACION')
        {   //OBSERVACIONES INGRESO
            $this->salida .= "      <table border=\"0\" width=\"80%\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
            $this->salida .= "  <tr>";
            $this->salida .= "  <td   width=\"30%\" class=\"".$this->SetStyle("ObservacionesI")."\" align=\"left\">OBSERVACIONES INGRESO:<br>( Esta observación será mostrada durante todo el manejo de toda la cuenta. ) </td>";
            $this->salida .= "  <td><textarea  cols=\"80\" rows=\"3\" class=\"textarea\" name=\"ObservacionesI\">".$_SESSION['AUTORIZACIONES']['ObservacionesI']."</textarea></td>";
            $this->salida .= "  </tr><br>";
            $this->salida .= "     </table><BR>";
        }
        //url protocolo
        if(!empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['protocolo']))
        {
            if(file_exists("protocolos/".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['protocolo'].""))
            {
                $Protocolo=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['protocolo'];
                $this->salida .= "<script>";
                $this->salida .= "function Protocolo(valor){";
                $this->salida .= "window.open('protocolos/'+valor,'PROTOCOLO','');";
                $this->salida .= "}";
                $this->salida .= "</script>";
                $accion="javascript:Protocolo('$Protocolo')";
            }
            $this->salida .= "          <br><table width=\"40%\" align=\"center\" border=\"0\" class=\"normal_10\" cellpadding=\"3\">";
            $this->salida .= "             <tr class=\"modulo_list_claro\">";
            $this->salida .= "                 <td width=\"30%\" class=\"label\">PROTOCOLO</td>";
            $this->salida .= "                 <td><a href=\"$accion\">$Protocolo</a></td>";
            $this->salida .= "             </tr>";
            $this->salida .= "            </table><br>";
        }
				//cambio sos
				if($sw_ocultar==1)
				{
					$this->AutorizacionElectronicaSOS();
				}
				$this->salida .= "<br><table border=\"0\" cellspacing=\"3\" cellpadding=\"2\" width=\"60%\" align=\"center\">";
				$this->salida .= "  <tr>";
				$this->salida .= "  <td align=\"center\" width=\"50%\"><input class=\"input-submit\" type=\"submit\" name=\"Autorizar\" value=\"AUTORIZAR\"></td>";
				if($_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO']!='CENTROAUTORIZACION')
				{  $this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"NoAutorizar\" value=\"NO AUTORIZAR\"></td>";  }
				$this->salida .= "      </form>";
				$accion=ModuloGetURL('app','Autorizacion','user','RetornarAutorizacion');
				$this->salida .= "  <td align=\"center\"><form name=\"forma2\" action=\"$accion\" method=\"post\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"></td>";
				$this->salida .= "      </form>";
				$this->salida .= "  </tr>";
				$this->salida .= "  </table>";
				$this->salida .= "      </form>";
				$this->salida .= ThemeCerrarTabla();
				return true;
  }

  /**
  *
  */
  function FormaDatosPaciente()
  {
        $this->salida .= " <table border=\"0\" width=\"70%\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\" class=\"modulo_table_list\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"modulo_table_list_title\" width=\"20%\">IDENTIFICACION:</td><td width=\"20%\" class=\"modulo_list_claro\">".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente']." ".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id']."</td>";
        $nombre=$this->NombrePaciente($_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente'],$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id']);
        if(empty($nombre))
        {
            if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']))
            {
                $nombre=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_Primer_nombre']." ".$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_Segundo_nombre']." ".$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_Primer_apellido']." ".$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_Segundo_apellido'];
                if(empty($nombre) OR $nombre=='   ')
                {
                      if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_nombre_completo']))
                      {  $this->salida .= "  <td class=\"modulo_table_list_title\" width=\"20%\">PACIENTE:</td><td class=\"modulo_list_claro\">".$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_nombre_completo']."</td>";   }
                      else
                      {  $this->salida .= "  <td colspan=2 class=\"modulo_list_claro\"></td>";   }
                }
                else
                {  $this->salida .= "  <td class=\"modulo_table_list_title\" width=\"20%\">PACIENTE:</td><td class=\"modulo_list_claro\">".$nombre."</td>";   }
            }
            else
            {  $this->salida .= "  <td colspan=2 class=\"modulo_list_claro\"></td>";   }
        }
        else
        {  $this->salida .= "  <td class=\"modulo_table_list_title\" width=\"20%\">PACIENTE:</td><td class=\"modulo_list_claro\">".$nombre[nombre]."</td>";   }
        $this->salida .= "  </tr>";
        $plan=$this->DatosPlan();
        $this->salida .= "  <td class=\"modulo_table_list_title\" width=\"20%\">PLAN:</td><td class=\"modulo_list_claro\">".$plan[plan_descripcion]."</td>";
        $this->salida .= "  <td class=\"modulo_table_list_title\" width=\"20%\">RESPONSABLE:</td><td class=\"modulo_list_claro\">".$plan[nombre_tercero]."</td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table><br>";
  }


  /**
  *
  */
  function FormaDatosAfiliado()
  {
      if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_tipo_afiliado'])
          AND !empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_nivel']))
      {
          //tipo afiliado
          if(empty($_SESSION['AUTORIZACIONES']['AFILIADO']))
          {
              $tipo=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_tipo_afiliado'];
              $_SESSION['AUTORIZACIONES']['AFILIADO']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_tipo_afiliado'];
          }
          else
          {  $tipo=$_SESSION['AUTORIZACIONES']['AFILIADO'];  }
          //rango
          if(empty($_SESSION['AUTORIZACIONES']['RANGO']))
          {
              $Nivel=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_nivel'];
              $_SESSION['AUTORIZACIONES']['RANGO']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_nivel'];
          }
          else
          {  $Nivel=$_SESSION['AUTORIZACIONES']['RANGO'];  }
          //semanas
          if(empty($_SESSION['AUTORIZACIONES']['SEMANAS']))
          {
              $s=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_semanas_cotizadas'];
              $_SESSION['AUTORIZACIONES']['SEMANAS']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_semanas_cotizadas'];
          }
          else
          {  $s=$_SESSION['AUTORIZACIONES']['SEMANAS'];  }
					if(empty($s))
					{ 
							$_SESSION['AUTORIZACIONES']['SEMANAS']=0;  
							$s=$_SESSION['AUTORIZACIONES']['SEMANAS'];
					}
          $this->salida .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
          $this->salida .= "          <tr>";
          $this->salida .= "            <td  width=\"15%\" class=\"".$this->SetStyle("TipoAfiliado")."\">TIPO AFILIADO: </td>";
          $NomAfi=$this->NombreAfiliado($tipo);
          $this->salida .= "            <td align=\"left\" width=\"20%\"><input type=\"hidden\"  class=\"input-text\" name=\"TipoAfiliado\" value=\"".$tipo."\">".$NomAfi[tipo_afiliado_nombre]."</td>";
          $this->salida .= "            <td></td>";
          $this->salida .= "             <td width=\"10%\" class=\"".$this->SetStyle("Nivel")."\">RANGO: </td>";
          $this->salida .= "            <td align=\"left\" width=\"7%\"><input type=\"hidden\"  class=\"input-text\" name=\"Nivel\" value=\"".$Nivel."\">".$Nivel."</td>";
          $this->salida .= "            <td></td>";
          $this->salida .= "            <td width=\"20%\" class=\"".$this->SetStyle("Semanas")."\" width=\"23%\">SEMANAS COTIZADAS: </td>";
          $this->salida .= "            <td align=\"left\" width=\"10%\"><input type=\"text\" name=\"Semanas\" size=\"8\" value=\"".$s."\" readonly></td>";
          $this->salida .= "            <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Cambiar\" value=\"CAMBIAR\"></td>";
          $this->salida .= "          </tr>";
          $this->salida .= "       </table><br>";
      }
      else
      {
          $this->salida .= "    <input type=\"hidden\" name=\"Si\" value=\"1\">";
          $this->salida .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
          $tipo_afiliado=$this->Tipo_Afiliado();
          $this->salida .= "          <tr>";
          $TipoAfiliado=$_SESSION['AUTORIZACIONES']['AFILIADO'];
          if(empty($TipoAfiliado))
          { $TipoAfiliado=$tipo_afiliado[0][tipo_afiliado_id]; }
          if(sizeof($tipo_afiliado)>1)
          {
              $this->salida .= "               <td class=\"".$this->SetStyle("TipoAfiliado")."\">TIPO AFILIADO: </td><td><select name=\"TipoAfiliado\" class=\"select\">";
              $this->BuscarIdTipoAfiliado($tipo_afiliado,$TipoAfiliado);
              $this->salida .= "              </select></td>";
          }
          else
          {
              $this->salida .= "            <td class=\"".$this->SetStyle("TipoAfiliado")."\">TIPO AFILIADO: </td>";
              $NomAfi=$this->NombreAfiliado($TipoAfiliado);
              $this->salida .= "            <td><input type=\"hidden\"  class=\"input-text\" name=\"TipoAfiliado\" value=\"".$tipo_afiliado[0][tipo_afiliado_id]."\">".$NomAfi[tipo_afiliado_nombre]."</td>";
              $this->salida .= "            <td></td>";
          }
          $niveles=$this->Niveles();
          $Nivel=$_SESSION['AUTORIZACIONES']['RANGO'];
          if(sizeof($niveles)>1)
          {
            $this->salida .= "               <td class=\"".$this->SetStyle("Nivel")."\">RANGO: </td><td><select name=\"Nivel\" class=\"select\">";
            $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
            for($i=0; $i<sizeof($niveles); $i++)
            {
                if($niveles[$i][rango]==$Nivel){
                  $this->salida .=" <option value=\"".$niveles[$i][rango]."\" selected>".$niveles[$i][rango]."</option>";
                }
                else{
                    $this->salida .=" <option value=\"".$niveles[$i][rango]."\">".$niveles[$i][rango]."</option>";
                }
            }
          }
          else
          {
              $this->salida .= "             <td class=\"".$this->SetStyle("Nivel")."\">RANGO: </td>";
              $this->salida .= "            <td><input type=\"hidden\"  class=\"input-text\" name=\"Nivel\" value=\"".$niveles[0][rango]."\">".$niveles[0][rango]."</td>";
              $this->salida .= "            <td></td>";
          }
          $this->salida .= "            <td class=\"".$this->SetStyle("Semanas")."\" width=\"23%\">SEMANAS COTIZADAS: </td>";
          $s=$_SESSION['AUTORIZACIONES']['SEMANAS'];
					if(empty($s))
					{  $s=0;  }
          $this->salida .= "            <td><input type=\"text\" name=\"Semanas\" size=\"8\" value=\"".$s."\"></td>";
          $this->salida .= "          </tr>";
          $this->salida .= "       </table><br>";
      }
  }


  /**
  *
  */
  function FormaCambiar($TipoAfiliado,$Nivel,$s)
  {
      $this->salida .= ThemeAbrirTabla('AUTORIZACIONES - CAMBIAR DATOS AFILIADO');
      if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']))
      {
          //$a=ImplodeArrayAssoc($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']);
					$this->FormaCamposBD();
         /* $this->salida .= "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"60%\" align=\"center\">";
          $this->salida .= "  <tr>";
          $this->salida .= "  <td colspan=\"2\">";
          $this->salida .= "            <table width=\"80%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
          $this->salida .= "               <tr>";
          $this->salida .= "                  <td colspan=\"2\" align=\"center\" class=\"modulo_table_list_title\">DATOS AFILIADO EN LA BASE DE DATOS DE LA ENTIDAD</td>";
          $this->salida .= "               </tr>";
          $arreglon=ExplodeArrayAssoc($a);
          $i=0;
          foreach($arreglon as $k => $v)
          {
              if($i % 2) {  $estilo="modulo_list_claro";  }
              else {  $estilo="modulo_list_oscuro";   }
              $this->salida .= "         <tr class=\"$estilo\">";
              $this->salida .= "            <td align=\"center\">$k</td>";
              $this->salida .= "            <td align=\"center\">$v</td>";
              $this->salida .= "        </tr>";
              $i++;
          }
          $this->salida .= "           </table>";
          $this->salida .= "               </td>";
          $this->salida .= "               </tr>";
          $this->salida .= "           </table><BR>";*/
      }
      $this->salida .= "            <table width=\"50%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
      $this->salida .= "               <tr>";
      $this->salida .= $this->SetStyle("MensajeError");
      $this->salida .= "               </tr>";
      $this->salida .= "           </table>";
      $accion=ModuloGetURL('app','Autorizacion','user','GuardarCambiosAfiliado');
      $this->salida .= "   <form name=\"forma\" action=\"$accion\" method=\"post\">";
      $this->salida .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
      $tipo_afiliado=$this->Tipo_Afiliado();
      $this->salida .= "          <tr>";
      if(sizeof($tipo_afiliado)>1)
      {
          $this->salida .= "               <td class=\"".$this->SetStyle("TipoAfiliado")."\">TIPO AFILIADO: </td><td><select name=\"TipoAfiliado\" class=\"select\">";
          $this->BuscarIdTipoAfiliado($tipo_afiliado,$TipoAfiliado);
          $this->salida .= "              </select></td>";
      }
      else
      {
          $this->salida .= "            <td class=\"".$this->SetStyle("TipoAfiliado")."\">TIPO AFILIADO: </td>";
          $NomAfi=$this->NombreAfiliado($TipoAfiliado);
          $this->salida .= "            <td><input type=\"hidden\"  class=\"input-text\" name=\"TipoAfiliado\" value=\"".$_SESSION['SOLICITUDAUTORIZACION']['tipo_afiliado_id']."\">".$NomAfi[tipo_afiliado_nombre]."</td>";
          $this->salida .= "            <td></td>";
      }
      $niveles=$this->Niveles();
      if(sizeof($niveles)>1)
      {
        $this->salida .= "               <td class=\"".$this->SetStyle("Nivel")."\">RANGO: </td><td><select name=\"Nivel\" class=\"select\">";
        $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
        for($i=0; $i<sizeof($niveles); $i++)
        {
            if($niveles[$i][rango]==$Nivel){
              $this->salida .=" <option value=\"".$niveles[$i][rango]."\" selected>".$niveles[$i][rango]."</option>";
            }
            else{
                $this->salida .=" <option value=\"".$niveles[$i][rango]."\">".$niveles[$i][rango]."</option>";
            }
        }
      }
      else
      {
          $this->salida .= "             <td class=\"".$this->SetStyle("Nivel")."\">RANGO: </td>";
          $this->salida .= "            <td><input type=\"hidden\"  class=\"input-text\" name=\"Nivel\" value=\"".$niveles[0][rango]."\">".$niveles[0][rango]."</td>";
          $this->salida .= "            <td></td>";
      }
      $this->salida .= "            <td class=\"".$this->SetStyle("Semanas")."\" width=\"23%\">SEMANAS COTIZADAS: </td>";
      $this->salida .= "            <td align=\"left\" width=\"10%\"><input type=\"text\" name=\"Semanas\" size=\"8\" value=\"".$s."\" ></td>";
      $this->salida .= "          </tr>";
      $this->salida .= "          <tr>";
      $this->salida .= "            <td colspan=\"6\" align=\"center\">OBSERVACION: <textarea name=\"Observacion\" cols=\"65\" rows=\"3\" class=\"textarea\"></textarea></td>";
      $this->salida .= "          </tr>";      
      $this->salida .= "       </table><br>";
      $this->salida .= "<br><table border=\"0\" cellspacing=\"3\" cellpadding=\"2\" width=\"40%\" align=\"center\">";
      $this->salida .= "  <tr>";
      $this->salida .= "  <td align=\"center\" width=\"50%\"><input class=\"input-submit\" type=\"submit\" name=\"Autorizar\" value=\"GUARDAR\"></td>";
      $this->salida .= "  </form>";
      $accion=ModuloGetURL('app','Autorizacion','user','LlamarFormaAutorizacion');
      $this->salida .= "  <td align=\"center\"><form name=\"forma2\" action=\"$accion\" method=\"post\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"></td>";
      $this->salida .= "      </form>";
      $this->salida .= "  </tr>";
      $this->salida .= "  </table>";
      $this->salida .= ThemeCerrarTabla();
      return true;
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
  *
  */
  function FormaJustificar($auto)
  {
      $this->salida .= ThemeAbrirTabla('AUTORIZACIONES - JUSTIFICAR NO AUTORIAZCION');
      $accion=ModuloGetURL('app','Autorizacion','user','JustificarNoAutorizacion',array('auto'=>$auto));
      $this->salida .= "   <form name=\"forma\" action=\"$accion\" method=\"post\">";
      $jus=$this->CallMetodoExterno('app','Autorizacion_Solicitud','user','Justificacion');
      $this->ComboJustificacion();
      $this->salida .= " <table border=\"0\" width=\"90%\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
      $this->salida .= "  <tr>";
      $this->salida .= "  <td class=\"label_error\" colspan=\"2\" align=\"center\">DEBE JUSTIFICAR PORQUE NO AUTORIZO</td>";
      $this->salida .= "  </tr>";
      $this->salida .= $this->SetStyle("MensajeError");
      $this->salida .= "  <tr>";
      $this->salida .= "  <td class=\"".$this->SetStyle("Observaciones")."\">OBSERVACIONES: </td>";
      $this->salida .= "  <td class=\"label\">JUSTIFICACION: </td>";
      $this->salida .= "  </tr>";
      $this->salida .= "  <tr>";
      $this->salida .= "  <td><textarea  cols=\"85\" rows=\"7\" class=\"textarea\" name=\"Observaciones\">$Observaciones</textarea></td>";
      $this->salida .= "  <td><select name=\"Tipo\" class=\"select\" onchange=\"ComboJustificacion(this.value,this.form)\">";
      $this->salida .=" <option value=\"-1\">-----SELECCIONE-----</option>";
      for($j=0; $j<sizeof($jus); $j++)
      {
          $f=$r='';
          if($jus[$j][justificacion])
          {  $f='JUSTIFICACION: '.$jus[$j][justificacion];  }
          if($jus[$j][recomendaciones])
          {  $r='RECOMENDACIONES: '.$jus[$j][recomendaciones];  }
          $this->salida .=" <option value=\"".$f."\n\n".$r."\">".$jus[$j][nombre]."</option>";
      }
      $this->salida .= "  </td>";
      $this->salida .= "  </tr>";
      $this->salida .= "  </table><BR>";
      $this->salida .= "<br><table border=\"0\" cellspacing=\"3\" cellpadding=\"2\" width=\"40%\" align=\"center\">";
      $this->salida .= "  <tr>";
      $this->salida .= "  <td align=\"center\" width=\"50%\"><input class=\"input-submit\" type=\"submit\" name=\"Autorizar\" value=\"GUARDAR\"></td>";
      $this->salida .= "  </form>";
      $accion=ModuloGetURL('app','Autorizacion','user','LlamarFormaAutorizacion');
      $this->salida .= "   <form name=\"forma\" action=\"$accion\" method=\"post\">";
      $this->salida .= "  <td align=\"center\" width=\"50%\"><input class=\"input-submit\" type=\"submit\" name=\"Autorizar\" value=\"VOLVER\"></td>";
      $this->salida .= "  </form>";
      $this->salida .= "  </tr>";
      $this->salida .= "  </table>";
      $this->salida .= ThemeCerrarTabla();
      return true;
  }

  /**
  * Crear el combo de tipos de afiliados
  * @access private
  * @return string
  * @param array arreglo con los tipos de afiliados
  * @param int tipo de afiliado
  */
  function BuscarIdTipoAfiliado($tipo_afiliado,$TipoAfiliado='')
  {
        $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
        for($i=0; $i<sizeof($tipo_afiliado); $i++)
        {
          if($tipo_afiliado[$i][tipo_afiliado_id]==$TipoAfiliado){
           $this->salida .=" <option value=\"".$tipo_afiliado[$i][tipo_afiliado_id]."\" selected>".$tipo_afiliado[$i][tipo_afiliado_nombre]."</option>";
          }
          elseif($tipo_afiliado[$i][tipo_afiliado_id]==$_SESSION['SOLICITUDAUTORIZACION']['AFILIADO'][$tipo_afiliado[$i][tipo_afiliado_id]]){
           $this->salida .=" <option value=\"".$tipo_afiliado[$i][tipo_afiliado_id]."\" selected>".$tipo_afiliado[$i][tipo_afiliado_nombre]."</option>";
          }
          else{
           $this->salida .=" <option value=\"".$tipo_afiliado[$i][tipo_afiliado_id]."\">".$tipo_afiliado[$i][tipo_afiliado_nombre]."</option>";
          }
        }
  }

  /**
  *
  */
  function BuscarTipoAutorizacion($TiposAuto,$Tipo)
  {
      $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
      for($i=0; $i<sizeof($TiposAuto); $i++){
          if($TiposAuto[$i][tipo_autorizacion]==$Tipo){
            $this->salida .=" <option value=\"".$TiposAuto[$i][tipo_autorizacion]."\" selected>".$TiposAuto[$i][descripcion]."</option>";
          }
          else{
            $this->salida .=" <option value=\"".$TiposAuto[$i][tipo_autorizacion]."\">".$TiposAuto[$i][descripcion]."</option>";
          }
    }
  }


  /**
  *
  */
  function FormaSolicitud($cargos,$data,$grupo,$tipo,$nivel)
  {
        if(!empty($cargos))
        {
            $this->salida .= "            <table width=\"90%\" align=\"center\" border=\"0\"  cellpadding=\"3\" class=\"modulo_table_list\">";
            $this->salida .= "             <tr align=\"center\" class=\"modulo_table_list_title\">";
            $this->salida .= "                 <td colspan=\"4\">CARGOS AUTORIZADOS</td>";
            $this->salida .= "             </tr>";
            $this->salida .= "             <tr align=\"center\" class=\"modulo_table_list_title\">";
            $this->salida .= "                 <td width=\"10%\">CODIGO</td>";
            $this->salida .= "                 <td>CARGO</td>";
            if($_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO']=='FACTURACION')
            {    $this->salida .= "            <td width=\"5%\" colspan=\"2\">CANT</td>";   }
            else
            {
                $this->salida .= "                 <td width=\"5%\">CANT</td>";
                $this->salida .= "                 <td></td>";
            }
            $this->salida .= "             </tr>";
            for($i=0; $i<sizeof($cargos); $i++)
            {
                  $this->salida .= "             <tr class=\"modulo_list_claro\">";
                  $this->salida .= "                 <td align=\"center\">".$cargos[$i][cargo]."</td>";
                  $this->salida .= "                 <td>".$cargos[$i][descripcion]."</td>";
                  if($_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO']=='FACTURACION')
                  {   $this->salida .= "                 <td align=\"center\" colspan=\"2\">".$cargos[$i][cantidad]."</td>";  }
                  else
                  {
                      $this->salida .= "                 <td align=\"center\">".$cargos[$i][cantidad]."</td>";
                      $msg='Esta seguro que desea Eliminar el Cargo '.$cargos[$i][descripcion];
                      $arreglo=array('tarifario'=>$cargos[$i][tarifario_id],'cargo'=>$cargos[$i][cargo],'autorizacion'=>$cargos[$i][autorizacion]);
                      $accion=ModuloGetURL('app','Autorizacion','user','LlamaConfirmarAccion',array('c'=>'app','m'=>'Autorizacion','me2'=>'LlamarFormaAutorizacion','me'=>'EliminarCargoAutorizado','mensaje'=>$msg,'titulo'=>'ELIMINAR CARGO AUTORIZADO','arreglo'=>$arreglo,'boton1'=>'ACEPTAR','boton2'=>'CANCELAR'));
                      $this->salida .= "                 <td width=\"3%\" align=\"center\"><a href=\"$accion\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
                  }
                  $this->salida .= "             </tr>";
            }
            $this->salida .= "            </table>";
        }
        if(!empty($data))
        {
            $this->salida .= "     <br><table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "             <tr align=\"center\" class=\"modulo_table_list_title\">";
            $this->salida .= "                 <td colspan=\"6\">GRUPOS CARGOS AUTORIZADOS</td>";
            $this->salida .= "             </tr>";
            $this->salida .= "            <tr class=\"modulo_table_list_title\">";
            $this->salida .= "              <td align=\"center\" width=\"25%\">GRUPO</td>";
            $this->salida .= "              <td align=\"center\" width=\"35%\">TIPO</td>";
            $ser=$this->CallMetodoExterno('app','Autorizacion_Solicitud','user','NivelesAtencion');
            for($i=0; $i<sizeof($ser); $i++)
            { $this->salida .= "              <td width=\"8%\" align=\"center\">".$ser[$i][descripcion_corta]."</td>"; }
            $this->salida .= "            </tr>";
            $j=0;
            $d=0;
            foreach($nivel as $g => $t)
            {
                  if($j % 2) {  $estilo="modulo_list_claro";  }
                  else {  $estilo="modulo_list_oscuro";   }
                  $this->salida .= "            <tr>";
                  $this->salida .= "              <td colspan\"".$grupo[$g]."\" align=\"center\" class=\"$estilo\">$g</td>";
                  $this->salida .= "              <td colspan=\"5\">";
                  $f=0;
                  foreach($t as $destipo => $desnivel)
                  {
                      if($f % 2) {  $estilo="modulo_list_claro";  }
                      else {  $estilo="modulo_list_oscuro";   }
                      $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
                      $this->salida .= "            <tr class=\"$estilo\">";
                      $this->salida .= "              <td  width=\"35%\" colspan\"".$tipo[$destipo]."\"  align=\"center\">$destipo</td>";
                      for($i=0; $i<sizeof($ser); $i++)
                      {
                          $check='';
                          if($desnivel[$ser[$i][descripcion_corta]])
                          { $check="<img src=\"".GetThemePath()."/images/endturn.png\">"; }
                          $this->salida .= "                  <td width=\"8%\" align=\"center\">".$check."</td>";
                      }
                      $this->salida .= "            </tr>";
                      $this->salida .= "            </table>";
                      $f++;
                  }
                  $j++;
                  $this->salida .= "              </td>";
                  $this->salida .= "            </tr>";
                  $d++;
            }
            $this->salida .= "            </table>";
        }
  }


  /**
  *
  */
  function FormaAutorizacionTipo($Tipo)
  {
        $this->salida .= ThemeAbrirTabla('AUTORIZACIONES - DETALLE TIPO AUTORIZACION');
        $this->salida .= "    <table width=\"90%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "      <tr>";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "      </tr>";
        $this->salida .= "    </table>";
        $accion=ModuloGetURL('app','Autorizacion','user','InsertarTipoAutorizacion',array('Tipo'=>$Tipo));
        $this->salida .= "  <form name=\"forma\" action=\"$accion\" method=\"post\">";
        //elige el tipo de autorizacion
        if($Tipo=='01')
        {  $this->AutorizacionTele();      }
        if($Tipo=='02')
        {   $this->AutorizacionEscrita();  }
        if($Tipo=='04')
        {
            $usu=$this->BuscarUsuarios($_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']);
            $this->AutorizacionInterna();
        }
        if($Tipo=='05')
        {   $this->AutorizacionElectronica(); }
        if($Tipo=='06')
        {   $this->AutorizacionCertificadoCartera(); }
				//cambio para sos
				if($Tipo=='07')
        {   $this->AutorizacionElectronicaSOS(); }
				//fin cambio
        $this->salida .= "      <table border=\"0\" width=\"40%\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\">";
        $this->salida .= "      </tr>";
        $this->salida .= "      <tr align=\"center\">";
        if($Tipo=='04' AND empty($usu))
        { }
        else
        {  $this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Autorizar\" value=\"ACEPTAR\"></td>";  }
        $this->salida .= "      </form>";
        $accion=ModuloGetURL('app','Autorizacion','user','LlamarFormaAutorizacion');
        $this->salida .= "      <td><form name=\"forma2\" action=\"$accion\" method=\"post\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"></td>";
        $this->salida .= "      </form>";				
        $this->salida .= "      </tr>";
        $this->salida .= "     </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
  }

	//cambio para sos

	function AutorizacionElectronicaSOS()
  {
        $var=$this->BuscarAutorizaciones('autorizaciones_electronicas_sos');
        if($var)
        {
            $this->salida .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "  <tr class=\"modulo_table_list_title\">";
            $this->salida .= "  <td>COD. AUTORIZACION</td>";
            $this->salida .= "  <td>VALIDEZ</td>";
            $this->salida .= "  <td>OBSERVACIONES</td>";
            $this->salida .= "  <td></td>";
            $this->salida .= "  </tr>";
            for($i=0; $i<sizeof($var); $i++)
            {
                if( $i % 2) $estilo='modulo_list_claro';
                else $estilo='modulo_list_oscuro';
                $this->salida .= "  <tr class=\"$estilo\">";
                $this->salida .= "  <td align=\"center\">".$var[$i][codigo_autorizacion]."</td>";
                $this->salida .= "  <td align=\"center\">".$var[$i][validez]."</td>";
                $this->salida .= "  <td>".$var[$i][observaciones]."</td>";
                $msg='Esta seguro que desea Eliminar La Autorizaciï¿½.';
                $arreglo=array('tabla'=>'autorizaciones_electronicas_sos','campo'=>'autorizacion_electronica_sos_id','id'=>$var[$i][autorizacion_electronica_sos_id],'TipoAutorizacion'=>'07');
                $accion=ModuloGetURL('app','Autorizacion','user','LlamaConfirmarAccion',array('c'=>'app','m'=>'Autorizacion','me2'=>'LlamarFormaAutorizacionTipo','me'=>'EliminarAutorizaciones','mensaje'=>$msg,'titulo'=>'ELIMINAR AUTORIZACION ELECTRONICA','arreglo'=>$arreglo,'boton1'=>'ACEPTAR','boton2'=>'CANCELAR'));
                $this->salida .= "  <td width=\"3%\" align=\"center\"><a href=\"$accion\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
                $this->salida .= "  </tr>";
            }
            $this->salida .= "  </table><br>";
        }
				if(!empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARGUMENTOS']['NoAutorizacion']))
				{
					$_REQUEST['CodAuto']=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARGUMENTOS']['NoAutorizacion'];
				}
				$this->salida.="<script>\n";
				$this->salida.="function IrA(forma)\n";
				$this->salida.="{\n";
				$this->salida.="if(forma.Validez.value!='')\n";
				$this->salida.="{\n";
				$this->salida.="if(forma.CodAuto.value!='')\n";
				$this->salida.="{\n";
				$this->salida.="document.location='".ModuloGetURL('app','Autorizacion','user','LlamarAutorizacionExterna',array('TipoAutorizacion'=>'07'))."'+'&Observaciones='+forma.Observaciones.value+'&Validez='+forma.Validez.value+'&CodAuto='+forma.CodAuto.value;\n";
				$this->salida.="}\n";
				$this->salida.="else\n";
				$this->salida.="{\n";
				$this->salida.="document.location='".ModuloGetURL('app','Autorizacion','user','LlamarAutorizacionExterna',array('TipoAutorizacion'=>'07'))."'+'&Observaciones='+forma.Observaciones.value+'&Validez='+forma.Validez.value;\n";
				$this->salida.="}\n";
				$this->salida.="}\n";
				$this->salida.="else\n";
				$this->salida.="{\n";
				$this->salida.="if(forma.Validez.value=='')\n";
				$this->salida.="{\n";
				$this->salida.="alert('La Fecha de Validez esta vacia');\n";
				$this->salida.="}\n";
				$this->salida.="}\n";
				$this->salida.="}\n";
				$this->salida.="</script>\n";
				if(empty($var))
				{
					$this->salida .= "<table border=\"1\" cellspacing=\"3\" cellpadding=\"2\" width=\"90%\" align=\"center\" class=\"normal_10\">";
					$this->salida .= "  <tr>";
					$this->salida .= "  <td class=\"modulo_table_list_title\">DATOS AUTORIZACION ELECTRONICA SOS</td>";
					$this->salida .= "  </tr>";
					$this->salida .= "  <tr>";
					$this->salida .= "  <td>";
					$this->salida .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"2\" width=\"90%\" align=\"center\" class=\"normal_10\">";
					if($_REQUEST['CodAuto']==='0' or !empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['error']))
					{
						if($_REQUEST['CodAuto']==='0')
						{
							$this->salida .= "  <tr>";
							$this->salida .= "  <td align='center' class='label_error' colspan=\"4\">La Autorizacion no pudo ser dada remitase al sistema S.O.S</td>";
							$this->salida .= "  </tr>";
						}
						if(!empty($_SESSION['AUTORIZACIONES']['AUTORIZAR']['error']))
						{
							$this->salida .= "  <tr>";
							$this->salida .= "  <td align='center' class='label_error' colspan=\"4\">".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['error'].'   '.$_SESSION['AUTORIZACIONES']['AUTORIZAR']['mensajeDeError']."</td>";
							$this->salida .= "  </tr>";
						}
					}
					$this->salida .= "  <tr>";
					$this->salida .= "  <td class=\"".$this->SetStyle("CodAuto")."\">COD. AUTORIZACION: </td>";
					$this->salida .= "  <td><input type=\"text\" class=\"input-text\" name=\"CodAuto\" size=\"12\" value=\"".$_REQUEST['CodAuto']."\" readonly></td>";
					$this->salida .= "  <td class=\"".$this->SetStyle("Validez")."\">VALIDEZ: </td>";
					$_REQUEST['Validez']=date("d/m/Y");
					$this->salida .= "  <td><input type=\"text\" class=\"input-text\" name=\"Validez\" size=\"12\" value=\"".$_REQUEST['Validez']."\" onFocus=\"this.select();\"  onBlur=\"IsValidDate(this,'DD/MM/YYYY')\" onKeyUp=\"setDate(this,'DD/MM/YYYY','es')\" readonly>";
					//$this->salida .= "&nbsp;&nbsp;".ReturnOpenCalendario('forma','Validez','/')."</td>";
					$this->salida .= "  </tr>";
					$this->salida .= "  <tr>";
					$this->salida .= "  <td class=\"".$this->SetStyle("Observaciones")."\">OBSERVACIONES: </td>";
					$this->salida .= "  <td colspan=\"3\"><textarea  cols=\"75\" rows=\"3\" class=\"textarea\" name=\"Observaciones\">".$_REQUEST['Observaciones']."</textarea></td>";
					$this->salida .= "  </tr>";
					if((empty($_REQUEST['CodAuto']) and !($_REQUEST['CodAuto']==='0')))
					{
						if($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_activo']==1)
						{
							$this->salida .= "  <tr>";
							$this->salida .= "  <td colspan=\"4\" align=\"center\"><input type=\"button\" name=\"VALIDAR DERECHOS\" value=\"VALIDAR DERECHOS\" class=\"input-submit\" onclick=\"IrA(this.form);\"></td>";
							$this->salida .= "  </tr>";
						}
					}
					$this->salida .= "  </table>";
					$this->salida .= "  </td>";
					$this->salida .= "  </tr>";
					$this->salida .= "  </table>";
				}
  }

	//fin cambio sos
	
  /**
  *
  */
  function AutorizacionCertificadoCartera()
  {
        $var=$this->BuscarAutorizaciones('autorizaciones_certificados');
        if($var)
        {
            $this->salida .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "  <tr class=\"modulo_table_list_title\">";
            $this->salida .= "  <td align=\"center\">COD. AUTORIZACION</td>";
            $this->salida .= "  <td align=\"center\">RESPONSABLE</td>";
            $this->salida .= "  <td>OBSERVACIONES</td>";
            $this->salida .= "  <td></td>";
            $this->salida .= "  </tr>";
            for($i=0; $i<sizeof($var); $i++)
            {
                if( $i % 2) $estilo='modulo_list_claro';
                else $estilo='modulo_list_oscuro';
                $this->salida .= "  <tr class=\"$estilo\">";
                $this->salida .= "  <td>".$var[$i][codigo_autorizacion]."</td>";
                $this->salida .= "  <td>".$var[$i][responsable]."</td>";
                $this->salida .= "  <td>".$var[$i][observaciones]."</td>";
                $msg='Esta seguro que desea Eliminar La Autorización.';
                $arreglo=array('tabla'=>'autorizaciones_certificados','campo'=>'autorizacion_certificado_id','id'=>$var[$i][autorizacion_certificado_id],'TipoAutorizacion'=>'06');
                $accion=ModuloGetURL('app','Autorizacion','user','LlamaConfirmarAccion',array('c'=>'app','m'=>'Autorizacion','me2'=>'LlamarFormaAutorizacionTipo','me'=>'EliminarAutorizaciones','mensaje'=>$msg,'titulo'=>'ELIMINAR AUTORIZACION CERTIFICADO DE CARTERA','arreglo'=>$arreglo,'boton1'=>'ACEPTAR','boton2'=>'CANCELAR'));
                $this->salida .= "  <td width=\"3%\" align=\"center\"><a href=\"$accion\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
                $this->salida .= "  </tr>";
            }
            $this->salida .= "  </table><br>";
        }
        $this->salida .= "<table border=\"1\" cellspacing=\"3\" cellpadding=\"2\" width=\"90%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"modulo_table_list_title\">DATOS AUTORIZACION CERTIFICADO CARTERA</td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td>";
        $this->salida .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"2\" width=\"90%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"".$this->SetStyle("CodAuto")."\">COD. AUTORIZACION: </td>";
        $this->salida .= "  <td><input type=\"text\" class=\"input-text\" name=\"CodAuto\" size=\"12\" value=\"".$_REQUEST['CodAuto']."\"></td>";
        $this->salida .= "  <td class=\"".$this->SetStyle("Responsable")."\">RESPONSABLE: </td>";
        $this->salida .= "  <td><input type=\"text\" class=\"input-text\" name=\"Responsable\" size=\"20\" value=\"".$_REQUEST['Responsable']."\"></td>";
        $this->salida .= "  <td class=\"".$this->SetStyle("Validez")."\">TERMINACION: </td>";
        $this->salida .= "  <td><input type=\"text\" class=\"input-text\" name=\"Validez\" size=\"12\" value=\"".$_REQUEST['Validez']."\" onFocus=\"this.select();\"  onBlur=\"IsValidDate(this,'DD/MM/YYYY')\" onKeyUp=\"setDate(this,'DD/MM/YYYY','es')\">";
        $this->salida .= "&nbsp;&nbsp;".ReturnOpenCalendario('forma','Validez','/')."</td>";        
				$this->salida .= "  </tr>";			
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"".$this->SetStyle("Observaciones")."\">OBSERVACIONES: </td>";
        $this->salida .= "  <td colspan=\"4\"><textarea  cols=\"75\" rows=\"3\" class=\"textarea\" name=\"Observaciones\">".$_REQUEST['Observaciones']."</textarea></td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
  }
	
  /**
  *
  */
  function AutorizacionTele()
  {
        $var=$this->BuscarAutorizaciones('autorizaciones_telefonicas');
        if($var)
        {
            $this->salida .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "  <tr class=\"modulo_table_list_title\">";
            $this->salida .= "  <td align=\"center\">COD. AUTORIZACION</td>";
            $this->salida .= "  <td align=\"center\">RESPONSABLE</td>";
            $this->salida .= "  <td>OBSERVACIONES</td>";
            $this->salida .= "  <td></td>";
            $this->salida .= "  </tr>";
            for($i=0; $i<sizeof($var); $i++)
            {
                if( $i % 2) $estilo='modulo_list_claro';
                else $estilo='modulo_list_oscuro';
                $this->salida .= "  <tr class=\"$estilo\">";
                $this->salida .= "  <td>".$var[$i][codigo_autorizacion]."</td>";
                $this->salida .= "  <td>".$var[$i][responsable]."</td>";
                $this->salida .= "  <td>".$var[$i][observaciones]."</td>";
                $msg='Esta seguro que desea Eliminar La Autorización.';
                $arreglo=array('tabla'=>'autorizaciones_telefonicas','campo'=>'autorizacion_telefonica_id','id'=>$var[$i][autorizacion_telefonica_id],'TipoAutorizacion'=>'01');
                $accion=ModuloGetURL('app','Autorizacion','user','LlamaConfirmarAccion',array('c'=>'app','m'=>'Autorizacion','me2'=>'LlamarFormaAutorizacionTipo','me'=>'EliminarAutorizaciones','mensaje'=>$msg,'titulo'=>'ELIMINAR AUTORIZACION TELEFONICA','arreglo'=>$arreglo,'boton1'=>'ACEPTAR','boton2'=>'CANCELAR'));
                $this->salida .= "  <td width=\"3%\" align=\"center\"><a href=\"$accion\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
                $this->salida .= "  </tr>";
            }
            $this->salida .= "  </table><br>";
        }
        $this->salida .= "<table border=\"1\" cellspacing=\"3\" cellpadding=\"2\" width=\"90%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"modulo_table_list_title\">DATOS AUTORIZACION TELEFONICA</td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td>";
        $this->salida .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"2\" width=\"90%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"".$this->SetStyle("CodAuto")."\">COD. AUTORIZACION: </td>";
        $this->salida .= "  <td><input type=\"text\" class=\"input-text\" name=\"CodAuto\" size=\"12\" value=\"".$_REQUEST['CodAuto']."\"></td>";
        $this->salida .= "  <td class=\"".$this->SetStyle("Responsable")."\">RESPONSABLE: </td>";
        $this->salida .= "  <td><input type=\"text\" class=\"input-text\" name=\"Responsable\" size=\"20\" value=\"".$_REQUEST['Responsable']."\"></td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"".$this->SetStyle("Observaciones")."\">OBSERVACIONES: </td>";
        $this->salida .= "  <td colspan=\"3\"><textarea  cols=\"75\" rows=\"3\" class=\"textarea\" name=\"Observaciones\">".$_REQUEST['Observaciones']."</textarea></td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
  }

  /**
  *
  */
  function AutorizacionEscrita()
  {
        $var=$this->BuscarAutorizaciones('autorizaciones_escritas');
        if($var)
        {
            $this->salida .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "  <tr class=\"modulo_table_list_title\">";
            $this->salida .= "  <td>COD. AUTORIZACION</td>";
            $this->salida .= "  <td>VALIDEZ</td>";
            $this->salida .= "  <td>OBSERVACIONES</td>";
            $this->salida .= "  <td></td>";
            $this->salida .= "  </tr>";
            for($i=0; $i<sizeof($var); $i++)
            {
                if( $i % 2) $estilo='modulo_list_claro';
                else $estilo='modulo_list_oscuro';
                $this->salida .= "  <tr class=\"$estilo\">";
                $this->salida .= "  <td align=\"center\">".$var[$i][codigo_autorizacion]."</td>";
                $this->salida .= "  <td align=\"center\">".$var[$i][validez]."</td>";
                $this->salida .= "  <td>".$var[$i][observaciones]."</td>";
                $msg='Esta seguro que desea Eliminar La Autorización.';
                $arreglo=array('tabla'=>'autorizaciones_escritas','campo'=>'autorizacion_escrita_id','id'=>$var[$i][autorizacion_escrita_id],'TipoAutorizacion'=>'02');
                $accion=ModuloGetURL('app','Autorizacion','user','LlamaConfirmarAccion',array('c'=>'app','m'=>'Autorizacion','me2'=>'LlamarFormaAutorizacionTipo','me'=>'EliminarAutorizaciones','mensaje'=>$msg,'titulo'=>'ELIMINAR AUTORIZACION ESCRITA','arreglo'=>$arreglo,'boton1'=>'ACEPTAR','boton2'=>'CANCELAR'));
                $this->salida .= "  <td width=\"3%\" align=\"center\"><a href=\"$accion\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
                $this->salida .= "  </tr>";
            }
            $this->salida .= "  </table><br>";
        }
        $this->salida .= "<br><table border=\"1\" cellspacing=\"3\" cellpadding=\"2\" width=\"90%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"modulo_table_list_title\">DATOS AUTORIZACION ESCRITA</td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td>";
        $this->salida .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"2\" width=\"90%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"".$this->SetStyle("CodAuto")."\">COD. AUTORIZACION: </td>";
        $this->salida .= "  <td><input type=\"text\" class=\"input-text\" name=\"CodAuto\" size=\"12\" value=\"".$_REQUEST['CodAuto']."\"></td>";
        $this->salida .= "  <td class=\"".$this->SetStyle("Validez")."\">VALIDEZ: </td>";
        $this->salida .= "  <td><input type=\"text\" class=\"input-text\" name=\"Validez\" size=\"12\" value=\"".$_REQUEST['Validez']."\" onFocus=\"this.select();\"  onBlur=\"IsValidDate(this,'DD/MM/YYYY')\" onKeyUp=\"setDate(this,'DD/MM/YYYY','es')\">";
        $this->salida .= "&nbsp;&nbsp;".ReturnOpenCalendario('forma','Validez','/')."</td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"".$this->SetStyle("Observaciones")."\">OBSERVACIONES: </td>";
        $this->salida .= "  <td colspan=\"3\"><textarea  cols=\"75\" rows=\"3\" class=\"textarea\" name=\"Observaciones\">".$_REQUEST['Observaciones']."</textarea></td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
  }

  /**
  *
  */
  function AutorizacionInterna()
  {
        $var=$this->BuscarAutorizaciones('autorizaciones_por_sistema');
        if($var)
        {
            $this->salida .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "  <tr class=\"modulo_table_list_title\">";
            $this->salida .= "  <td>COD. AUTORIZACION</td>";
            $this->salida .= "  <td>USUARIO</td>";
            $this->salida .= "  <td>OBSERVACIONES</td>";
            $this->salida .= "  <td></td>";
            $this->salida .= "  </tr>";
            for($i=0; $i<sizeof($var); $i++)
            {
                if( $i % 2) $estilo='modulo_list_claro';
                else $estilo='modulo_list_oscuro';
                $this->salida .= "  <tr class=\"$estilo\">";
                $this->salida .= "  <td align=\"center\" width=\"10%\">".$var[$i][autorizacion_por_sistema_id]."</td>";
                $this->salida .= "  <td align=\"center\">".$var[$i][nombre]."</td>";
                $this->salida .= "  <td>".$var[$i][observaciones]."</td>";
                $msg='Esta seguro que desea Eliminar La Autorización.';
                $arreglo=array('tabla'=>'autorizaciones_por_sistema','TipoAutorizacion'=>'04','campo'=>'autorizacion_por_sistema_id','id'=>$var[$i][autorizacion_por_sistema_id]);
                $accion=ModuloGetURL('app','Autorizacion','user','LlamaConfirmarAccion',array('c'=>'app','m'=>'Autorizacion','me2'=>'LlamarFormaAutorizacionTipo','me'=>'EliminarAutorizaciones','mensaje'=>$msg,'titulo'=>'ELIMINAR AUTORIZACION ESCRITA','arreglo'=>$arreglo,'boton1'=>'ACEPTAR','boton2'=>'CANCELAR'));
                $this->salida .= "  <td width=\"3%\" align=\"center\"><a href=\"$accion\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
                $this->salida .= "  </tr>";
            }
            $this->salida .= "  </table><br>";
        }
        $this->salida .= "<br><table border=\"1\" cellspacing=\"3\" cellpadding=\"2\" width=\"90%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"modulo_table_list_title\">DATOS AUTORIZACION INTERNA</td>";
        $this->salida .= "  </tr>";
        //usuarios
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"label_error\" align=\"center\">";
        $usu=$this->BuscarUsuarios($_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']);
        if($usu)
        {
            $this->salida .= "      <table border=\"0\" width=\"30%\" align=\"center\"  cellspacing=\"3\" cellpadding=\"3\">";
            $this->salida .= "          <tr align=\"center\" class=\"modulo_table_list_title\">";
            $this->salida .= "            <td>USUARIOS AUTORIZADORES</td>";
            $this->salida .= "            <td></td>";
            $this->salida .= "          </tr>";
            for($i=0; $i<sizeof($usu); $i++)
            {
                $this->salida .= "          <tr class=\"modulo_list_claro\">";
                $this->salida .= "            <td>".$usu[$i][nombre]."</td>";
                $this->salida .= "            <td align=\"center\"><input type=\"radio\" value=\"".$usu[$i][usuario_id]."\" name=\"Responsable\"></td>";
                $this->salida .= "          </tr>";
            }
            $this->salida .= "       </table><br>";

        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td>";
        $this->salida .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"2\" width=\"90%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"".$this->SetStyle("Observaciones")."\">OBSERVACIONES: </td>";
        $this->salida .= "  <td colspan=\"3\"><textarea  cols=\"75\" rows=\"3\" class=\"textarea\" name=\"Observaciones\">".$_REQUEST['Observaciones']."</textarea></td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        }
        else
        {   $this->salida .= "NO HAY USUARIO AUTORIZADORES PARA ESTE PLAN";  }
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
  }


  /**
  *
  */
  function AutorizacionElectronica()
  {
        $var=$this->BuscarAutorizaciones('autorizaciones_electronicas');
        if($var)
        {
            $this->salida .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"90%\" align=\"center\" class=\"modulo_table_list\">";
            $this->salida .= "  <tr class=\"modulo_table_list_title\">";
            $this->salida .= "  <td>COD. AUTORIZACION</td>";
            $this->salida .= "  <td>VALIDEZ</td>";
            $this->salida .= "  <td>OBSERVACIONES</td>";
            $this->salida .= "  <td></td>";
            $this->salida .= "  </tr>";
            for($i=0; $i<sizeof($var); $i++)
            {
                if( $i % 2) $estilo='modulo_list_claro';
                else $estilo='modulo_list_oscuro';
                $this->salida .= "  <tr class=\"$estilo\">";
                $this->salida .= "  <td align=\"center\">".$var[$i][codigo_autorizacion]."</td>";
                $this->salida .= "  <td align=\"center\">".$var[$i][validez]."</td>";
                $this->salida .= "  <td>".$var[$i][observaciones]."</td>";
                $msg='Esta seguro que desea Eliminar La Autorización.';
                $arreglo=array('tabla'=>'autorizaciones_electronicas','campo'=>'autorizacion_electronica_id','id'=>$var[$i][autorizacion_electronica_id],'TipoAutorizacion'=>'05');
                $accion=ModuloGetURL('app','Autorizacion','user','LlamaConfirmarAccion',array('c'=>'app','m'=>'Autorizacion','me2'=>'LlamarFormaAutorizacionTipo','me'=>'EliminarAutorizaciones','mensaje'=>$msg,'titulo'=>'ELIMINAR AUTORIZACION ESCRITA','arreglo'=>$arreglo,'boton1'=>'ACEPTAR','boton2'=>'CANCELAR'));
                $this->salida .= "  <td width=\"3%\" align=\"center\"><a href=\"$accion\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
                $this->salida .= "  </tr>";
            }
            $this->salida .= "  </table><br>";
        }
        $this->salida .= "<table border=\"1\" cellspacing=\"3\" cellpadding=\"2\" width=\"90%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"modulo_table_list_title\">DATOS AUTORIZACION ELECTRONICA</td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td>";
        $this->salida .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"2\" width=\"90%\" align=\"center\" class=\"normal_10\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"".$this->SetStyle("CodAuto")."\">COD. AUTORIZACION: </td>";
        $this->salida .= "  <td><input type=\"text\" class=\"input-text\" name=\"CodAuto\" size=\"12\" value=\"".$_REQUEST['CodAuto']."\"></td>";
        $this->salida .= "  <td class=\"".$this->SetStyle("Validez")."\">VALIDEZ: </td>";
        $this->salida .= "  <td><input type=\"text\" class=\"input-text\" name=\"Validez\" size=\"12\" value=\"".$_REQUEST['Validez']."\" onFocus=\"this.select();\"  onBlur=\"IsValidDate(this,'DD/MM/YYYY')\" onKeyUp=\"setDate(this,'DD/MM/YYYY','es')\">";
        $this->salida .= "&nbsp;&nbsp;".ReturnOpenCalendario('forma','Validez','/')."</td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"".$this->SetStyle("Observaciones")."\">OBSERVACIONES: </td>";
        $this->salida .= "  <td colspan=\"3\"><textarea  cols=\"75\" rows=\"3\" class=\"textarea\" name=\"Observaciones\">".$_REQUEST['Observaciones']."</textarea></td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
        $this->salida .= "  </td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table>";
  }

  /**
  *
  */
  function EncabezadoAutoPlan()
  {
        $datos=$_SESSION['TRIAGE']['VECT']['DATOS'];
        $this->salida .= "    <table width=\"90%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr>";
        $this->salida .= "        <td class=\"modulo_table_list_title\" width=\"15%\" nowrap>IDENTIFICACION: </td>";
        $this->salida .= "        <td class=\"modulo_list_claro\" colspan=\"2\">".$datos[tipo_id_paciente]." ".$datos[paciente_id]."</td>";
        $this->salida .= "        <td class=\"modulo_table_list_title\" width=\"12%\" nowrap>PACIENTE: </td>";
        $this->salida .= "        <td class=\"modulo_list_claro\" colspan=\"5\" width=\"50%\">".$datos[nombre]."</td>";
        $this->salida .= "      <tr>";
        $this->salida .= "      <tr>";
        $this->salida .= "        <td class=\"modulo_table_list_title\" width=\"15%\" nowrap>TIPO AFILIADO: </td>";
        $this->salida .= "        <td class=\"modulo_list_claro\" colspan=\"1\" width=\"15%\" >".$datos[tipo_afiliado_nombre]."</td>";
        $this->salida .= "        <td class=\"modulo_table_list_title\" width=\"12%\" nowrap>RANGO: </td>";
        $this->salida .= "        <td class=\"modulo_list_claro\" >".$datos[rango]."</td>";
        $this->salida .= "        <td class=\"modulo_table_list_title\" width=\"5%\">INGRESO: </td>";
        $this->salida .= "        <td class=\"modulo_list_claro\" colspan=\"2\" width=\"30%\">".$datos[ingreso]."</td>";
        $this->salida .= "        <td class=\"modulo_table_list_title\" width=\"5%\">PLAN: </td>";
        $this->salida .= "        <td class=\"modulo_list_claro\"  width=\"25%\">".$datos[plan_descripcion]."</td>";
        $this->salida .= "      <tr>";
        $this->salida .= "      <tr>";
        $this->salida .= "        <td class=\"modulo_table_list_title\" width=\"8%\" colspan=\"2\">OBSERVACION INGRESO: </td>";
        $this->salida .= "        <td class=\"modulo_list_claro\" colspan=\"7\"></td>";
        $this->salida .= "      </tr>";
        $this->salida .= "    </table>";
  }

  /**
  *
  */
  function FormaDetalleAutorizacion()
  { 
				$this->salida .= ThemeAbrirTabla('AUTORIZACIONES PLAN');
        $this->EncabezadoAutoPlan();
        //SERVICIOS
        $this->salida .= "    <br><table height=\"100%\" border=\"0\" width=\"70%\" align=\"center\" class=\"modulo_table_list\">";
        $this->salida .= "      <tr class=\"modulo_table_list_title\">";
        $this->salida .= "      <td width=\"100%\" colspan=\"2\">SERVICIOS ASISTENCIALES CONTRATADOS</td>";
        $this->salida .= "      </tr>";
        $servicios=$_SESSION['TRIAGE']['VECT']['SERVICIOS'];
        for($i=0;$i<sizeof($servicios);$i++)
        {
          $this->salida .= "  <tr class=\"modulo_list_claro\">";
          $this->salida .= "  <td align=\"center\" width=\"50%\">";
          $this->salida .= "".$servicios[$i]['descripcion']."";
          $this->salida .= "  </td>";
          $this->salida .= "  <td align=\"center\" width=\"50%\">";
          $this->salida .= "<a href=\"". ModuloGetURL('app','Autorizacion','user','VerServicio',array('Servicio'=>$servicios[$i]['servicio'],'Descripcion'=>$servicios[$i]['descripcion']))."\">VER</a>";
          $this->salida .= "  </td>";
          $this->salida .= "  </tr>";
        }
        $this->salida .= "      </table>";
        $this->salida .= "        <table width=\"100%\" align=\"center\" border=\"0\">";
        $contenedor=$_SESSION['AUTORIZACIONES']['RETORNO']['contenedor'];
        $modulo=$_SESSION['AUTORIZACIONES']['RETORNO']['modulo'];
        $tipo=$_SESSION['AUTORIZACIONES']['RETORNO']['tipo'];
        $metodo=$_SESSION['AUTORIZACIONES']['RETORNO']['metodo'];
        $argumentos=$_SESSION['AUTORIZACIONES']['RETORNO']['argumentos'];
        $actionM=ModuloGetURL($contenedor,$modulo,$tipo,$metodo,$argumentos);
        $this->salida .= "             <form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\" colspan=\"5\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"><br></td></form>";
        $this->salida .= "  </tr>";
        $this->salida .= "      </table>";
				$this->salida .= ThemeCerrarTabla();
        return true;
  }

  /**
  *
  */
  function FormaDetalleAutorizacionServicio($var)
  {
        $this->salida = ThemeAbrirTabla('AUTORIZACIONES PLAN');
        $this->EncabezadoAutoPlan();
        $this->salida .= "    <br> <table height=\"100%\" border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        if(!empty($var))
        {
            $this->salida .= "      <tr class=\"modulo_table_list_title\">";
            $this->salida .= "      <td width=\"25%\">GRUPOS CARGOS</td>";
            $this->salida .= "      <td width=\"26%\">TIPOS CARGOS</td>";
            $this->salida .= "      <td width=\"25%\">INTERNA</td>";
            $this->salida .= "      <td width=\"25%\">EXTERNA</td>";
            $this->salida .= "      <td width=\"15%\">DETALLES</td>";
            $this->salida .= "      </tr>";
            $Niveles=$this->CallMetodoExterno('app','Contratacion','user','BuscarNivelesAteContra');
            $j=0;
            for($i=0;$i<sizeof($var);)
            {
              if($j==0)
              {
                $color="class=\"modulo_list_claro\"";
                $j=1;
              }
              else
              {
                $color="class=\"modulo_list_oscuro\"";
                $j=0;
              }
              $this->salida .= "  <tr $color>";
              $this->salida .= "  <td>";
              $this->salida .= "".$var[$i]['des1']."";
              $this->salida .= "  </td>";
              $this->salida .= "  <td colspan=\"4\">";
              $this->salida .= "      <table border=\"1\" width=\"100%\" align=\"center\" $color>";
              $k=$i;
              While($var[$i]['grupo_tipo_cargo']==$var[$k]['grupo_tipo_cargo'])
              {
                $this->salida .= "  <tr>";
                $this->salida .= "  <td width=\"31%\">";
                $this->salida .= "".$var[$k]['des2']."";
                $this->salida .= "  </td>";
                $this->salida .= "  <td width=\"30%\">";
                $this->salida .= "      <table width=\"90%\" align=\"center\" border=\"0\" class=\"modulo_table_list_title\" cellpadding=\"3\">";
                $this->salida .= "      <tr class=\"modulo_table_list_title\">";
                for($m=0;$m<sizeof($Niveles);$m++)
                {
                  $this->salida .= "      <td>";
                  $this->salida .= "".$Niveles[$m]['descripcion_corta']."";
                  $this->salida .= "      </td>";
                }
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr class=\"modulo_list_claro\">";
                $l=$k;
                $l2=0;
                While($var[$k]['tipo_cargo']==$var[$l]['tipo_cargo'])
                {
                    $p=0;
                    $n=$l;
                    while($var[$n]['tipo_cargo']==$var[$l]['tipo_cargo'] AND
                    $var[$n]['grupo_tipo_cargo']==$var[$l]['grupo_tipo_cargo'])
                    {
                        if($var[$n]['interno']==0)
                        {
                            $p++;
                        }
                        else if($l2==0)
                        {
                            $l2=$n;
                        }
                        $n++;
                    }
                    $p2=$n;
                    $n=$l;
                    for($m=0;( $m<sizeof($Niveles));$m++)
                    {
                      if($p<>0)
                      {
                        if($var[$n]['nivel']==$Niveles[$m]['nivel'])
                        {
                              $this->salida .= "      <td>";
                              $this->salida .= "<img src=\"".GetThemePath()."/images/checksi.png\" border=\"0\">";
                              $this->salida .= "      </td>";
                              $p--;
                              $n++;
                        }
                        else
                        {
                              $this->salida .= "      <td>";
                              $this->salida .= "<img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">";
                              $this->salida .= "      </td>";
                        }
                      }
                      else
                      {
                          $this->salida .= "      <td>";
                          $this->salida .= "<img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">";
                          $this->salida .= "      </td>";
                      }
                    }
                    $l=$p2;
                }
                $this->salida .= "      </tr>";
                $this->salida .= "      </table>";
                $this->salida .= "  </td>";
                $this->salida .= "  <td width=\"30%\">";
                $this->salida .= "      <table width=\"90%\" align=\"center\" border=\"0\" class=\"modulo_table_list_title\" cellpadding=\"3\">";
                $this->salida .= "      <tr class=\"modulo_table_list_title\">";
                for($m=0;$m<sizeof($Niveles);$m++)
                {
                  $this->salida .= "      <td>";
                  $this->salida .= "".$Niveles[$m]['descripcion_corta']."";
                  $this->salida .= "      </td>";
                }
                $this->salida .= "      </tr>";
                $this->salida .= "      <tr class=\"modulo_list_claro\">";
                $l=$k;
                While($var[$k]['tipo_cargo']==$var[$l]['tipo_cargo'])
                {
                    $p=0;
                    $n=$l2;
                    while($var[$n]['tipo_cargo']==$var[$l]['tipo_cargo'] AND
                    $var[$n]['grupo_tipo_cargo']==$var[$l]['grupo_tipo_cargo'])
                    {
                        if($var[$n]['interno']==1)
                        {
                            $p++;
                        }
                        $n++;
                    }
                    $n=$l2;
                    for($m=0;( $m<sizeof($Niveles));$m++)
                    {
                      if($p<>0)
                      {
                        if($var[$n]['nivel']==$Niveles[$m]['nivel'])
                        {
                              $this->salida .= "      <td>";
                              $this->salida .= "<img src=\"".GetThemePath()."/images/checksi.png\" border=\"0\">";
                              $this->salida .= "      </td>";
                              $p--;
                              $n++;
                        }
                        else
                        {
                              $this->salida .= "      <td>";
                              $this->salida .= "<img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">";
                              $this->salida .= "      </td>";
                        }
                      }
                      else
                      {
                          $this->salida .= "      <td>";
                          $this->salida .= "<img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">";
                          $this->salida .= "      </td>";
                      }
                    }
                    $l=$p2;
                }
                $this->salida .= "      </tr>";
                $this->salida .= "      </table>";
                $this->salida .= "  </td>";
                $this->salida .= "  <td align=\"center\" width=\"10%\">";
                if($var[$k]['servicio']==NULL)
                {
                  $this->salida .= "EXCEPCIONES";
                }
                else
                {
                  $this->salida .= "<a href=\"". ModuloGetURL('app','Autorizacion','user','VerExcepciones',
                  array('Tipo'=>$var[$k]['tipo_cargo'],'Grupo'=>$var[$k]['grupo_tipo_cargo'])) ."\"><img src=\"".GetThemePath()."/images/pexcepcion.png\" border=\"0\"></a>";
                }
                $this->salida .= "  </td>";
                $this->salida .= "  </tr>";
                $k=$l;
              }
              $this->salida .= "      </table>";
              $this->salida .= "  </td>";
              $this->salida .= "  </tr>";
              $i=$k;
            }
        }
        else
        {
              $this->salida .= "  <tr>";
              $this->salida .= "   <td align=\"center\" colspan=\"5\">NO HAY AUTORIZACIONES CONTRATADAS</td>";
              $this->salida .= "  </tr>";
        }
        $this->salida .= "      </table>";
        $this->salida .= "        <table width=\"100%\" align=\"center\" border=\"0\">";
        $actionM=ModuloGetURL('app','Autorizacion','user','DetalleAutorizacion');
        $this->salida .= "             <form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\" colspan=\"5\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"><br></td></form>";
        $this->salida .= "  </tr>";
        $this->salida .= "      </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
  }

  /**
  *
  */
  function FormaDetalleAutorizacioExcepciones($var)
  {
        $this->salida = ThemeAbrirTabla('AUTORIZACIONES EXCEPCIONES PLAN');
        $this->EncabezadoAutoPlan();
        $this->salida .= "     <BR><table height=\"100%\" border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
        if(!empty($var))
        {
            $this->salida .= "      <tr class=\"modulo_table_list_title\">";
            $this->salida .= "      <td width=\"20%\">GRUPOS CARGOS</td>";
            $this->salida .= "      <td width=\"20%\">TIPOS CARGOS</td>";
            $this->salida .= "      <td width=\"20%\">CARGO</td>";
            $this->salida .= "      <td width=\"20%\">INTERNA</td>";
            $this->salida .= "      <td width=\"20%\">EXTERNA</td>";
            $this->salida .= "      </tr>";
            $j=0;
            for($i=0;$i<sizeof($var);)
            {
              if($j==0)
              {
                $color="class=\"modulo_list_claro\"";
                $j=1;
              }
              else
              {
                $color="class=\"modulo_list_oscuro\"";
                $j=0;
              }
              $this->salida .= "  <tr $color>";
              $this->salida .= "  <td>";
              $this->salida .= "".$var[$i]['des1']."";
              $this->salida .= "  </td>";
              $this->salida .= "  <td colspan=\"4\">";
              $this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\" $color>";
              $k=$i;
              While($var[$i]['grupo_tipo_cargo']==$var[$k]['grupo_tipo_cargo'])
              {
                $this->salida .= "  <tr>";
                $this->salida .= "  <td width=\"24%\">";
                $this->salida .= "".$var[$k]['des2']."";
                $this->salida .= "  </td>";
                $this->salida .= "  <td width=\"76%\">";
                $this->salida .= "      <table width=\"100%\" align=\"center\" border=\"1\" $color>";
                $l=$k;
                While($var[$k]['tipo_cargo']==$var[$l]['tipo_cargo'])
                {
                    $this->salida .= "        <tr>";
                    $this->salida .= "        <td width=\"34%\">".$var[$l]['descripcion']."";
                    $this->salida .= "        </td>";
                    $this->salida .= "        <td width=\"33%\">";
                    $this->salida .= "        <table width=\"100%\" align=\"center\" border=\"0\" $color>";
                    //$l=$k;
                    While($var[$k]['tipo_cargo']==$var[$l]['tipo_cargo'] AND $var[$l]['interno']==0)
                    {
                        $this->salida .= "        <tr>";
                        $this->salida .= "            <td>";
                        $this->salida .= "              <table width=\"95%\" align=\"center\" border=\"0\" class=\"modulo_table_list_title\">";
                        $this->salida .= "              <tr class=\"modulo_table_list_title\">";
                        $this->salida .= "              <td width=\"45%\" align=\"center\">";
                        $this->salida .= "AUTORIZADO";
                        $this->salida .= "              </td>";
                        $this->salida .= "              <td width=\"55%\" align=\"center\">";
                        $this->salida .= "VALOR MÁXIMO";
                        $this->salida .= "              </td>";
                        $this->salida .= "              </tr>";
                        $this->salida .= "              <tr $color>";
                        $this->salida .= "              <td align=\"center\">";
                        if($var[$l]['sw_autorizado']==1)
                        {
                          $this->salida .= "<img src=\"".GetThemePath()."/images/checksi.png\" border=\"0\">";
                        }
                        else
                        {
                          $this->salida .= "<img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">";
                        }
                        $this->salida .= "              </td>";
                        $this->salida .= "              <td align=\"center\">";
                        $this->salida .= "".$var[$l]['valor_maximo']."";
                        $this->salida .= "              </td>";
                        $this->salida .= "              </tr>";
                        $this->salida .= "              <tr class=\"modulo_table_list_title\">";
                        $this->salida .= "              <td width=\"45%\" align=\"center\">";
                        $this->salida .= "PERIOCIDAD";
                        $this->salida .= "              </td>";
                        $this->salida .= "              <td width=\"55%\" align=\"center\">";
                        $this->salida .= "CANTIDAD";
                        $this->salida .= "              </td>";
                        $this->salida .= "              </tr>";
                        $this->salida .= "              <tr $color>";
                        $this->salida .= "              <td align=\"center\">";
                        $this->salida .= "".$var[$l]['periocidad_dias']."";
                        $this->salida .= "              </td>";
                        $this->salida .= "              <td align=\"center\">";
                        $this->salida .= "".$var[$l]['cantidad']."";
                        $this->salida .= "              </td>";
                        $this->salida .= "              </tr>";
                        $this->salida .= "            </table>";
                        $this->salida .= "            </td>";
                        $this->salida .= "        </tr>";
                        $l++;
                    }
                    $this->salida .= "        </table>";
                    $this->salida .= "        </td>";
                    $this->salida .= "        <td width=\"33%\">";
                    $this->salida .= "        <table width=\"100%\" align=\"center\" border=\"0\" $color>";
                    //$l=$k;
                    While($var[$k]['tipo_cargo']==$var[$l]['tipo_cargo'] AND $var[$l]['interno']==1)
                    {
                        $this->salida .= "        <tr>";
                        $this->salida .= "            <td>";
                        $this->salida .= "              <table width=\"95%\" align=\"center\" border=\"0\" class=\"modulo_table_list_title\">";
                        $this->salida .= "              <tr class=\"modulo_table_list_title\">";
                        $this->salida .= "              <td width=\"45%\" align=\"center\">";
                        $this->salida .= "AUTORIZADO";
                        $this->salida .= "              </td>";
                        $this->salida .= "              <td width=\"55%\" align=\"center\">";
                        $this->salida .= "VALOR MÁXIMO";
                        $this->salida .= "              </td>";
                        $this->salida .= "              </tr>";
                        $this->salida .= "              <tr $color>";
                        $this->salida .= "              <td align=\"center\">";
                        if($var[$l]['sw_autorizado']==1)
                        {
                          $this->salida .= "<img src=\"".GetThemePath()."/images/checksi.png\" border=\"0\">";
                        }
                        else
                        {
                          $this->salida .= "<img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">";
                        }
                        $this->salida .= "              </td>";
                        $this->salida .= "              <td align=\"center\">";
                        $this->salida .= "".$var[$l]['valor_maximo']."";
                        $this->salida .= "              </td>";
                        $this->salida .= "              </tr>";
                        $this->salida .= "              <tr class=\"modulo_table_list_title\">";
                        $this->salida .= "              <td width=\"45%\" align=\"center\">";
                        $this->salida .= "PERIOCIDAD";
                        $this->salida .= "              </td>";
                        $this->salida .= "              <td width=\"55%\" align=\"center\">";
                        $this->salida .= "CANTIDAD";
                        $this->salida .= "              </td>";
                        $this->salida .= "              </tr>";
                        $this->salida .= "              <tr $color>";
                        $this->salida .= "              <td align=\"center\">";
                        $this->salida .= "".$var[$l]['periocidad_dias']."";
                        $this->salida .= "              </td>";
                        $this->salida .= "              <td align=\"center\">";
                        $this->salida .= "".$var[$l]['cantidad']."";
                        $this->salida .= "              </td>";
                        $this->salida .= "              </tr>";
                        $this->salida .= "            </table>";
                        $this->salida .= "            </td>";
                        $this->salida .= "        </tr>";
                        $l++;
                    }
                    $this->salida .= "        </table>";
                    $this->salida .= "        </td>";
                    $this->salida .= "        </tr>";
                }
                $this->salida .= "      </table>";
                $this->salida .= "  </td>";
                $this->salida .= "  </tr>";
                $k=$l;
              }
              $this->salida .= "      </table>";
              $this->salida .= "  </td>";
              $this->salida .= "  </tr>";
              $i=$k;
            }
        }
        else
        {
              $this->salida .= "  <tr>";
              $this->salida .= "   <td align=\"center\" colspan=\"5\">NO HAY EXCEPCIONES</td>";
              $this->salida .= "  </tr>";
        }
        $this->salida .= "      </table>";
        $this->salida .= "        <table width=\"100%\" align=\"center\" border=\"0\">";
        $actionM=ModuloGetURL('app','Autorizacion','user','VerServicio');
        $this->salida .= "             <form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\" colspan=\"5\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"><br></td></form>";
        $this->salida .= "  </tr>";
        $this->salida .= "      </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
  }

  /**
  *
  */
  function FormaCargosAutorizados($var)
  {
        $this->salida = ThemeAbrirTabla('AUTORIZACIONES EXCEPCIONES PLAN');
        IncludeLib("tarifario");
        $this->EncabezadoAutoPlan();
        if(!empty($var))
        {
              $this->salida .= "     <br> <table width=\"90%\" align=\"center\" border=\"0\" class=\"modulo_table_list\"  cellpadding=\"4\" cellpadding=\"4\">";
              $this->salida .= "             <tr class=\"modulo_table_list_title\" align=\"center\">";
              $this->salida .= "                 <td colspan=\"6\">CARGOS AUTORIZADOS</td>";
              $this->salida .= "             </tr>";
              $this->salida .= "             <tr align=\"center\" class=\"modulo_table_list_title\">";
              $this->salida .= "                 <td width=\"10%\">CODIGO</td>";
              $this->salida .= "                 <td>CARGO</td>";
              $this->salida .= "                 <td width=\"5%\">CANT</td>";
              $this->salida .= "                 <td width=\"5%\">AUTORIZADOR</td>";
              $this->salida .= "                 <td width=\"10%\">FECHA</td>";
              $this->salida .= "                 <td width=\"10%\">TIPO</td>";
              $this->salida .= "             </tr>";
              for($i=0; $i<sizeof($var); $i++)
              {
                  if($i % 2) {  $estilo="modulo_list_claro";  }
                  else {  $estilo="modulo_list_oscuro";   }
                  $this->salida .= "             <tr align=\"center\" class=\"$estilo\">";
                  if($var[$i][cargoa]==NULL)
                  { $cod=$var[$i][cargoc]; }
                  else{ $cod=$var[$i][cargoa]; }
                  $this->salida .= "                 <td width=\"10%\">".$cod."</td>";
                  if($var[$i][desa]==NULL)
                  { $cargo=$var[$i][desc]; }
                  else{ $cargo=$var[$i][desa]; }
                  $this->salida .= "                 <td>".$cargo."</td>";
                  if($var[$i][canta]==NULL)
                  { $cant=$var[$i][cantc]; }
                  else{ $cant=$var[$i][canta]; }
                  $this->salida .= "                 <td width=\"5%\">".FormatoValor($cant)."</td>";
                  $this->salida .= "                 <td width=\"5%\">".$var[$i][nombre]."</td>";
                  $this->salida .= "                 <td width=\"10%\">".$var[$i][fecha_registro]."</td>";
                  $this->salida .= "                 <td width=\"10%\">".$var[$i][tipo]."</td>";
                  $this->salida .= "             </tr>";
              }
              $this->salida .= "            </table>";
        }
        else
        {
              $this->salida .= "     <BR><table height=\"100%\" border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">";
              $this->salida .= "  <tr>";
              $this->salida .= "   <td align=\"center\" colspan=\"5\">NO HAY CARGOS AUTORIZADOS</td>";
              $this->salida .= "  </tr>";
              $this->salida .= "      </table>";
        }
        $this->salida .= "        <table width=\"100%\" align=\"center\" border=\"0\">";
        $contenedor=$_SESSION['AUTORIZACIONES']['RETORNO']['contenedor'];
        $modulo=$_SESSION['AUTORIZACIONES']['RETORNO']['modulo'];
        $tipo=$_SESSION['AUTORIZACIONES']['RETORNO']['tipo'];
        $metodo=$_SESSION['AUTORIZACIONES']['RETORNO']['metodo'];
        $argumentos=$_SESSION['AUTORIZACIONES']['RETORNO']['argumentos'];
        $actionM=ModuloGetURL($contenedor,$modulo,$tipo,$metodo,$argumentos);
        $this->salida .= "             <form name=\"formabuscar\" action=\"$actionM\" method=\"post\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"><br></td></form>";
        $this->salida .= "  </tr>";
        $this->salida .= "      </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
  }



  /**
  *
  */
  function FormaAfiliado($TipoAfiliado,$Nivel,$s)
  {
	    $this->salida .= ThemeAbrirTabla('AUTORIZACIONES - DATOS AFILIADO');
      if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']))
      {
          $a=ImplodeArrayAssoc($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']);
          $_SESSION['AUTORIZACIONES']['AFILIADO']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_tipo_afiliado'];
          $_SESSION['AUTORIZACIONES']['RANGO']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_nivel'];
          $_SESSION['AUTORIZACIONES']['SEMANAS']=$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_semanas_cotizadas'];
					if(empty($_SESSION['AUTORIZACIONES']['SEMANAS']))
					{  $_SESSION['AUTORIZACIONES']['SEMANAS']=0;  }
					$this->FormaCamposBD();
         /* $this->salida .= "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"60%\" align=\"center\">";
          $this->salida .= "  <tr>";
          $this->salida .= "  <td colspan=\"2\">";
          $this->salida .= "            <table width=\"80%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
          $this->salida .= "               <tr>";
          $this->salida .= "                  <td colspan=\"2\" align=\"center\" class=\"modulo_table_list_title\">DATOS AFILIADO EN LA BASE DE DATOS DE LA ENTIDAD</td>";
          $this->salida .= "               </tr>";
          $arreglon=ExplodeArrayAssoc($a);
          $i=0;
          foreach($arreglon as $k => $v)
          {
              if($i % 2) {  $estilo="modulo_list_claro";  }
              else {  $estilo="modulo_list_oscuro";   }
              $this->salida .= "         <tr class=\"$estilo\">";
              $this->salida .= "            <td align=\"center\">$k</td>";
              $this->salida .= "            <td align=\"center\">$v</td>";
              $this->salida .= "        </tr>";
              $i++;
          }
          $this->salida .= "           </table>";
          $this->salida .= "               </td>";
          $this->salida .= "               </tr>";
          $this->salida .= "           </table><BR>";*/
      }
      $this->salida .= "            <table width=\"50%\" border=\"0\" align=\"center\" class=\"normal_10\" cellspacing=\"3\" cellpadding=\"3\">";
      $this->salida .= "               <tr>";
      $this->salida .= $this->SetStyle("MensajeError");
      $this->salida .= "               </tr>";
      $this->salida .= "           </table>";
      $accion=ModuloGetURL('app','Autorizacion','user','GuardarAfiliado');
      $this->salida .= "   <form name=\"forma\" action=\"$accion\" method=\"post\">";
      $this->salida .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
			if(!empty($_SESSION['AUTORIZACIONES']['CAJA']['CAMBIO']))
			{
						$this->salida .= "          <tr>";
						$this->salida .= "            <td colspan=\"6\" align=\"center\" class=\"label_error\">LOS DATOS DEL PACIENTE EN LA ORDEN DE SERVICIO SON</td>";
						$this->salida .= "          </tr>";
						$this->salida .= "          <tr>";
						$this->salida .= "            <td class=\"".$this->SetStyle("TipoAfiliado")."\"  width=\"15%\">TIPO AFILIADO: </td>";
						$NomAfi=$this->NombreAfiliado($_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_afiliado_id']);
						$this->salida .= "            <td><input type=\"hidden\"  class=\"input-text\" name=\"TipoAfiliado2\" value=\"".$NomAfi[tipo_afiliado_id]."\">".$NomAfi[tipo_afiliado_nombre]."</td>";
						$this->salida .= "            <td></td>";
						$this->salida .= "             <td class=\"".$this->SetStyle("Nivel")."\">RANGO: </td>";
						$this->salida .= "            <td><input type=\"hidden\"  class=\"input-text\" name=\"Nivel2\" value=\"".$niveles[0][rango]."\"   width=\"20%\">".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['rango']."</td>";
						$this->salida .= "            <td class=\"".$this->SetStyle("Semanas")."\" width=\"23%\">SEMANAS COTIZADAS: </td>";
						$this->salida .= "            <td align=\"left\" width=\"10%\"><input type=\"text\" name=\"Semanas2\" size=\"8\" value=\"".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['semanas_cotizadas']."\" ></td>";
						$this->salida .= "          </tr>";
						$this->salida .= "          <tr>";
						$this->salida .= "            <td colspan=\"6\" align=\"center\" class=\"label_error\">&nbsp;</td>";
						$this->salida .= "          </tr>";
			}
      $tipo_afiliado=$this->Tipo_Afiliado();
      $this->salida .= "          <tr>";
      if(sizeof($tipo_afiliado)>1)
      {
						$this->salida .= "               <td class=\"".$this->SetStyle("TipoAfiliado")."\"  width=\"15%\">TIPO AFILIADO: </td><td><select name=\"TipoAfiliado\" class=\"select\">";
						$this->BuscarIdTipoAfiliado($tipo_afiliado,$_SESSION['AUTORIZACIONES']['AFILIADO']);
						$this->salida .= "              </select></td>";
						$this->salida .= "            <td></td>";
      }
      else
      {
          $this->salida .= "            <td class=\"".$this->SetStyle("TipoAfiliado")."\"  width=\"15%\">TIPO AFILIADO: </td>";
          $NomAfi=$this->NombreAfiliado($TipoAfiliado);
          $this->salida .= "            <td><input type=\"hidden\"  class=\"input-text\" name=\"TipoAfiliado\" value=\"".$NomAfi[tipo_afiliado_id]."\">".$NomAfi[tipo_afiliado_nombre]."</td>";
          $this->salida .= "            <td></td>";
      }
      $niveles=$this->Niveles();
      if(sizeof($niveles)>1)
      {
        $this->salida .= "               <td class=\"".$this->SetStyle("Nivel")."\">RANGO: </td><td><select name=\"Nivel\" class=\"select\">";
        $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
        for($i=0; $i<sizeof($niveles); $i++)
        {
            if($niveles[$i][rango]==$_SESSION['AUTORIZACIONES']['RANGO']){
              $this->salida .=" <option value=\"".$niveles[$i][rango]."\" selected>".$niveles[$i][rango]."</option>";
            }
            else{
                $this->salida .=" <option value=\"".$niveles[$i][rango]."\">".$niveles[$i][rango]."</option>";
            }
        }
      }
      else
      {
          $this->salida .= "             <td class=\"".$this->SetStyle("Nivel")."\">RANGO: </td>";
          $this->salida .= "            <td><input type=\"hidden\"  class=\"input-text\" name=\"Nivel\" value=\"".$niveles[0][rango]."\">".$niveles[0][rango]."</td>";
          $this->salida .= "            <td></td>";
      }
      $this->salida .= "            <td class=\"".$this->SetStyle("Semanas")."\" width=\"23%\">SEMANAS COTIZADAS: </td>";
			if(empty($_SESSION['AUTORIZACIONES']['SEMANAS']))
			{  $_SESSION['AUTORIZACIONES']['SEMANAS']=0; }
      $this->salida .= "            <td align=\"left\" width=\"10%\"><input type=\"text\" name=\"Semanas\" size=\"8\" value=\"".$_SESSION['AUTORIZACIONES']['SEMANAS']."\" ></td>";
      $this->salida .= "          </tr>";
      if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']))
      {
          $this->salida .= "          <tr>";
          $this->salida .= "            <td colspan=\"6\" align=\"center\">OBSERVACION: <textarea name=\"Observacion\" cols=\"65\" rows=\"3\" class=\"textarea\"></textarea></td>";
          $this->salida .= "          </tr>";
      }
      $this->salida .= "       </table><br>";
      $this->salida .= "<br><table border=\"0\" cellspacing=\"3\" cellpadding=\"2\" width=\"40%\" align=\"center\">";
      $this->salida .= "  <tr>";
      $this->salida .= "  <td align=\"center\" width=\"50%\"><input class=\"input-submit\" type=\"submit\" name=\"Autorizar\" value=\"ACEPTAR\"></td>";
      $this->salida .= "  </form>";
      $accion=ModuloGetURL('app','CentroAutorizacion','user','RetornarAutorizacion');
      $this->salida .= "  <td align=\"center\"><form name=\"forma2\" action=\"$accion\" method=\"post\"><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"VOLVER\"></td>";
      $this->salida .= "      </form>";
      $this->salida .= "  </tr>";
      $this->salida .= "  </table>";
      $this->salida .= ThemeCerrarTabla();
      return true;
  }

	function FormaCargoCups()
	{
			$this->salida .= "            <table width=\"90%\" align=\"center\" border=\"0\"  cellpadding=\"3\" class=\"modulo_table_list\">";
			$this->salida .= "             <tr align=\"center\" class=\"modulo_table_list_title\">";
			$this->salida .= "                 <td colspan=\"3\">CARGOS AUTORIZADOS</td>";
			$this->salida .= "             </tr>";
			$this->salida .= "             <tr align=\"center\" class=\"modulo_table_list_title\">";
			$this->salida .= "                 <td width=\"10%\">CODIGO</td>";
			$this->salida .= "                 <td>CARGO</td>";
			$this->salida .= "                 <td width=\"5%\">CANT</td>";
			$this->salida .= "             </tr>";
			$this->salida .= "             <tr class=\"modulo_list_claro\">";
			$this->salida .= "                 <td align=\"center\">".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['CUPS']."</td>";
			$nom=$this->NombreCups($_SESSION['AUTORIZACIONES']['AUTORIZAR']['CUPS']);
			$this->salida .= "                 <td>".$nom."</td>";
			$this->salida .= "                 <td>1</td>";
			$this->salida .= "             </tr>";
			$this->salida .= "            </table>";
	}

	function FormaCamposBD()
	{
			$a=ImplodeArrayAssoc($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']);
			$arreglon=ExplodeArrayAssoc($a);
			$plantilla=$this->PlantilaBD($_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']);
			$this->salida .= "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"60%\" align=\"center\">";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td colspan=\"2\">";
			$this->salida .= "            <table width=\"80%\" border=\"0\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
			$this->salida .= "               <tr>";
			$this->salida .= "                  <td colspan=\"2\" align=\"center\" class=\"modulo_table_list_title\">DATOS AFILIADO EN LA BASE DE DATOS DE LA ENTIDAD</td>";
			$this->salida .= "               </tr>";
			$i=0;
			foreach($arreglon as $k => $v)
			{
					$mostrar='';
					$mostrar=$this->CamposMostrarBD($k,$plantilla);
					if(!empty($mostrar[sw_mostrar]))
					{
							if($i % 2) {  $estilo="modulo_list_claro";  }
							else {  $estilo="modulo_list_oscuro";   }
							$this->salida .= "         <tr class=\"$estilo\">";
							if(!empty($mostrar[nombre_mostrar]))
							{  $k=$mostrar[nombre_mostrar];}
							$this->salida .= "            <td align=\"center\">$k</td>";
							$this->salida .= "            <td align=\"center\">$v</td>";
							$this->salida .= "        </tr>";
							$i++;
					}
			}
			$this->salida .= "           </table>";
			$this->salida .= "               </td>";
			$this->salida .= "               </tr>";
			$this->salida .= "           </table><BR>";
	}

	function FormaAutorizacionExistentes($vars)
	{
			$this->salida .= ThemeAbrirTabla('AUTORIZACIONES - DATOS AUTORIZACION');
      //mensaje
      $this->salida .= "<p align=\"center\" class=\"label_mark\">YA EXISTE AUTORIZACION PARA LA ORDEN DEL PACIENTE, POR FAVOR VERIFIQUE LA INFORMACION</p>";
			$this->salida .= "	<table width=\"70%\" cellspacing=\"3\" border=\"0\" cellpadding=\"3\" align=\"center\" class=\"modulo_list_oscuro\">";
			$this->salida .=  "		<tr class=\"modulo_list_claro\">";
			$this->salida .=  "		  <td width=\"18%\" class=\"label\">FECHA REGISTRO: </td>";
			$this->salida .=  "		  <td>".$vars[0][fecha_registro]."</td>";
			$this->salida .=  "		  <td width=\"12%\" class=\"label\">USUARIO: </td>";
			$this->salida .=  "		  <td width=\"40%\">".$vasr[0][nombre]."</td>";
			$this->salida .=  "		</tr>";
			$this->salida .=  "		<tr class=\"modulo_list_claro\">";
			$this->salida .=  "		  <td class=\"label\">OBSERVACIONES: </td>";
			$this->salida .=  "		  <td colspan=\"3\">".$vars[0][observaciones]."</td>";
			$this->salida .=  "		</tr>";
			$this->salida .=  "</table><br>";
			//certificado
			$cart=$this->DatosAutorizaciones($vars[0]['autorizacion'],'autorizaciones_certificados');
			if(!empty($cart))
			{
					$this->salida .= "	<table width=\"80%\" cellspacing=\"3\" border=\"1\" cellpadding=\"3\" align=\"center\" class=\"modulo_list_oscuro\">";
					$this->salida .=  "		<tr class=\"modulo_table_title\"><td>AUTORIZACIONES POR CERTIFICADO DE CARTERA</td></tr>";
					$this->salida .=  "		<tr class=\"modulo_list_oscuro\"><td>";
					$this->salida .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"100%\" align=\"center\" class=\"modulo_list_oscuro\">";
					$this->salida .= "  <tr class=\"modulo_table_list_title\">";
					$this->salida .= "  <td width=\"6%\">COD. AUTO.</td>";
					$this->salida .= "  <td width=\"26%\">RESPONSABLE</td>";
					$this->salida .= "  <td width=\"6%\">TERMINACION</td>";
					$this->salida .= "  <td>OBSERVACIONES</td>";
					$this->salida .= "  </tr>";
					for($i=0; $i<sizeof($cart); $i++)
					{
							$fecha = explode(' ',$cart[$i][fecha_terminacion]);
							$class='';
							if(strtotime(date("Y-m-d")) > strtotime($fecha[0]))
							{   $class='label_error';   }
							$this->salida .= "  <tr class=\"modulo_list_claro\">";
							$this->salida .= "  <td align=\"center\" width=\"10%\">".$cart[$i][codigo_autorizacion]."</td>";
							$this->salida .= "  <td align=\"center\">".$cart[$i][responsable]."</td>";
							$this->salida .= "  <td align=\"center\" class=\"$class\">".$fecha[0]."</td>";
							$this->salida .= "  <td>".$cart[$i][observaciones]."</td>";
							$this->salida .= "  </tr>";
					}
					$this->salida .=  "</table>";
					$this->salida .= "  </td></tr>";
					$this->salida .=  "</table>";
			}
			//escrita
			$escrita=$this->DatosAutorizaciones($vars[0]['autorizacion'],'autorizaciones_escritas');
			if(!empty($escrita))
			{
					$this->salida .= "	<table width=\"80%\" cellspacing=\"3\" border=\"1\" cellpadding=\"3\" align=\"center\" class=\"modulo_list_oscuro\">";
					$this->salida .=  "		<tr class=\"modulo_table_title\"><td>AUTORIZACIONES ESCRITAS</td></tr>";
					$this->salida .=  "		<tr class=\"modulo_list_oscuro\"><td>";
					$this->salida .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"100%\" align=\"center\" class=\"modulo_list_oscuro\">";
					$this->salida .= "  <tr class=\"modulo_table_list_title\">";
					$this->salida .= "  <td width=\"10%\">COD. AUTO.</td>";
					$this->salida .= "  <td width=\"10%\">TERMINACION</td>";
					$this->salida .= "  <td>OBSERVACIONES</td>";
					$this->salida .= "  </tr>";
					for($i=0; $i<sizeof($escrita); $i++)
					{
							$fecha = explode(' ',$escrita[$i][validez]);
							$class='';
							if(strtotime(date("Y-m-d")) > strtotime($fecha[0]))
							{   $class='label_error';   }
							$this->salida .= "  <tr class=\"modulo_list_claro\">";
							$this->salida .= "  <td align=\"center\" width=\"10%\">".$escrita[$i][codigo_autorizacion]."</td>";
							$this->salida .= "  <td align=\"center\" class=\"$class\">".$fecha[0]."</td>";
							$this->salida .= "  <td>".$escrita[$i][observaciones]."</td>";
							$this->salida .= "  </tr>";
					}
					$this->salida .=  "</table>";
					$this->salida .= "  </td></tr>";
					$this->salida .=  "</table>";
			}
			//INTERNAS
			$sistema=$this->DatosAutorizaciones($vars[0]['autorizacion'],'autorizaciones_por_sistema');
			if(!empty($sistema))
			{
					$this->salida .= "	<table width=\"80%\" cellspacing=\"3\" border=\"1\" cellpadding=\"3\" align=\"center\" class=\"modulo_list_oscuro\">";
					$this->salida .=  "		<tr class=\"modulo_table_title\"><td>AUTORIZACIONES INTERNAS</td></tr>";
					$this->salida .=  "		<tr class=\"modulo_list_oscuro\"><td>";
					$this->salida .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"100%\" align=\"center\" class=\"modulo_list_oscuro\">";
					$this->salida .= "  <tr class=\"modulo_table_list_title\">";
					$this->salida .= "  <td width=\"10%\">COD. AUTO.</td>";
					$this->salida .= "  <td width=\"33%\">USUARIO AUTORIZADOR</td>";
					$this->salida .= "  <td>OBSERVACIONES</td>";
					$this->salida .= "  </tr>";
					for($i=0; $i<sizeof($sistema); $i++)
					{
							$this->salida .= "  <tr class=\"modulo_list_claro\">";
							$this->salida .= "  <td align=\"center\" width=\"10%\">".$sistema[$i][autorizacion_por_sistema_id]."</td>";
							$this->salida .= "  <td align=\"center\" class=\"$class\">".$sistema[$i][nombre]."</td>";
							$this->salida .= "  <td>".$sistema[$i][observaciones]."</td>";
							$this->salida .= "  </tr>";
					}
					$this->salida .=  "</table>";
					$this->salida .= "  </td></tr>";
					$this->salida .=  "</table>";
			}
			//TELEFONICAS
			$tele=$this->DatosAutorizaciones($vars[0]['autorizacion'],'autorizaciones_telefonicas');
			if(!empty($tele))
			{
					$this->salida .= "	<table width=\"80%\" cellspacing=\"3\" border=\"1\" cellpadding=\"3\" align=\"center\" class=\"modulo_list_oscuro\">";
					$this->salida .=  "		<tr class=\"modulo_table_title\"><td>AUTORIZACIONES INTERNAS</td></tr>";
					$this->salida .=  "		<tr class=\"modulo_list_oscuro\"><td>";
					$this->salida .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"100%\" align=\"center\" class=\"modulo_list_oscuro\">";
					$this->salida .= "  <tr class=\"modulo_table_list_title\">";
					$this->salida .= "  <td width=\"10%\">COD. AUTO.</td>";
					$this->salida .= "  <td width=\"30%\">RESPONSABLE</td>";
					$this->salida .= "  <td>OBSERVACIONES</td>";
					$this->salida .= "  </tr>";
					for($i=0; $i<sizeof($tele); $i++)
					{
							$this->salida .= "  <tr class=\"modulo_list_claro\">";
							$this->salida .= "  <td align=\"center\" width=\"10%\">".$tele[$i][codigo_autorizacion]."</td>";
							$this->salida .= "  <td align=\"center\" class=\"$class\">".$tele[$i][responsable]."</td>";
							$this->salida .= "  <td>".$tele[$i][observaciones]."</td>";
							$this->salida .= "  </tr>";
					}
					$this->salida .=  "</table>";
					$this->salida .= "  </td></tr>";
					$this->salida .=  "</table>";
			}
			//electronica
			$elec=$this->DatosAutorizaciones($vars[0]['autorizacion'],'autorizaciones_electronicas');
			if(!empty($elec))
			{
					$this->salida .= "	<table width=\"80%\" cellspacing=\"3\" border=\"1\" cellpadding=\"3\" align=\"center\" class=\"modulo_list_oscuro\">";
					$this->salida .=  "		<tr class=\"modulo_table_title\"><td>AUTORIZACIONES INTERNAS</td></tr>";
					$this->salida .=  "		<tr class=\"modulo_list_oscuro\"><td>";
					$this->salida .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"100%\" align=\"center\" class=\"modulo_list_oscuro\">";
					$this->salida .= "  <tr class=\"modulo_table_list_title\">";
					$this->salida .= "  <td width=\"10%\">COD. AUTO.</td>";
					$this->salida .= "  <td width=\"30%\">VALIDEZ</td>";
					$this->salida .= "  <td>OBSERVACIONES</td>";
					$this->salida .= "  </tr>";
					for($i=0; $i<sizeof($elec); $i++)
					{
							$fecha = explode(' ',$elec[$i][validez]);
							$class='';
							if(strtotime(date("Y-m-d")) > strtotime($fecha[0]))
							{   $class='label_error';   }
							$this->salida .= "  <tr class=\"modulo_list_claro\">";
							$this->salida .= "  <td align=\"center\" width=\"10%\">".$elec[$i][codigo_autorizacion]."</td>";
							$this->salida .= "  <td align=\"center\" class=\"$class\">".$fecha[0]."</td>";
							$this->salida .= "  <td>".$elec[$i][observaciones]."</td>";
							$this->salida .= "  </tr>";
					}
					$this->salida .=  "</table>";
					$this->salida .= "  </td></tr>";
					$this->salida .=  "</table>";
			}
			$this->salida .= "	<br><table width=\"50%\" cellspacing=\"3\" border=\"0\" cellpadding=\"3\" align=\"center\" class=\"normal_10\">";
			$this->salida .= "               <tr>";
			$accion=ModuloGetURL('app','Autorizacion','user','ContinuarConsultaExt');
			$this->salida .= "                <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
			$this->salida .= "               <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"AUTORIZAR\"></td>";
			$this->salida .= "                </form>";
			$Contenedor=$_SESSION['AUTORIZACIONES']['RETORNO']['contenedor'];
			$Modulo=$_SESSION['AUTORIZACIONES']['RETORNO']['modulo'];
			$Tipo=$_SESSION['AUTORIZACIONES']['RETORNO']['tipo'];
			$Metodo=$_SESSION['AUTORIZACIONES']['RETORNO']['metodo'];
			$argu=$_SESSION['AUTORIZACIONES']['AUTORIZAR']['ARGUMENTOS'];
			$accion=ModuloGetURL($Contenedor,$Modulo,$Tipo,$Metodo,$argu);
			$this->salida .= "                <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
			$this->salida .= "               <td align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"CONTINUAR\"></td>";
			$this->salida .= "                </form>";
			$this->salida .= "               </tr>";
			$this->salida .=  "</table>";
      $this->salida .= ThemeCerrarTabla();
      return true;
	}

//-----------------------------------------------------------------------
}//fin clase

?>

