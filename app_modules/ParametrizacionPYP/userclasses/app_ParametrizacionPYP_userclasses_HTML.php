
<?php

/**
* Modulo de PyP (PHP).
*
//*
*
* @author Carlos A. Henao <carlosarturohenao@gmail.com>
* @version 1.0
* @package SIIS
**/

/**
* app_ParametrizacionPYP_userclasses_HTML.php
*
//*
**/
IncludeClass("ClaseHTML");
class app_ParametrizacionPYP_userclasses_HTML extends app_ParametrizacionPYP_user
{
    function app_ParametrizacionPYP_userclasses_HTML()
    {
        $this->app_ParametrizacionPYP_user(); //Constructor del padre 'modulo'
        $this->salida='';
        return true;
    }
    
    //Determina las empresas, en las cuales el usuario tiene permisos
    //Selecciona las empresas disponibles
    function PrincipalPyP()
    {
        UNSET($_SESSION['pyp']);
        if($this->UsuariosPyP()==false)
        {
            return false;
        }
        return true;
    }
    
    /**
    * La funcion FormaMensaje se encarga de retornar un mensaje para el usuario
    * @return boolean
    * @param string mensaje a retornar para el usuario
    * @param string titulo de la ventana a mostrar
    * @param string lugar a donde debe retornar la ventana
    * @param boolean tipo boton de la ventana
    */
    function FormaMensaje($mensaje,$titulo,$accion,$boton,$origen){
        $this->salida .= ThemeAbrirTabla($titulo,'70%');
        $this->salida .= "                <table class=\"normal_10\" width=\"60%\" align=\"center\">";
        $this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
        $this->salida .= "                     <tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>";
        if($boton){
            $this->salida .= "                     <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"AceptarMen\" value=\"$boton\"></td></tr>";
        }
      else{
            $this->salida .= "                     <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"AceptarMen\" value=\"Aceptar\">";
            if($origen==1){
        $this->salida .= "                    <input class=\"input-submit\" type=\"submit\" name=\"CancelarProceso\" value=\"Cancelar\">";
            }
            $this->salida .= "                     </td></tr>";
      }
        $this->salida .= "               </form>";
        $this->salida .= "               </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }
 
	function FrmParametrizacionProgramas()
	{
		$this->salida .= ThemeAbrirTabla('PARAMETRIZACION DE PROGRAMAS PYP','80%');
		$this->salida .= "                <table class=\"normal_10\" width=\"50%\" align=\"center\" border=\"0\">";
		$accion=ModuloGetURL('app','ParametrizacionPYP','user','PrincipalPyP');
		
		$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
		$this->salida .= "                     <tr><td colspan=\"2\" class=\"modulo_table_title\" align=\"center\">MODULOS ADMINISTRACIÓN";
		$this->salida .= "                     </td></tr>";
		$datosPrograma=$this->TraerDatosPyP();
		$k=0;
		for($i=0;$i<sizeof($datosPrograma);$i++)
		{
			if($k%2==0)
				$estilo='modulo_list_oscuro';
			else
				$estilo='modulo_list_claro';
				
			$accion=ModuloGetURL('app','ParametrizacionPYP','user','FrmControlCPN',array('programa'=>$datosPrograma[$i][programa_id]));
			$this->salida .= "                     <tr class=\"$estilo\"><td colspan=\"2\"  align=\"center\"><label  class=\"label\"><a href=\"$accion\">".strtoupper($datosPrograma[$i][descripcion])."</a></label></td></tr>";
			$k++;
		}
		$this->salida .= "                     <tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"AceptarMen\" value=\"VOLVER\">";
		$this->salida .= "                     </td></tr>";
		$this->salida .= "               </form>";
		$this->salida .= "               </table>";
		$this->salida .= ThemeCerrarTabla();
		
		return true;
	}

	function FrmControlCPN()
	{
				if(!empty($_REQUEST['programa']))
					$_SESSION['programa']=$_REQUEST['programa'];
				switch($_SESSION['programa'])
				{
					case 1:
						$titulo='CONTROL CPN';
					break;
					
					case 2:
						$titulo='CONTROL RENOPROTECCION';
					break;
					
					case 3:
						$titulo='CONTROL PLANIFICACION FAMILIAR';
					break;
				}
				$this->salida .= ThemeAbrirTabla($titulo,'80%');
				$this->salida .= "                <table class=\"normal_10\" width=\"50%\" align=\"center\" border=\"0\">";
				$accion=ModuloGetURL('app','ParametrizacionPYP','user','FrmParametrizacionProgramas');
				$accion1=ModuloGetURL('app','ParametrizacionPYP','user','FrmConfiguracionAyudas');
				$accion2=ModuloGetURL('app','ParametrizacionPYP','user','FrmCronogramaCitasyProcedimientos');
				$accion3=ModuloGetURL('app','ParametrizacionPYP','user','FrmProtocolosAtencion');
				$accion4=ModuloGetURL('app','ParametrizacionPYP','user','FrmReportesSeguimiento');
				$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$this->salida .= "                     <tr><td colspan=\"2\" class=\"modulo_table_title\" align=\"center\">MENU ADMINISTRATIVO";
				$this->salida .= "                     </td></tr>";
				switch($_SESSION['programa'])
				{
					case 1:
						$this->salida .= "                     <tr><td colspan=\"2\" class=\"modulo_list_oscuro\" align=\"center\"><label  class=\"label\"><a href=\"$accion1\">AYUDAS MEMORIAS</a></label></td></tr>";
						$this->salida .= "                     <tr><td colspan=\"2\" class=\"modulo_list_claro\" align=\"center\"><label  class=\"label\"><a href=\"$accion2\">CRONOGRAMA DE CITAS Y PROCEDIMIENTOS</a></label></td></tr>";
						$this->salida .= "                     <tr><td colspan=\"2\" class=\"modulo_list_oscuro\" align=\"center\"><label  class=\"label\"><a href=\"$accion3\">PROTOCOLOS DE ATENCIÓN</a></label></td></tr>";
						$this->salida .= "                     <tr><td colspan=\"2\" class=\"modulo_list_claro\" align=\"center\"><label  class=\"label\"><a href=\"$accion4\">REPORTES DE GESTIÓN</a></label></td></tr>";
						$this->salida .= "                     <tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"AceptarMen\" value=\"VOLVER\"></td></tr>";
					break;
					case 2:
						$this->salida .= "                     <tr><td colspan=\"2\" class=\"modulo_list_oscuro\" align=\"center\"><label  class=\"label\"><a href=\"$accion1\">AYUDAS MEMORIAS</a></label></td></tr>";
						$this->salida .= "                     <tr><td colspan=\"2\" class=\"modulo_list_oscuro\" align=\"center\"><label  class=\"label\"><a href=\"$accion3\">PROTOCOLOS DE ATENCIÓN</a></label></td></tr>";
						$this->salida .= "                     <tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"AceptarMen\" value=\"VOLVER\"></td></tr>";
					break;
					case 3:
						$this->salida .= "                     <tr><td colspan=\"2\" class=\"modulo_list_oscuro\" align=\"center\"><label  class=\"label\"><a href=\"$accion1\">AYUDAS MEMORIAS</a></label></td></tr>";
						$this->salida .= "                     <tr><td colspan=\"2\" class=\"modulo_list_oscuro\" align=\"center\"><label  class=\"label\"><a href=\"$accion3\">PROTOCOLOS DE ATENCIÓN</a></label></td></tr>";
						$this->salida .= "                     <tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"AceptarMen\" value=\"VOLVER\"></td></tr>";
					break;
				}
				$this->salida .= "                     ";
				$this->salida .= "               </form>";
				$this->salida .= "               </table>";
				$this->salida .= ThemeCerrarTabla();
				return true;
	}

	function FrmConfiguracionAyudas()
	{
				$this->salida .= ThemeAbrirTabla('CONFIGURACIÓN DE AYUDAS','80%');
				$this->salida .= "                <table class=\"normal_10\" width=\"30%\" align=\"center\" border=\"0\">";
				$accion=ModuloGetURL('app','ParametrizacionPYP','user','FrmControlCPN');
				$accion1=ModuloGetURL('app','ParametrizacionPYP','user','FrmAyudasUsuario');
				$accion2=ModuloGetURL('app','ParametrizacionPYP','user','FrmAyudasPaciente');
				$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$this->salida .= "                     <tr><td colspan=\"2\" class=\"modulo_table_title\" align=\"center\">MENU";
				$this->salida .= "                     </td></tr>";
				$this->salida .= "                     <tr><td colspan=\"2\" class=\"modulo_list_oscuro\" align=\"center\"><label  class=\"label\"><a href=\"$accion1\">AYUDAS EDUCATIVAS USUARIO</a></label></td></tr>";
				$this->salida .= "                     <tr><td colspan=\"2\" class=\"modulo_list_claro\" align=\"center\"><label  class=\"label\"><a href=\"$accion2\">AYUDAS EDUCATIVAS PACIENTES</a></label></td></tr>";
				$this->salida .= "                     <tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"AceptarMen\" value=\"VOLVER\">";
				$this->salida .= "                     </td></tr>";
				$this->salida .= "               </form>";
				$this->salida .= "               </table>";
				$this->salida .= ThemeCerrarTabla();
				return true;
	}

	function FrmAyudasUsuario()
	{
				$this->salida .= ThemeAbrirTabla('CONFIGURACIÓN DE AYUDAS EDUCATIVAS USUARIO','80%');
				if ($this->uno == 1)
				{
						$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
						$this->salida .= $this->SetStyle("MensajeError");
						$this->salida .= "      </table><br>";       
				}
				if($_REQUEST['ayuda'])
				{
					$datos=$this->TraerAyudasEditar($_SESSION['programa'],$_REQUEST['ayuda'],'USUARIO');
					$_REQUEST['tema']=$datos[tema];
					$_REQUEST['contenido']=$datos[contenido];
				}
				$this->salida .= "                <table class=\"normal_10\" width=\"60%\" align=\"center\" border=\"0\">";
				$accion=ModuloGetURL('app','ParametrizacionPYP','user','GuardarAyudasUsuario');
				$accion1=ModuloGetURL('app','ParametrizacionPYP','user','FrmAyudasUsuario');
				$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$this->salida .= "                     <tr><td width=\"10%\" class=\"label\" align=\"left\"><label class=\"".$this->SetStyle("tema")."\" width=\"10%\">TEMA</label></td><td width=\"90%\" class=\"label\" align=\"center\"><input type=\"text\" name=\"tema\" size=\"40\" value=\"".$_REQUEST['tema']."\" class=\"input-text\"></td></tr>";
				$this->salida .= "                     <tr><td width=\"10%\" class=\"label\" align=\"left\"><label class=\"".$this->SetStyle("contenido")."\" width=\"10%\">CONTENIDO</label></td>";
				$this->salida .= "                     <td width=\"90%\" class=\"label\" align=\"center\"><textarea name=\"contenido\" cols=\"80\" rows=\"5\" style = \"width:78%\" class=\"textarea\">".$_REQUEST['contenido']."</textarea></td></tr>";
				$this->salida .= "                     <input type=\"hidden\" name=\"programa\" value=\"".$_SESSION['programa']."\">";
				$this->salida .= "                     <input type=\"hidden\" name=\"ayuda\" value=\"".$_REQUEST['ayuda']."\">";
				$this->salida .= "                     <tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"AceptarMen\" value=\"GUARDAR\">";
				$this->salida .= "                     </td></tr>";
				$this->salida .= "               </form>";
				$this->salida .= "               </table>";
				$this->salida .= "               <BR>";
				$this->salida .= "               <BR>";

				$this->salida .= "                <table class=\"normal_10\" width=\"75%\" align=\"center\" border=\"0\">";
				$accion=ModuloGetURL('app','ParametrizacionPYP','user','FrmConfiguracionAyudas');
				$accion1=ModuloGetURL('app','ParametrizacionPYP','user','FrmAyudasUsuario');
				$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$datosAyudas=$this->TraerAyudas('USUARIO');
				if(sizeof($datosAyudas)>0)
				{
					$this->salida .= "                     <tr>";
					$this->salida .= "											<td width=\"80%\" class=\"modulo_table_title\" align=\"center\">AYUDAS EDUCATIVAS</td>";
					$this->salida .= "											<td width=\"10%\" class=\"modulo_table_title\" align=\"center\">MOD</td>";
					$this->salida .= "											<td width=\"10%\" class=\"modulo_table_title\" align=\"center\">ELIM</td>";
					$this->salida .= "                     </tr>";
					$k=0;
					for($i=0; $i<sizeof($datosAyudas);$i++)
					{
						if($k % 2 == 0)
							$estilo='modulo_list_oscuro';
						else
							$estilo='modulo_list_claro';
							
						$this->salida .= "											<tr class=\"$estilo\">";
						$this->salida .= "											<td width=\"80%\" align=\"left\">".$datosAyudas[$i][tema]."</td>";
						$accion1=ModuloGetURL('app','ParametrizacionPYP','user','FrmAyudasUsuario',array('programa'=>$datosAyudas[$i][programa_id],'ayuda'=>$datosAyudas[$i][ayuda_id]));
						$accion2=ModuloGetURL('app','ParametrizacionPYP','user','EliminarAyudasUsuario',array('programa'=>$datosAyudas[$i][programa_id],'ayuda'=>$datosAyudas[$i][ayuda_id]));
						$this->salida .= "											<td width=\"10%\" align=\"center\"><a href=\"$accion1\"><img src=\"".GetThemePath()."/images/pmodificar.png\" border=\"0\" title=\"MODIFICAR\"></a></td>";
						$this->salida .= "											<td width=\"10%\" align=\"center\"><a href=\"$accion2\"><img src=\"".GetThemePath()."/images/elimina.png\" border=\"0\"  title=\"ELIMINAR\"></a></td>";
						$this->salida .= "											</tr>";
						$k++;
					}
				}
				$this->salida .= "                     <tr><td colspan=\"3\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"AceptarMen\" value=\"VOLVER\">";
				$this->salida .= "                     </td></tr>";
				$this->salida .= "               </form>";
				$this->salida .= "               </table>";
				$this->salida .= ThemeCerrarTabla();
				return true;
	}

	function FrmAyudasPaciente()
	{
				$this->salida .= ThemeAbrirTabla('CONFIGURACIÓN DE AYUDAS EDUCATIVAS PACIENTES','80%');
				if ($this->uno == 1)
				{
					$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
					$this->salida .= $this->SetStyle("MensajeError");
					$this->salida .= "      </table><br>";       
				}
				if($_REQUEST['ayuda'])
				{
					$datos=$this->TraerAyudasEditar($_SESSION['programa'],$_REQUEST['ayuda'],'PACIENTES');
					$_REQUEST['tema']=$datos[tema];
					$_REQUEST['ubicacion']=$datos[nombre_archivo];
				}
				$this->salida .= "                <table class=\"normal_10\" width=\"60%\" align=\"center\" border=\"0\">";
				$accion=ModuloGetURL('app','ParametrizacionPYP','user','GuardarAyudasPaciente');
				$accion1=ModuloGetURL('app','ParametrizacionPYP','user','FrmAyudasUsuario');
				$this->salida .= "            <form name=\"formarchivo\" action=\"$accion\" method=\"post\" enctype=\"multipart/form-data\">";
				$this->salida .= "                     <tr><td width=\"15%\" class=\"label\" align=\"left\"><label class=\"".$this->SetStyle("tema")."\" width=\"15%\">TEMA</label></td><td width=\"85%\" class=\"label\" align=\"left\"><input type=\"text\" name=\"tema\" size=\"40\" value=\"".$_REQUEST['tema']."\" class=\"input-text\"></td></tr>";
		
				if ($_REQUEST['ubicacion'])
				{
					$ruta=explode('/',$_REQUEST['ubicacion']);
					$this->salida .= "                     <tr><td colspan=\"2\" align=\"left\">Archivo actual:&nbsp;&nbsp;&nbsp;&nbsp;<label class=\"label_mark\">".$ruta[4]."/".$ruta[5]."/".$ruta[6]."</label>";
					$this->salida .= "                     </td></tr>";
				}
				$this->salida .= "                     <tr><td width=\"15%\" class=\"label\" align=\"left\"><label class=\"".$this->SetStyle("ubicacion")."\" width=\"55%\">ARCHIVO</label></td>";
				$this->salida .= "                     <td width=\"85%\" colspan=\"2\" align=\"left\"><input name=\"ubicacion\" type=\"file\" value=\"".$_REQUEST['ubicacion']."\" class=\"input-text\"></td></tr>";
				$this->salida .= "                     <input type=\"hidden\" name=\"userfilename\" id=\"userfilename\">";
				$this->salida .= "                     <tr><td width=\"25%\" colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" value=\"Enviar\"></td></tr>";
				$this->salida .= "                     <input type=\"hidden\" name=\"ayuda\" value=\"".$_REQUEST['ayuda']."\">";
				$this->salida .= "               </form>";
				$this->salida .= "               </table>";
				$this->salida .= "               <BR>";
				$this->salida .= "               <BR>";

				$this->salida .= "<table class=\"normal_10\" width=\"75%\" align=\"center\" border=\"0\">";
				$accion=ModuloGetURL('app','ParametrizacionPYP','user','FrmConfiguracionAyudas');
				$accion1=ModuloGetURL('app','ParametrizacionPYP','user','FrmAyudasUsuario');
				$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$datosAyudas=$this->TraerAyudas('PACIENTES');
				if(sizeof($datosAyudas)>0)
				{
					$this->salida .= "                     <tr>";
					$this->salida .= "											<td width=\"80%\" class=\"modulo_table_title\" align=\"center\">AYUDAS EDUCATIVAS</td>";
					$this->salida .= "											<td width=\"10%\" class=\"modulo_table_title\" align=\"center\">MOD</td>";
					$this->salida .= "											<td width=\"10%\" class=\"modulo_table_title\" align=\"center\">ELIM</td>";
					$this->salida .= "                     </tr>";
					$k=0;
					for($i=0; $i<sizeof($datosAyudas);$i++)
					{
						if($k%2==0)
							$estilo='modulo_list_oscuro';
						else
							$estilo='modulo_list_claro';
							
						$this->salida .= "											<tr class=\"$estilo\">";
						$this->salida .= "											<td width=\"80%\" align=\"left\">".$datosAyudas[$i][tema]."</td>";
						$accion1=ModuloGetURL('app','ParametrizacionPYP','user','FrmAyudasPaciente',array('programa'=>$datosAyudas[$i][programa_id],'ayuda'=>$datosAyudas[$i][ayuda_educativa_id]));
						$accion2=ModuloGetURL('app','ParametrizacionPYP','user','EliminarAyudasPaciente',array('programa'=>$datosAyudas[$i][programa_id],'ayuda'=>$datosAyudas[$i][ayuda_educativa_id]));
						$this->salida .= "											<td width=\"10%\" align=\"center\"><a href=\"$accion1\"><img src=\"".GetThemePath()."/images/pmodificar.png\" border=\"0\" title=\"MODIFICAR\"></a></td>";
						$this->salida .= "											<td width=\"10%\" align=\"center\"><a href=\"$accion2\"><img src=\"".GetThemePath()."/images/elimina.png\" border=\"0\"  title=\"ELIMINAR\"></a></td>";
						$this->salida .= "											</tr>";
						$k++;
					}
				}
				$this->salida .= "                     <tr><td colspan=\"3\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"AceptarMen\" value=\"VOLVER\">";
				$this->salida .= "                     </td></tr>";
				$this->salida .= "               </form>";
				$this->salida .= "               </table>";
				$this->salida .= ThemeCerrarTabla();
				return true;
	}
	
	function FrmCronogramaCitasyProcedimientos()
	{
				$this->salida .= ThemeAbrirTabla('CRONOGRAMA DE CITAS Y PROCEDIMIENTOS','1500');
				$periodos=9;
				
				if($_REQUEST['Guardar'])
				{
					$this->GuardarCronogramaCitas($_REQUEST);
				}

				if ($this->ban == 1)
				{
						$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
						$this->salida .= $this->SetStyle("MensajeError");
						$this->salida .= "      </table><br>";       
				}
				
				$cronograma=$this->GetPeriodosPrograma();
				
				$accion=ModuloGetURL('app','ParametrizacionPYP','user','FrmCronogramaCitasyProcedimientos');
				$this->salida .= "            	<form name=\"forma_crono\" action=\"$accion\" method=\"post\">";
				$this->salida .= "		<table class=\"normal_10\" width=\"100%\"align=\"center\" border=\"0\">";
				$this->salida .= "                     	<tr class=\"modulo_table_title\">";
				$this->salida .= "                    	 <td width=\"5%\"> </td>";
				for($i=1;$i<=$periodos;$i++)
				{
				$this->salida .= "                    	 <td>Periodo $i</td>";
				}
				$this->salida .= "                    	 <td width=\"5%\"> </td>";
				$this->salida .= "                    	</tr>";
				$this->salida .= "                     	<tr class=\"modulo_table_title\">";
				$this->salida .= "                    	 <td width=\"5%\">Rango Semanas &nbsp;&nbsp;&nbsp;&nbsp;[ de / a ] </td>";
				
				for($i=0;$i<$periodos;$i++)
				{	
						$ban=0;
						$this->salida .= " <td> ";				
						$this->salida .= "	<select name=\"rango_inicio$i\" class=\"select\"> 
										<option value=\"-1\">--</option>";
										if(sizeof($cronograma)>0)
										{
											for($j=0;$j<sizeof($cronograma);$j++)
											{
												if($cronograma[$j][periodo_metrica]==$i && $ban==0)
												{
													for($k=0;$k<46;$k++)
													{
														if($cronograma[$j][rango_inicio]==$k)											
															$this->salida .= "							<option value=\"$k\" selected>".$k."</option>";
														else
															$this->salida .= "							<option value=\"$k\">".$k."</option>";	
													}
													$ban=1;						
												}
											}
											if($ban!=1)
											{
												for($k=0;$k<46;$k++)
												{
													if($_REQUEST['rango_inicio'.$i]==$k)
														$this->salida .= " <option value=\"$k\" selected>".$k."</option>";	
													else
														$this->salida .= " <option value=\"$k\">".$k."</option>";	
												}
											}
										}
										else
										{
											for($k=0;$k<46;$k++)
											{
												if($_REQUEST['rango_inicio'.$i]==$k)
													$this->salida .= " <option value=\"$k\" selected>".$k."</option>";	
												else
													$this->salida .= " <option value=\"$k\">".$k."</option>";	
											}
																		
										}
										$this->salida .= "	</select>";
										$this->salida .= " / <select name=\"rango_fin$i\" class=\"select\"> 
												<option value=\"-1\">--</option>";
										if(sizeof($cronograma)>0)
										{
											for($j=0;$j<sizeof($cronograma);$j++)
											{
												if($cronograma[$j][periodo_metrica]==$i && $ban==1)
												{
													
													for($k=0;$k<46;$k++)
													{
														if($cronograma[$j][rango_fin]==$k)											
															$this->salida .= "							<option value=\"$k\" selected>".$k."</option>";
														else
															$this->salida .= "							<option value=\"$k\">".$k."</option>";	
													}
													$ban=2;						
												}
											}
											if($ban!=2)
											{
												for($k=0;$k<46;$k++)
												{
													if($_REQUEST['rango_fin'.$i]==$k)
														$this->salida .= " <option value=\"$k\" selected>".$k."</option>";	
													else
														$this->salida .= " <option value=\"$k\">".$k."</option>";	
												}
											}
										}
										else
										{
											for($k=0;$k<46;$k++)
											{
												if($_REQUEST['rango_fin'.$i]==$k)
													$this->salida .= " <option value=\"$k\" selected>".$k."</option>";	
												else
													$this->salida .= " <option value=\"$k\">".$k."</option>";	
											}					
										}
						
						$this->salida .= "			</select>";				
						$this->salida .= "  </td>";
				}

				$this->salida .= "                    	 <td rowspan=\"3\">Observacion</td>";
				$this->salida .= "					</tr>";
				$this->salida .= "					<tr class=\"modulo_table_title\">";
				$this->salida .= "                    	 <td>Semana Gestacion</td>";
				for($i=0;$i<$periodos;$i++)
				{
						$ban=0;
						$this->salida .= " <td align=\"center\">";
						$this->salida .= "	<select name=\"rango_media$i\" class=\"select\"> 
															<option value=\"-1\">--</option>";
										if(sizeof($cronograma)>0)
										{
											for($j=0;$j<sizeof($cronograma);$j++)
											{
												if($cronograma[$j][periodo_metrica]==$i && $ban==0)
												{
													for($k=0;$k<46;$k++)
													{
														if($cronograma[$j][rango_media]==$k)											
						$this->salida .= "							<option value=\"$k\" selected>".$k."</option>";
														else
						$this->salida .= "							<option value=\"$k\">".$k."</option>";	
													}
													$ban=1;						
												}
											}
											if($ban!=1)
											{
												for($k=0;$k<46;$k++)
												{
													if($_REQUEST['rango_media'.$i]==$k)
														$this->salida .= " <option value=\"$k\" selected>".$k."</option>";	
													else
														$this->salida .= " <option value=\"$k\">".$k."</option>";	
												}
											}
										}
										else
										{
											for($k=0;$k<46;$k++)
											{
												if($_REQUEST['rango_media'.$i]==$k)
													$this->salida .= " <option value=\"$k\" selected>".$k."</option>";	
												else
													$this->salida .= " <option value=\"$k\">".$k."</option>";	
											}
										}
						
						$this->salida .= "	</select>";	
						$this->salida .= " </td>";
				}
				
				$this->salida .= "               	</tr>";
				$this->salida .= "                     	<tr class=\"modulo_table_title\">";
				$this->salida .= "                    	 <td>Profesional</td>";
				
				$tp=$this->GetPeriodosProgramaProfesional();
				
				for($i=0;$i<$periodos;$i++)
				{
					$tipo1=0;
					$tipo2=0;
					
					if(sizeof($cronograma)>0)
					{
						for($j=0;$j<sizeof($tp);$j++)
						{
							if($tp[$j][periodo_id]==($i+1))
							{
								if($tp[$j][tipo_profesional]=='2')
								{
									$tipo1=2;
								}
								if($tp[$j][tipo_profesional]=='3')
								{
									$tipo2=3;	
								}
							}
						}
						
						if($tipo1==2 && $tipo2!=3)
						{
							$this->salida .= "<td>MED<input type=\"checkbox\" name=\"medico$i\" value=\"2\" checked> 
												ENF<input type=\"checkbox\" name=\"enfermera$i\" value=\"3\"></td>";
						}
						else 
						if($tipo2==3 && $tipo1!=2)
						{
							$this->salida .= "<td>MED<input type=\"checkbox\" name=\"medico$i\" value=\"2\"> 
												ENF<input type=\"checkbox\" name=\"enfermera$i\" value=\"3\" checked></td>";
						}
						else 
						if($tipo1==2 && $tipo2==3)
						{
							$this->salida .= "<td>MED<input type=\"checkbox\" name=\"medico$i\" value=\"2\" checked> 
												ENF<input type=\"checkbox\" name=\"enfermera$i\" value=\"3\" checked></td>";
						}
						else
						{
							if(!empty($_REQUEST['medico'.$i]) && !empty($_REQUEST['enfermera'.$i]))
							{
								$this->salida .= "<td>MED<input type=\"checkbox\" name=\"medico$i\" value=\"2\" checked> 
												ENF<input type=\"checkbox\" name=\"enfermera$i\" value=\"3\" checked></td>";	
							}
							elseif(!empty($_REQUEST['medico'.$i]))
							{
								$this->salida .= "<td>MED<input type=\"checkbox\" name=\"medico$i\" value=\"2\" checked> 
												ENF<input type=\"checkbox\" name=\"enfermera$i\" value=\"3\"></td>";	
							}elseif(!empty($_REQUEST['enfermera'.$i]))
							{
								$this->salida .= "<td>MED<input type=\"checkbox\" name=\"medico$i\" value=\"2\"> 
												ENF<input type=\"checkbox\" name=\"enfermera$i\" value=\"3\" checked></td>";	
							}
							else
							{
								$this->salida .= "<td>MED<input type=\"checkbox\" name=\"medico$i\" value=\"2\"> 
												ENF<input type=\"checkbox\" name=\"enfermera$i\" value=\"3\"></td>";	
							}
						}
					}
					else
					{
						if(!empty($_REQUEST['medico'.$i]) && !empty($_REQUEST['enfermera'.$i]))
						{
							$this->salida .= "<td>MED<input type=\"checkbox\" name=\"medico$i\" value=\"2\" checked> 
											ENF<input type=\"checkbox\" name=\"enfermera$i\" value=\"3\" checked></td>";	
						}
						elseif(!empty($_REQUEST['medico'.$i]))
						{
							$this->salida .= "<td>MED<input type=\"checkbox\" name=\"medico$i\" value=\"2\" checked> 
											ENF<input type=\"checkbox\" name=\"enfermera$i\" value=\"3\"></td>";	
						}elseif(!empty($_REQUEST['enfermera'.$i]))
						{
							$this->salida .= "<td>MED<input type=\"checkbox\" name=\"medico$i\" value=\"2\"> 
											ENF<input type=\"checkbox\" name=\"enfermera$i\" value=\"3\" checked></td>";	
						}
						else
						{
							$this->salida .= "<td>MED<input type=\"checkbox\" name=\"medico$i\" value=\"2\"> 
											ENF<input type=\"checkbox\" name=\"enfermera$i\" value=\"3\"></td>";	
						}
					}
				}
				$this->salida .= "               	</tr>";

				$proc=$this->GetCronogramaCitas();
				
				$this->salida .= "<input type=\"hidden\" name=\"numproT\" value=\"".sizeof($proc)."\"";
				
				for($k=0;$k<sizeof($proc);$k++)
				{
					$this->salida .= "<input type=\"hidden\" name=\"cargos$k\" value=\"".$proc[$k][cargo]."\"";
					
					if($i % 2 == 0)
						$estilo="modulo_list_oscuro";
					else
						$estilo="modulo_list_claro";
						
					$this->salida .= "                     	<tr class=\"$estilo\">";
					if(empty($proc[$k][alias]))
						$descripcion=$proc[$k][descripcion];
					else
						$descripcion=$proc[$k][alias];
					
					$this->salida .= "                    	 <td>".strtoupper($descripcion)."</td>";
					for($i=0;$i<$periodos;$i++)
					{
						$bandera=0;
						if(sizeof($cronograma)>0)
						{
							for($j=0;$j<sizeof($cronograma);$j++)
							{
								if($cronograma[$j][periodo_metrica]==$i)
								{
									if($proc[$k][cargo]==$cronograma[$j][cargo_cups])
									{
										$bandera=1;
										break;
									}
								}
							}
							if($bandera==1)
								$this->salida .= " <td align=\"center\"><input type=\"checkbox\" name=\"procedimiento$k$i\" value=\"".$proc[$k][cargo]."\" checked></td>";
							else
							{
								if($_REQUEST['procedimiento'.$k.$i])
									$this->salida .= " <td align=\"center\"><input type=\"checkbox\" name=\"procedimiento$k$i\" value=\"".$proc[$k][cargo]."\" checked></td>";
								else
									$this->salida .= " <td align=\"center\"><input type=\"checkbox\" name=\"procedimiento$k$i\" value=\"".$proc[$k][cargo]."\"></td>";
							}
						}
						else
						{
							if($_REQUEST['procedimiento'.$k.$i])
								$this->salida .= " <td align=\"center\"><input type=\"checkbox\" name=\"procedimiento$k$i\" value=\"".$proc[$k][cargo]."\" checked></td>";
							else
								$this->salida .= " <td align=\"center\"><input type=\"checkbox\" name=\"procedimiento$k$i\" value=\"".$proc[$k][cargo]."\"></td>";
						}
					}
					$this->salida .= "                    	 <td><textarea class=\"textarea\" name=\"observacion$k\" cols=\"22\" rows=\"4\">".$proc[$k][observacion]."</textarea></td>";
					$this->salida .= "               	</tr>";
				}
				
				$this->salida .= "            <input type=\"hidden\" name=\"numero_pro\" value=\"".sizeof($proc)."\">";
				$this->salida .= "               </table>";	
				$this->salida .= "               <br>";
				$this->salida .= "               <br>";
				$this->salida .= "             	 <table class=\"normal_10\" width=\"75%\" align=\"center\" border=\"0\" celspacing=\"10\">";
				$this->salida .= "                     <tr><td align=\"right\"><input class=\"input-submit\" type=\"submit\" name=\"Guardar\" value=\"GUARDAR\">";
				$this->salida .= "                     </td>";
				$this->salida .= "               </form>";
							 
				$accion1=ModuloGetURL('app','ParametrizacionPYP','user','FrmControlCPN');
				$this->salida .= "              <form name=\"formabuscar\" action=\"$accion1\" method=\"post\">";
				$this->salida .= "                     <td align=\"left\"><input class=\"input-submit\" type=\"submit\" name=\"AceptarMen\" value=\"VOLVER\">";
				$this->salida .= "                     </td></tr>";
				$this->salida .= "              </form>";
				$this->salida .= "             </table></center>";
				$this->salida .= ThemeCerrarTabla();
				return true;
	}
	
	
	function FrmProtocolosAtencion()
	{
				$this->salida .= ThemeAbrirTabla('PROTOCOLOS DE ATENCION','80%');

				if($_REQUEST['proAten'])
				{
					$datos=$this->TraerProtocolosAtencion($_REQUEST['proAten']);
					$_REQUEST['nombre']=$datos[0][nombre];
					$_REQUEST['url']=$datos[0][url];
				}
			
				if($_REQUEST['Eliminar'])
				{
					$this->EliminarProtocoloAtencion($_REQUEST['eliminar']);
				}
				
				if ($this->ban==1)
				{
						$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
						$this->salida .= $this->SetStyle("MensajeError");
						$this->salida .= "      </table><br>";       
				}
				
				$accion=ModuloGetURL('app','ParametrizacionPYP','user','AdicionarProtocoloAtencion');
				
				$this->salida .= "            <table class=\"normal_10\" width=\"60%\" align=\"center\" border=\"0\">";
				$this->salida .= "            	<form name=\"forma\" action=\"$accion\" method=\"post\">";
				$this->salida .= "                     <tr><td width=\"25%\" class=\"label\" align=\"left\"><label class=\"".$this->SetStyle("nombre")."\" width=\"25%\"> NOMBRE </label>
									</td><td width=\"75%\" class=\"label\" align=\"left\"><input type=\"text\" name=\"nombre\" size=\"40\" value=\"".$_REQUEST['nombre']."\" class=\"input-text\"></td></tr>";
				$this->salida .= "                     <tr><td width=\"25%\" class=\"label\" align=\"left\"><label class=\"".$this->SetStyle("url")."\" width=\"25%\"> URL </label></td>";
				$this->salida .= "                     <td width=\"75%\" class=\"label\" align=\"left\"><input type=\"text\" name=\"url\" size=\"50\" value=\"".$_REQUEST['url']."\" class=\"input-text\"></td></tr>";
				$this->salida .= "                     <input type=\"hidden\" name=\"protocolo\" value=\"".$_REQUEST['proAten']."\">";
				$this->salida .= "                     <input type=\"hidden\" name=\"programa\" value=\"".$_SESSION['programa']."\">";
				$this->salida .= "                     <tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"AceptarMen\" value=\"AÑADIR ENLACE\">";
				$this->salida .= "              </td></tr>";
				$this->salida .= "              </form>";
				$this->salida .= "              </table>";
				$this->salida .= "              <BR>";
				$this->salida .= "              <BR>";

				$this->salida .= "                <table class=\"normal_10\" width=\"75%\" align=\"center\" border=\"0\">";
				$accion=ModuloGetURL('app','ParametrizacionPYP','user','FrmProtocolosAtencion');
				
				$this->salida .= "          <form name=\"forma_lista\" action=\"$accion\" method=\"post\">";
				$protocolos=$this->GetProtocolosAtencion();
				$k=0;
				if(sizeof($protocolos)>0)
				{
					$this->salida .= "                     <tr class=\"modulo_table_title\">";
					$this->salida .= "											<td width=\"80%\">LISTA DE PROTOCOLOS DE ATENCION DISPONIBLES </td>";
					$this->salida .= "											<td width=\"10%\"> MODIFICAR </td>";
					$this->salida .= "											<td width=\"10%\"> ELIMINAR </td>";
					$this->salida .= "                     </tr>";
					
					for($i=0; $i<sizeof($protocolos);$i++)
					{
						if($k % 2 == 0)
							$estilo="modulo_list_oscuro";
						else
							$estilo="modulo_list_claro";
						$this->salida .= "											<tr class=\"$estilo\">";
						$this->salida .= "											<td width=\"80%\" align=\"left\">".$protocolos[$i][nombre]."</td>";
						$accion1=ModuloGetURL('app','ParametrizacionPYP','user','FrmProtocolosAtencion',array('proAten'=>$protocolos[$i][protocolo_id]));
					
						$this->salida .= "											<td width=\"10%\" align=\"center\"><a href=\"$accion1\"><img src=\"".GetThemePath()."/images/pmodificar.png\" border=\"0\" title=\"MODIFICAR\"></a></td>";
					
						$this->salida .= "											<td width=\"10%\" align=\"center\"><input type=\"checkbox\" name=\"eliminar[]\" value=\"".$protocolos[$i][protocolo_id]."\"></a></td>";
						$this->salida .= "											</tr>";
						$k++;
					}
				}
				$this->salida .= "                     <tr><td colspan=\"3\" align=\"right\"><br><input class=\"input-submit\" type=\"submit\" name=\"Eliminar\" value=\"ELIMINAR\">";
				$this->salida .= "         	 </form>";
				
				$accion3=ModuloGetURL('app','ParametrizacionPYP','user','FrmControlCPN');
				
				$this->salida .= "         	 <form name=\"formavolver\" action=\"$accion3\" method=\"post\">";
				$this->salida .= "                     <tr><td colspan=\"3\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"AceptarMen\" value=\"VOLVER\">";
				$this->salida .= "                     </td></tr>";
				$this->salida .= "               </form>";
				$this->salida .= "               </table>";
				$this->salida .= ThemeCerrarTabla();
				return true;	
	}
	
	function FrmReportesSeguimiento()
	{
				$this->salida .= ThemeAbrirTabla('MENU REPORTES DE SEGUIMIENTO','80%');
				$accion1=ModuloGetURL('app','ParametrizacionPYP','user','FrmReporteSeguimientoCitas');
				$accion2=ModuloGetURL('app','ParametrizacionPYP','user','FrmEstadisticaGestion');
				$accion3=ModuloGetURL('app','ParametrizacionPYP','user','FrmReporteActividades');
				$this->salida .= "                <table class=\"normal_10\" width=\"50%\" align=\"center\" border=\"0\">";
				$this->salida .= "                     <tr><td colspan=\"2\" class=\"modulo_table_title\" align=\"center\">MENU";
				$this->salida .= "                     </td></tr>";
				$this->salida .= "                     <tr class=\"modulo_list_oscuro\"><td colspan=\"2\" class=\"label\" align=\"center\"><a href=\"$accion1\">REPORTE DE SEGUIMIENTO DE CITAS</a></td></tr>";
				$this->salida .= "                     <tr class=\"modulo_list_claro\"><td colspan=\"2\" class=\"label\" align=\"center\"><a href=\"$accion2\">ESTADISTICAS DE GESTION DE SEGUIMIENTO MENSUAL</a></td></tr>";
				$this->salida .= "                     <tr class=\"modulo_list_oscuro\"><td colspan=\"2\" class=\"label\" align=\"center\"><a href=\"$accion3\">REPORTE MENSUAL DE ACTIVIDADES CPN</a></td></tr>"; 
				$this->salida .= "               </table>";
				$accion=ModuloGetURL('app','ParametrizacionPYP','user','FrmControlCPN');
				$this->salida .= "         	 <form name=\"formavolver\" action=\"$accion\" method=\"post\">";
				$this->salida .= "                     <center><br><input class=\"input-submit\" type=\"submit\" name=\"AceptarMen\" value=\"VOLVER\"></center>";
				$this->salida .= "               </form>";
				$this->salida .= ThemeCerrarTabla();
				return true;
	}
	
	function FrmReporteSeguimientoCitas()
	{
				$this->salida .= ThemeAbrirTabla('REPORTE DE SEGUIMIENTO DE CITAS','1500');
				
				SessionSetVar("GetThemePath",GetThemePath());
				
				$datos=$this->ReporteSeguimientoCitas($_REQUEST);

				$accion=ModuloGetURL('app','ParametrizacionPYP','user','FrmReporteSeguimientoCitas');
				$this->salida .= "            <table class=\"normal_10\" width=\80%\" align=\"center\" border=\"0\">";
				$this->salida .= "            	<form name=\"forma_reporte\" action=\"$accion\" method=\"post\">";
				$this->salida .= "                    <tr class=\"modulo_table_list_title\">";
				$this->salida .= "                    <td colspan=\"4\"> FILTRO </td> ";
				$this->salida .= "                    </tr>";
				$this->salida .= "                    <tr class=\"modulo_table_title\">";
				$this->salida .= "	<td width=\"30%\" align=\"left\">";
				$this->salida .= "		DESDE <input type=\"text\" name=\"fecha_ini\" size=\"8\" value=\"".$_REQUEST['fecha_ini']."\" class=\"input-text\">";
				$this->salida .= "		<sub>".ReturnOpenCalendario("forma_reporte","fecha_ini","-")."</sub>";
				$this->salida .= "		HASTA <input type=\"text\" name=\"fecha_fin\" size=\"8\" value=\"".$_REQUEST['fecha_fin']."\" class=\"input-text\">";
				$this->salida .= "		<sub>".ReturnOpenCalendario("forma_reporte","fecha_fin","-")."</sub>";
				$this->salida .= "	</td>";
				
				$vector = array('TIPO ATENCION','CLASIFICACION RIESGO','TIPO RIESGO','PATOLOGIA ASOCIADA','CUMPLIMIENTO','ACCION DE SEGUIMIENTO');
				
				$this->salida .= "<td  width=\"20%\" align=\"left\">";
				$this->salida .= "						FILTRO";
				$this->salida .= "						<select name=\"filtro\" class=\"select\" onChange=\"EnviarFiltro(document.forma_reporte)\">";
				$this->salida .= "							<option value=\"0\">--SELECCIONE--</option>";
				for($i=0;$i<sizeof($vector);$i++)
					if($_REQUEST['filtro']==$i+1)
						$this->salida .= "							<option value=\"".($i+1)."\" selected>".$vector[$i]."</option>";
					else
						$this->salida .= "							<option value=\"".($i+1)."\">".$vector[$i]."</option>";
				$this->salida .= "						</select>";
				$this->salida .= "					</td>";	
				$this->salida .= "				<td width=\"20%\" id=\"op\" align=\"left\">";
				$this->salida .= "				</td>";
				$this->salida .= "			<td width=\"20%\" align=\"left\">";
				$this->salida .= "						ORDENADO POR  ";
				$this->salida .= "						<select name=\"ordenado_por\" class=\"select\">";
				$this->salida .= "							<option value=\"0\">--SELECCIONE--</option>";
				if($_REQUEST['ordenado_por']==1)
				{
					$this->salida .= "							<option value=\"1\" selected>NOMBRE PACIENTE</option>	";
					$this->salida .= "							<option value=\"2\">FECHA</option>";
				}
				elseif($_REQUEST['ordenado_por']==2)
				{
					$this->salida .= "							<option value=\"1\">NOMBRE PACIENTE</option>	";
					$this->salida .= "							<option value=\"2\" selected>FECHA</option>";
				}
				else
				{
					$this->salida .= "							<option value=\"1\">NOMBRE PACIENTE</option>	";
					$this->salida .= "							<option value=\"2\">FECHA</option>";
				}
				$this->salida .= "						</select>";
				$this->salida .= "					</td>";
				$this->salida .= "		</tr>";
				$this->salida .= "					<input type=\"hidden\" name=\"opcion\" value=\"1\">";
				$this->salida .= "  		<tr>";
				$this->salida .= "					<td colspan=\"4\" align=\"center\"><br>";
				$this->salida .= "						<input class=\"input-submit\" type=\"button\" name=\"AceptarMen\" value=\"FILTRAR REPORTE\" onclick=\"EnviarDatos(document.forma_reporte)\">";
				$this->salida .= "</td>";
				$this->salida .= "				</tr>";
				$this->salida .= "	</form>";
				$this->salida .= "</table>";
				$this->salida .= "<BR>";
				
				$reporte1= new GetReports();
				
				$accionF=ModuloGetURL('app','ParametrizacionPYP','user','FrmProtocolosAtencion');
				
				$_SESSION['DATOS_REPORTE_SEGUIMIENTO_CPN']=$datos;
				
				$this->salida .= "	<script language=\"javascript\">";
				$this->salida .= "		function mOvr(src,clrOver)";
				$this->salida .= "		{";
				$this->salida .= "			src.style.background = clrOver;";
				$this->salida .= "		}";
				$this->salida .= "		function mOut(src,clrIn)";
				$this->salida .= "		{";
				$this->salida .= "			src.style.background = clrIn;";
				$this->salida .= "		}";
				$this->salida .= "	</script>";
		
				
				$this->salida .= "	<div id=\"reporte\" style=\"display:block\">";
				$this->salida .= "   <table class=\"normal_10\" width=\"100%\" align=\"center\" border=\"0\">";
				$this->salida .= "			<tr class=\"modulo_table_list_title\">";
				$this->salida .= "				<td rowspan=\"2\" width=\"10%\">FECHA CONTACTO</td>";
				$this->salida .= "				<td rowspan=\"2\" width=\"20%\">NOMBRE</td>";
				$this->salida .= "				<td colspan=\"2\" width=\"5%\">TIPO ATENCION</td>";
				$this->salida .= "				<td colspan=\"3\" width=\"10%\">CLASIFICACION RIESGO</td>";
				$this->salida .= "				<td colspan=\"2\" width=\"10%\">TIPO RIESGO</td>";
				$this->salida .= "				<td colspan=\"4\" width=\"10%\">PATOLOGIA ASOCIADA</td>";
				$this->salida .= "				<td colspan=\"2\" width=\"5%\">CUMPLIMIENTO CITA</td>";
				$this->salida .= "				<td colspan=\"4\" width=\"20%\">ACCION DE SEGUIMIENTO</td>";
				$this->salida .= "			</tr>";
				$this->salida .= "			<tr class=\"modulo_table_title\">";
				$this->salida .= "				<td align=\"center\" width=\"5%\">1 VEZ</td>";
				$this->salida .= "				<td align=\"center\" width=\"5%\">CONTROL</td>";
				$this->salida .= "				<td align=\"center\" width=\"3%\">BAJO</td>";
				$this->salida .= "				<td align=\"center\" width=\"3%\">ALTO</td>";
				$this->salida .= "				<td align=\"center\" width=\"3%\">SIN RIESGO</td>";
				$this->salida .= "				<td align=\"center\" width=\"5%\">BIOLOGICO</td>";
				$this->salida .= "				<td align=\"center\" width=\"5%\">PSICOSOCIAL</td>";
				$this->salida .= "				<td align=\"center\" width=\"2.5%\">ITU</td>";
				$this->salida .= "				<td align=\"center\" width=\"2.5%\">CERVICOVAGINITIS</td>";
				$this->salida .= "				<td align=\"center\" width=\"2.5%\">HTA</td>";
				$this->salida .= "				<td align=\"center\" width=\"2.5%\">DIABETES GESTASIONAL</td>";
				$this->salida .= "				<td align=\"center\" width=\"5%\">SI</td>";
				$this->salida .= "				<td align=\"center\" width=\"5%\">NO</td>";
				$this->salida .= "				<td align=\"center\" width=\"2.5%\">HALLAZGO EN CONTACTO TELEFONICO</td>";
				$this->salida .= "				<td align=\"center\" width=\"2.5%\">DIRECCIONAMIENTO A OTRA IPS</td>";
				$this->salida .= "				<td align=\"center\" width=\"2.5%\">CAPTACION EFECTIVA</td>";
				$this->salida .= "				<td align=\"center\" width=\"12.5%\">CAUSA</td>";
				$this->salida .= "			</tr>";
				if(!$datos)
				{
					$this->salida .= "			<tr class=\"modulo_list_oscuro\">";
					$this->salida .= "				<td colspan=\"21\" align=\"center\"><label class=\"label_error\">NO SE ENCONTRARON RESGITROS EN LA BUSQUEDA</label></td>";
					$this->salida .= "			</tr>";
				}
				else
				{
					$k=0;
					
					foreach($datos as $reporte)
					{
						if($k % 2 == 0)
						{
							$estilo='modulo_list_oscuro';
							$background = "#CCCCCC";
						}
						else
						{
							$estilo='modulo_list_claro';
							$background = "#DDDDDD";
						}
						
						$this->salida .= "			<tr class=\"$estilo\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>";
						$this->salida .= "				<td align=\"center\" width=\"10%\">".substr($reporte['fecha_contacto'],0,10)."</td>";
						$this->salida .= "				<td align=\"center\" width=\"20%\">".$reporte['nombre_paciente']."</td>";
						
						if($reporte['tipo_atencion']=='PRIMERA ATENCION')
						{
							$this->salida .= "				<td align=\"center\" width=\"5%\"><img src=\"".GetThemePath()."/images/delete.gif\"></td>";
							$this->salida .= "				<td align=\"center\" width=\"5%\">&nbsp;</td>";	
						}
						else if($reporte['tipo_atencion']=='CONTROL')
						{
							$this->salida .= "				<td align=\"center\" width=\"5%\">&nbsp;</td>";	
							$this->salida .= "				<td align=\"center\" width=\"5%\"><img src=\"".GetThemePath()."/images/delete.gif\"></td>";
						}
						else
						{
							$this->salida .= "				<td align=\"center\" width=\"5%\">&nbsp;</td>";
							$this->salida .= "				<td align=\"center\" width=\"5%\">&nbsp;</td>";	
						}
						
						if($reporte['riesgo']=='BAJO')
						{
							$this->salida .= "				<td align=\"center\" width=\"3%\"><img src=\"".GetThemePath()."/images/delete.gif\"></td>";
							$this->salida .= "				<td align=\"center\" width=\"3%\">&nbsp;</td>";	
							$this->salida .= "				<td align=\"center\" width=\"3%\">&nbsp;</td>";	
						}
						elseif($reporte['riesgo']=='ALTO')
						{
							$this->salida .= "				<td align=\"center\" width=\"3%\">&nbsp;</td>";	
							$this->salida .= "				<td align=\"center\" width=\"3%\"><img src=\"".GetThemePath()."/images/delete.gif\"></td>";
							$this->salida .= "				<td align=\"center\" width=\"3%\">&nbsp;</td>";	
						}
						elseif(empty($reporte['riesgo']))
						{
							$this->salida .= "				<td align=\"center\" width=\"3%\">&nbsp;</td>";	
							$this->salida .= "				<td align=\"center\" width=\"3%\">&nbsp;</td>";	
							$this->salida .= "				<td align=\"center\" width=\"3%\"><img src=\"".GetThemePath()."/images/delete.gif\"></td>";
						}
						
						if($reporte['biologico'] OR $reporte['psicosocial'])
						{
							$this->salida .= "				<td align=\"center\">".$reporte['biologico']."</td>";
							$this->salida .= "				<td align=\"center\">".$reporte['psicosocial']."</td>";
						}
						else
						{
							$this->salida .= "				<td align=\"center\">&nbsp;</td>";
							$this->salida .= "				<td align=\"center\">&nbsp;</td>";
						}
						
						if($reporte['itu'])
							$this->salida .= "				<td align=\"center\"><img src=\"".GetThemePath()."/images/delete.gif\"></td>";
						else
							$this->salida .= "				<td align=\"center\">&nbsp;</td>";
						
						if($reporte['cervico'])
							$this->salida .= "				<td align=\"center\"><img src=\"".GetThemePath()."/images/delete.gif\"></td>";
						else
							$this->salida .= "				<td align=\"center\">&nbsp;</td>";
							
						if($reporte['hta'])
							$this->salida .= "				<td align=\"center\"><img src=\"".GetThemePath()."/images/delete.gif\"></td>";
						else
							$this->salida .= "				<td align=\"center\">&nbsp;</td>";
						
						if($reporte['diabetes_gestacional'])
							$this->salida .= "				<td align=\"center\"><img src=\"".GetThemePath()."/images/delete.gif\"></td>";
						else
							$this->salida .= "				<td align=\"center\">&nbsp;</td>";	
							
						$cumplio=0;
						if($reporte['sw_estado'])
						{
							if($reporte['sw_estado']=='3')
							{
								$this->salida.= "<td align=\"center\"><img src=\"".GetThemePath()."/images/delete.gif\"></td>";
								$this->salida.= "<td align=\"center\">&nbsp;</td>";	
								$cumplio=1;
							}
							else
							{
								$this->salida .= "<td align=\"center\">&nbsp;</td>";
								if($reporte['fecha_turno'] < date("Y-m-d"))
									$this->salida .= "<td align=\"center\"><img src=\"".GetThemePath()."/images/delete.gif\"></td>";
								else
									$this->salida .= "<td align=\"center\">&nbsp;</td>";
							}
						}
						else
						{
							$this->salida .= "<td align=\"center\">&nbsp;</td>";	
							$this->salida .= "<td align=\"center\">&nbsp;</td>";
						}
						
						if($reporte['contacto_telefonico'])
							$ver1="<a href=\"javascript:Inicio('".$reporte['seguimiento_id']."','capa1$k')\">Ver</a>";
						else
							$ver1="&nbsp;";
							
						$this->salida .= "				<td align=\"center\">$ver1</td>";	
						
						if($reporte['ips_dir'])
							$ver2="<a href=\"javascript:Inicio('".$reporte['seguimiento_id']."','capa2$k')\">Ver</a>";
						else
							$ver2="&nbsp;";
						
						$this->salida .= "				<td align=\"center\">$ver2</td>";
						
						if($cumplio==1 AND (!empty($reporte['contacto_telefonico']) OR !empty($reporte['ips_dir'])))
							$ver3="<a href=\"javascript:Inicio('".$reporte['seguimiento_id']."','capa3$k')\">Ver</a>";
						else
							$ver3="&nbsp;";
							
						$this->salida .= "				<td align=\"center\">$ver3</td>";
						$this->salida .= "				<td align=\"center\">".strtoupper($reporte['observacion'])."</td>";
						$this->salida .= "			</tr>";
						
						$k++;
					}
				}
				$this->salida .= "</table>";
				
				$this->salida .= "</div>";

				$mostrar=$reporte1->GetJavaReport('app','ParametrizacionPYP','ReporteSeguimientoCitas',array(),array('rpt_name'=>'ReporteSeguimientoCitas','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
				$funcion=$reporte1->GetJavaFunction();
				
				$this->salida .= "<table border=\"0\" width=\"100%\">";
				$this->salida .= "		<tr><td align=\"right\"><label class=\"label\"><a href=\"javascript:$funcion\">IMPRIMIR</a></label> <sub> <img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\" title=\"IMPRIMIR\"></sub></td></tr>";
				$this->salida .= "</table>";
				
				$this->salida .="$mostrar";
				
				$Paginador=new ClaseHTML();
		
				$accionT = ModuloGetURL('app','ParametrizacionPYP','user','FrmReporteSeguimientoCitas');
				$this->salida .= "".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$accionT,$this->limit);
				
				$accion=ModuloGetURL('app','ParametrizacionPYP','user','FrmReportesSeguimiento');
				
				$this->salida .= "	<form name=\"formavolver\" action=\"$accion\" method=\"post\">";
				$this->salida .= "  	<br><center><input class=\"input-submit\" type=\"submit\" name=\"Aceptarvol\" value=\"VOLVER\">";
				$this->salida .= "  	</center>";
				$this->salida .= "		</form>";
				
				$this->IncludeJS("CrossBrowser");
				$this->IncludeJS("RemoteScripting");
				$this->IncludeJS("CrossBrowserDrag");
				$this->IncludeJS("CrossBrowserEvent");
				
				$this->salida .= "<script>";
				$this->salida .= "	var capaAtual;\n";
				$this->salida .= "	var hiZ=2;\n";
				$this->salida .= "	var datos=new Array();\n";
				
				$this->salida .= "	function EnviarOpcion(valor)\n";
				$this->salida .= "	{\n";
				$this->salida .= "		document.forma_reporte.opcion.value=valor;\n";
				$this->salida .= "	}\n";
				
				$this->salida .= "	function EnviarFiltro(obj)\n";
				$this->salida .= "	{\n";
				$this->salida .= "		var x=obj.filtro.value;\n";
				$this->salida .= "		document.forma_reporte.opcion.value=1;\n";
				$this->salida .= "		jsrsExecute('app_modules/ParametrizacionPYP/RemoteScripting/filtrar.php',filtroReporte,'filtroReporte',x);\n";
				$this->salida .= "	}\n";
				
				$this->salida .= "	function filtroReporte(combo)\n";
				$this->salida .= "	{\n";
				$this->salida .= "		document.getElementById('op').innerHTML = combo;\n";
				$this->salida .= "	}\n";
				
				$this->salida .= "	function EnviarDatos(forma)\n";
				$this->salida .= "	{\n";
				$this->salida .= "		datos[0]=forma.fecha_ini.value;\n";
				$this->salida .= "		datos[1]=forma.fecha_fin.value;\n";
				$this->salida .= "		datos[2]=forma.filtro.value;\n";
				$this->salida .= "		datos[3]=forma.opcion.value;\n";
				$this->salida .= "		datos[4]=forma.ordenado_por.value;\n";
				$this->salida .= "		jsrsExecute('app_modules/ParametrizacionPYP/RemoteScripting/filtrar.php',Reporte,'Reporte',datos);\n";
				$this->salida .= "	}\n";
				
				$this->salida .= "	function Reporte(report)\n";
				$this->salida .= "	{\n";
				$this->salida .= "		document.getElementById('reporte').innerHTML = report;\n";
				$this->salida .= "	}\n";
				
				$this->salida .= "	function Inicio(dato,capita)\n";
				$this->salida .= "	{\n";
				$this->salida .= "		capaAtual=capita\n";
				$this->salida .= "		document.getElementById('titulo').innerHTML = '<center>SEGUIMIENTO</center>';\n";
				$this->salida .= "		document.getElementById('error').innerHTML = '';\n";
				$this->salida .= "	 	contenedor = 'd2Container';\n";
				$this->salida .= "		titulo = 'titulo';\n";
				$this->salida .= "		ele = xGetElementById('d2Container');\n";
				$this->salida .= "	  xMoveTo(ele, xClientWidth(), xScrollTop()+24);\n";
				$this->salida .= "	  xResizeTo(ele,350, 'auto');\n";
				$this->salida .= "		ele = xGetElementById('titulo');\n";
				$this->salida .= "	  xResizeTo(ele,330, 20);\n";
				$this->salida .= "		xMoveTo(ele, 0, 0);\n";
				$this->salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
				$this->salida .= "		ele = xGetElementById('cerrar');\n";
				$this->salida .= "	  xResizeTo(ele,20, 20);\n";
				$this->salida .= "		xMoveTo(ele, 330, 0);\n";
				$this->salida .= "		Seguimiento(dato);\n";
				$this->salida .= "	}\n";
				
				$this->salida .= "	function Seguimiento(x)\n";
				$this->salida .= "	{\n";
				$this->salida .= "		jsrsExecute('app_modules/ParametrizacionPYP/RemoteScripting/filtrar.php',VerSeguimiento,'VerSeguimiento',x);\n";
				$this->salida .= "	}\n";
				
				$this->salida .= "	function VerSeguimiento(html)\n";
				$this->salida .= "	{\n";
				$this->salida .= "		document.getElementById('d2Contents').innerHTML = html;\n";
				$this->salida .= "		MostrarSpan(contenedor);\n";
				$this->salida .= "	}\n";
				
				$this->salida .= "	function myOnDragStart(ele, mx, my)\n";
				$this->salida .= "	{\n";
				$this->salida .= "	  window.status = '';\n";
				$this->salida .= "	  if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
				$this->salida .= "	  else xZIndex(ele, hiZ++);\n";
				$this->salida .= "	  ele.myTotalMX = 0;\n";
				$this->salida .= "	  ele.myTotalMY = 0;\n";
				$this->salida .= "	}\n";
				$this->salida .= "	function myOnDrag(ele, mdx, mdy)\n";
				$this->salida .= "	{\n";
				$this->salida .= "	  if (ele.id == titulo) {\n";
				$this->salida .= "	    xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
				$this->salida .= "	  }\n";
				$this->salida .= "	  else {\n";
				$this->salida .= "	    xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
				$this->salida .= "	  }  \n";
				$this->salida .= "	  ele.myTotalMX += mdx;\n";
				$this->salida .= "	  ele.myTotalMY += mdy;\n";
				$this->salida .= "	}\n";
				$this->salida .= "	function myOnDragEnd(ele, mx, my)\n";
				$this->salida .= "	{}\n";
				
				$this->salida .= "	function MostrarSpan(Seccion)\n";
				$this->salida .= "	{ \n";
				$this->salida .= "		e = xGetElementById(Seccion);\n";
				$this->salida .= "		e.style.display = \"\";\n";
				$this->salida .= "	}\n";
				$this->salida .= "	function Cerrar(Seccion)\n";
				$this->salida .= "	{ \n";
				$this->salida .= "		e = xGetElementById(Seccion);\n";
				$this->salida .= "		e.style.display = \"none\";\n";
				$this->salida .= "	}\n";
				
				$this->salida .= "</script>";
				
				$this->salida .= "<div id='d2Container' class='d2Container' style=\"display:none\">\n";
				$this->salida .= "	<div id='titulo' class='draggable' style=\"	text-transform: uppercase;\"></div>\n";
				$this->salida .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('d2Container')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
				$this->salida .= "	<div id='error' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
				$this->salida .= "	<div id='d2Contents'>\n";
				$this->salida .= "	</div>"; 
				$this->salida .= "</div>";
				
				$this->salida .= ThemeCerrarTabla();

				return true;		
	}
	
	function FrmEstadisticaGestion()
	{
		$this->salida.= ThemeAbrirTabla('ESTADISTICAS DE GESTION DE SEGUIMIENTO MENSUAL','1200');
		
		$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida .= "      </table><br>"; 
		
		$accionk=ModuloGetURL('app','ParametrizacionPYP','user','FrmEstadisticaGestion');
		
		$this->salida .= "<form name=\"forma_reporte\" action=\"$accionk\" method=\"post\">";
		$this->salida .= "	<table class=\"normal_10\" width=\"60%\" align=\"center\" border=\"0\">";
		$this->salida .= "		<tr class=\"modulo_table_list_title\">";
		$this->salida .= "			<td colspan=\"2\">FECHAS</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr class=\"modulo_list_oscuro\">";
		$this->salida .= "			<td width=\"80%\" align=\"center\">
															DESDE <input type=\"text\" name=\"fecha_ini\" size=\"10\" value=\"".$_REQUEST['fecha_ini']."\" class=\"input-text\">
															<sub>".ReturnOpenCalendario("forma_reporte","fecha_ini","-")."</sub>
															HASTA <input type=\"text\" name=\"fecha_fin\" size=\"10\" value=\"".$_REQUEST['fecha_fin']."\" class=\"input-text\">
															<sub>".ReturnOpenCalendario("forma_reporte","fecha_fin","-")."</sub>
														</td>";
		$this->salida .= "			<td width=\"20%\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"VER\" value=\"VER REPORTE\"></td>";
		$this->salida .= "		</tr>";
		$this->salida .= "	</table><br>";
		$this->salida .= "</form>";
		
		$meses=array('ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE');

		$this->salida .= "	<div id=\"reporte2\" style=\"display:block\">";
		if($_REQUEST['VER'])
		{
			$reporte=$this->ReporteSeguimientoMensual($_REQUEST,0);
			if($reporte)
			{
				$this->salida .= "		<table class=\"normal_10\" width=\"95%\" align=\"center\" border=\"0\">";
				$this->salida .= "			<tr class=\"modulo_table_list_title\" align=\"center\">";
				$this->salida .= "				<td colspan=\"2\" width=\"10%\">ACTIVIDAD</td>";
				for($i=0;$i<sizeof($meses);$i++)
					$this->salida .= "				<td>".$meses[$i]."</td>";
				$this->salida .= "				<td width=\"10%\">TOTALES</td>";
				$this->salida .= "			</tr>";
	
				$reporte1= new GetReports();
				foreach($reporte as $key=>$valor1)
				{
					if($estilo=="modulo_list_oscuro")
						$estilo="modulo_list_claro";
					else
						$estilo="modulo_list_oscuro";
					
					$ban=false;
					$this->salida .= "			<tr class=\"modulo_list_claro\" align=\"center\">";
					$this->salida .= "				<td class=\"modulo_table_list_title\" rowspan=\"".sizeof($valor1)."\">$key</td>";
					
					foreach($valor1 as $key1=>$valor2)
					{
						if($ban)
							$this->salida .= "			<tr class=\"modulo_list_claro\" align=\"center\">";
						
						$this->salida .= "				<td class=\"modulo_list_oscuro\"><b>".$key1."</b></td>";
						$sum=0;
						for($i=0;$i<sizeof($meses);$i++)
						{
							$flag=0;
							foreach($valor2 as $key2=>$valor3)
							{
								if($valor3['mes']==($i+1))
								{
									$this->salida .= "				<td width=\"10%\"><b>".$valor3['count']."</b></td>";
									$sum+=$valor3['count'];
									$flag=1;
									break;
								}
							}
							if($flag==0)
								$this->salida .= "				<td width=\"10%\">&nbsp;</td>";
						}
						$this->salida .= "				<td class=\"modulo_list_oscuro\"><b class=\"label_error\">$sum</b></td>";
						$this->salida .= "			</tr>";
						
						$ban=true;
					}
				}
				$_SESSION['reporte_2']=$reporte;
				$mostrar=$reporte1->GetJavaReport('app','ParametrizacionPYP','ReporteEstadisticaGestion',array(),array('rpt_name'=>'ReporteEstadisticaGestion','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
				$funcion=$reporte1->GetJavaFunction();
				$this->salida .= "		<tr><td colspan=\"15\" width=\"100%\" align=\"right\"><label class=\"label\"><a href=\"javascript:$funcion\">IMPRIMIR</a></label> <sub> <img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\" title=\"IMPRIMIR\"></sub></td></tr>";
				$this->salida .= "</table>";
				$this->salida .="$mostrar";
			}
			else
			{
				$this->salida .= "		<center><label class=\"label_error\">NO SE ENCONTRARON REGISTROS</center>";
			}
		}
		

		$this->salida .= "	</div>";
		$this->salida .= "	<br>";
		
		$accion=ModuloGetURL('app','ParametrizacionPYP','user','FrmReportesSeguimiento');
		$this->salida .= "<form name=\"formavolver\" action=\"$accion\" method=\"post\">";
		$this->salida .= "	<center><input class=\"input-submit\" type=\"submit\" name=\"AceptarMen\" value=\"VOLVER\">";
		$this->salida .= "	</center>";
		$this->salida .= "</form>";
		
		$this->salida .= ThemeCerrarTabla();
		
		return true;
	}
	
	function FrmReporteActividades()
	{
		
		$meses=12;
		
		$this->salida.= ThemeAbrirTabla('REPORTE MENSUAL DE ACTIVIDADES CPN');
		
		$accionk=ModuloGetURL('app','ParametrizacionPYP','user','FrmReporteActividades');
		
		$this->salida .= "<form name=\"forma_reporte\" action=\"$accionk\" method=\"post\">";
		$this->salida .= "	<table class=\"normal_10\" width=\"60%\" align=\"center\" border=\"0\">";
		$this->salida .= "		<tr class=\"modulo_table_list_title\">";
		$this->salida .= "			<td colspan=\"2\">FECHAS</td>";
		$this->salida .= "		</tr>";
		$this->salida .= "		<tr class=\"modulo_list_oscuro\">";
		$this->salida .= "			<td width=\"80%\" align=\"center\">
															DESDE <input type=\"text\" name=\"fecha_ini\" size=\"10\" value=\"".$_REQUEST['fecha_ini']."\" class=\"input-text\">
															<sub>".ReturnOpenCalendario("forma_reporte","fecha_ini","-")."</sub>
															HASTA <input type=\"text\" name=\"fecha_fin\" size=\"10\" value=\"".$_REQUEST['fecha_fin']."\" class=\"input-text\">
															<sub>".ReturnOpenCalendario("forma_reporte","fecha_fin","-")."</sub>
														</td>";
		$this->salida .= "			<td width=\"20%\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"VER\" value=\"VER REPORTE\"></td>";
		$this->salida .= "		</tr>";
		$this->salida .= "	</table><br>";
		$this->salida .= "</form>";
		
		$this->salida .= "	<div id=\"reporte3\" style=\"display:block\">";
		
		if($_REQUEST['VER'])
		{
			$reporte=$this->ReporteSeguimientoMensual($_REQUEST,1);
			
			if($reporte)
			{
				$this->salida .= "		<table class=\"normal_10\" width=\"50%\" align=\"center\" border=\"0\">";
				$this->salida .= "			<tr class=\"modulo_table_list_title\" align=\"center\">";
				$this->salida .= "				<td colspan=\"2\" width=\"80%\">ACTIVIDAD</td>";
				$this->salida .= "				<td width=\"20%\">TOTALES</td>";
				$this->salida .= "			</tr>";
	
				$reporte1=new GetReports();
				
				foreach($reporte as $key=>$valor1)
				{
					if($estilo=="modulo_list_oscuro")
						$estilo="modulo_list_claro";
					else
						$estilo="modulo_list_oscuro";
					
					$ban=false;
					$this->salida .= "			<tr class=\"modulo_list_claro\" align=\"center\">";
					$this->salida .= "				<td width=\"40%\" class=\"modulo_table_list_title\" rowspan=\"".sizeof($valor1)."\">$key</td>";
					
					foreach($valor1 as $key1=>$valor2)
					{
						if($ban)
							$this->salida .= "			<tr class=\"modulo_list_claro\" align=\"center\">";
						
						$this->salida .= "				<td width=\"60%\"><b>".$key1."</b></td>";
						$sum=0;
						for($i=0;$i<$meses;$i++)
						{
							$flag=0;
							foreach($valor2 as $valor3)
							{
								if($valor3['mes']==($i+1))
								{
									$sum+=$valor3['count'];
									$flag=1;
									break;
								}
							}
						}
						$this->salida .= "				<td class=\"modulo_list_oscuro\"><b class=\"label_error\">$sum</b></td>";
						$this->salida .= "			</tr>";
						
						$ban=true;
					}
				}
				$_SESSION['reporte_3']=$reporte;
				$mostrar=$reporte1->GetJavaReport('app','ParametrizacionPYP','ReporteActividadesTotal',array(),array('rpt_name'=>'ReporteActividadesTotal','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
				$funcion=$reporte1->GetJavaFunction();
				$this->salida .= "		<tr><td colspan=\"3\" width=\"100%\" align=\"right\"><label class=\"label\"><a href=\"javascript:$funcion\">IMPRIMIR</a></label> <sub> <img src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\" title=\"IMPRIMIR\"></sub></td></tr>";
				$this->salida .= "</table>";
				$this->salida .="$mostrar";
			}
			else
			{
				$this->salida .= "		<center><label class=\"label_error\">NO SE ENCONTRARON REGISTROS</center>";
			}
		}

		$this->salida .= "	</div>";
		$this->salida .= "	<br>";
		
		$accion=ModuloGetURL('app','ParametrizacionPYP','user','FrmReportesSeguimiento');
		$this->salida .= "<form name=\"formavolver\" action=\"$accion\" method=\"post\">";
		$this->salida .= "	<center><input class=\"input-submit\" type=\"submit\" name=\"AceptarMen\" value=\"VOLVER\">";
		$this->salida .= "	</center>";
		$this->salida .= "</form>";
		$this->salida .= ThemeCerrarTabla();
		
		return true;
	}
	
}//fin de la clase
?>
