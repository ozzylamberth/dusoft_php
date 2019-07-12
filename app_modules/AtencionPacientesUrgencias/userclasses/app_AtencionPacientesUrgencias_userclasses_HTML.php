<?php

/**
 * $Id: app_AtencionPacientesUrgencias_userclasses_HTML.php,v 1.4 2006/02/16 17:08:40 lorena Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 *
 */

class app_AtencionPacientesUrgencias_userclasses_HTML extends app_AtencionPacientesUrgencias_user
{

    function app_AtencionPacientesUrgencias_user_HTML()
    {
      $this->app_AtencionPacientesUrgencias_user(); //Constructor del padre 'modulo'
        $this->salida='';
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

    function PantallaInicial()
    {
        $this->ReturnMetodoExterno('app', 'EstacionEnfermeria', 'user', '',array('modulo_externo'=>'AtencionPacientesUrgencias','metodo_externo'=>'Pantalla_de_Opciones'));
        return true;
				//ListadoPaciente
    }




		function Pantalla_de_Opciones()
    {
			IncludeLib('funciones_admision');
			unset($_SESSION['CONSULTORIO']);
			if(!empty($_REQUEST['AtencionUrgencias']['empresa_id']))
      {
            $_SESSION['AtencionUrgencias']=$_REQUEST['AtencionUrgencias'];
            $_SESSION['url_origen']=$_REQUEST['url_origen'];
      }
			$this->salida .= ThemeMenuAbrirTabla("".$_SESSION['AtencionUrgencias']['descripcion5']."","50%");
			$this->salida.="<table align='center' border='0' width='95%' cellpadding=\"4\" cellspacing=\"4\">";

    	$conteo_cons=FormatoValor($this->Get_Conteo_Pacientes_Consulta($_SESSION['AtencionUrgencias']['estacion_id']));
			if($conteo_cons > 0)
			{
				$cons=''; $cant=0;
				$cons=BuscarConsultoriosEstacion($_SESSION['AtencionUrgencias']['estacion_id']);
				if(!empty($cons))
				{
						$this->salida.="	<tr class='modulo_list_claro'>";
						$this->salida.="		<td  width='2%' align='center' class='normal_10N'></td>";
						$this->salida.="		<td width='68%' align='left'><b>PACIENTES EN CONSULTA</b></td>";
						$this->salida.="	</tr>";
						for($d=0; $d<sizeof($cons); $d++)
						{
								$cant=CantidadPacientesUrgenciasConsultorios($cons[$d][paciente_urgencia_consultorio_id],$_SESSION['AtencionUrgencias']['estacion_id']);
								if($cant >0)
								{
										$this->salida.="	<tr class='modulo_list_oscuro'>";
										$this->salida.="		<td width='2%' align='center'>&nbsp;</td>";
										$this->salida.="		<td width='68%' align='left'>";
										$nomCons='';
										if(!empty($cons[$d][descripcion]) AND !empty($cons[$d][descripcion2]))
										{  $nomCons=$cons[$d][descripcion].' - '.$cons[$d][descripcion2];  }
										elseif(!empty($cons[$d][descripcion]))
										{  $nomCons=$cons[$d][descripcion];   }
										elseif(!empty($cons[$d][descripcion2]))
										{  $nomCons=$cons[$d][descripcion2];   }
										$this->salida.="			<img src=\"". GetThemePath() ."/images/editar.gif\"><a href=\"".ModuloGetURL('app','AtencionPacientesUrgencias','user','Pac_consultas_Urgencias',array("estacion"=>$_SESSION['AtencionUrgencias'],'consultorio'=>$cons[$d][paciente_urgencia_consultorio_id]))."\">&nbsp;<b>".$nomCons."</b></a> ";
										$this->salida.="		 &nbsp;pacientes&nbsp;($cant)</td>";
										$his->salida.="	</tr>";
								}
						}
						$this->salida.="	<tr class='modulo_list_oscuro'>";
						$this->salida.="		<td width='2%' align='center'>&nbsp;</td>";
						$this->salida.="		<td width='68%' align='left'>";
						$this->salida.="			<img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;Numero de pacientes &nbsp; (".$conteo_cons.")";
						$this->salida.="		</td>";
						$this->salida.="	</tr>";
				}
				else
				{
						$this->salida.="	<tr class='modulo_list_claro'>";
						$this->salida.="		<td  width='2%' align='center' class='normal_10N'>";
						$this->salida.="			<img src=\"". GetThemePath() ."/images/editar.gif\">&nbsp;&nbsp;";
						$this->salida.="		</td>";
						$this->salida.="		<td width='68%' align='left'><a href=\"".ModuloGetURL('app','AtencionPacientesUrgencias','user','Pac_consultas_Urgencias',array("estacion"=>$_SESSION['AtencionUrgencias']))."\"><b>PACIENTES EN CONSULTA</b></a></td>";
						$this->salida.="	</tr>";
						$this->salida.="	<tr class='modulo_list_oscuro'>";
						$this->salida.="		<td width='2%' align='center'>&nbsp;</td>";
						$this->salida.="		<td width='68%' align='left'>";
						$this->salida.="			<img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;Numero de pacientes &nbsp; (".$conteo_cons.")";
						$this->salida.="		</td>";
						$this->salida.="	</tr>";
				}
		 }else{$contador=$contador +1;}


		  $conteo_admin=FormatoValor($this->Get_Conteo_Pacientes_Admision($_SESSION['AtencionUrgencias']['estacion_id']));
			if($conteo_admin > 0)
			{
				$this->salida.="	<tr class='modulo_list_claro'>";
				$this->salida.="		<td  width='2%' align='center' class='normal_10N'>";
				$this->salida.="			<img src=\"". GetThemePath() ."/images/editar.gif\">&nbsp;&nbsp;";
				$this->salida.="		</td>";
				$this->salida.="		<td width='68%' align='left'><a href=\"".ModuloGetURL('app','AtencionPacientesUrgencias','user','ListadoPacientesConfirmarAdmision',array("estacion"=>$_SESSION['AtencionUrgencias']))."\"><b>PACIENTES PARA CONFIRMAR ADMISION</b></a></td>";
				$this->salida.="	</tr>";
				$this->salida.="	<tr  class='modulo_list_oscuro'>";
				$this->salida.="		<td width='2%' align='center'>&nbsp;</td>";
				$this->salida.="		<td width='68%' align='left'>";
				$this->salida.="			<img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;Numero de pacientes &nbsp; (".$conteo_admin.")";
				$this->salida.="		</td>";
				$this->salida.="	</tr>";
			}else{$contador=$contador +1;}

			$conteo_remision=FormatoValor($this->Get_Conteo_Pacientes_remision());
			if($conteo_remision > 0 )
			{
				$this->salida.="	<tr class='modulo_list_claro'>";
				$this->salida.="		<td  width='2%' align='center' class='normal_10N'>";
				$this->salida.="			<img src=\"". GetThemePath() ."/images/editar.gif\">&nbsp;&nbsp;";
				$this->salida.="		</td>";
				$this->salida.="		<td width='68%' align='left'><a href=\"".ModuloGetURL('app','AtencionPacientesUrgencias','user','ListadoPacientesClasificar',array("estacion"=>$_SESSION['AtencionUrgencias']))."\"><b>PACIENTES PARA CONFIRMAR REMISION</b></a></td>";
				$this->salida.="	</tr>";
				$this->salida.="	<tr  class='modulo_list_oscuro'>";
				$this->salida.="		<td width='2%' align='center'>&nbsp;</td>";
				$this->salida.="		<td width='68%' align='left'>";
				$this->salida.="			<img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;Numero de pacientes &nbsp; (".$conteo_remision.")";
				$this->salida.="		</td>";
				$this->salida.="	</tr>";
			}else{$contador=$contador +1;}


			$arr=$this->PacientesAtendidosTriage();
  		$nom=UserGetVars(UserGetUID());
			if(sizeof($arr) > 0)
			{
				$this->salida.="	<tr class='modulo_list_claro'>";
				$this->salida.="		<td  width='2%' align='center' class='normal_10N'>";
				$this->salida.="			<img src=\"". GetThemePath() ."/images/editar.gif\">&nbsp;&nbsp;";
				$this->salida.="		</td>";
				$this->salida.="		<td width='68%' align='left'><a href=\"".ModuloGetURL('app','AtencionPacientesUrgencias','user','ListadoPacientesAtendidosTriage',array("estacion"=>$_SESSION['AtencionUrgencias']))."\"><b>PACIENTES CLASIFICADOS POR &nbsp;".$nom['nombre']."</b></a></td>";
				$this->salida.="	</tr>";
				$this->salida.="	<tr  class='modulo_list_oscuro'>";
				$this->salida.="		<td width='2%' align='center'>&nbsp;</td>";
				$this->salida.="		<td width='68%' align='left'>";
				$this->salida.="			<img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;Numero de pacientes &nbsp; (".FormatoValor(sizeof($arr)).")";
				$this->salida.="		</td>";
				$this->salida.="	</tr>";
		  }else{$contador=$contador +1;}


			if($contador == 4)
			{
				$this->salida.="	<tr align=center>";
			  $this->salida.="		<td><label class='label_mark'>NO HAY INFORMACIÓN PARA &nbsp;".$nom['nombre']."</label></td></tr>";
			}

			$this->salida.="</table>";

			$this->salida.="<table align=\"center\" width='20%' border=\"0\">";
			$action2=ModuloGetURL('app','AtencionPacientesUrgencias','user','main');
			$this->salida .= "           <form name=\"formados\" action=\"$action2\" method=\"post\">";
			$this->salida .= "    <td  align=\"center\"><br><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"Volver\"></form></td>";
			$this->salida .= "</tr>";
			$this->salida.="</table><br>";
			$this->salida .= ThemeMenuCerrarTabla();

			$this->salida.="</table>\n";
	 return true;
	}







		/**
		*
		*/
		function FormaSacarLista($tipoid,$id,$nombre,$triage,$ingreso)
		{
					$this->salida .= ThemeAbrirTabla('SACAR PACIENTE LISTADO');
					//mensaje
					$this->salida .= "       <table border=\"0\" width=\"90%\" align=\"center\">";
					$this->salida .= $this->SetStyle("MensajeError");
					$this->salida .= "  </table><BR>";
					$accion=ModuloGetURL('app','AtencionPacientesUrgencias','user','SacarPaciente',array('triage_id'=>$triage,'nombre'=>$nombre,'tipo_id_paciente'=>$tipoid,'paciente_id'=>$id,'ingreso'=>$ingreso));
					$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
					$this->salida .= "			      <table width=\"60%\" align=\"center\" >";
					$this->salida .= "				       <tr>";
					$this->salida .= "				       <td align=\"center\" class=\"label_MARK\" colspan=\"2\">IDENTIFICACION: ".$tipoid." ".$id."<BR>PACIENTE: ".$nombre."<BR>
					EL PACIENTE SERA SACADO DEL LISTADO Y SE CANCELARA SU PROCESO DE ATENCION EN LA INSTITUCION <BR>
					POR FAVOR ESPECIFIQUE EL MOTIVO</td>";
					$this->salida .= "              </tr>";
					$this->salida .= "				       <tr>";
					$this->salida .= "				       <td align=\"center\" class=\"label\">OBSERVACION: </td>";
					$this->salida .= "				       <td align=\"center\"><textarea cols=\"70\" rows=\"3\" class=\"textarea\"name=\"observacion\"></textarea>";
					$this->salida .= "              </tr>";
					$this->salida .= "			     </table>";
					$this->salida .= "			      <table width=\"50%\" align=\"center\" >";
					$this->salida .= "				       <tr>";
					$this->salida .= "				       <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"ACEPTAR\"></td>";
					$this->salida .= "			     </form>";

          if($_SESSION['ATENCION']['URGENCIAS']['SWITCH']=='admision')
				  {$accion=ModuloGetURL('app','AtencionPacientesUrgencias','user','ListadoPacientesConfirmarAdmision');}

          if($_SESSION['ATENCION']['URGENCIAS']['SWITCH']=='consulta')
            {$accion=ModuloGetURL('app','AtencionPacientesUrgencias','user','Pac_consultas_Urgencias');}

					if($_SESSION['ATENCION']['URGENCIAS']['SWITCH']=='remision')
					{$accion=ModuloGetURL('app','AtencionPacientesUrgencias','user','ListadoPacientesClasificar');}

					$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
					$this->salida .= "				       <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"CANCELAR\"></td>";
					$this->salida .= "              </tr>";
					$this->salida .= "			     </form>";
					$this->salida .= "			     </table>";
					$this->salida .= ThemeCerrarTabla();
					return true;
		}



    function ListadoPacienteUrgencias()
    {
        $modulo=$this->TipoModulo('consulta_urgencias');
        if($modulo==false)
        {
            return false;
        }
        $DatosEstacion=$this->BuscarPacientesEstacion();
        $prueba=$this->ReconocerProfesional();
        if($prueba==1 or $prueba==2)
        {
            if($DatosEstacion)
            {
                $this->SetJavaScripts('DatosPaciente');
                $this->salida .= "<BR>";
                $this->salida .= '<table width="80%" align="center" border="0" class="modulo_table">';
                $this->salida .= '<tr align="center" class="modulo_table_title">';
                $this->salida .= '<td>';
                $this->salida .= "PACIENTES PARA CONSULTA EN URGENCIAS";
                $this->salida .= "</td>";
                $this->salida .= "</tr>";
                $this->salida .= '<tr align="center">';
                $this->salida .= '<td align="center">';
                $this->salida .= '<table width="100%" align="center" class="modulo_table_list" border="1">';
                $this->salida .= '<tr align="center" class="modulo_table_list_title">';
                $this->salida .= '<td align="center" width="35%">';
                $this->salida .= "Pacientes";
                $this->salida .= "</td>";
                $this->salida .= '<td align="center">';
                $this->salida .= "Tiempo en Espera";
                $this->salida .= "</td>";
                $this->salida .= '<td align="center">';
                $this->salida .= "Fecha Evolucion";
                $this->salida .= "</td>";
                $this->salida .= '<td align="center">';
                $this->salida .= "Profesional";
                $this->salida .= "</td>";
                $this->salida .= '<td align="center">';
                $this->salida .= "Acción";
                $this->salida .= "</td>";
            		$this->salida .= '<td align="center" width="10%">Sacar Listado</td>';
                $this->salida .= "</tr>";
                $spy=0;
                foreach($DatosEstacion as $k=>$v)
                {
                    foreach($v as $t=>$r)
                    {
                        foreach($r as $p=>$h)
                        {
                            $s=0;
                            $prof=0;
                            foreach($h as $i=>$j)
                            {
                                if($s==0)
                                {
                                    if(!empty($j[0]))
                                    {
                                        $this->salida.='<tr align="center" class="'.$j[2].'">';
                                        $dato='<tr align="center" class="'.$j[2].'">';
                                    }
                                    else
                                    {
                                        if(empty($j[0]) or $j[0]==1)
                                        {
                                            if($spy==0)
                                            {
                                                $this->salida.='<tr align="center" class="modulo_list_claro">';
                                                $dato='<tr align="center" class="modulo_list_claro">';
                                                $spy=1;
                                            }
                                            else
                                            {
                                                $this->salida.='<tr align="center" class="modulo_list_oscuro">';
                                                $dato='<tr align="center" class="modulo_list_oscuro">';
                                                $spy=0;
                                            }
                                        }
                                    }
                                    $this->salida .= "<td>";
                                    $open=RetornarWinOpenDatosPaciente($t,$k,$p);
                                    $this->salida .=$open;
                                    $this->salida .= "</td>";
                                    $this->salida .= "<td>";
                                    if($j[0]==1)
                                    {
                                        $this->salida .="<label class=\"label_error\">";
                                    }
                                    $this->salida .=$j[1];
                                    if($j[0]==1)
                                    {
                                        $this->salida .="</label>";
                                    }
                                    $this->salida .= "</td>";
                                    $s=1;
                                }
                                $salida1 .= '<table width="100%" align="center" border="0">';
                                $salida1.=$dato;
                                $salida1 .= "<td>";
                                $salida1 .=$j[5];
                                $salida1 .= "</td>";
                                $salida1 .= "</tr>";
                                $salida1 .= '</table>';
                                $salida2 .= '<table width="100%" align="center" border="0">';
                                $salida2.=$dato;
                                $salida2 .= "<td>";
                                $salida2 .=$j[7];
                                $salida2 .= "</td>";
                                $salida2 .= "</tr>";
                                $salida2 .= '</table>';
                                $salida3 .= '<table width="100%" align="center" border="0">';
                                $salida3.=$dato;
                                $salida3 .= "<td>";
                                if(empty($j[6]))
                                {
                                    if($j[9]==='0')
                                    {
                                        $accion=ModuloGetURL('app','AtencionPacientesUrgencias','user','ClasificarTriage', array('tipo_id_paciente'=>$t, 'paciente_id'=>$k, 'plan_id'=>$j[10], 'triage_id'=>$j[11], 'punto_triage_id'=>$j[12], 'punto_admision_id'=>$j[13], 'sw_no_atender'=>$j[14], 'ingreso'=>$j[3], 'moduloh'=>$modulo, 'estacion_id'=>$j[8]));
                                    }
                                    else
                                    {
                                        $accion=ModuloHCGetURL(0,'',$j[15],$modulo,$modulo,array('estacion'=>$j[8],'HC_DATOS_CONTROL'=>array('CONTROL'=>'CONSULTA_URGENCIAS','ESTACION'=>$j[8])));
                                    }
                                    $salida3 .="<a href='$accion'>Atender</a>";
                                    $prof=1;
                                }
                                else
                                {
                                    if($j[6]==$_SESSION['SYSTEM_USUARIO_ID'])
                                    {
                                        $accion=ModuloHCGetURL($j[4],'',0,$modulo,$modulo,array('estacion'=>$j[8],'HC_DATOS_CONTROL'=>array('CONTROL'=>'CONSULTA_URGENCIAS','ESTACION'=>$j[8])));
                                        $salida3 .="<a href='$accion'>Continuar Atencion</a>";
                                        $prof=1;
                                    }
                                    else
                                    {
                                        $salida3 .="Otro Profesional";
                                    }
                                }
                                $salida3 .= "</td>";
                                $salida3 .= "</tr>";
                                $salida3 .= '</table>';
                            }
                            $this->salida .= "<td valign='top'>";
                            $this->salida.=$salida1;
                            $this->salida .= "</td>";
                            $this->salida .= "<td valign='top'>";
                            $this->salida.=$salida2;
                            $this->salida .= "</td>";
                            $this->salida .= "<td valign='top'>";
                            if($prof==0)
                            {
                                $salida3 .='<table width="100%" align="center" border="0">';
                                $salida3.=$dato;
                                $salida3 .= "<td>";
                                $accion=ModuloHCGetURL(0,'',$j[15],$modulo,$modulo,array('estacion'=>$j[8],'HC_DATOS_CONTROL'=>array('CONTROL'=>'CONSULTA_URGENCIAS','ESTACION'=>$j[8])));
                                $salida3.='<a href="'.$accion.'">Nueva Atencion</a>';
                                $salida3 .= "</td>";
                                $salida3 .= "</tr>";
                                $salida3 .= '</table>';
                            }
                            $this->salida.=$salida3;
                            $this->salida .= "</td>";
														//sacar listado
														$this->salida .= '<td align="center">';
														$accionS=ModuloGetURL('app','AtencionPacientesUrgencias','user','SacarPacienteLista',array('nombre'=>$p,'paciente_id'=>$k, 'tipo_id_paciente'=>$t,'ingreso'=>$j[3],'lista'=>1));
														$this->salida.='<a href="'.$accionS.'">Sacar</a>';
														$this->salida .= "</td>";
                            $this->salida .= "</tr>";
                            $salida='';
                            $salida1='';
                            $salida2='';
                            $salida3='';
                        }
                    }
                }
                $this->salida .= '</table>';
                $this->salida .= "</td>";
                $this->salida .= "</tr>";
                $this->salida .= '</table>';
            }
            else
            {
                $this->salida .= '<table width="80%" align="center">';
                $this->salida .= '<tr align="center">';
                $this->salida .= '<td align="center">';
                $this->salida .= '<label class="label_error">NO HAY PACIENTES PARA ATENDER EN URGENCIAS</label>';
                $this->salida .= "</td>";
                $this->salida .= "</tr>";
                $this->salida .= "</table>";
            }
            $this->salida .= "<BR>";
        }
        return true;
    }


    function ContinuarHistoria()
    {
        $this->salida.="<script>\n";
        $this->salida.="location.href=\"".ModuloHCGetURL(0,'',$_SESSION['Atencion']['ingreso'],$_SESSION['Atencion']['modulo'],$_SESSION['Atencion']['modulo'],array('estacion'=>$_SESSION['Atencion']['estacion_id']))."\";\n";
        $this->salida.="</script>\n";
        return true;
    }







    function ListadoPaciente()
    {
        $_SESSION['HISTORIACLINICA']['RETORNO']['modulo']='AtencionPacientesUrgencias';
        $_SESSION['HISTORIACLINICA']['RETORNO']['metodo']='ListadoPaciente';
        $_SESSION['HISTORIACLINICA']['RETORNO']['tipo']='user';
        $_SESSION['HISTORIACLINICA']['RETORNO']['contenedor']='app';

        $prueba=$this->ReconocerProfesional();
        $hospitaesta1=$this->BuscarPacienteHosptalizados();
        $hospitaesta=$hospitaesta1[0];
        $DatosHospitalizacion=$hospitaesta1[1];
        $this->salida = ThemeAbrirTabla('PACIENTES PARA ATENDER');
        $this->salida .= "<BR>";
        $this->salida .= '<table width="80%" align="center" border="0" class="modulo_table_list">';
        $this->salida .= '<tr align="center" class="modulo_table_list_title">';
        $this->salida .= '<td align="center">';
        $this->salida .= "Empresa";
        $this->salida .= "</td>";
        $this->salida .= '<td align="center">';
        $this->salida .= "Departamento";
        $this->salida .= "</td>";
        $this->salida .= '<td align="center">';
        $this->salida .= "Estación de Enfermeria";
        $this->salida .= "</td>";
        $this->salida .= "</tr>";
        $this->salida .= '<tr class="modulo_list_oscuro">';
        $this->salida .= '<td align="center">';
        $this->salida .= $_SESSION['AtencionUrgencias']['descripcion1'];
        $this->salida .= "</td>";
        $this->salida .= '<td align="center">';
        $this->salida .= $_SESSION['AtencionUrgencias']['descripcion3'];
        $this->salida .= "</td>";
        $this->salida .= '<td align="center">';
        $this->salida .= $_SESSION['AtencionUrgencias']['descripcion5'];
        $this->salida .= "</td>";
        $this->salida .= "</tr>";
        $this->salida .= "</table>";
        $this->salida .= "<BR>";

        if($DatosHospitalizacion)
        {
            $this->SetJavaScripts('DatosPaciente');
            $this->salida .= "<BR>";
            $this->salida .= "<BR>";
            $this->salida .= '<table width="80%" align="center" border="0" class="modulo_table">';
            $this->salida .= '<tr align="center" class="modulo_table_title">';
            $this->salida .= '<td>';
            $this->salida .= "PACIENTES HOSPITALIZADOS Y/O EN OBSERVACION EN LA ESTACION DE ENFERMERIA";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
            $this->salida .= '<tr align="center">';
            $this->salida .= '<td align="center">';
            $this->salida .= '<table width="100%" align="center" class="modulo_table_list" border="1">';
            $this->salida .= '<tr align="center" class="modulo_table_list_title">';
            $this->salida .= '<td align="center" width="35%">';
            $this->salida .= "Pacientes";
            $this->salida .= "</td>";
            $this->salida .= '<td align="center" width="10">';
            $this->salida .= "Pieza - Cama";
            $this->salida .= "</td>";
            $this->salida .= '<td align="center">';
            $this->salida .= "Fecha Evolución";
            $this->salida .= "</td>";
            $this->salida .= '<td align="center">';
            $this->salida .= "Nombre";
            $this->salida .= "</td>";
            $this->salida .= '<td align="center">';
            $this->salida .= "Acción";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
            $i=$spy=0;
            foreach($DatosHospitalizacion as $k=>$v)
            {
                foreach($v as $t=>$r)
                {
                    foreach($r as $p=>$q)
                    {
                        if($spy==0)
                        {
                            $this->salida.='<tr align="center" class="modulo_list_claro">';
                            $dato='<tr align="center" class="modulo_list_claro">';
                            $spy=1;
                        }
                        else
                        {
                            $this->salida.='<tr align="center" class="modulo_list_oscuro">';
                            $dato='<tr align="center" class="modulo_list_oscuro">';
                            $spy=0;
                        }
                        $this->salida .= '<td align="center">';
                        $open=RetornarWinOpenDatosPaciente($t,$k,$p);
                        $this->salida .=$open;
                        $this->salida .= "</td>";
                        $t=0;
                        $prof=0;
                        foreach($q as $h=>$j)
                        {
                            if($t==0)
                            {
                                $this->salida .= '<td align="center">';
                                $this->salida .=$j['cama'];
                                $this->salida .= "</td>";
                                $t=1;
                            }
                            $salida1 .= '<table width="100%" align="center" border="0">';
                            $salida1.=$dato;
                            $salida1 .= "<td>";
                            $salida1 .=$j['fecha'];
                            $salida1 .= "</td>";
                            $salida1 .= "</tr>";
                            $salida1 .= '</table>';
                            $salida2 .= '<table width="100%" align="center" border="0">';
                            $salida2.=$dato;
                            $salida2 .= "<td>";
                            $salida2 .=$j['nombre'];
                            $salida2 .= "</td>";
                            $salida2 .= "</tr>";
                            $salida2 .= '</table>';
                            $salida .= '<table width="100%" align="center" border="0">';
                            $salida.=$dato;
                            $salida .= "<td>";
                            if(empty($j['evolucion_id']))
                            {
                                $accion=ModuloHCGetURL(0,'',$j['numerodecuenta'],$_SESSION['AtencionUrgencias']['hc_modulo_medico'],$_SESSION['AtencionUrgencias']['hc_modulo_medico'],array('estacion'=>$_SESSION['AtencionUrgencias']['estacion_id'],'HC_DATOS_CONTROL'=>array('CONTROL'=>'HOSPITALIZACION','ESTACION'=>$j[8])));
                                $salida .="<a href='$accion'>Atender</a>";
                                $prof=1;
                            }
                            else
                            {
                                if($j['usuario_id']==$_SESSION['SYSTEM_USUARIO_ID'])
                                {
                                    $accion=ModuloHCGetURL($j['evolucion_id'],'',0,$_SESSION['AtencionUrgencias']['hc_modulo_medico'],$_SESSION['AtencionUrgencias']['hc_modulo_medico'],array('estacion'=>$_SESSION['AtencionUrgencias']['estacion_id'],'HC_DATOS_CONTROL'=>array('CONTROL'=>'HOSPITALIZACION','ESTACION'=>$j[8])));
                                    $salida .="<a href='$accion'>Continuar Atencion</a>";
                                    $prof=1;
                                }
                                else
                                {
                                    $salida .="Otro Profesional";
                                }
                            }
                            $salida .= "</td>";
                            $salida .= "</tr>";
                            $salida .= '</table>';
                        }
                        if($prof==0)
                        {
                            $salida .= '<table width="100%" align="center" border="0">';
                            $salida.=$dato;
                            $salida .= "<td>";
                            $accion=ModuloHCGetURL(0,'',$j['numerodecuenta'],$_SESSION['AtencionUrgencias']['hc_modulo_medico'],$_SESSION['AtencionUrgencias']['hc_modulo_medico'],array('estacion'=>$_SESSION['AtencionUrgencias']['estacion_id'],'HC_DATOS_CONTROL'=>array('CONTROL'=>'HOSPITALIZACION','ESTACION'=>$j[8])));
                            $salida .="<a href='$accion'>Nueva Atencion</a>";
                            $salida .= "</td>";
                            $salida .= "</tr>";
                            $salida .= '</table>';
                        }
                        $this->salida .= '<td align="center" valign="top">';
                        $this->salida .=$salida1;
                        $this->salida .= "</td>";
                        $this->salida .= '<td align="center" valign="top">';
                        $this->salida .=$salida2;
                        $this->salida .= "</td>";
                        $this->salida .= '<td align="center" valign="top">';
                        $this->salida .=$salida;
                        $this->salida .= "</td>";
                        $this->salida .= '</tr>';
                        $salida='';
                        $salida1='';
                        $salida2='';
                    }
                }
            }
            $this->salida .= '</table>';
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
            $this->salida .= '</table>';
        }
        else
        {
            $this->salida .= '<table width="80%" align="center">';
            $this->salida .= '<tr align="center">';
            $this->salida .= '<td align="center">';
            $this->salida .= '<label class="label_error">NO HAY PACIENTES PARA ATENDER EN HOSPITALIZACIÓN</label>';
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
            $this->salida .= "</table>";
        }
        $this->salida .= "<BR>";
        $this->ListadoPacienteUrgencias();
        $this->ListadoPacientesConfirmarAdmision();
        $this->ListadoPacientesClasificar();
				$this->ListadoPacientesAtendidosTriage();
        $this->salida .= "<BR>";
        $this->salida .='<table border="0" align="center" width="50%">';
        $this->salida .='<tr>';
        $this->salida .='<td align="center">';
        //print_r($_SESSION['url_origen']);
        if(!empty($_SESSION['url_origen']))
        {
            $accion=ModuloGetURL($_SESSION['url_origen']['contenedor'],$_SESSION['url_origen']['modulo'],$_SESSION['url_origen']['tipo'],$_SESSION['url_origen']['metodo'],array('estacion'=>$_SESSION['AtencionUrgencias']));
        }
        else
        {
            $accion=ModuloGetURL('app','AtencionPacientesUrgencias','','');
        }
        $this->salida .='<form name="volver" method="post" action="'.$accion.'">';
        $this->salida .='<input type="submit" name="VOLVER" value="VOLVER" class="input-submit">';
        $this->salida .='</form>';
        $this->salida .='</td>';
        $this->salida .='<td align="center">';
        $accion=ModuloGetURL('app','AtencionPacientesUrgencias','','ListadoPaciente');
        $this->salida .='<form name="volver" method="post" action="'.$accion.'">';
        $this->salida .='<input type="submit" name="REFRESCAR" value="REFRESCAR" class="input-submit">';
        $this->salida .='</form>';
        $this->salida .='</td>';
        $this->salida .='</tr>';
        $this->salida .='</table>';
        $this->salida .= "<BR>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }




/*funcion que debe estar en el mod estacione_controlpaciente*/
		/*
		*
		*
		*		@Author Jairo Duvan Diaz Martinez
		*		@access Private
		*		@return bool
		*/
		function ListRevisionPorSistemas($estacion)
		{


			$this->salida .= ThemeAbrirTabla("".$_SESSION['AtencionUrgencias']['descripcion5']."");
			$mostrar ="\n<script language='javascript'>\n";
			$mostrar.="function mOvr(src,clrOver) {;\n";
			$mostrar.="src.style.background = clrOver;\n";
			$mostrar.="}\n";

			$mostrar.="function mOut(src,clrIn) {\n";
			$mostrar.="src.style.background = clrIn;\n";
			$mostrar.="}\n";
			$mostrar.="</script>\n";
			$this->salida .="$mostrar";

			if(empty($_SESSION['AtencionUrgencias']))
			{
 					if(empty($estacion)){$estacion=$_REQUEST['estacion'];}
			}
			else
			{
				$estacion=$_SESSION['AtencionUrgencias'];
			}



			//variable de session para comunicarse con los controles programados
			// de la estación de enfermeria.
			$_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['modulo']='AtencionPacientesUrgencias';
			$_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['metodo']='ListRevisionPorSistemas';
			$_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['tipo']='user';
			$_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['contenedor']='app';
			$_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['argumentos']=array('estacion'=>$estacion);

			$_SESSION['HISTORIACLINICA']['RETORNO']['modulo']='AtencionPacientesUrgencias';
      $_SESSION['HISTORIACLINICA']['RETORNO']['metodo']='ListRevisionPorSistemas';
      $_SESSION['HISTORIACLINICA']['RETORNO']['tipo']='user';
      $_SESSION['HISTORIACLINICA']['RETORNO']['contenedor']='app';

			//AQUI ES PARA COMUNICARSE CON LA CENTRA DE IMPRESION DE ORDENES DE DAR.
			$_SESSION['CENTRALHOSP']['RETORNO']['modulo']='AtencionPacientesUrgencias';
			$_SESSION['CENTRALHOSP']['RETORNO']['metodo']='ListRevisionPorSistemas';
			$_SESSION['CENTRALHOSP']['RETORNO']['tipo']='user';
			$_SESSION['CENTRALHOSP']['RETORNO']['contenedor']='app';
			$_SESSION['CENTRALHOSP']['RETORNO']['argumentos']=array('estacion'=>$estacion);
			$datoscenso=$this->GetPacientesControles($estacion['estacion_id']);

			if($datoscenso=== "ShowMensaje")
			{
				$datoscenso='';//esto es para que entre al if
			}
			if(!empty($datoscenso))
			{
				//$this->salida .= ThemeAbrirTabla("NOTAS DE ENFERMERIA - [ ".$estacion['descripcion5']." ]");
				$w=$x=0;
				foreach($datoscenso as $key => $value)
				{//echo "<br>".$key;//
					if($key == "hospitalizacion")
					{
						if(!empty($_SESSION['AtencionUrgencias']['titulo_atencion_pacientes']))
						{
							$titulo=$_SESSION['AtencionUrgencias']['titulo_atencion_pacientes'];
						}
						else
						{
							$titulo="PACIENTES INTERNADOS";
						}
						$this->salida .= "<br><table align=\"center\" width=\"100%\"  border=\"0\" >\n";
						$this->salida .= "	<tr class=\"modulo_table_title\"><td colspan='12' height='30'>".$titulo."</td></tr>\n";
						$this->salida .= "	<tr class=\"modulo_table_title\">\n";
						$this->salida .= "		<td width=\"2%\"></td>\n";
						$this->salida .= "		<td width=\"2%\"><sub>HAB.</sub></td>\n";
						$this->salida .= "		<td width=\"2%\"><sub>CAMA</sub></td>\n";
						$this->salida .= "		<td width=\"3%\"><sub>TIEMPO<BR>HOSP.</sub></td>\n";
						$this->salida .= "		<td width=\"16%\"><sub>PACIENTE</sub></td>\n";
						$this->salida .= "		<td width=\"3%\"><sub>CONTROL</sub></td>\n";
						$this->salida .= "		<td width=\"3%\"><sub>APOYO</sub></td>\n";
						$this->salida .= "		<td width=\"3%\"><sub>MEDIC.</sub></td>\n";
  					$this->salida .= "		<td width=\"3%\"><sub>ORDEN</sub></td>\n";
						$this->salida .= "		<td width=\"14%\"><sub>FECHA<BR>EVOLUCIÓN</sub></td>\n";
						$this->salida .= "		<td width=\"29%\"><sub>MEDICO</sub></td>\n";
						$this->salida .= "		<td width=\"36%\"><sub>HISTORIA<BR> CLINICA</sub></td>\n";

						$this->salida .= "	</tr>\n";

						//mostramos los pacientes pendientes por ingresar .. si hay
						if($w==0)
						{
							$pacientes = $this->GetPacientesPendientesXHospitalizar($estacion);
							$this->Pacientes_X_Ingresar($estacion,$reporte,$pacientes);
							$w=1;
						}


						foreach($value as $A => $B)
						{
											$traslado=$this->Revisar_Si_esta_trasladado($B[ingreso]);
						  				$info=$this->RevisarSi_Es_Egresado($B[ingreso_dpto_id]);
																		//print_r($B);
											$conteo_lectura=$this->Revisar_Lectura_Examen_Para_Medico($B['tipo_id_paciente'],$B['paciente_id'],$B['ingreso']);
											$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');

											if($p++ % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
											$this->salida .= "<tr class='$estilo' onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";
											//$this->salida .= "	<td  align=\"center\"><a href='$urla'><img src=\"". GetThemePath() ."/images/honorarios.png\" border='0'></a></td>\n";

											//info nos dice si el egreso es 1 o 2 o 0 para asi colocar el estado del egreso.
											$linker=ModuloGetURL('app','EstacionE_Pacientes','user','CallListPacientesPorEgresar',array("datos_estacion"=>$estacion,"paciente_id"=>$B['paciente_id'],"tipo_id_paciente"=>$B['tipo_id_paciente'],"cama"=>$B['cama']));
											if($info[1]==2)//si es 2 egreso efectuado
											{
												$_SESSION['ESTACION_ENF']['CONTEO']['EGRESO']=$_SESSION['ESTACION_ENF']['CONTEO']['EGRESO']+ 1;
												$this->salida .= "	<td  align=\"center\"><img src=\"". GetThemePath() ."/images/egresook.png\" border='0'></td>\n";
											}
											elseif($info[1]=='1' OR $info[1]=='0')//es 1 enfermera-0 medico
											{
												$_SESSION['ESTACION_ENF']['CONTEO']['EGRESO']=$_SESSION['ESTACION_ENF']['CONTEO']['EGRESO']+ 1;
												//$this->salida .= "	<td  align=\"center\"><a href='$linker'><img src=\"". GetThemePath() ."/images/egreso.png\" border='0'></a></td>\n";
												$this->salida .= "	<td  align=\"center\"><img src=\"". GetThemePath() ."/images/egreso.png\" border='0'></td>\n";
										
											}
											else
											{
												if($traslado >0)
												{
													$_SESSION['ESTACION_ENF']['CONTEO']['HOSP']=$_SESSION['ESTACION_ENF']['CONTEO']['HOSP']+ 1;
													$this->salida .= "	<td  align=\"center\"><img src=\"". GetThemePath() ."/images/uf.png\" border='0'></td>\n";
												}
												else
												{
													$_SESSION['ESTACION_ENF']['CONTEO']['HOSP']=$_SESSION['ESTACION_ENF']['CONTEO']['HOSP']+ 1;
													$this->salida .= "	<td  align=\"center\"><img src=\"". GetThemePath() ."/images/honorarios.png\" border='0'></td>\n";
												}
											}
											//unset($info);
											$this->salida .= "	<td align=\"center\">".$B[pieza]."</td>\n";
											$this->salida .= "	<td align=\"center\">".$B[cama]."</td>\n";
											$diasHospitalizacion = $this->GetDiasHospitalizacion($B[fecha_ingreso]);
											$this->salida .= "	<td align=\"center\">".$diasHospitalizacion."</td>\n";

										//	$open=RetornarWinOpenDatosPaciente($t,$k,$p);
                    //  $this->salida .=$open;
											$nombre=$B[primer_nombre]." ".$B[segundo_nombre]." ".$B[primer_apellido]." ".$B[segundo_apellido];
										
										if($conteo_lectura == 0)
										{
											$xx="bestell.gif";
											$titulo="TIENE EXAMENES FIRMADOS  DEL PACIENTE &nbsp;$nombre &nbsp; SIN HABERLO LEIDO";
											$IMG="<img src=\"". GetThemePath() ."/images/$xx\" border='0' width='16' heigth='16'>";
										}
										else{$xx='';$titulo="";$IMG="";}
											$linkVerDatos = ModuloGetURL('app','EE_PanelEnfermeria','user','CallMostrarDatosIngreso',array("ingresoID"=>$B['ingreso'],"retorno"=>"CallListRevisionPorSistemas","modulito"=>'AtencionPacientesUrgencias',"datos_estacion"=>$estacion));

											$this->salida .= "	<td><a href='$linkVerDatos'>".$B[primer_nombre]." ".$B[segundo_nombre]." ".$B[primer_apellido]." ".$B[segundo_apellido]."</a><div title='$titulo'>$IMG</div></td>\n";



											//Esta Informacion es para el reporte de plantilla de signos vitales.
											//$_SESSION['ESTACION']['VECT'][]=array("pieza"=>$B['pieza'],"cama"=>$B['cama'],"NombrePaciente"=>$B['primer_nombre']." ".$B['segundo_nombre']." ".$B['primer_apellido']." ".$B['segundo_apellido'],"paciente_id"=>$B['paciente_id'],"tipo_id_paciente"=>$B['tipo_id_paciente'],"Hora"=>$tiempo,"ingreso"=>$B['ingreso'],"control_id"=>15,"control_descripcion"=>"CONTROL DE SIGNOS VITALES");


											$conteop=$this->CountControles($B['ingreso']);
											//REVISAR CONTROLES PROGRAMADOS
											$nombre=$B[primer_nombre]." ".$B[segundo_nombre]." ".$B[primer_apellido]." ".$B[segundo_apellido];
											$url = ModuloGetURL('app','EstacionE_ControlPacientes','user','Listado_controles',array("estacion"=>$estacion,"ingreso"=>$B[ingreso],"nombre"=>urlencode($nombre),"pieza"=>$B['pieza'],"cama"=>$B['cama']));
											//echo "<br>".$conteop.$B['ingreso'];
											if($conteop==1)
											{
												$imgp="resultado.png";
												$this->salida .= "	<td align=\"center\"><a href='$url'><img src=\"". GetThemePath() ."/images/$imgp\" border='0'>&nbsp;CP</a></td>\n";
											}else
											{
												$imgp="prangos.png";
												$this->salida .= "	<td align=\"center\"><img src=\"". GetThemePath() ."/images/$imgp\" border='0'>&nbsp;CP</td>\n";
											}
												unset($conteop);
											//TRANSFUSIONES
										//	$urlt = ModuloGetURL('app','EstacionE_ControlPacientes','user','CallFrmTransfusiones',array("estacion"=>$estacion,"datos_estacion"=>array("pieza"=>$B['pieza'],"cama"=>$B['cama'],"NombrePaciente"=>$B['primer_nombre']." ".$B['segundo_nombre']." ".$B['primer_apellido']." ".$B['segundo_apellido'],"paciente_id"=>$B['paciente_id'],"tipo_id_paciente"=>$B['tipo_id_paciente'],"ingreso"=>$B['ingreso'],"control_id"=>24,"control_descripcion"=>'CONTROL DE TRANSFUSIONES')));
										//	$this->salida .= "	<td align=\"center\"><a href='$urlt'><img src=\"". GetThemePath() ."/images/pparamedin.png\" border='0'>&nbsp;TR</a></td>\n";

											$centinela=0;
											//Traemos las fechas de los apoyos diagnosticos pendientes.
											$fech_apoyo=$this->GetFechasHcApoyos($B['ingreso']);
											for($max=0;$max < sizeof($fech_apoyo);$max++)
											{
												if(strtotime($fech_apoyo[$max][fecha]) <= strtotime(date("y-m-d H:i:s")))
												{ $centinela=1; break;}
												$centinela=0;
											}

											if($centinela==1)
											{
													$urlAP = ModuloGetURL('app','EstacionEnfermeriaControlA','user','CallControlesPacientes',array("estacion"=>$estacion,"datos_estacion"=>array("pieza"=>$B['pieza'],"cama"=>$B['cama'],"NombrePaciente"=>$B['primer_nombre']." ".$B['segundo_nombre']." ".$B['primer_apellido']." ".$B['segundo_apellido'],"paciente_id"=>$B['paciente_id'],"tipo_id_paciente"=>$B['tipo_id_paciente'],"ingreso"=>$B['ingreso'],"control_descripcion"=>'CONTROLES DE APOYOS DIAGNOSTICOS PENDIENTES')));
													$img='alarma.png';
													$this->salida .= "	<td align=\"center\"><a href='$urlAP'><img src=\"". GetThemePath() ."/images/$img\" border='0'>&nbsp;AD</a></td>\n";
											}
											else
											{
													//PROGRAMACION DE APOYOS DIAGNOSTICOS PENDIENTES.....
													//$enlaceProgramacion = "<a href=\"".ModuloGetURL('app','EstacionEnfermeriaControlA','user','CallControlesPacientes',array("control_descripcion"=>"CONTROLES DE APOYOS DIAGNOSTICOS PENDIENTES","estacion"=>$datos)) ."\" target=\"Contenido\">Programaci&oacute;n y Apoyos Diagnostico Pendientes</a>\n";
													$urlAP = ModuloGetURL('app','EstacionEnfermeriaControlA','user','CallControlesPacientes',array("estacion"=>$estacion,"datos_estacion"=>array("pieza"=>$B['pieza'],"cama"=>$B['cama'],"NombrePaciente"=>$B['primer_nombre']." ".$B['segundo_nombre']." ".$B['primer_apellido']." ".$B['segundo_apellido'],"paciente_id"=>$B['paciente_id'],"tipo_id_paciente"=>$B['tipo_id_paciente'],"ingreso"=>$B['ingreso'],"control_descripcion"=>'CONTROLES DE APOYOS DIAGNOSTICOS PENDIENTES')));
													$conteo=$this->GetConteo_Hc_control_apoyod($B['ingreso']);
													if(empty($conteo)){$img='fecha_inicio.png';} else {$img='tabla_activa.png';}
													$this->salida .= "	<td align=\"center\"><a href='$urlAP'><img src=\"". GetThemePath() ."/images/$img\" border='0'>&nbsp;AD</a></td>\n";
												}



											//MEDICAMENTOS
											$medicamento=$this->GetPacMedicamentosPorSolicitar($B['ingreso']);
											if($medicamento==1)
											{$imgM="pparamedin.png";}else{$imgM="pparamed.png";}
											$urla = ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmMedicamentos',array("estacion"=>$estacion,"datos_estacion"=>array("pieza"=>$B['pieza'],"cama"=>$B['cama'],"NombrePaciente"=>$B['primer_nombre']." ".$B['segundo_nombre']." ".$B['primer_apellido']." ".$B['segundo_apellido'],"paciente_id"=>$B['paciente_id'],"tipo_id_paciente"=>$B['tipo_id_paciente'],"Hora"=>$tiempo,"ingreso"=>$B['ingreso'],"control_id"=>2,"control_descripcion"=>"CONTROL MEDICAMENTOS PACIENTE")));
											$this->salida .= "	<td align=\"center\"><a href='$urla'><img src=\"". GetThemePath() ."/images/$imgM\" border='0'>&nbsp;MP</a></td>\n";



											$conteo_os=$this->ConteoOrdenesPaciente($B['ingreso']);
											if($conteo_os==1)
											{
												$href=ModuloGetURL('app','CentralImpresionHospitalizacion','user','BuscarPorEstacion',array("estacion"=>$estacion[estacion_id],
												"paciente_id"=>$B['paciente_id'],"tipo_id_paciente"=>$B['tipo_id_paciente'],"nombre_estacion"=>$estacion[descripcion4],"ingreso"=>$B['ingreso']));
												$this->salida .= "	<td align=\"center\"><a href=\"$href\"><img src=\"". GetThemePath() ."/images/pinactivo.png\" border='0'>&nbsp;OS</a></td>\n";
											}
											else
											{
												$this->salida .= "	<td align=\"center\"><img src=\"". GetThemePath() ."/images/editar.png\" border='0'>&nbsp;OS</td>\n";
											}

                        //buscamos las evoluciones pasadas
												unset($fechas_evol);
												unset($medico);
												unset($salida);
												$prof=0;
											  $arreglo_info= $this->Buscar_Evoluciones_Medicas($B['ingreso'],UserGetUID());
												for($n=0;$n<sizeof($arreglo_info);$n++)
												{
													$fechas_evol.=$arreglo_info[$n]['fecha']."<BR>";
													if(!empty($arreglo_info[$n]['nombre']))
													{$medico.=$arreglo_info[$n]['nombre']."<BR>";}


                               if($arreglo_info[$n]['usuario_id']==$_SESSION['SYSTEM_USUARIO_ID'])
                                {
                                    $accion=ModuloHCGetURL($arreglo_info[$n]['evolucion_id'],'',0,$_SESSION['AtencionUrgencias']['hc_modulo_medico'],$_SESSION['AtencionUrgencias']['hc_modulo_medico'],array('estacion'=>$_SESSION['AtencionUrgencias']['estacion_id'],'HC_DATOS_CONTROL'=>array('CONTROL'=>'HOSPITALIZACION','ESTACION'=>$_SESSION['AtencionUrgencias']['estacion_id'])));
                                    $salida .="<img src=\"". GetThemePath() ."/images/noneurologico.png\" border='0' width='10' heigth='12'>&nbsp;<a href='$accion'>Continuar Atencion</a><br>";
                                    $prof=1;
                                }
                                else
                                {
                                   		$accion=ModuloHCGetURL(0,'',$arreglo_info[$n]['ingreso'],$_SESSION['AtencionUrgencias']['hc_modulo_medico'],$_SESSION['AtencionUrgencias']['hc_modulo_medico'],array('estacion'=>$_SESSION['AtencionUrgencias']['estacion_id'],'HC_DATOS_CONTROL'=>array('CONTROL'=>'HOSPITALIZACION','ESTACION'=>$_SESSION['AtencionUrgencias']['estacion_id'])));
																			$salida .="<img src=\"". GetThemePath() ."/images/neurologico.png\" border='0' width='10' heigth='12'>&nbsp;<a href='$accion'>Nueva Atencion</a><br>";

																}

												}//fin for.

												$this->salida .= "	<td align=\"center\">$fechas_evol</td>\n";unset($fechas_evol);
												$this->salida .= "	<td align=\"center\">$medico</td>\n";unset($medico);
												$this->salida .= "	<td align=\"center\">$salida</td>\n";unset($salida);



											$this->salida .= "</tr>\n";

					}//fin for


						$this->salida .= "</table><br>\n";



					//$this->salida .= "<div class=\"label\" align=\"center\">TOTAL PACIENTES HOSPITALIZACION = ".sizeof($datoscenso[hospitalizacion])."<br>\n";
					}//fin formato hospitalizacio
				}//fin foreach



			//	$href2 = ModuloGetURL('app','EstacionE_ControlPacientes','user','ListRevisionPorSistemas',array("estacion"=>$estacion));
			//	$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href2."'>Refrescar</a><br>";

			//	$href = ModuloGetURL('app','EstacionEnfermeria','user','CallMenu',array("estacion"=>$estacion));
			//	$this->salida .= "<div class='normal_10' align='center'><br><a href='".$href."'>Volver al Menu Estaci&oacute;n</a><br>";

				//$this->salida .= themeCerrarTabla();
				unset($ItemBusqueda);

			}
			else //es por que no hay hospitalizados pero todavia podemos revisar los pendientes x ingresar.
			{
						$pacientes = $this->GetPacientesPendientesXHospitalizar($estacion);

						if(is_array($pacientes))
						{

								if(!empty($_SESSION['AtencionUrgencias']['titulo_atencion_pacientes']))
								{
									$titulo=$_SESSION['AtencionUrgencias']['titulo_atencion_pacientes'];
								}
								else
								{
									$titulo="PACIENTES INTERNADOS";
								}
								$this->salida .= "<br><table align=\"center\" width=\"100%\"  border=\"0\" >\n";
								$this->salida .= "	<tr class=\"modulo_table_title\"><td colspan='12' height='30'>".$titulo."</td></tr>\n";
								$this->salida .= "	<tr class=\"modulo_table_title\">\n";
								$this->salida .= "		<td width=\"2%\"></td>\n";
								$this->salida .= "		<td width=\"2%\"><sub>HAB.</sub></td>\n";
								$this->salida .= "		<td width=\"2%\"><sub>CAMA</sub></td>\n";
								$this->salida .= "		<td width=\"3%\"><sub>TIEMPO<BR>HOSP.</sub></td>\n";
								$this->salida .= "		<td width=\"16%\"><sub>PACIENTE</sub></td>\n";
								$this->salida .= "		<td width=\"3%\"><sub>CONTROL</sub></td>\n";
								$this->salida .= "		<td width=\"3%\"><sub>APOYO</sub></td>\n";
								$this->salida .= "		<td width=\"3%\"><sub>MEDIC.</sub></td>\n";
								$this->salida .= "		<td width=\"3%\"><sub>ORDEN</sub></td>\n";
								$this->salida .= "		<td width=\"14%\"><sub>FECHA<BR>EVOLUCIÓN</sub></td>\n";
								$this->salida .= "		<td width=\"29%\"><sub>MEDICO</sub></td>\n";
								$this->salida .= "		<td width=\"36%\"><sub>HISTORIA<BR> CLINICA</sub></td>\n";
								$this->salida .= "	</tr>\n";

								//mostramos los pacientes pendientes por ingresar .. si hay
								if(is_array($pacientes))
								{$this->Pacientes_X_Ingresar($estacion,$reporte,$pacientes);}

								$this->salida .= "</table><br>\n";

					}
					else
					{
								$mensaje = "LA ESTACI&Oacute;N [ ".$estacion['descripcion5']." ] NO CUENTA CON PACIENTES.";
								$titulo = "ALERTA DEL SISTEMA";
								$boton = "SELECCIONAR ESTACION";
								$controles=$this->GetControles($datoscenso['hospitalizacion'][$i]['ingreso']);
								$href=ModuloGetURL('app','EstacionEnfermeria','user','');
								$this->FormaMensaje($mensaje,$titulo,$href,$boton);

								$refresh = ModuloGetURL('app','AtencionPacientesUrgencias','user','ListRevisionPorSistemas',array('estacion'=>$estacion));
								$href = ModuloGetURL('app','AtencionPacientesUrgencias','user','Pantalla_de_Opciones');
								$this->salida .= "<div class='normal_10' align='center'><br>\n";
								$this->salida .= "	<a href='".$href."'>Retornar al Menu</a>&nbsp;&nbsp;-&nbsp;&nbsp;\n";
								$this->salida .= "	<a href='$refresh'>Refrescar</a><br>\n";
								$this->salida .= "\n";

								$this->salida .= themeCerrarTabla();
								return true;
					}
			}

				$refresh = ModuloGetURL('app','AtencionPacientesUrgencias','user','ListRevisionPorSistemas',array('estacion'=>$estacion));
				$href = ModuloGetURL('app','AtencionPacientesUrgencias','user','Pantalla_de_Opciones');

				$this->salida .= "<div class='normal_10' align='center'><br>\n";
				$this->salida .= "	<a href='".$href."'>Retornar al Menu</a>&nbsp;&nbsp;-&nbsp;&nbsp;\n";
				$this->salida .= "	<a href='$refresh'>Refrescar</a><br>\n";
				$this->salida .= "\n";

			$this->salida .= themeCerrarTabla();
			return true;
		}



		/*
* funcion que revisa los pacientes que estan por ingresar
*/
function Pacientes_X_Ingresar($estacion,&$reporte,$pacientes)
{
			if(is_array($pacientes))
		{
			for($i=0; $i<sizeof($pacientes); $i++)
			{
				$viaIngreso = $this->GetViaIngresoPaciente($pacientes[$i][4]);//le envio el ingreso
				$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');

				if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_oscuro";
				$this->salida .= "<tr class=\"$estilo\" onmouseout=mOut(this,'$backgrounds[$estilo]'); onmouseover=mOvr(this,'#7A99BB');>\n";
				$linkVerDatos = ModuloGetURL('app','EE_PanelEnfermeria','user','CallMostrarDatosIngreso',array("ingresoID"=>$pacientes[$i][4],"retorno"=>"CallListRevisionPorSistemas","modulito"=>'AtencionPacientesUrgencias',"datos_estacion"=>$estacion));


				$linker=ModuloGetURL('app','EstacionE_Pacientes','user','CallListPacientesPorIngresar',array("datos_estacion"=>$estacion,"paciente_id"=>$pacientes[$i][2],"tipo_id_paciente"=>$pacientes[$i][3]));

				$this->salida .= "	<td  align=\"center\"><img src=\"". GetThemePath() ."/images/ingresar.png\" border='0'></td>\n";
				$this->salida .= "	<td  colspan='3' align=\"center\">Pendiente Asignación Cama</td>\n";
				$nombre=$pacientes[$i][0]." ".$pacientes[$i][1];
				
			  $conteo_lectura=$this->Revisar_Lectura_Examen_Para_Medico($pacientes[$i][3],$pacientes[$i][2],$pacientes[$i][4]);
				if($conteo_lectura == 0)
				{
					$xx="bestell.gif";
					$titulo="TIENE EXAMENES FIRMADOS  DEL PACIENTE &nbsp; $nombre &nbsp;SIN HABERLO LEIDO";
				  $IMG="<img src=\"". GetThemePath() ."/images/$xx\" border='0' width='16' heigth='16'>";
				}
				else{$xx='';$titulo="";$IMG="";}

				
				
				$this->salida .= "	<td nowrap><a href=\"$linkVerDatos\">".$pacientes[$i][0]." ".$pacientes[$i][1]."</a><div title='$titulo'>$IMG</div></td>\n";


				$nombre=$pacientes[$i][0]." ".$pacientes[$i][1];
				//SIGNOS VITALES
				

				unset($funcion);


					$conteop=$this->CountControles($pacientes[$i][4]);
				//REVISAR CONTROLES PROGRAMADOS
				//$nombre=$B[primer_nombre]." ".$B[segundo_nombre]." ".$B[primer_apellido]." ".$B[segundo_apellido];
				$url = ModuloGetURL('app','EstacionE_ControlPacientes','user','Listado_controles',array("estacion"=>$estacion,"ingreso"=>$pacientes[$i][4],"nombre"=>urlencode($nombre),"pieza"=>'No Ingresado',"cama"=>'No Ingresado'));
				//echo "<br>".$conteop.$B['ingreso'];
				if($conteop==1)
				{
					$imgp="resultado.png";
					$this->salida .= "	<td align=\"center\"><a href='$url'><img src=\"". GetThemePath() ."/images/$imgp\" border='0'>&nbsp;CP</a></td>\n";
				}else
				{
					$imgp="prangos.png";
					$this->salida .= "	<td align=\"center\"><img src=\"". GetThemePath() ."/images/$imgp\" border='0'>&nbsp;CP</td>\n";
				}
					unset($conteop);

					$centinela=0;
							//Traemos las fechas de los apoyos diagnosticos pendientes.
							$fech_apoyo=$this->GetFechasHcApoyos($pacientes[$i][4]);
							for($max=0;$max < sizeof($fech_apoyo);$max++)
							{
								if(strtotime($fech_apoyo[$max][fecha]) <= strtotime(date("y-m-d H:i:s")))
								{ $centinela=1; break;}
								$centinela=0;
							}

							 if($centinela==1)
							 {
							 		$urlAP = ModuloGetURL('app','EstacionEnfermeriaControlA','user','CallControlesPacientes',array("estacion"=>$estacion,"datos_estacion"=>array("pieza"=>'No Ingresado',"cama"=>'No Ingresado',"NombrePaciente"=>$nombre,"paciente_id"=>$pacientes[$i][2],"tipo_id_paciente"=>$pacientes[$i][3],"ingreso"=>$pacientes[$i][4],"control_descripcion"=>'CONTROLES DE APOYOS DIAGNOSTICOS PENDIENTES')));
									$img='alarma.png';
									$this->salida .= "	<td align=\"center\"><a href='$urlAP'><img src=\"". GetThemePath() ."/images/$img\" border='0'>&nbsp;AD</a></td>\n";
							 }
							 else
							 {
									//PROGRAMACION DE APOYOS DIAGNOSTICOS PENDIENTES.....
									//$enlaceProgramacion = "<a href=\"".ModuloGetURL('app','EstacionEnfermeriaControlA','user','CallControlesPacientes',array("control_descripcion"=>"CONTROLES DE APOYOS DIAGNOSTICOS PENDIENTES","estacion"=>$datos)) ."\" target=\"Contenido\">Programaci&oacute;n y Apoyos Diagnostico Pendientes</a>\n";
									$urlAP = ModuloGetURL('app','EstacionEnfermeriaControlA','user','CallControlesPacientes',array("estacion"=>$estacion,"datos_estacion"=>array("pieza"=>'No Ingresado',"cama"=>'No Ingresado',"NombrePaciente"=>$nombre,"paciente_id"=>$pacientes[$i][2],"tipo_id_paciente"=>$pacientes[$i][3],"ingreso"=>$pacientes[$i][4],"control_descripcion"=>'CONTROLES DE APOYOS DIAGNOSTICOS PENDIENTES')));
									if(empty($B[hc_control_apoyod])){$img='fecha_inicio.png';} else {$img='tabla_activa.png';}
									$this->salida .= "	<td align=\"center\"><a href='$urlAP'><img src=\"". GetThemePath() ."/images/$img\" border='0'>&nbsp;AD</a></td>\n";
								}




				//MEDICAMENTOS
				$medicamento=$this->GetPacMedicamentosPorSolicitar($pacientes[$i][4]);
				if($medicamento==1)
				{$imgM="pparamedin.png";}else{$imgM="pparamed.png";}
				$urla = ModuloGetURL('app','EstacionE_Medicamentos','user','CallFrmMedicamentos',array("estacion"=>$estacion,"datos_estacion"=>array("pieza"=>'No Ingresado',"cama"=>'No Ingresado',"NombrePaciente"=>$nombre,"paciente_id"=>$pacientes[$i][2],"tipo_id_paciente"=>$pacientes[$i][3],"Hora"=>$tiempo,"ingreso"=>$pacientes[$i][4],"control_id"=>2,"control_descripcion"=>"CONTROL MEDICAMENTOS PACIENTE")));
				$this->salida .= "	<td align=\"center\"><a href='$urla'><img src=\"". GetThemePath() ."/images/$imgM\" border='0'>&nbsp;MP</a></td>\n";



					$conteo_os=$this->ConteoOrdenesPaciente($pacientes[$i][4],$nombre);
					if($conteo_os==1)
					{
						$href=ModuloGetURL('app','CentralImpresionHospitalizacion','user','BuscarPorEstacion',array("estacion"=>$estacion[estacion_id],
						"paciente_id"=>$pacientes[$i][2],"tipo_id_paciente"=>$pacientes[$i][3],"nombre_estacion"=>$estacion[descripcion4],"ingreso"=>$pacientes[$i][4]));
						$this->salida .= "	<td align=\"center\"><a href=\"$href\"><img src=\"". GetThemePath() ."/images/pinactivo.png\" border='0'>&nbsp;OS</a></td>\n";
					}
					else
					{
						$this->salida .= "	<td align=\"center\"><img src=\"". GetThemePath() ."/images/editar.png\" border='0'>&nbsp;OS</td>\n";
					}



					 //buscamos las evoluciones pasadas
					unset($fechas_evol);
					unset($medico);
					unset($salida);
					$prof=0;
					$arreglo_info= $this->Buscar_Evoluciones_Medicas($pacientes[$i][4],UserGetUID());
					for($n=0;$n<sizeof($arreglo_info);$n++)
					{
						$fechas_evol.=$arreglo_info[$n]['fecha']."<BR>";
						if(!empty($arreglo_info[$n]['nombre']))
						{$medico.=$arreglo_info[$n]['nombre']."<BR>";}


									if($arreglo_info[$n]['usuario_id']==$_SESSION['SYSTEM_USUARIO_ID'])
									{
											$accion=ModuloHCGetURL($arreglo_info[$n]['evolucion_id'],'',0,$_SESSION['AtencionUrgencias']['hc_modulo_medico'],$_SESSION['AtencionUrgencias']['hc_modulo_medico'],array('estacion'=>$_SESSION['AtencionUrgencias']['estacion_id'],'HC_DATOS_CONTROL'=>array('CONTROL'=>'HOSPITALIZACION','ESTACION'=>$_SESSION['AtencionUrgencias']['estacion_id'])));
											$salida .="<img src=\"". GetThemePath() ."/images/noneurologico.png\" border='0' width='10' heigth='12'>&nbsp;<a href='$accion'>Continuar Atencion</a><br>";
											$prof=1;
									}
									else
									{
												$accion=ModuloHCGetURL(0,'',$pacientes[$i][4],$_SESSION['AtencionUrgencias']['hc_modulo_medico'],$_SESSION['AtencionUrgencias']['hc_modulo_medico'],array('estacion'=>$_SESSION['AtencionUrgencias']['estacion_id'],'HC_DATOS_CONTROL'=>array('CONTROL'=>'HOSPITALIZACION','ESTACION'=>$_SESSION['AtencionUrgencias']['estacion_id'])));
 												$salida .="<img src=\"". GetThemePath() ."/images/neurologico.png\" border='0' width='10' heigth='12'>&nbsp;<a href='$accion'>Nueva Atencion</a><br>";
									}


						}//fin for

           	$this->salida .= "	<td align=\"center\">$fechas_evol</td>\n";
						$this->salida .= "	<td align=\"center\">$medico</td>\n";
						$this->salida .= "	<td align=\"center\">$salida</td>\n";


					$this->salida .= "</tr>\n";

			}

		}//pacientes por ingresar
return true;
}


/**
		*
		*/
    function ListadoPacientesAtendidosTriage()
    {
				if(empty($_SESSION['AtencionUrgencias']))
				{
							if(empty($estacion)){$estacion=$_REQUEST['estacion'];}
				}
				else
				{
						$estacion=$_SESSION['AtencionUrgencias'];
				}
				$this->salida .= ThemeAbrirTabla("PACIENTES CLASIFICADOS &nbsp;".$_SESSION['AtencionUrgencias']['descripcion5']."");

        $arr=$this->PacientesAtendidosTriage();
				$reporte= new GetReports();
        if($arr)
        {
            $this->SetJavaScripts('DatosPaciente');
						$this->salida .= "<br><table align=\"center\" width=\"80%\"  border=\"0\" >\n";
						$this->salida .= "	<tr class=\"modulo_table_title\"><td colspan='2' height='30'>PACIENTES CLASIFICADOS POR ESTE PROFESIONAL EN LAS ULTIMAS 12 HORAS</td></tr>\n";
						$this->salida .= "	<tr class=\"modulo_table_title\">\n";
						$this->salida .= "		<td width=\"60%\"><sub>PACIENTES</sub></td>\n";
						$this->salida .= "		<td width=\"20%\"><sub>ACCIÓN</sub></td>\n";
						$this->salida .= "	</tr>\n";


            for($i=0; $i<sizeof($arr); $i++)
            {
								if( $i % 2){ $estilo='modulo_list_claro';}
								else {$estilo='modulo_list_oscuro';}
								$this->salida.="<tr align=\"center\" class=\"$estilo\">";
								$this->salida .= '<td align="center">';
								$this->salida.=RetornarWinOpenDatosPaciente($arr[$i]['tipo_id_paciente'],$arr[$i]['paciente_id'],$arr[$i]['nombre']);
								$this->salida .= "</td>";
								$mostrar=$reporte->GetJavaReport('app','Admisiones','triage',array('triage_id'=>$arr[$i]['triage_id'],'empresa'=>$_SESSION['ADMISIONES']['NOMEMPRESA'],'nombre'=>$arr[$i]['nombre']),array('rpt_name'=>'triage','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
								$funcion=$reporte->GetJavaFunction();
								$this->salida .=$mostrar;
								$this->salida .= "				       <td align=\"center\"><a href=\"javascript:$funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'>&nbsp;  Imprimir</a></td>";
								$this->salida .= "</tr>";
            }
            $this->salida .= "</table>";
            /*$this->salida .= "</td>";
            $this->salida .= "</tr>";
            $this->salida .= '</table>';*/
        }
				else
				{
						$this->salida .= '<table width="80%" align="center">';
						$this->salida .= '<tr align="center">';
						$this->salida .= '<td align="center" class="label_mark">';
						$this->salida .= 'NO HAY PACIENTES CLASIFICADOS HOY';
						$this->salida .= "</td>";
						$this->salida .= "</tr>";
						$this->salida .= "</table>";
				}
				unset($reporte);
        $this->salida .= "<BR>";
				$refresh = ModuloGetURL('app','AtencionPacientesUrgencias','user','ListadoPacientesAtendidosTriage',array('estacion'=>$estacion));
				$href = ModuloGetURL('app','AtencionPacientesUrgencias','user','Pantalla_de_Opciones');
				$this->salida .= "<div class='normal_10' align='center'><br>\n";
				$this->salida .= "	<a href='".$href."'>Retornar al Menu</a>&nbsp;&nbsp;-&nbsp;&nbsp;\n";
				$this->salida .= "	<a href='$refresh'>Refrescar</a><br>\n";
			  $this->salida .= themeCerrarTabla();

        return true;
    }



function ListadoPacientesClasificar($estacion)
{
				//var de session para devolverse cuando clasifique al paciente
				//tambien cuando lo saca del listado.
				$_SESSION['ATENCION']['URGENCIAS']['SWITCH']='remision';
				if(empty($_SESSION['AtencionUrgencias']))
				{
							if(empty($estacion)){$estacion=$_REQUEST['estacion'];}
				}
				else
				{
						$estacion=$_SESSION['AtencionUrgencias'];
				}
        $pacientestriage=$this->PacientesClasificacionTriage();
				$this->salida .= ThemeAbrirTabla("PACIENTES PENDIENTES PARA CONFIRMACION DE REMISION&nbsp;".$_SESSION['AtencionUrgencias']['descripcion5']."");

        if($pacientestriage)
        {
            $this->SetJavaScripts('DatosPaciente');
            $this->salida .= "<br><table align=\"center\" width=\"100%\"  border=\"0\" >\n";
						$this->salida .= "	<tr class=\"modulo_table_title\"><td colspan='3' height='30'>PACIENTES PARA CONFIRMAR REMISION</td></tr>\n";
						$this->salida .= "	<tr class=\"modulo_table_title\">\n";
						$this->salida .= "		<td width=\"60%\"><sub>PACIENTES</sub></td>\n";
						$this->salida .= "		<td width=\"20%\"><sub>ACCIÓN</sub></td>\n";
						$this->salida .= "		<td width=\"20%\"><sub>SACAR DEL LISTADO</sub></td>\n";
						$this->salida .= "	</tr>\n";
            $spy=0;
            foreach($pacientestriage as $k=>$v)
            {
                foreach($v as $t=>$s)
                {
                    if($spy==0)
                    {
                        $this->salida.='<tr align="center" class="modulo_list_claro">';
                        $spy=1;
                    }
                    else
                    {
                        $this->salida.='<tr align="center" class="modulo_list_oscuro">';
                        $spy=0;
                    }
                    $this->salida .= '<td align="center">';
                    $this->salida.=RetornarWinOpenDatosPaciente($s['tipo_id_paciente'],$s['paciente_id'],$s['nombre']);
                    $this->salida .= "</td>";
                    $this->salida .= '<td align="center">';
                    $accion=ModuloGetURL('app','AtencionPacientesUrgencias','user','ClasificarTriage',array('paciente_id'=>$s['paciente_id'], 'tipo_id_paciente'=>$s['tipo_id_paciente'], 'plan_id'=>$s['plan_id'], 'triage_id'=>$s['triage_id'], 'punto_triage_id'=>$s['punto_triage_id'], 'punto_admision_id'=>$s['punto_admision_id'], 'sw_no_atender'=>$s['sw_no_atender']));
                    $this->salida.='<a href="'.$accion.'">Clasificar</a>';
                    $this->salida .= "</td>";
										//sacar listado
                    $this->salida .= '<td align="center">';
                    $accionS=ModuloGetURL('app','AtencionPacientesUrgencias','user','SacarPacienteLista',array('nombre'=>$s['nombre'],'paciente_id'=>$s['paciente_id'], 'tipo_id_paciente'=>$s['tipo_id_paciente'], 'triage_id'=>$s['triage_id']));
                    $this->salida.='<a href="'.$accionS.'">Sacar</a>';
                    $this->salida .= "</td>";
                    $this->salida .= "</tr>";
                }
            }
            $this->salida .= "</table>";
            /*$this->salida .= "</td>";
            $this->salida .= "</tr>";
            $this->salida .= '</table>';*/
        }
				else
				{
						$this->salida .= '<table width="80%" align="center">';
						$this->salida .= '<tr align="center">';
						$this->salida .= '<td align="center">';
						$this->salida .= '<label class="label_mark">NO HAY PACIENTES PARA CONFIRMACION DE REMISION</label>';
						$this->salida .= "</td>";
						$this->salida .= "</tr>";
						$this->salida .= "</table>";
				}
        $this->salida .= "<BR>";
				$refresh = ModuloGetURL('app','AtencionPacientesUrgencias','user','ListadoPacientesClasificar',array('estacion'=>$estacion));
				$href = ModuloGetURL('app','AtencionPacientesUrgencias','user','Pantalla_de_Opciones');
				$this->salida .= "<div class='normal_10' align='center'><br>\n";
				$this->salida .= "	<a href='".$href."'>Retornar al Menu</a>&nbsp;&nbsp;-&nbsp;&nbsp;\n";
				$this->salida .= "	<a href='$refresh'>Refrescar</a><br>\n";
			  $this->salida .= themeCerrarTabla();
        return true;
    }




function ListadoPacientesConfirmarAdmision($estacion)
{
				if(empty($_SESSION['AtencionUrgencias']))
					{
							if(empty($estacion)){$estacion=$_REQUEST['estacion'];}
					}
					else
					{
						$estacion=$_SESSION['AtencionUrgencias'];
					}
        $pacientestriage=$this->GetPacientesConfirmarAdmision();
			  $this->salida .= ThemeAbrirTabla("PACIENTES PARA CONFIRMAR ADMISION&nbsp;".$_SESSION['AtencionUrgencias']['descripcion5']."");

        if($pacientestriage)
        {
						$this->SetJavaScripts('DatosPaciente');
            $this->salida .= "<br><table align=\"center\" width=\"100%\"  border=\"0\" >\n";
						$this->salida .= "	<tr class=\"modulo_table_title\"><td colspan='4' height='30'>PACIENTES PARA CONFIRMAR ADMISION</td></tr>\n";
						$this->salida .= "	<tr class=\"modulo_table_title\">\n";
						$this->salida .= "		<td width=\"30%\"><sub>PACIENTES</sub></td>\n";
						$this->salida .= "		<td width=\"40%\"><sub>OBSERVACION ENFERMERA</sub></td>\n";
						$this->salida .= "		<td width=\"10%\"><sub>ACCIÓN</sub></td>\n";
						$this->salida .= "		<td width=\"20%\"><sub>SACAR DEL LISTADO</sub></td>\n";
						$this->salida .= "	</tr>\n";
						$spy=0;
            foreach($pacientestriage as $k=>$v)
            {
                foreach($v as $t=>$s)
                {
                    if($spy==0)
                    {
                        $this->salida.='<tr align="center" class="modulo_list_claro">';
                        $spy=1;
                    }
                    else
                    {
                        $this->salida.='<tr align="center" class="modulo_list_oscuro">';
                        $spy=0;
                    }
                    $this->salida .= '<td align="center">';
                    $this->salida.=RetornarWinOpenDatosPaciente($s['tipo_id_paciente'],$s['paciente_id'],$s['nombre']);
                    $this->salida .= "</td>";
                    $this->salida .= '<td align="center">'.$s['observacion_enfermera'].'</td>';
                    $this->salida .= '<td align="center">';
                    $accion=ModuloGetURL('app','AtencionPacientesUrgencias','user','ClasificarTriage',array('paciente_id'=>$s['paciente_id'], 'tipo_id_paciente'=>$s['tipo_id_paciente'], 'plan_id'=>$s['plan_id'], 'triage_id'=>$s['triage_id'], 'punto_triage_id'=>$s['punto_triage_id'], 'punto_admision_id'=>$s['punto_admision_id'], 'sw_no_atender'=>$s['sw_no_atender'],'pte'=>true));
                    //var de session para determinar a donde me devulevo cuando clasifico el triage
										$_SESSION['ATENCION']['URGENCIAS']['SWITCH']='admision';
                    $this->salida.='<a href="'.$accion.'">Clasificar</a>';
                    $this->salida .= "</td>";
										//sacar listado
                    $this->salida .= '<td align="center">';
                    $accionS=ModuloGetURL('app','AtencionPacientesUrgencias','user','SacarPacienteLista',array('nombre'=>$s['nombre'],'paciente_id'=>$s['paciente_id'], 'tipo_id_paciente'=>$s['tipo_id_paciente'], 'triage_id'=>$s['triage_id'],'lista'=>2));
                    $this->salida.='<a href="'.$accionS.'">Sacar</a>';
                    $this->salida .= "</td>";
                    $this->salida .= "</tr>";
                }
            }
            $this->salida .= "</table>";
            /*$this->salida .= "</td>";
            $this->salida .= "</tr>";
            $this->salida .= '</table>';*/
        }else{
                $this->salida .= '<table width="80%" align="center">';
                $this->salida .= '<tr align="center">';
                $this->salida .= '<td align="center">';
                $this->salida .= '<label class="label_mark">NO HAY PACIENTES PARA CONFIRMACION DE ADMISION</label>';
                $this->salida .= "</td>";
                $this->salida .= "</tr>";
                $this->salida .= "</table>";
        }

				$refresh = ModuloGetURL('app','AtencionPacientesUrgencias','user','ListadoPacientesConfirmarAdmision',array('estacion'=>$estacion));
				$href = ModuloGetURL('app','AtencionPacientesUrgencias','user','Pantalla_de_Opciones');
				$this->salida .= "<div class='normal_10' align='center'><br>\n";
				$this->salida .= "	<a href='".$href."'>Retornar al Menu</a>&nbsp;&nbsp;-&nbsp;&nbsp;\n";
				$this->salida .= "	<a href='$refresh'>Refrescar</a><br>\n";
			  $this->salida .= themeCerrarTabla();
        return true;
    }



function Pac_consultas_Urgencias($estacion)
{
					if(empty($_SESSION['AtencionUrgencias']))
					{
							if(empty($estacion)){$estacion=$_REQUEST['estacion'];}
					}
					else
					{
						$estacion=$_SESSION['AtencionUrgencias'];
					}

					if(empty($_SESSION['CONSULTORIO']))
					{  $_SESSION['CONSULTORIO']=$_REQUEST['consultorio'];  }

					$this->salida .= ThemeAbrirTabla("PACIENTES EN CONSULTA&nbsp;".$_SESSION['AtencionUrgencias']['descripcion5']."");
					$pacientes=$this->BuscarPacientesConsulta_Urgencias($estacion,$_SESSION['CONSULTORIO']);

//BuscarPacientesConsulta_Urgencias


						if(is_array($pacientes))
						{
								$mostrar ="\n<script language='javascript'>\n";
								$mostrar.="function mOvr(i,valor) {;\n";
								$mostrar.= "document.getElementById(i).className=valor;\n";
								$mostrar.="}\n";

								$mostrar.="function mOut(i,clrIn) {\n";

								//$mostrar.="document.getElementById(i).style.background = clrIn;\n";
								$mostrar.= "document.getElementById(i).className='modulo_list_claro';\n";

								$mostrar.="}\n";
								$mostrar.="</script>\n";
								$this->salida .="$mostrar";

						/*		//variable de session para comunicarse con los controles programados
								// de la estación de enfermeria.
								$_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['modulo']='AtencionPacientesUrgencias';
								$_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['metodo']='Pac_consultas_Urgencias';
								$_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['tipo']='user';
								$_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['contenedor']='app';
								$_SESSION['ESTACION_ENFERMERIA_URG']['RETORNO']['argumentos']=array('estacion'=>$estacion);
						*/



								$_SESSION['HISTORIACLINICA']['RETORNO']['modulo']='AtencionPacientesUrgencias';
								$_SESSION['HISTORIACLINICA']['RETORNO']['metodo']='Pac_consultas_Urgencias';
								$_SESSION['HISTORIACLINICA']['RETORNO']['tipo']='user';
								$_SESSION['HISTORIACLINICA']['RETORNO']['contenedor']='app';

								//AQUI ES PARA COMUNICARSE CON LA CENTRA DE IMPRESION DE ORDENES DE DAR.
								$_SESSION['CENTRALHOSP']['RETORNO']['modulo']='AtencionPacientesUrgencias';
								$_SESSION['CENTRALHOSP']['RETORNO']['metodo']='Pac_consultas_Urgencias';
								$_SESSION['CENTRALHOSP']['RETORNO']['tipo']='user';
								$_SESSION['CENTRALHOSP']['RETORNO']['contenedor']='app';
								$_SESSION['CENTRALHOSP']['RETORNO']['argumentos']=array('estacion'=>$estacion);



								$this->salida .= "<br><table align=\"center\" width=\"100%\"  border=\"0\" >\n";
								$this->salida .= "	<tr class=\"modulo_table_title\"><td colspan='6' height='30'>PACIENTES EN CONSULTA</td></tr>\n";
								$this->salida .= "	<tr class=\"modulo_table_title\">\n";
								$this->salida .= "		<td width=\"2%\"></td>\n";
								$this->salida .= "		<td width=\"20%\"><sub>PACIENTE</sub></td>\n";
								//$this->salida .= "		<td><sub>MED.<BR>PACIENTES</sub></td>\n";
								//$this->salida .= "		<td width=\"3%\"><sub>CONTROL</sub></td>\n";
							//	$this->salida .= "		<td>TRANFUSIONES</td>\n";
								//$this->salida .= "		<td width=\"3%\"><sub>APOYO</sub></td>\n";
								//$this->salida .= "		<td width=\"3%\"><sub>MEDIC.</sub></td>\n";
							//	$this->salida .= "		<td width=\"3%\"><sub>ORDEN</sub></td>\n";
								//$this->salida .= "		<td width=\"12%\"><sub>FECHA<BR>EVOLUCIÓN</sub></td>\n";
								$this->salida .= "		<td width=\"25%\"><sub>ATENCIONES ACTIVAS</sub></td>\n";
								$this->salida .= "		<td width=\"15%\"><sub>ACCIÓN</sub></td>\n";
								$this->salida .= "		<td width=\"3%\"><sub>SACAR<BR>LISTADO</sub></td>\n";
								$this->salida .= "		<td width=\"11%\"><sub>TIEMPO EN<BR>ESPERA</sub></td>\n";


								$this->salida .= "	</tr>\n";

								//mostramos los pacientes pendientes por ingresar .. si hay
								if(is_array($pacientes))
								{$this->Pacientes_X_Consulta_Urgencias($estacion,$pacientes);}

								$this->salida .= "</table><br>\n";
								$refresh = ModuloGetURL('app','AtencionPacientesUrgencias','user','Pac_consultas_Urgencias',array('estacion'=>$estacion));
								$href = ModuloGetURL('app','AtencionPacientesUrgencias','user','Pantalla_de_Opciones');
								$this->salida .= "<div class='normal_10' align='center'><br>\n";
								$this->salida .= "	<a href='".$href."'>Retornar al Menu</a>&nbsp;&nbsp;-&nbsp;&nbsp;\n";
								$this->salida .= "	<a href='$refresh'>Refrescar</a><br>\n";
								$this->salida .= "\n";

					}
					else
					{
								$mensaje = "LA ESTACI&Oacute;N [ ".$estacion['descripcion5']." ] NO TIENE PACIENTES EN CONSULTA.";
								$titulo = "ALERTA DEL SISTEMA";
								$boton = "SELECCIONAR ESTACION";
								$controles=$this->GetControles($datoscenso['hospitalizacion'][$i]['ingreso']);
								$href=ModuloGetURL('app','EstacionEnfermeria','user','');
								$this->FormaMensaje($mensaje,$titulo,$href,$boton);

								$refresh = ModuloGetURL('app','AtencionPacientesUrgencias','user','Pac_consultas_Urgencias',array('estacion'=>$estacion));
								$href = ModuloGetURL('app','AtencionPacientesUrgencias','user','Pantalla_de_Opciones');
								$this->salida .= "<div class='normal_10' align='center'><br>\n";
								$this->salida .= "	<a href='".$href."'>Retornar al Menu</a>&nbsp;&nbsp;-&nbsp;&nbsp;\n";
								//$this->salida .= "	<a href='$refresh'>Refrescar</a><br>\n";
								$this->salida .= "\n";
					}

	$this->salida .= themeCerrarTabla();
	return true;
}





/*
* funcion que revisa los pacientes que esta en consulta de urgencias..
*/
function Pacientes_X_Consulta_Urgencias($estacion,$pacientes)
{
		IncludeLib('funciones_admision');

		if(is_array($pacientes))
		{
      //fondos para los colores intercalados
  		$backgrounds=array('modulo_list_claro'=>'#DDDDDD','modulo_list_oscuro'=>'#CCCCCC');
			//fondos para los colores de triage
			//$backgrounds_triage=array('nivel1_oscuro'=>'#304E8E','nivel1_claro'=>'#DDECFF',
		//	/'nivel2_oscuro'=>'#B50000','nivel2_claro'=>'#FFD5D5','nivel3_oscuro'=>'#004800',
			//'nivel3_claro'=>'#91DBD0','nivel4_oscuro'=>'#E2C505','nivel4_claro'=>'#F6F39D');

			$vector_ingresos=array();//reiniciamos el vector q va a comparar los ingresos.
							$spy=0;
							for($i=0; $i<sizeof($pacientes); $i++)
							{

								if(in_array($pacientes[$i][4], $vector_ingresos)==FALSE)
								{

									//$viaIngreso = $this->GetViaIngresoPaciente($pacientes[$i][4]);//le envio el ingreso

										unset($mostrar);
										//$pacientes[$i][12]=date("Y-m-d H:i:s");
										if($pacientes[$i][12])//hora_llegada
										{
													$a=explode("-",$pacientes[$i][12]);
                    			$b=explode(" ",$a[2]);
                    			$c=explode(":",$b[1]);


													if(date("Y-m-d H:i:s",mktime($c[0],$c[1],$c[2],$a[1],$b[0],$a[0]))<date("Y-m-d H:i:s",mktime(date("H"), (date("i")-$pacientes[$i][13]), 0, date("m"), date("d"), date("Y"))))
													{
															//$prueba[$result->fields[0]][$result->fields[1]][$result->fields[2]][$i][]=1;
																											if(!empty($pacientes[$i][11]))
																											{
																																	$color_estilo = ColorTriageClaro($pacientes[$i][11]);
																											}
															else
															{
																	if($spy==0)
																	{
																			$color_estilo='modulo_list_oscuro';
																			$spy=1;
																	}
																	else
																	{
																			$color_estilo='modulo_list_claro';
																			$spy=0;
																	}
															}
													}
													//$total=(mktime(date("H"),date("i"),0,date("m"),date("d"),date("Y"))-mktime($c[0],$c[1],$c[2],$a[1],$b[0],$a[0]));

													/*$total=floor($total/60);
													$minutos=($total%60);
													$total=floor($total/60);
													$horas=($total%24);
													$total=floor($total/24);*/
													$date1=date('Y-m-d H:i:s');
													$fecha_llegada=explode(".",$pacientes[$i][12]);
													$s = strtotime($date1)-strtotime($fecha_llegada[0]);
													$d = intval($s/86400);
													$s -= $d*86400;
													$h = intval($s/3600);
													$s -= $h*3600;
													$m = intval($s/60);
													$s -= $m*60;

													$dif= (($d*24)+$h).hrs." ".$m."min";
													$dif2= $d.$space.dias." ".$h.hrs." ".$m."min";
													$mostrar="";
													//if(!empty($total))
													//{
															$mostrar='<label class=label_error>'.$dif2.'';
													//}
													//$mostrar.=$horas.':'.$minutos.'</label>';
										}
										else{


													if($spy==0)
													{
															$color_estilo='modulo_list_oscuro';
															$spy=1;
													}
													else
													{
															$color_estilo='modulo_list_claro';
															$spy=0;
													}

													/*$a=explode("-",$pacientes[$i][23]);
                    			$b=explode(" ",$a[2]);
                    			$c=explode(":",$b[1]);*/

													//$total=(mktime(date("H"),date("i"),0,date("m"),date("d"),date("Y"))-mktime($c[0],$c[1],$c[2],$a[1],$b[0],$a[0]));

													/*$total=floor($total/60);
													$minutos=($total%60);
													$total=floor($total/60);
													$horas=($total%24);
													$total=floor($total/24);*/
													$date1=date('Y-m-d H:i:s');
													$fecha_ing=explode(".",$pacientes[$i][23]);
													$s = strtotime($date1)-strtotime($fecha_ing[0]);

//echo "<br>".$pacientes[$i][23];

													$d = intval($s/86400);
													$s -= $d*86400;
													$h = intval($s/3600);
													$s -= $h*3600;
													$m = intval($s/60);
													$s -= $m*60;

													$dif= (($d*24)+$h).hrs." ".$m."min";
													$dif2= $d.$space.dias." ".$h.hrs." ".$m."min";
													$mostrar="";
													//if(!empty($dif2))
													//{
															$mostrar='<label class=label_mark>'.$dif2.' ';
													//}
													//$mostrar.=$horas.':'.$minutos.'</label>';

										}



									if($i % 2)  $estilo = "modulo_list_claro";  else  $estilo = "modulo_list_claro";
									$this->salida .= "<tr  class='$estilo' id=$i onmouseout=mOut('$i','$estilo');  onmouseover=mOvr('$i','$color_estilo');>\n";
									$linkVerDatos = ModuloGetURL('app','EE_PanelEnfermeria','user','CallMostrarDatosIngreso',array("ingresoID"=>$pacientes[$i][4],"retorno"=>"Pac_consultas_Urgencias","modulito"=>'AtencionPacientesUrgencias',"datos_estacion"=>$estacion));


									$linker=ModuloGetURL('app','EstacionE_Pacientes','user','CallListPacientesPorIngresar',array("datos_estacion"=>$estacion,"paciente_id"=>$pacientes[$i][2],"tipo_id_paciente"=>$pacientes[$i][3]));

									$this->salida .= "	<td  align=\"center\"><img src=\"". GetThemePath() ."/images/consulta_ur.png\" border='0'></td>\n";
									$conteo_lectura=$this->Revisar_Lectura_Examen_Para_Medico($pacientes[$i][3],$pacientes[$i][2],$pacientes[$i][4]);
									$nombre=$pacientes[$i][0]." ".$pacientes[$i][1];

									if($conteo_lectura == 0)
									{
										$xx="bestell.gif";
										$titulo="TIENE EXAMENES FIRMADOS DEL PACIENTE &nbsp; $nombre &nbsp; SIN HABERLO LEIDO";
										$IMG="<img src=\"". GetThemePath() ."/images/$xx\" border='0' width='16' heigth='16'>";
									}
									else{$xx='';$titulo="";$IMG="";}
									$this->salida .= "	<td nowrap><a href=\"$linkVerDatos\">".$pacientes[$i][0]." ".$pacientes[$i][1]."</a><div title='$titulo'>$IMG</div></td>\n";



									$nombre=$pacientes[$i][0]." ".$pacientes[$i][1];

										if(empty($pacientes[$i][15]))
										{
												$arr=$this->Buscar_Evoluciones_Pasadas($pacientes[$i][4]);
												$this->salida .= "	<td align=\"center\"><DIV title='Ultima atención de este profesional &nbsp;".$arr[0]['evolucion_id']."&nbsp;de&nbsp;".$arr[0]['fecha']."'><label class='label_mark'>".$arr[0]['nombre']."</label></DIV></td>\n";unset($arr);
										}
										else
										{
											$this->salida .= "	<td align=\"center\"><DIV title='Evolución Activa:".$pacientes[$i][14]."&nbsp;de&nbsp;".$pacientes[$i][16]."'>".$pacientes[$i][15]."</DIV></td>\n";
										}

										if(empty($pacientes[$i][14]))
										{

												if($pacientes[$i][11] ==='0' )
												{
														 $_SESSION['ATENCION']['URGENCIAS']['SWITCH']='consulta';
													   $accion=ModuloGetURL('app','AtencionPacientesUrgencias','user','ClasificarTriage', array('tipo_id_paciente'=>$pacientes[$i][3], 'paciente_id'=>$pacientes[$i][2], 'plan_id'=>$pacientes[$i][18], 'triage_id'=>$pacientes[$i][19], 'punto_triage_id'=>$pacientes[$i][20], 'punto_admision_id'=>$pacientes[$i][22], 'sw_no_atender'=>$pacientes[$i][21], 'ingreso'=>$pacientes[$i][4], 'moduloh'=>$_SESSION['AtencionUrgencias']['hc_modulo_consulta_urgencias'], 'estacion_id'=>$_SESSION['AtencionUrgencias']['estacion_id']));

												}
												else
												{
															$accion=ModuloHCGetURL(0,'',$pacientes[$i][4],$_SESSION['AtencionUrgencias']['hc_modulo_consulta_urgencias'],$_SESSION['AtencionUrgencias']['hc_modulo_consulta_urgencias'],array('HC_DATOS_CONTROL'=>array('CONTROL'=>'HOSPITALIZACION','ESTACION'=>$_SESSION['AtencionUrgencias']['estacion_id'])));
												}
												$this->salida .= "	<td align=\"left\"><img src=\"". GetThemePath() ."/images/neurologico.png\" border='0' width='10' heigth='12'>&nbsp;<a href='$accion'>Atender</a></td>\n";unset($salida1);
										}
										else
										{
											if($pacientes[$i][17]==$_SESSION['SYSTEM_USUARIO_ID'])
											{
													$accion=ModuloHCGetURL($pacientes[$i][14],'',0,$_SESSION['AtencionUrgencias']['hc_modulo_consulta_urgencias']	,$_SESSION['AtencionUrgencias']['hc_modulo_consulta_urgencias'],array('HC_DATOS_CONTROL'=>array('CONTROL'=>'HOSPITALIZACION','ESTACION'=>$_SESSION['AtencionUrgencias']['estacion_id'])));
													$this->salida .= "	<td align=\"left\"><img src=\"". GetThemePath() ."/images/noneurologico.png\" border='0' width='10' heigth='12'>&nbsp;<a href='$accion'>Continuar Atencion</a></td>\n";
											}
											else
											{
													$accion=ModuloHCGetURL(0,'',$pacientes[$i][4],$_SESSION['AtencionUrgencias']['hc_modulo_consulta_urgencias'],$_SESSION['AtencionUrgencias']['hc_modulo_consulta_urgencias'],array('HC_DATOS_CONTROL'=>array('CONTROL'=>'HOSPITALIZACION','ESTACION'=>$_SESSION['AtencionUrgencias']['estacion_id'])));
													$this->salida .= "	<td align=\"left\"><img src=\"". GetThemePath() ."/images/neurologico.png\" border='0' width='10' heigth='12'>&nbsp;<a href='$accion'>Atender</a></td>\n";unset($salida1);
											}
										}
										$nom=$pacientes[$i][0]." ".$pacientes[$i][1];
										$_SESSION['ATENCION']['URGENCIAS']['SWITCH']='consulta';
  									$accionS=ModuloGetURL('app','AtencionPacientesUrgencias','user','SacarPacienteLista',array('nombre'=>$nom,'paciente_id'=>$pacientes[$i][2], 'tipo_id_paciente'=>$pacientes[$i][3],'ingreso'=>$pacientes[$i][4],'lista'=>1));
										$this->salida .= "	<td align=\"center\"><a href=$accionS>Cancelar Atención</a></td>\n";
										$this->salida .= "	<td align=\"center\"><table border='1' width=100%><tr ><td class='$color_estilo'  onmouseout=mOut('$i','$estilo');  onmouseover=mOvr('$i','$color_estilo');>$mostrar</td></tr></table></td>\n";





										$this->salida .= "</tr>\n";
										$vector_ingresos[$i]=$pacientes[$i][4];
								}//fin de preguntar si el ingreso esta en el arreglo..//esto es para sacar el paciente si se repite.
								else
								{
									$vector_ingresos[$i]=$pacientes[$i][4];
								}
						}//fin for
						//print_r($vector_ingresos);
		unset($vector_ingresos);//unseteamos el vector de ingresos.
		}//pacientes por ingresar
	return true;
}






/**
	*		FormaMensaje => muestra mensajes al usuario
	*
	*		@Author Rosa Maria Angel
	*		@access Private
	*		@param string => mensaje a mostrar
	*		@param string => titulo de la tabla
	*		@param string => action del form
	*		@param string => value del input-submit
	*		@return boolean
	*/
	function FormaMensaje($mensaje,$titulo,$accion,$boton)
	{
		$this->salida .= ThemeAbrirTabla($titulo)."<br>";
		$this->salida .= "<table width=\"60%\" align=\"center\" class=\"normal_10\" border='0'>\n";
		$this->salida .= "	<form name=\"formaMensaje\" action=\"$accion\" method=\"post\">\n";
		$this->salida .= "		<tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>\n";
		if(!empty($boton)){
			$this->salida .= "	<tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"$boton\"></td></tr>\n";
		}
		else{
			$this->salida .= "	<tr><td colspan=\"2\" align=\"center\">&nbsp;</td></tr>\n";
		}
		$this->salida .= "	</form>\n";
		$this->salida .= "</table>\n";
		$this->salida .= themeCerrarTabla();
		return true;
	}//fin FormaMensaje

		

}
?>
