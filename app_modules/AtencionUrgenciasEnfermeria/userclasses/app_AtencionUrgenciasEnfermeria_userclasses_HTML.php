<?php

class app_AtencionUrgenciasEnfermeria_userclasses_HTML extends app_AtencionUrgenciasEnfermeria_user
{

    function app_AtencionUrgenciasEnfermeria_user_HTML()
    {
      $this->app_AtencionUrgenciasEnfermeria_user(); //Constructor del padre 'modulo'
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
        $this->ReturnMetodoExterno('app', 'EstacionEnfermeria', 'user', '',array('modulo_externo'=>'AtencionUrgenciasEnfermeria','metodo_externo'=>'ListadoPaciente'));
        return true;
    }







    function ListadoPacientesConfirmarAdmision()
    {
        $pacientestriage=$this->GetPacientesConfirmarAdmision();

        if($pacientestriage)
        {
            $this->SetJavaScripts('DatosPaciente');
            $this->salida .= '<table width="80%" align="center" border="0" class="modulo_table">';
            $this->salida .= '<tr align="center" class="modulo_table_title">';
            $this->salida .= '<td>';
            $this->salida .= "PACIENTES PARA CONFIRMAR ADMISION";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
            $this->salida .= '<tr align="center">';
            $this->salida .= '<td align="center">';
            $this->salida .= '<table width="100%" align="center" class="modulo_table_list" border="1">';
            $this->salida .= '<tr align="center" class="modulo_table_list_title">';
            $this->salida .= '<td align="center" width="70%">';
            $this->salida .= "Pacientes";
            $this->salida .= "</td>";
            $this->salida .= '<td align="center">';
            $this->salida .= "Acción";
            $this->salida .= "</td>";
            $this->salida .= '<td align="center" width="10%">Sacar Listado</td>';
            $this->salida .= "</tr>";
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
                    $accion=ModuloGetURL('app','AtencionUrgenciasEnfermeria','user','ClasificarTriage',array('paciente_id'=>$s['paciente_id'], 'tipo_id_paciente'=>$s['tipo_id_paciente'], 'plan_id'=>$s['plan_id'], 'triage_id'=>$s['triage_id'], 'punto_triage_id'=>$s['punto_triage_id'], 'punto_admision_id'=>$s['punto_admision_id'], 'sw_no_atender'=>$s['sw_no_atender'],'pte'=>true));
                    $this->salida.='<a href="'.$accion.'">Clasificar</a>';
                    $this->salida .= "</td>";
										//sacar listado
                    $this->salida .= '<td align="center">';
                    $accionS=ModuloGetURL('app','AtencionUrgenciasEnfermeria','user','SacarPacienteLista',array('nombre'=>$s['nombre'],'paciente_id'=>$s['paciente_id'], 'tipo_id_paciente'=>$s['tipo_id_paciente'], 'triage_id'=>$s['triage_id']));
                    $this->salida.='<a href="'.$accionS.'">Sacar</a>';
                    $this->salida .= "</td>";
                    $this->salida .= "</tr>";
                }
            }
            $this->salida .= "</table>";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
            $this->salida .= '</table>';
        }else{
                $this->salida .= '<table width="80%" align="center">';
                $this->salida .= '<tr align="center">';
                $this->salida .= '<td align="center">';
                $this->salida .= '<label class="label_error">NO HAY PACIENTES PARA CONFIRMACION DE ADMISION</label>';
                $this->salida .= "</td>";
                $this->salida .= "</tr>";
                $this->salida .= "</table>";
        }
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
					$accion=ModuloGetURL('app','AtencionUrgenciasEnfermeria','user','SacarPaciente',array('triage_id'=>$triage,'nombre'=>$nombre,'tipo_id_paciente'=>$tipoid,'paciente_id'=>$id,'ingreso'=>$ingreso));
					$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
					$this->salida .= "			      <table width=\"60%\" align=\"center\" >";
					$this->salida .= "				       <tr>";
					$this->salida .= "				       <td align=\"center\" class=\"label_MARK\" colspan=\"2\">IDENTIFICACION: ".$tipoid." ".$id."<BR>PACIENTE: ".$nombre."<BR>
					EL PACIENTE SERA SACADO DEL LISTADO Y SE CANCELARA SU PROCESO DE ATENCION EN AL INSTITUCION <BR>
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
					$accion=ModuloGetURL('app','AtencionUrgenciasEnfermeria','user','ListadoPaciente');
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
        $modulo=$this->TipoModulo('enfermeria');//consulta_urgencias

				 if($modulo==false)
        {
            return false;
        }
        $DatosEstacion=$this->BuscarPacientesEstacion();
        $prueba=$this->ReconocerProfesional();
				if($prueba==1 or $prueba==2 or $prueba==4)
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
                                        $accion=ModuloGetURL('app','AtencionUrgenciasEnfermeria','user','ClasificarTriage', array('tipo_id_paciente'=>$t, 'paciente_id'=>$k, 'plan_id'=>$j[10], 'triage_id'=>$j[11], 'punto_triage_id'=>$j[12], 'punto_admision_id'=>$j[13], 'sw_no_atender'=>$j[14], 'ingreso'=>$j[3], 'moduloh'=>$modulo, 'estacion_id'=>$j[8]));
                                    }
                                    else
                                    {
                                        $accion=ModuloHCGetURL(0,'',$j[15],$modulo,$modulo,array('estacion'=>$j[8],'HC_DATOS_CONTROL'=>array('CONTROL'=>'CONSULTA_URGENCIAS','ESTACION'=>$j[8],'INGRESO'=>$j[3])));
                                    }
                                    $salida3 .="<a href='$accion'>Atender</a>";
                                    $prof=1;
                                }
                                else
                                {
                                    if($j[6]==$_SESSION['SYSTEM_USUARIO_ID'])
                                    {
                                        $accion=ModuloHCGetURL($j[4],'',0,$modulo,$modulo,array('estacion'=>$j[8],'HC_DATOS_CONTROL'=>array('CONTROL'=>'CONSULTA_URGENCIAS','ESTACION'=>$j[8],'INGRESO'=>$j[3])));
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
                                $accion=ModuloHCGetURL(0,'',$j[15],$modulo,$modulo,array('estacion'=>$j[8],'HC_DATOS_CONTROL'=>array('CONTROL'=>'CONSULTA_URGENCIAS','ESTACION'=>$j[8],'INGRESO'=>$j[3])));
                                $salida3.='<a href="'.$accion.'">Nueva Atencion</a>';
                                $salida3 .= "</td>";
                                $salida3 .= "</tr>";
                                $salida3 .= '</table>';
                            }
                            $this->salida.=$salida3;
                            $this->salida .= "</td>";
														//sacar listado
														$this->salida .= '<td align="center">';
														$accionS=ModuloGetURL('app','AtencionUrgenciasEnfermeria','user','SacarPacienteLista',array('nombre'=>$p,'paciente_id'=>$k, 'tipo_id_paciente'=>$t,'ingreso'=>$j[3]));
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

    function ListadoPacientesClasificar()
    {
        $pacientestriage=$this->PacientesClasificacionTriage();
        if($pacientestriage)
        {
            $this->SetJavaScripts('DatosPaciente');
            $this->salida .= '<table width="80%" align="center" border="0" class="modulo_table">';
            $this->salida .= '<tr align="center" class="modulo_table_title">';
            $this->salida .= '<td>';
            $this->salida .= "PACIENTES PENDIENTES PARA CONFIRMACION DE REMISION";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
            $this->salida .= '<tr align="center">';
            $this->salida .= '<td align="center">';
            $this->salida .= '<table width="100%" align="center" class="modulo_table_list" border="1">';
            $this->salida .= '<tr align="center" class="modulo_table_list_title">';
            $this->salida .= '<td align="center" width="70%">';
            $this->salida .= "Pacientes";
            $this->salida .= "</td>";
            $this->salida .= '<td align="center">';
            $this->salida .= "Acción";
            $this->salida .= "</td>";
            $this->salida .= '<td align="center" width="10%">Sacar Listado</td>';
            $this->salida .= "</tr>";
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
                    $accion=ModuloGetURL('app','AtencionUrgenciasEnfermeria','user','ClasificarTriage',array('paciente_id'=>$s['paciente_id'], 'tipo_id_paciente'=>$s['tipo_id_paciente'], 'plan_id'=>$s['plan_id'], 'triage_id'=>$s['triage_id'], 'punto_triage_id'=>$s['punto_triage_id'], 'punto_admision_id'=>$s['punto_admision_id'], 'sw_no_atender'=>$s['sw_no_atender']));
                    $this->salida.='<a href="'.$accion.'">Clasificar</a>';
                    $this->salida .= "</td>";
										//sacar listado
                    $this->salida .= '<td align="center">';
                    $accionS=ModuloGetURL('app','AtencionUrgenciasEnfermeria','user','SacarPacienteLista',array('nombre'=>$s['nombre'],'paciente_id'=>$s['paciente_id'], 'tipo_id_paciente'=>$s['tipo_id_paciente'], 'triage_id'=>$s['triage_id']));
                    $this->salida.='<a href="'.$accionS.'">Sacar</a>';
                    $this->salida .= "</td>";
                    $this->salida .= "</tr>";
                }
            }
            $this->salida .= "</table>";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
            $this->salida .= '</table>';
        }
        $this->salida .= "<BR>";
        return true;
    }


		/**
		*
		*/
    function ListadoPacientesAtendidosTriage()
    {
        $arr=$this->PacientesAtendidosTriage();
				$reporte= new GetReports();
        if($arr)
        {
            $this->SetJavaScripts('DatosPaciente');
            $this->salida .= '<table width="80%" align="center" border="0" class="modulo_table">';
            $this->salida .= '<tr align="center" class="modulo_table_title">';
            $this->salida .= '<td>';
            $this->salida .= "PACIENTES CLASIFICADOS POR ESTE PROFESIONAL EN LAS ULTIMAS 12 HORAS";
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
            $this->salida .= '<tr align="center">';
            $this->salida .= '<td align="center">';
            $this->salida .= '<table width="100%" align="center" class="modulo_table_list" border="1">';
            $this->salida .= '<tr align="center" class="modulo_table_list_title">';
            $this->salida .= '<td align="center" width="70%">';
            $this->salida .= "Pacientes";
            $this->salida .= "</td>";
            $this->salida .= '<td align="center" width="10%">Triage</td>';
            $this->salida .= "</tr>";
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
            $this->salida .= "</td>";
            $this->salida .= "</tr>";
            $this->salida .= '</table>';
        }
				else
				{
						$this->salida .= '<table width="80%" align="center">';
						$this->salida .= '<tr align="center">';
						$this->salida .= '<td align="center" class="label_error">';
						$this->salida .= 'NO HAY PACIENTES CLASIFICADOS HOY';
						$this->salida .= "</td>";
						$this->salida .= "</tr>";
						$this->salida .= "</table>";
				}
				unset($reporte);
        $this->salida .= "<BR>";
        return true;
    }


    function ListadoPaciente()
    {

		//print_r($_REQUEST);
		    $_SESSION['HISTORIACLINICA']['RETORNO']['modulo']='AtencionUrgenciasEnfermeria';
        $_SESSION['HISTORIACLINICA']['RETORNO']['metodo']='ListadoPaciente';
        $_SESSION['HISTORIACLINICA']['RETORNO']['tipo']='user';
        $_SESSION['HISTORIACLINICA']['RETORNO']['contenedor']='app';
        if(!empty($_REQUEST['AtencionUrgencias']['empresa_id']))
        {
            $_SESSION['AtencionUrgenciasEnfermeria']=$_REQUEST['AtencionUrgencias'];
            $_SESSION['url_origen']=$_REQUEST['url_origen'];
        }
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
        $this->salida .= $_SESSION['AtencionUrgenciasEnfermeria']['descripcion1'];
        $this->salida .= "</td>";
        $this->salida .= '<td align="center">';
        $this->salida .= $_SESSION['AtencionUrgenciasEnfermeria']['descripcion3'];
        $this->salida .= "</td>";
        $this->salida .= '<td align="center">';
        $this->salida .= $_SESSION['AtencionUrgenciasEnfermeria']['descripcion5'];
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
                                $accion=ModuloHCGetURL(0,'',$j['numerodecuenta'],$_SESSION['AtencionUrgenciasEnfermeria']['hc_modulo_enfermera'],$_SESSION['AtencionUrgenciasEnfermeria']['hc_modulo_medico'],array('estacion'=>$_SESSION['AtencionUrgenciasEnfermeria']['estacion_id'],'HC_DATOS_CONTROL'=>array('CONTROL'=>'HOSPITALIZACION','ESTACION'=>$j[8],'INGRESO'=>$j[3])));
                                $salida .="<a href='$accion'>Atender</a>";
                                $prof=1;
                            }
                            else
                            {
                                if($j['usuario_id']==$_SESSION['SYSTEM_USUARIO_ID'])
                                {
                                    $accion=ModuloHCGetURL($j['evolucion_id'],'',0,$_SESSION['AtencionUrgenciasEnfermeria']['hc_modulo_enfermera'],$_SESSION['AtencionUrgenciasEnfermeria']['hc_modulo_medico'],array('estacion'=>$_SESSION['AtencionUrgenciasEnfermeria']['estacion_id'],'HC_DATOS_CONTROL'=>array('CONTROL'=>'HOSPITALIZACION','ESTACION'=>$j[8],'INGRESO'=>$j[3])));
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
                            $accion=ModuloHCGetURL(0,'',$j['numerodecuenta'],$_SESSION['AtencionUrgenciasEnfermeria']['hc_modulo_enfermera'],$_SESSION['AtencionUrgenciasEnfermeria']['hc_modulo_medico'],array('estacion'=>$_SESSION['AtencionUrgenciasEnfermeria']['estacion_id'],'HC_DATOS_CONTROL'=>array('CONTROL'=>'HOSPITALIZACION','ESTACION'=>$j[8],'INGRESO'=>$j[3])));
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
       // print_r($_SESSION['url_origen']);
        if(!empty($_SESSION['url_origen']))
        {
            $accion=ModuloGetURL($_SESSION['url_origen']['contenedor'],$_SESSION['url_origen']['modulo'],$_SESSION['url_origen']['tipo'],$_SESSION['url_origen']['metodo'],array('estacion'=>$_SESSION['AtencionUrgenciasEnfermeria']));
        }
        else
        {
            $accion=ModuloGetURL('app','AtencionUrgenciasEnfermeria','','');
        }
        $this->salida .='<form name="volver" method="post" action="'.$accion.'">';
        $this->salida .='<input type="submit" name="VOLVER" value="VOLVER" class="input-submit">';
        $this->salida .='</form>';
        $this->salida .='</td>';
        $this->salida .='<td align="center">';
        $accion=ModuloGetURL('app','AtencionUrgenciasEnfermeria','','ListadoPaciente');
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


}
?>
