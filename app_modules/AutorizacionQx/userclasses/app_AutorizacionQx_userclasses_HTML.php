<?php

/**
 * $Id: app_AutorizacionQx_userclasses_HTML.php,v 1.6 2005/09/26 18:23:50 darling Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Proposito del Archivo: Manejo visual de las autorizaciones.
 */

/**
*Contiene los metodos visuales para realizar las autorizaciones.
*/

class app_AutorizacionQx_userclasses_HTML extends app_AutorizacionQx_user
{
  /**
  *Constructor de la clase app_AutorizacionQx_user_HTML
  *El constructor de la clase app_AutorizacionQx_user_HTML se encarga de llamar
  *a la clase app_AutorizacionQx_user quien se encarga de el tratamiento
  * de la base de datos.
  */

  function app_AutorizacionQx_user_HTML()
  {
        $this->salida='';
        $this->app_AutorizacionQx_user();
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
  function FormaDatosPaciente()
  {
        $this->salida .= " <table border=\"0\" width=\"75%\" align=\"center\" cellspacing=\"3\" cellpadding=\"3\" class=\"modulo_table_list\">";
        $this->salida .= "  <tr>";
        $this->salida .= "  <td class=\"modulo_table_list_title\" width=\"20%\">IDENTIFICACION:</td><td width=\"30%\" class=\"modulo_list_claro\">".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente']." ".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id']."</td>";
        $this->salida .= "  <td class=\"modulo_table_list_title\" width=\"20%\">PACIENTE:</td><td class=\"modulo_list_claro\">".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['nombre_paciente']."</td>";
        $this->salida .= "  </tr>";
        $plan=$this->DatosPlan();
        $this->salida .= "  <td class=\"modulo_table_list_title\" width=\"20%\">PLAN:</td><td class=\"modulo_list_claro\">".$plan[plan_descripcion]."</td>";
        $this->salida .= "  <td class=\"modulo_table_list_title\" width=\"20%\">RESPONSABLE:</td><td class=\"modulo_list_claro\">".$plan[nombre_tercero]."</td>";
        $this->salida .= "  </tr>";
        $this->salida .= "  </table><br>";
  }

  function FormaAutorizacion()
  {
				IncludeLib('funciones_admision');
				$this->SetJavaScripts('DatosAutorizacion');				
				$this->SetJavaScripts('DatosBD');
				$this->SetJavaScripts('DatosBDAnteriores');
				$this->SetJavaScripts('DatosEvolucionInactiva');
				$this->salida .= ThemeAbrirTabla('AUTORIZACION PROCEDIMIENTOS QX');
				if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']))
				{
						$this->salida .= "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"50%\" align=\"center\" class=\"normal_10\">";
						$this->salida .= "  <tr class=\"modulo_list_claro\">";
						$this->salida .= "   <td align=\"center\">".RetornarWinOpenDatosBD($_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente'],$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id'],$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'])."</td>";
						$x=$this->CantidadMeses($_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']);
						if($x>1)
						{
								$this->salida .= "   <td align=\"center\">".RetornarWinOpenDatosBDAnteriores($_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_id_paciente'],$_SESSION['AUTORIZACIONES']['AUTORIZAR']['paciente_id'],$_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id'],$x)."</td>";
						}
						$this->salida .= "  </tr>";
						$this->salida .= "</table>";
				}
				$sw=$this->BuscarSwHc();
				if(!empty($sw))
				{
						$dat=$this->BuscarEvolucion();
						if($dat)//1139
						{
								$this->salida .= "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"30%\" align=\"center\" class=\"normal_10\">";
								$this->salida .= "  <tr class=\"modulo_list_claro\">";
								$_SESSION['HISTORIACLINICA']['RETORNO']['modulo']='AutorizacionQx';
								$_SESSION['HISTORIACLINICA']['RETORNO']['metodo']='FormaAutorizacion';
								$_SESSION['HISTORIACLINICA']['RETORNO']['tipo']='user';
								$_SESSION['HISTORIACLINICA']['RETORNO']['contenedor']='app';
								$accion=ModuloHCGetURL($dat,'','','','');
								$this->salida .= "   <td align=\"center\"><a href=\"$accion\">HISTORIA CLINICA</a></td>";
								$this->salida .= "  </tr>";
								$this->salida .= "</table><BR>";
						}
				}

				//mensaje
				//$this->salida .= "<div align=\"center\" class=\"label_error\">".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['msg']."</div><br>";
				$this->salida .= "          <table width=\"90%\" align=\"center\" border=\"0\" cellpadding=\"3\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "          </table>";
				//llamar en encabezado datos paciente
				$this->FormaDatosPaciente();

				$accion=ModuloGetURL('app','AutorizacionQx','user','InsertarAutorizacion');
				$this->salida .= "      <form name=\"forma\" action=\"$accion\" method=\"post\">";
				if($_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO']!='FACTURACION'
					AND $_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_plan']!=2)
				{   //tipo afiliado y rango
						$this->FormaDatosAfiliado();
				}
				if($_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_plan']==2
					OR $_SESSION['AUTORIZACIONES']['AUTORIZAR']['tipo_plan']==1)
				{
						$dat=$this->DatosPlanUnico($_SESSION['AUTORIZACIONES']['AUTORIZAR']['plan_id']);
						$_SESSION['AUTORIZACIONES']['SEMANAS']=0;
						$_SESSION['AUTORIZACIONES']['AFILIADO']=$dat[tipo_afiliado_id];
						$_SESSION['AUTORIZACIONES']['RANGO']=$dat[rango];
				}
				//otros datos de la bd
				if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']))
				{
							$this->salida .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
							$this->salida .= "          <tr>";
							$this->salida .= "            <td  width=\"10%\" class=\"".$this->SetStyle("TipoAfiliado")."\">EMPLEADOR: </td>";
							$this->salida .= "            <td align=\"left\" width=\"35%\">".$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_empleador']."</td>";
							$this->salida .= "            <td></td>";
							$this->salida .= "             <td width=\"7%\" class=\"".$this->SetStyle("Nivel")."\">EDAD: </td>";
							$this->salida .= "            <td align=\"left\" width=\"5%\">".$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_edad']."</td>";
							$this->salida .= "            <td></td>";
							$this->salida .= "            <td width=\"10%\" class=\"".$this->SetStyle("Semanas")."\" width=\"23%\">ESTADO: </td>";
							if($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_estado_bd']=='SUSPENDIDO'
							OR $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_estado_bd']=='INACTIVO'
							OR $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_estado_bd']=='URGENCIAS'
							OR $_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_estado_bd']=='PROTECCION')
							{  $x='label_error';  }
							else
							{  $x='label_mark';  }
							$this->salida .= "            <td align=\"left\" width=\"10%\" class=\"$x\">".$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_estado_bd']."</td>";
							$this->salida .= "            <td width=\"12%\" class=\"".$this->SetStyle("Semanas")."\" width=\"23%\">URGENCIAS: </td>";
							if($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_urgencias']==1)
							{  $ur='MES URG'; }
							$this->salida .= "            <td align=\"left\" width=\"10%\">".$ur."</td>";
							$this->salida .= "          </tr>";
							$this->salida .= "          <tr>";
							$this->salida .= "            <td  width=\"10%\" class=\"".$this->SetStyle("TipoAfiliado")."\">RADICACION BD: </td>";
							$this->salida .= "            <td align=\"left\" width=\"35%\">".$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['fecha_radicacion']."</td>";
							$this->salida .= "            <td></td>";
							$this->salida .= "             <td width=\"7%\" class=\"".$this->SetStyle("Nivel")."\">VENCIMIENTO BD: </td>";
							$this->salida .= "            <td align=\"left\" width=\"5%\">".$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['fecha_vencimiento']."</td>";
							$this->salida .= "            <td></td>";
							$this->salida .= "            <td width=\"10%\" class=\"".$this->SetStyle("Semanas")."\" width=\"23%\"></td>";
							$this->salida .= "            <td align=\"left\" width=\"10%\"></td>";
							$this->salida .= "            <td width=\"12%\" class=\"".$this->SetStyle("Semanas")."\" width=\"23%\"></td>";
							$this->salida .= "            <td align=\"left\" width=\"10%\"></td>";
							$this->salida .= "          </tr>";
							$this->salida .= "       </table>";
				}/*
        $this->CargosSolicitadosAutorizacion();
        //autorizaciones que tienen tramite
        if(!empty($_SESSION['AUTORIZACIONES']['TRAMITE']))
        {
            $this->salida .= "   <BR> <table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\">";
            foreach($_SESSION['AUTORIZACIONES']['TRAMITE'] as $k => $v)
            {        $s='';
											foreach($v as $key => $value)
											{  $s.=$key.' - ';  }
											$this->salida .= "             <tr class=\"modulo_table_list_title\">";
											$this->salida .= "                 <td colspan=\"8\" align=\"LEFT\">TRAMITES SOLICITUD: &nbsp;$s</td>";
											$this->salida .= "             </tr>";
											$this->salida .= "          <tr>";
											if(!empty($value[sw_personalmente]))
											{  $value[nombre]='Personalmente';  }
											if(!empty($value[sw_telefonica]))
											{  $value[sw_telefonica]='Si';  }
											else
											{  $value[sw_telefonica]='No';  }
                    $this->salida .= "                 <td class=\"modulo_table_list_title\" align=\"LEFT\" width=\"12%\">RECIBIO: </td>";
                    $this->salida .= "                 <td class=\"modulo_list_claro\">".$value[nombre]."</td>";
                    $this->salida .= "                 <td class=\"modulo_table_list_title\" align=\"LEFT\" width=\"7%\">FECHA: </td>";
                    $this->salida .= "                 <td class=\"modulo_list_claro\" width=\"18%\">".$this->FechaStamp($value[fecha_resgistro])." ".$this->HoraStamp($value[fecha_resgistro])."</td>";
                    $this->salida .= "                 <td class=\"modulo_table_list_title\" align=\"LEFT\" width=\"7%\">USUARIO: </td>";
                    $this->salida .= "                 <td class=\"modulo_list_claro\" width=\"25%\">".$value[usuario]."</td>";
                    $this->salida .= "                 <td class=\"modulo_table_list_title\" align=\"LEFT\" width=\"4%\">TELE: </td>";
                    $this->salida .= "                 <td class=\"modulo_list_claro\" width=\"2%\">".$value[sw_telefonica]."</td>";
                    $this->salida .= "          </tr>";
                    $this->salida .= "          <tr>";
                    $this->salida .= "                 <td class=\"modulo_table_list_title\" align=\"LEFT\">OBSERVACION : </td>";
                    $this->salida .= "                 <td colspan=\"7\" class=\"modulo_list_claro\">".$value[observacion_autorizador]."</td>";
                    $this->salida .= "          </tr>";
                    $this->salida .= "          <tr>";
                    $this->salida .= "                 <td class=\"modulo_table_list_title\" align=\"LEFT\">OBS. PACIENTE: </td>";
                    $this->salida .= "                 <td colspan=\"7\" class=\"modulo_list_claro\">".$value[observacion_paciente]."</td>";
                    $this->salida .= "          </tr>";
            }
            $this->salida .= "       </table>";
        }*/
				//TIPO AUTORIZACION
				$this->salida .= "     <br><table border=\"0\" width=\"90%\" align=\"center\" class=\"modulo_table_list\" cellspacing=\"3\" cellpadding=\"3\">";
				$this->salida .= "      <tr align=\"center\" class=\"modulo_table_list_title\">";
				$this->salida .= "      <td width=\"33%\">SELECCIONE TIPO AUTORIZACION: </td>";
				$this->salida .= "      <td class=\"modulo_list_claro\"><select name=\"TipoAutorizacion\" class=\"select\">";
				$TiposAuto=$this->TiposAuto();
				$this->BuscarTipoAutorizacion($TiposAuto,$_REQUEST['TipoAutorizacion']);
				$this->salida .= "      </select></td>";
				$this->salida .= "      <td><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td>";
				$this->salida .= "      </tr>";
				$this->salida .= "     </table><BR>";

				//---------SOLICITUDES

				$datos = $this->DatosProcedimiento($_SESSION['AUTORIZACIONES']['AUTORIZAR']['solicitud']);
				$this->salida .= "<table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\" cellspacing=\"2\" cellpadding=\"2\">";
				$this->salida .= "    <tr align=\"center\" class=\"modulo_table_list_title\">";
				$this->salida .= "    <td width=\"10%\">CARGO</td>";
				$this->salida .= "    <td width=\"82%\">PROCEDIMIENTO QX</td>";
				$this->salida .= "    <td width=\"4%\">CANTIDAD</td>";
				$this->salida .= "    <td width=\"4%\">ESTADO</td>";
				$this->salida .= "    </tr>";
				//------------------PROCEDIMIENTO PRINCIPAL
				$this->salida .= "    <tr class=\"modulo_list_claro\">";
				$this->salida .= "    <td width=\"10%\"4 align=\"center\">".$datos[0][cargo]."</td>";
				$this->salida .= "    <td width=\"81%\" class=\"label_mark\">".$datos[0][descargo]."</td>";
				$this->salida .= "    <td width=\"5%\" class=\"label\" align=\"center\">".$datos[0][cantidad]."</td>";
				if($datos[0][sw_estado]==1)
				{  $this->salida .= "    <td align=\"center\" width=\"4%\"><input type = checkbox name=\"\" value =\"\"></td>";  }
				elseif($datos[0][sw_estado]===0)
				{  $this->salida .= "    <td align=\"center\" width=\"4%\">AUTORIZADO</td>";  }
				elseif($datos[0][sw_estado]==2)
				{  $this->salida .= "    <td align=\"center\" width=\"4%\">NO AUTORIZADO</td>";  }
				$this->salida .= "    </tr>";
				$this->salida .= "    <tr class=\"modulo_list_claro\">";
				$this->salida .= "    <td colspan=\"4\">";
				$this->salida .= "				<table border=\"0\" width=\"98%\" align=\"center\" class=\"normal_10\" cellspacing=\"1\" cellpadding=\"1\">";
				$this->salida .= "    				<tr class=\"modulo_list_claro\">";
				$this->salida .= "  						  <td width=\"10%\" class=\"label\">AMBITO:</td>";
				$this->salida .= "  						  <td width=\"13%\">".$datos[0][ambito]."</td>";
				$this->salida .= "  						  <td width=\"15%\" class=\"label\">TIPO:</td>";
				$this->salida .= "  						  <td width=\"15%\">".$datos[0][tipo]."</td>";
				$this->salida .= "  						  <td width=\"13%\" class=\"label\">FINALIDAD:</td>";
				$this->salida .= "  						  <td>".$datos[0][finalidad]."</td>";
				$this->salida .= "   					</tr>";
				$this->salida .= "    				<tr class=\"modulo_list_claro\">";
				$this->salida .= "  						  <td width=\"10%\" class=\"label\">NIVEL:</td>";
				$this->salida .= "  						  <td width=\"13%\">".$datos[0][nivel]."</td>";
				$this->salida .= "  						  <td width=\"15%\" class=\"label\">FECHA TENTATIVA:</td>";
				$this->salida .= "  						  <td width=\"15%\">".$datos[0][fecha_tentativa_cirugia]."</td>";
				$this->salida .= "  						  <td width=\"13%\" class=\"label\">SOLICITADA POR:</td>";
				$this->salida .= "  						  <td>".$_SESSION['AUTORIZACIONES']['AUTORIZAR']['profesional']."</td>";
				$this->salida .= "   					</tr>";
				$this->salida .= "    				<tr class=\"modulo_list_claro\">";
				$this->salida .= "  						  <td width=\"10%\" class=\"label\">OBSERVACION:</td>";
				$this->salida .= "  						  <td colspan=\"5\">".$datos[0][obsacto]."</td>";
				$this->salida .= "   					</tr>";
				$this->salida .= "				</table>";
				$this->salida .= "    </td>";
				$this->salida .= "    </tr>";
				//------------------FIN PROCEDIMIENTO PRINCIPAL
				$this->salida .= "</table><BR>";
				//------------formas para cada una de las solicitudes	
				$apoyos=$this->Apoyos();
				if(!empty($apoyos))				
				{  $this->FormaApoyos($apoyos);  }
				$pro=$this->Procedimientos();
				if(!empty($pro))				
				{  $this->FormaProcedimientos($pro);  }		
				$prod=$this->Productos();
				if(!empty($prod))				
				{  $this->FormaProductos($prod);  }	
				$estancia=$this->Estancia();				
				if(!empty($estancia))				
				{  $this->FormaEstancia($estancia);  }									
				//---------FIN SOLICITUDES
				
				//--------------AUTORIZACIONES REALIZADAS
				//--------------FIN AUTORIZACIONES REALIZADAS
				
				//---------DIAGOLO
				$this->salida .= "<BR><table border=\"0\" width=\"90%\" align=\"center\" class=\"normal_10\" cellspacing=\"2\" cellpadding=\"2\">";
				$this->salida .= "    <tr class=\"modulo_table_list_title\">";
				$this->salida .= "    <td colspan=\"2\">REGISTRO DE OBSERVACIONES</td>";
				$this->salida .= "    </tr>";
				for($i=0; $i<sizeof($datos); $i++)
				{
						$this->salida .= "    <tr class=\"modulo_list_claro\">";
						$this->salida .= "    <td width=\"30%\" align=\"center\"><b>".$datos[$i][nombre]."<br>".FechaStamp($datos[$i][fecha])." ".HoraStamp($datos[$i][fecha])."<br>Modificado: ".FechaStamp($datos[$i][fecha_ultima_modificacion])." ".HoraStamp($datos[$i][fecha_ultima_modificacion])."</br></b></td>";
						$this->salida .= "    <td width=\"70%\">".$datos[$i][observacion]."</td>";
						$this->salida .= "    </tr>";
				}
				$this->salida .= "    <tr class=\"modulo_list_claro\">";
				$this->salida .= "    <td width=\"20%\" class=\"".$this->SetStyle("dialogo")."\" align=\"center\">NUEVA OBSERVACION: </td>";
				$this->salida .= "    <td><textarea name=\"dialogo\" cols=\"75\" rows=\"3\" class=\"textarea\"></textarea>";
				$this->salida .= "    &nbsp;<input class=\"input-submit\" type=\"submit\" name=\"Observacion\" value=\"GUARDAR\"></td>";
				$this->salida .= "    </tr>";
				$this->salida .= "</table><BR>";
				//---------FIN DIALOGO

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
				$this->salida .= "  <tr>";
				$this->salida .= "  <td  width=\"30%\" class=\"".$this->SetStyle("Observaciones")."\">OBSERVACIONES AUTORIZACION: </td>";
				$obs='';
				if(!empty($_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_urgencias']))
				{ $obs='PACIENTE EN MES DE URGENCIAS<br>'; }
				$this->salida .= "  <td><textarea  cols=\"80\" rows=\"4\" class=\"textarea\" name=\"ObservacionesA\">$obs".$_SESSION['AUTORIZACIONES']['ObservacionesA']."</textarea></td>";
				$this->salida .= "  </tr><br>";
				$this->salida .= "     </table>";
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
		
				$this->salida .= "<br><table border=\"0\" cellspacing=\"3\" cellpadding=\"2\" width=\"60%\" align=\"center\">";
				$this->salida .= "  <tr>";
				$this->salida .= "  <td align=\"center\" width=\"25%\"><input class=\"input-submit\" type=\"submit\" name=\"Autorizar\" value=\"AUTORIZAR\"></td>";
				$this->salida .= "  <td align=\"center\" width=\"25%\"><input class=\"input-submit\" type=\"submit\" name=\"NoAutorizar\" value=\"NO AUTORIZAR\"></td>";
				$this->salida .= "      </form>";
				if($_SESSION['AUTORIZACIONES']['AUTORIZAR']['TIPO_SERVICIO']!='CAJARAPIDA')
				{
						$accion=ModuloGetURL('app','AutorizacionQx','user','FormaTramite');
						$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
						$this->salida .= "  <td align=\"center\" width=\"25%\"><input class=\"input-submit\" type=\"submit\" name=\"Tramite\" value=\"TRAMITE\"></td>";
						$this->salida .= "      </form>";
				}
				$accion=ModuloGetURL('app','AutorizacionQx','user','RetornarAutorizacion');
				$this->salida .= "  <form name=\"forma2\" action=\"$accion\" method=\"post\">";
				$this->salida .= "  <td align=\"center\" width=\"25%\"><input class=\"input-submit\" type=\"submit\" name=\"Cancelar\" value=\"CANCELAR\"></td>";
				$this->salida .= "      </form>";
				$this->salida .= "  </tr>";
				$this->salida .= "  </table>";
				$this->salida .= "      </form>";
				$this->salida .= ThemeCerrarTabla();
				return true;
  }
	
	function FormaEstancia($estancia)
	{
				$this->salida .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"2\" width=\"80%\" align=\"center\">";
				$this->salida .= "    <tr class=\"modulo_table_list_title\">";
				$this->salida .= "    <td colspan=\"4\">ESTANCIAS SOLICITADA</td>";
				$this->salida .= "    </tr>";
				$this->salida.="<tr align=\"center\" class=\"modulo_table_title\">";				
				$this->salida .= "    <td width=\"15%\">CLASE</td>";		
				$this->salida .= "    <td width=\"15%\">TIPO</td>";		
				$this->salida .= "    <td width=\"5%\">DIAS</td>";				
				$this->salida .= "    <td width=\"65%\"></td>";																						
				$this->salida .= "  </tr>";				
				for($j=0; $j<sizeof($estancia); $j++)				
				{
						if( $j % 2){ $estilo='modulo_list_claro';}
						else {$estilo='modulo_list_oscuro';}
						$this->salida.="<tr align=\"center\" class=\"$estilo\">";				
						$this->salida .= "    <td align=\"center\">".$estancia[$j][descripcion]."</td>";		
						if($estancia[$j][sw_pre_qx]==1)
						{  $tipo='PREQUIRURGICA';  }
						if($estancia[$j][sw_pos_qx]==1)		
						{  $tipo='POSQUIRURGICA';  }
						$this->salida .= "    <td align=\"center\">".$tipo."</td>";		
						$this->salida .= "    <td>".$estancia[$j][cantidad_dias]."</td>";
						if(!empty($estancia[$j][autorizacion]))	
						{  $this->salida .= "    <td class=\"label_mark\">".RetornarWinOpenDatosAutorizacion($estancia[$j][autorizacion],$estancia[$j][autorizacion],'AUTORIZADO '.$estancia[$j][autorizacion])."<BR>".$estancia[$j][tipocama]."</td>";  }						
						else			
						{  
								$this->salida .= "    <td>";
								$this->salida .= " <table border=\"1\" cellspacing=\"1\" cellpadding=\"2\" width=\"100%\" align=\"center\">";
								$tipo=$this->TiposCamas($estancia[$j][tipo_clase_cama_id]);
								for($d=0; $d<sizeof($tipo); $d++)
								{
									$this->salida .= "    <tr class='modulo_list_claro'>";								
									$this->salida .= "    <td class='normal_10' width=\"95%\">".$tipo[$d][descripcion]."</td>";		
									$this->salida .= "    <td width=\"5%\"><input type='radio' name='radio' value=\"".$tipo[$d][tipo_cama_id]."\"></td>";										
									$this->salida .= "    </tr>";																
								}
								$this->salida .= "  </table>";								
								$this->salida .= "    </td>";  
						}
						$this->salida .= "  </tr>";
				}
				$this->salida .= "  </table>";
	}		
	
			
	function FormaProductos($prod)
	{
				$this->salida .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"2\" width=\"80%\" align=\"center\">";
				$this->salida .= "    <tr class=\"modulo_table_list_title\">";
				$this->salida .= "    <td colspan=\"4\">APOYOS SOLICITADOS</td>";
				$this->salida .= "    </tr>";
				$this->salida.="<tr align=\"center\" class=\"modulo_table_title\">";				
				$this->salida .= "    <td width=\"10%\">CODIGO</td>";		
				$this->salida .= "    <td width=\"74%\">DESCRIPCION</td>";		
				$this->salida .= "    <td width=\"6%\">CANTIDAD</td>";				
				$this->salida .= "    <td width=\"10%\"></td>";																						
				$this->salida .= "  </tr>";				
				for($j=0; $j<sizeof($prod); $j++)				
				{
						if( $j % 2){ $estilo='modulo_list_claro';}
						else {$estilo='modulo_list_oscuro';}
						$this->salida.="<tr align=\"center\" class=\"$estilo\">";				
						$this->salida .= "    <td>".$prod[$j][codigo_producto]."</td>";		
						$this->salida .= "    <td align=\"left\">".$prod[$j][descripcion]."</td>";		
						$this->salida .= "    <td>".$prod[$j][cantidad]."</td>";
						if(!empty($prod[$j][autorizacion]))	
						{  $this->salida .= "    <td class=\"label_mark\">".RetornarWinOpenDatosAutorizacion($prod[$j][autorizacion],$prod[$j][autorizacion],'AUTORIZADO '.$prod[$j][autorizacion])."</td>";  }						
						else			
						{  $this->salida .= "    <td><input type = checkbox name=\"\" value =\"\"></td>";  }
						$this->salida .= "  </tr>";
				}
				$this->salida .= "  </table>";
	}		
		
	function FormaApoyos($apoyos)
	{
				$this->salida .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"2\" width=\"80%\" align=\"center\">";
				$this->salida .= "    <tr class=\"modulo_table_list_title\">";
				$this->salida .= "    <td colspan=\"4\">APOYOS SOLICITADOS</td>";
				$this->salida .= "    </tr>";
				$this->salida.="<tr align=\"center\" class=\"modulo_table_title\">";				
				$this->salida .= "    <td width=\"10%\">CARGO</td>";		
				$this->salida .= "    <td width=\"74%\">DESCRIPCION</td>";		
				$this->salida .= "    <td width=\"6%\">CANTIDAD</td>";				
				$this->salida .= "    <td width=\"10%\"></td>";																						
				$this->salida .= "  </tr>";				
				for($j=0; $j<sizeof($apoyos); $j++)				
				{
						if( $j % 2){ $estilo='modulo_list_claro';}
						else {$estilo='modulo_list_oscuro';}
						$this->salida.="<tr align=\"center\" class=\"$estilo\">";				
						$this->salida .= "    <td>".$apoyos[$j][cargo]."</td>";		
						$this->salida .= "    <td align=\"left\">".$apoyos[$j][descripcion]."</td>";		
						$this->salida .= "    <td>".$apoyos[$j][cantidad]."</td>";
						if(!empty($apoyos[$j][autorizacion]))	
						{  $this->salida .= "    <td class=\"label_mark\">".RetornarWinOpenDatosAutorizacion($apoyos[$j][autorizacion],$apoyos[$j][autorizacion],'AUTORIZADO '.$apoyos[$j][autorizacion])."</td>";  }						
						else			
						{  $this->salida .= "    <td><input type = checkbox name=\"\" value =\"\"></td>";  }
						$this->salida .= "  </tr>";
				}
				$this->salida .= "  </table>";
	}
	
	function FormaProcedimientos($pro)
	{
				$this->salida .= "<table border=\"0\" cellspacing=\"3\" cellpadding=\"2\" width=\"80%\" align=\"center\">";
				$this->salida .= "    <tr class=\"modulo_table_list_title\">";
				$this->salida .= "    <td colspan=\"3\">PROCEDIMIENTOS SOLICITADOS</td>";
				$this->salida .= "    </tr>";
				$this->salida.="<tr align=\"center\" class=\"modulo_table_title\">";
				$this->salida .= "    <td width=\"10%\">CARGO</td>";		
				$this->salida .= "    <td width=\"80%\">DESCRIPCION</td>";	
				$this->salida .= "    <td width=\"10%\"></td>";																						
				$this->salida .= "  </tr>";				
				for($j=0; $j<sizeof($pro); $j++)				
				{
						if( $j % 2){ $estilo='modulo_list_claro';}
						else {$estilo='modulo_list_oscuro';}
						$this->salida.="<tr align=\"center\" class=\"$estilo\">";				
						$this->salida .= "    <td>".$pro[$j][cargo]."</td>";		
						$this->salida .= "    <td align=\"left\">".$pro[$j][descripcion]."</td>";		
						if(!empty($pro[$j][autorizacion]))	
						{  $this->salida .= "    <td class=\"label_mark\">".RetornarWinOpenDatosAutorizacion($pro[$j][autorizacion],$pro[$j][autorizacion],'AUTORIZADO '.$pro[$j][autorizacion])."</td>";  }						
						else			
						{  $this->salida .= "    <td><input type = checkbox name=\"\" value =\"\"></td>";  }
						$this->salida .= "  </tr>";
				}
				$this->salida .= "  </table>";
	}	

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
          $this->salida .= "       </table>";
      }
      else
      {
          $this->salida .= "    <input type=\"hidden\" name=\"Si\" value=\"1\">";
          $this->salida .= "    <table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
          $tipo_afiliado=$this->Tipo_Afiliado();
          $this->salida .= "          <tr>";
          $TipoAfiliado=$_SESSION['AUTORIZACIONES']['AFILIADO'];
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
              $this->salida .= "            <td><input type=\"hidden\"  class=\"input-text\" name=\"TipoAfiliado\" value=\"".$_SESSION['AUTORIZACIONES']['RETORNO']['ARREGLO']['campo_tipo_afiliado']."\">".$NomAfi[tipo_afiliado_nombre]."</td>";
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
          $this->salida .= "       </table>";
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
        $accion=ModuloGetURL('app','AutorizacionQx','user','InsertarTipoAutorizacion',array('Tipo'=>$Tipo));
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
        //$var=$this->BuscarAutorizaciones('autorizaciones_electronicas');
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


	
  function BuscarIdTipoAfiliado($tipo_afiliado,$TipoAfiliado='')
  {
        $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
        for($i=0; $i<sizeof($tipo_afiliado); $i++)
        {
          if($tipo_afiliado[$i][tipo_afiliado_id]==$TipoAfiliado){
           $this->salida .=" <option value=\"".$tipo_afiliado[$i][tipo_afiliado_id]."\" selected>".$tipo_afiliado[$i][tipo_afiliado_nombre]."</option>";
          }
          if($tipo_afiliado[$i][tipo_afiliado_id]==$_SESSION['SOLICITUDAUTORIZACION']['AFILIADO'][$tipo_afiliado[$i][tipo_afiliado_id]]){
           $this->salida .=" <option value=\"".$tipo_afiliado[$i][tipo_afiliado_id]."\" selected>".$tipo_afiliado[$i][tipo_afiliado_nombre]."</option>";
          }
          else{
           $this->salida .=" <option value=\"".$tipo_afiliado[$i][tipo_afiliado_id]."\">".$tipo_afiliado[$i][tipo_afiliado_nombre]."</option>";
          }
        }
  }

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
//-----------------------------------------------------------------------------------
}//fin clase

?>


