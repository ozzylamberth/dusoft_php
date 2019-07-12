	
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
	* app_Promocion_y_PrevencionAdmin_userclasses_HTML.php
	*
	//*
	**/
	
	class app_Promocion_y_PrevencionAdmin_userclasses_HTML extends app_Promocion_y_PrevencionAdmin_user
	{
		function app_Promocion_y_PrevencionAdmin_userclasses_HTML()
		{
			$this->app_Promocion_y_PrevencionAdmin_user(); //Constructor del padre 'modulo'
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
		
		function MostrarDatosSubmodulos($Arreglo,$Seleccionado='False',$variable=''){
			switch($Seleccionado)
			{
					case 'False':
					{
						$this->salida .=" <option value=\"-1\">---Seleccione---</option>";
						for($i=0;$i<sizeof($Arreglo);$i++)
						{
							$value=$Arreglo[$i]['programa_id'];
							//$titulo=$Arreglo[$i]['descripcion'];
							$titulo=$Arreglo[$i]['app_modulo'];
							if($value==$variable){
									$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
							}else
							{
									$this->salida .=" <option value=\"$value\">$titulo</option>";
							}
						}
						break;
					}
					case 'True':{
					for($i=0;$i<sizeof($Arreglo);$i++){
							$value=$Arreglo[$i]['programa_id'];
							//$titulo=$Arreglo[$i]['descripcion'];
							$titulo=$Arreglo[$i]['app_modulo'];
							if($value==$variable){
									$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
							}else{
									$this->salida .=" <option value=\"$value\">$titulo</option>";
							}
						}
						break;
					}
			}
		}
		
		function MostrarDatosCu($Arreglo,$Seleccionado='False',$variable='')
		{
			switch($Seleccionado){
					case 'False':{
					$this->salida .=" <option value=\"-1\">---Seleccione---</option>";
					for($i=0;$i<sizeof($Arreglo);$i++){
							$value=$Arreglo[$i]['unidad_funcional'];
							$titulo=$Arreglo[$i]['descripcion'];
							if($value==$variable){
									$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
							}else{
									$this->salida .=" <option value=\"$value\">$titulo</option>";
							}
						}
						break;
				}
					case 'True':{
					for($i=0;$i<sizeof($Arreglo);$i++){
							$value=$Arreglo[$i]['unidad_funcional'];
							$titulo=$Arreglo[$i]['descripcion'];
							if($value==$variable){
									$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
							}else{
									$this->salida .=" <option value=\"$value\">$titulo</option>";
							}
						}
						break;
					}
			}
		}
		
		function SeleccionParametros()
		{
			$this->salida .= ThemeAbrirTabla('CONTROL DE PROGRAMAS PYP','80%');
			$this->salida .= "                <table class=\"normal_10\" width=\"90%\" align=\"center\" border=\"0\">";
			$this->salida .= "                     <tr><td colspan=\"3\" class=\"modulo_table_title\" align=\"center\">PROGRAMAS";
			$this->salida .= "                     </td></tr>";
			$datosProgramas=$this->TraerDatosPyP();
			$k=0;
			for($i=0; $i<sizeof($datosProgramas);$i++)
			{
				if($k%2==0)
					$estilo='modulo_list_oscuro';
				else
					$estilo='modulo_list_claro';
				$this->salida .= "                     <tr class=\"$estilo\"><td width=\"70%\" class=\"label_mark\" align=\"left\">".$datosProgramas[$i][descripcion]."";
				$this->salida .= "                      </td>";
				$accionEd=ModuloGetURL('app','Promocion_y_PrevencionAdmin','user','FrmIngresarProgramas',array('programa_id'=>$datosProgramas[$i][programa_id]));
				$accionEl=ModuloGetURL('app','Promocion_y_PrevencionAdmin','user','FrmIngresarProgramas',array('programa_id'=>$datosProgramas[$i][programa_id],'accion'=>'eliminar'));
				$this->salida .= "                     <td width=\"15%\" class=\"label_mark\" align=\"center\"><a href=\"$accionEd\"><img src=\"".GetThemePath()."/images/pmodificar.png\" border=\"0\" title=\"Editar\"> EDITAR</a></td>";
				$this->salida .= "                     <td width=\"15%\" class=\"label_mark\" align=\"center\"><a href=\"$accionEl\"><img src=\"".GetThemePath()."/images/elimina.png\" border=\"0\" title=\"Eliminar\"> ELIMINAR</a></td></tr>";
				$k++;
			}
			$this->salida .= "                     </td></tr>";
			$this->salida .= "               </table>";
			$this->salida .= "               <BR>";
			$this->salida .= "               <BR>";        
			$this->salida .= "                <table class=\"normal_10\" width=\"60%\" align=\"center\" border=\"0\">";
			$accion=ModuloGetURL('app','Promocion_y_PrevencionAdmin','user','PrincipalPyP');
			$accion1=ModuloGetURL('app','Promocion_y_PrevencionAdmin','user','FrmIngresarProgramas');
			$accion2=ModuloGetURL('app','Promocion_y_PrevencionAdmin','user','FrmAdministrarCU');
			$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
			$this->salida .= "                     <tr><td colspan=\"2\" class=\"label\" align=\"center\"><a href=\"$accion1\">INGRESAR PROGRAMA PYP</a></td></tr>";
			$this->salida .= "                     <tr><td colspan=\"2\" class=\"label\" align=\"center\"><a href=\"$accion2\">ADMINISTRAR POR UNIDADES FUNCIONALES</a></td></tr>";
			$this->salida .= "                     <tr><td colspan=\"2\" align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"AceptarMen\" value=\"VOLVER\">";
			$this->salida .= "                     </td></tr>";
			$this->salida .= "               </form>";
			$this->salida .= "               </table>";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		
		function FrmIngresarProgramas()
		{
				$this->salida .= ThemeAbrirTabla('ADICIONAR PROGRAMAS PYP','95%');
				//TRAER PLANES
				$datosPlanes=$this->TraerDatosPlanes();
				//TRAER CENTROS DE UTILIDAD
				$datosPlanesCu=$this->TraerDatosCu();
				//TRAER DATOS SUBMODULOS
				$datos=$this->TraerDatos($_REQUEST['programa_id']);
				//TRAER RANGOS
				if($_REQUEST['programa_id'])
				{
					$DatosEditar=$this->TraerDatosEditar($_REQUEST['programa_id']);
					$_REQUEST['edadmin']=$DatosEditar[0][edad_min];
					$_REQUEST['edadmax']=$DatosEditar[0][edad_max];
					
					if($DatosEditar)
					{
						if($DatosEditar[0][sexo_id]=='F')
						{
								$_REQUEST['femenino']=on;
						}elseif($DatosEditar[0][sexo_id]=='M')
						{
								$_REQUEST['masculino']=on;
						}
						elseif(is_null($DatosEditar[0][sexo_id]))
						{
								$_REQUEST['femenino']=on;
								$_REQUEST['masculino']=on;
						}
					}
					$programa=$this->TraerNombrePrograma($_REQUEST['programa_id']);
					$_REQUEST['programa']=$programa[descripcion];
				}
				$accion=ModuloGetURL('app','Promocion_y_PrevencionAdmin','user','GuardarProgramas',array('planes'=>$datosPlanes,'CU'=>$datosPlanesCu));
				if ($this->uno == 1)
				{
						$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
						$this->salida .= $this->SetStyle("MensajeError");
						$this->salida .= "      </table><br>";       
				}
				$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$this->salida .= "                <table class=\"normal_10\" width=\"90%\" align=\"center\" border=\"0\">";
				$this->salida .= "                     <tr><td colspan=\"2\" class=\"modulo_table_title\" align=\"center\">DATOS A INGRESAR";
				$this->salida .= "                     </td></tr>";
				$this->salida .= "                     <tr class=\"modulo_list_claro\"><td width=\"57%\" class=\"label_mark\" align=\"left\"><label class=\"".$this->SetStyle("submodulo")."\" width=\"50%\">MÓDULO</label>";
				//$datos=$this->TraerDatos();
				if($_REQUEST['submodulos'])
				{
					$dato=$_REQUEST['submodulos'];
					//$sel='True';
				}
				elseif($_REQUEST['programa_id'])
				{
						$dato=$_REQUEST['programa_id'];
						//$sel='False';
				}
			$this->salida .= "                     <select name=\"submodulos\"  class=\"select\">";
			$this->MostrarDatosSubmodulos($datos,'False',$dato);
			$this->salida .= "                     </select>";
			$this->salida .= "                      </td>";
			$this->salida .= "                     <td width=\"43%\" class=\"label_mark\" align=\"left\"><label class=\"".$this->SetStyle("programa")."\" width=\"50%\">PROGRAMA</label><input type=\"text\" name=\"programa\" size=\"40\" value=\"".$_REQUEST['programa']."\" class=\"input-text\"></td></tr>";
			$this->salida .= "                     </td></tr>";
			$this->salida .= "               </table>";
			$this->salida .= "               <BR>";
			$this->salida .= "               <BR>";        
			$this->salida .= "                <table class=\"normal_10\" width=\"90%\" align=\"center\" border=\"0\">";
			$this->salida .= "                     <tr><td colspan=\"2\" class=\"modulo_table_title\" align=\"center\">PLANES";
			$this->salida .= "                     </td></tr>";
			if($_REQUEST['submodulos'])
			{
				$ProgramasPlanes=$this->TraerProgramasYplanes($_REQUEST['submodulos']);
			}
			elseif($_REQUEST['programa_id'])
			{
				$ProgramasPlanes=$this->TraerProgramasYplanes($_REQUEST['programa_id']);
			}
				$k=0;
				for($i=0; $i<sizeof($datosPlanes);$i++)
				{
					if($k%2==0)
						$estilo='modulo_list_oscuro';
					else
						$estilo='modulo_list_claro';
				
				$this->salida .= "                     <tr class=\"$estilo\"><td width=\"97%\" class=\"label_mark\" align=\"left\">".$datosPlanes[$i][plan_descripcion]."";
					$this->salida .= "                      </td>";
					if(sizeof($ProgramasPlanes)>0)
					{
						for($j=0;$j<sizeof($ProgramasPlanes);$j++)
						{
							if($ProgramasPlanes[$j][plan_id]==$datosPlanes[$i][plan_id])
							{
									$this->salida .= "<td td width=\"3%\" class=\"label_mark\" align=\"left\"><input type=\"checkbox\" name=\"".$datosPlanes[$i][plan_id]."\" value=\"".$datosPlanes[$i][plan_id]."\" checked></td></tr>";
									$j=sizeof($ProgramasPlanes);
							}
							elseif($j==(sizeof($ProgramasPlanes)-1))
							{
									$this->salida .= "<td td width=\"3%\" class=\"label_mark\" align=\"left\"><input type=\"checkbox\" name=\"".$datosPlanes[$i][plan_id]."\" value=\"".$datosPlanes[$i][plan_id]."\"></td></tr>";
							}   
						}
					}
					else
					{
						$this->salida .= "                     <td td width=\"3%\" class=\"label_mark\" align=\"left\"><input type=\"checkbox\" name=\"".$datosPlanes[$i][plan_id]."\" value=\"".$datosPlanes[$i][plan_id]."\"></td></tr>";
					}
					$k++;   
			}
			$this->salida .= "                     </td></tr>";
			$this->salida .= "               </table>";
			$this->salida .= "               <BR>";
			$this->salida .= "               <BR>";        
			$this->salida .= "                <table class=\"normal_10\" width=\"90%\" align=\"center\" border=\"0\">";
			$this->salida .= "                     <tr class=\"modulo_list_claro\"><td colspan=\"2\" class=\"modulo_table_title\" align=\"center\">UNIDAD FUNCIONAL";
			$this->salida .= "                     </td></tr>";
			if($_REQUEST['submodulos'])
			{
					$ProgramasPlanesCu=$this->TraerProgramasYplanesCu($_REQUEST['submodulos']);
			}
			elseif($_REQUEST['programa_id'])
			{
					$ProgramasPlanesCu=$this->TraerProgramasYplanesCu($_REQUEST['programa_id']);
			}
			$k=0;
			
			for($i=0; $i<sizeof($datosPlanesCu);$i++)
			{
				$ban=0;
				
				if($k%2==0)
					$estilo='modulo_list_claro';
				else
					$estilo='modulo_list_oscuro';
				
				$this->salida .= "<tr class=\"$estilo\"><td width=\"97%\" class=\"label_mark\" align=\"left\">".$datosPlanesCu[$i][descripcion ]."";
				$this->salida .= "</td>";
				if(sizeof($ProgramasPlanesCu)>0)
				{
					for($j=0;$j<sizeof($ProgramasPlanesCu);$j++)
					{
						if(trim($ProgramasPlanesCu[$j][unidad_funcional]," ")==$datosPlanesCu[$i][unidad_funcional])
						{
							$ban=1;
							break;
						}
					}
					if($ban==1)
					{
						$this->salida .= "<td td width=\"3%\" class=\"label_mark\" align=\"left\"><input type=\"checkbox\" name=\"descripcion_uf[]\" value=\"".$datosPlanesCu[$i][unidad_funcional]."\" checked></td></tr>";
					}
					else
					{
						$this->salida .= "<td td width=\"3%\" class=\"label_mark\" align=\"left\"><input type=\"checkbox\" name=\"descripcion_uf[]\" value=\"".$datosPlanesCu[$i][unidad_funcional]."\"></td></tr>";
					}
				}
				else
				{
					$this->salida .= "	<td width=\"3%\" class=\"label_mark\" align=\"left\"><input type=\"checkbox\" name=\"descripcion_uf[]\" value=\"".$datosPlanesCu[$i][unidad_funcional]."\"></td></tr>";
				} 
				$k++;
			}
			$this->salida .= "                     </td></tr>";
			$this->salida .= "               </table>";
			$this->salida .= "               <BR>";
			$this->salida .= "               <BR>";        
			$this->salida .= "                <table class=\"normal_10\" width=\"65%\" align=\"center\" border=\"0\">";
			$this->salida .= "                     <tr><td colspan=\"2\" class=\"modulo_table_title\" align=\"center\">GRUPO RIESGO";
			$this->salida .= "                     </td></tr>";
			$this->salida .= "                     <tr class=\"modulo_list_claro\"><td width=\"40%\" class=\"label_mark\" align=\"left\"><label class=\"".$this->SetStyle("rango")."\" width=\"50%\">RANGO EDAD</label>&nbsp;&nbsp;min<input type=\"text\" name=\"edadmin\" value=\"".$_REQUEST['edadmin']."\" size=\"8\" class=\"input-text\">";
			$this->salida .= "                      &nbsp;&nbsp;&nbsp;&nbsp;max<input type=\"text\" name=\"edadmax\" value=\"".$_REQUEST['edadmax']."\" size=\"8\" class=\"input-text\"></td>";
			$this->salida .= "                     <td width=\"60%\" class=\"label_mark\" align=\"right\"><label class=\"".$this->SetStyle("sexo")."\" width=\"50%\">SEXO</label>";
			if($_REQUEST['femenino']==on)
			{
					$this->salida .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"femenino\" checked>&nbsp;&nbsp;FEMENINO";
			}
			else
			{
					$this->salida .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"femenino\">&nbsp;&nbsp;FEMENINO";
			}
			if($_REQUEST['masculino']==on)
			{
					$this->salida .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"masculino\" checked>&nbsp;&nbsp;MASCULINO</td></td></tr>";
			}
			else
			{
					$this->salida .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"masculino\">&nbsp;&nbsp;MASCULINO</td></td></tr>";
			}
			if($_REQUEST['accion'])
				$this->salida .= "                     <tr><td align=\"right\" width=\"50%\"><br><input class=\"input-submit\" type=\"submit\" name=\"confirmar\" value=\"CONFIRMAR\"></td>";
			else
				$this->salida .= "                     <tr><td align=\"right\" width=\"50%\"><br><input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\"></td>";
			$this->salida .= "               </form>";
			$accion1=ModuloGetURL('app','Promocion_y_PrevencionAdmin','user','SeleccionParametros');
			$this->salida .= "            <form name=\"formabuscar\" action=\"$accion1\" method=\"post\">";
			$this->salida .= "                     <td align=\"left\" width=\"50%\"><br><input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\"></td></tr>";
			$this->salida .= "               </table>";
			$this->salida .= "               </form>";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		
			function FrmAdministrarCU($dato,$CuPyP)
			{
				$this->salida .= ThemeAbrirTabla('ADMINISTRAR POR UNIDAD FUNCIONAL','90%');
				
				//DATOS CENTROS DE UTILIDAD
				$datosCu=$this->TraerDatosCu();
				
				//DATOS PROGRAMAS
				$datos=$this->TraerDatosPyP();
				$accion=ModuloGetURL('app','Promocion_y_PrevencionAdmin','user','GuardarDatosCu',array('datosCu'=>$datosCu,'datos'=>$datos));
				if ($this->uno == 1)
				{
					$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
					$this->salida .= $this->SetStyle("MensajeError");
					$this->salida .= "      </table><br>";       
				}
				$this->salida .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$this->salida .= "                <table class=\"normal_10\" width=\"40%\" align=\"center\" border=\"0\">";
				$this->salida .= "                     <tr><td colspan=\"2\" class=\"modulo_table_title\" align=\"center\">DATOS A INGRESAR";
				$this->salida .= "                     </td></tr>";
				$this->salida .= "                     <tr class=\"modulo_list_claro\"><td width=\"90%\" class=\"label_mark\" align=\"center\"><label class=\"".$this->SetStyle("cu")."\" width=\"50%\"> UNIDAD FUNCIONAL </label>";
				$this->salida .= "                     <select name=\"unidad_funcional\"  class=\"select\">";
				$this->MostrarDatosCu($datosCu,'False',$dato);
				$this->salida .= "                     </select>";
				$this->salida .= "                      <input class=\"input-submit\" type=\"submit\" name=\"VER\" value=\"VER\">";
				$this->salida .= "                     </td></tr>";
				$this->salida .= "               </table>";
				$this->salida .= "               <BR>";
				$this->salida .= "               <BR>";        
				$this->salida .= "                <table class=\"normal_10\" width=\"70%\" align=\"center\" border=\"0\">";
				$this->salida .= "                     <tr><td colspan=\"2\" class=\"modulo_table_title\" align=\"center\">LISTA DE PROGRAMAS PYP";
				$this->salida .= "                     </td></tr>";
				
				$k=0;
				
				for($i=0; $i<sizeof($datos);$i++)
				{
					if($k%2==0)
						$estilo='modulo_list_claro';
					else
						$estilo='modulo_list_oscuro';
						$this->salida .= "                     <tr class=\"$estilo\"><td width=\"97%\" class=\"label_mark\" align=\"left\">".$datos[$i][descripcion]."";
						
						$this->salida .= "                      </td>";
								if(sizeof($CuPyP)>0)
								{
										for ($j=0; $j<sizeof($CuPyP); $j++)
										{
												if($CuPyP[$j][programa_id]==$datos[$i][programa_id])
												{
														$this->salida .= "<td width=\"3%\" class=\"label_mark\" align=\"left\"><input type=\"checkbox\" name=\"".$datos[$i][programa_id]."\" checked value=\"".$datos[$i][programa_id]."\"></td></tr>";
														$j=sizeof($CuPyP);
												}
												else
												if($j==(sizeof($CuPyP)-1))
												{
														$this->salida .= "<td width=\"3%\" class=\"label_mark\" align=\"left\"><input type=\"checkbox\" name=\"".$datos[$i][programa_id]."\" value=\"".$datos[$i][programa_id]."\"></td></tr>";
												}
										}
								}
								else
								{
									$this->salida .= "<td width=\"3%\" class=\"label_mark\" align=\"left\"><input type=\"checkbox\" name=\"".$datos[$i][programa_id]."\" value=\"".$datos[$i][programa_id]."\"></td></tr>";
								} 
						$k++;   
				}
				$this->salida .= "               </table>";
				
				$this->salida .= "                <table class=\"normal_10\" width=\"70%\" align=\"center\" border=\"0\">";
				$this->salida .= "                     <tr><td align=\"right\" width=\"50%\"><br><input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\"></td>";
				$this->salida .= "               </form>";
				$accion1=ModuloGetURL('app','Promocion_y_PrevencionAdmin','user','SeleccionParametros');
				$this->salida .= "            <form name=\"formabuscar\" action=\"$accion1\" method=\"post\">";
				$this->salida .= "                     <td align=\"left\" width=\"50%\" ><br><input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"VOLVER\"></td></tr>";
				$this->salida .= "               </table>";
				$this->salida .= "               </form>";
				$this->salida .= ThemeCerrarTabla();
				return true;
			}
			
	}//fin de la clase
	?>
