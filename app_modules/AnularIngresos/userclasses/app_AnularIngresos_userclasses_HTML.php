<?php
	/**************************************************************************************
	* $Id: app_Anularingresos_userclasses_HTML.php,v 1.1 2006/02/27 19:24:30 hugo Exp $
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* @author Hugo Freddy Manrique Arango
	*
	* MODULO TEMPORAL PARA ANULAR INGRESOS
	***************************************************************************************/
	IncludeClass("ClaseHTML");
	class app_AnularIngresos_userclasses_HTML extends app_AnularIngresos_user
	{
		function app_AnularIngresos_user_HTML()
		{
			$this->salida='';
			$this->app_AnularIngresos_user();
			return true;
		}
		/*************************************************************************************
		*
		**************************************************************************************/
		function main()
		{
			$this->AnularIngresos();
			$this->FormaAnularIngresos();
			return true;
		}
		/*************************************************************************************
		*
		**************************************************************************************/
		function FormaAnularIngresos()
		{
			$this->salida .= ThemeAbrirTabla("BUSCAR INGRESOS");

			$this->salida .= "<table width=\"50%\" align=\"center\" >\n";		
			$this->salida .= "	<tr>\n";
			$this->salida .= "		<td>\n";
			$this->salida .= "			<form name=\"buscadorfacturas\" action=\"".$this->action2."\" method=\"post\">\n";
			$this->salida .= "				<table class=\"modulo_table_list\" width=\"100%\">\n";
			$this->salida .= "					<tr>\n";
			$this->salida .= "						<td class=\"modulo_table_list_title\">BUSCADOR INGRESOS:&nbsp;</td>\n";
			$this->salida .= "						<td >NÚMERO</td>\n";
			$this->salida .= "						<td>\n";
			$this->salida .= "							<input type=\"text\" class=\"input-text\" name=\"ingreso\" size=\"25\" onkeypress=\"return acceptNum(event)\" >\n";
			$this->salida .= "						</td>\n";
			$this->salida .= "						<td>\n";
			$this->salida .= "							<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
			$this->salida .= "						</td>\n";
			$this->salida .= "					</tr>\n";
			$this->salida .= "				</table>\n";
			$this->salida .= "			</form>\n"; 
			$this->salida .= "		</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "</table><br>\n";
			if($this->Ingreso)
			{
				$this->ObtenerInfoIngreso();
				
				if(sizeof($this->Datos))
				{
					$this->salida .= "	<table border=\"0\" width=\"70%\" align=\"center\">\n";
					$this->salida .= "		<tr>\n";
					$this->salida .= "			<td>\n";
					$this->salida .= "				<fieldset><legend class=\"field\">DATOS DEL INGRESO Nº ".$this->Ingreso."</legend>\n";
						
					$this->salida .= "					<table width=\"86%\" align=\"center\" border=\"0\" class=\"modulo_table_list\">\n";
					$this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
					$this->salida .= "							<td style=\"text-align:left;text-indent:11pt\" width=\"20%\">F. INGRESO</td>\n";
					$this->salida .= "							<td style=\"text-align:left;text-indent:11pt\" colspan=\"2\" class=\"modulo_list_claro\">".$this->Datos['fecha_ingreso']."</td>\n";
					$this->salida .= "						</tr>\n";
					$this->salida .= "						<tr class=\"modulo_table_list_title\">\n";
					$this->salida .= "							<td style=\"text-align:left;text-indent:11pt\" width=\"20%\">PACIENTE:</td>\n";
					$this->salida .= "							<td style=\"text-align:left;text-indent:11pt\" width=\"25%\" class=\"modulo_list_claro\">\n";
					$this->salida .= "								".$this->Datos['tipo_id_paciente']." ".$this->Datos['paciente_id']."</td>\n";
					$this->salida .= "							<td style=\"text-align:left;text-indent:11pt\" class=\"modulo_list_claro\">\n";
					$this->salida .= "								".$this->Datos['nombres']." ".$this->Datos['apellidos']."</td>\n";
					$this->salida .= "						</tr>\n";
					$this->salida .= "					</table><br>\n";
					$this->salida .= "					<table border=\"0\" width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
					$this->salida .= "     				<tr class=\"modulo_table_list_title\" align=\"center\">\n";
					$this->salida .= "        			<td width=\"15%\" >Nº CUENTA</td>\n";
					$this->salida .= "	  	  			<td width=\"%\" >PLAN</td>\n";
					$this->salida .= "        			<td width=\"10%\">TOTAL</td>\n";
					$this->salida .= "        			<td width=\"10%\">ESTADO</td>\n";
					$this->salida .= "        			<td width=\"10%\">OPCIÓN</td>\n";
					$this->salida .= "      			</tr>\n";
					
					$cuentas = array();
					for($i=0; $i<sizeof($this->Cuentas); $i++)
					{
						if($i % 2 == 0)
						{
							$estilo='modulo_list_oscuro'; $background = "#CCCCCC";
						}
						else
						{
						  $estilo='modulo_list_claro';  $background = "#DDDDDD";
						}
						
						$action4 = ModuloGetURL('app','AnularIngresos','user','FormaMostrarInformacionGlosarCuenta',
	 								   								 array("ingreso"=>$this->Ingreso,"cuenta"=>$this->Cuentas[$i]['numerodecuenta']));
							
						$this->salida .= "						<tr class=\"".$estilo."\" height=\"21\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
						$this->salida .= "							<td align=\"center\"><b>".$this->Cuentas[$i]['numerodecuenta']."</b></td>\n";
						$this->salida .= "							<td align=\"left\">".$this->Cuentas[$i]['plan_descripcion']."</td>\n";
						$this->salida .= "							<td align=\"center\">".formatoValor($this->Cuentas[$i]['total_cuenta'])."</td>\n";
						$this->salida .= "							<td align=\"center\" ><b>".$this->Cuentas[$i]['descripcion']."</b></td>\n";						
						$this->salida .= "							<td><a href=\"".$action4."\" title=\"VER INFORMACIÓN\">\n";
						$this->salida .= "									<img src=\"".GetThemePath()."/images/auditoria.png\" border=\"0\"> VER </a>\n";
						$this->salida .= "							</td>\n";
						$this->salida .= "						</tr>\n";
						
						$cuentas[$i] = $this->Cuentas[$i]['numerodecuenta'];
					}
		    	$this->salida .= "          </table><br>\n";
		    	
		    	$this->action3 = ModuloGetURL('app','AnularIngresos','user','FormaInformacion',
		    																 array('cuentas'=>$cuentas,"ingreso"=>$this->Ingreso));
		    	
    			$this->salida .= "					<table width=\"90%\" align=\"center\">\n";
					$this->salida .= "						<tr>\n";
					$this->salida .= "							<td align=\"center\" id='lll'>\n";
					$this->salida .= "								<form name=\"volver\" action=\"".$this->action3."\" method=\"post\">\n";
					$this->salida .= "									<input type=\"submit\" class=\"input-submit\" value=\"Anular Ingreso\">\n";
					$this->salida .= "								</form>\n";
					$this->salida .= "							</td>\n";
					$this->salida .= "						</tr>\n";
					$this->salida .= "					</table>\n";
			    $this->salida .= "				</fieldset>\n";
					$this->salida .= "			</td>\n";
					$this->salida .= "		</tr>\n";
					$this->salida .= "	</table><br>\n";
		    }
		    else
		    {
		    	$this->salida .= "<center><b class=\"label_error\">EL INGRESO NO SE ENCUENTRA ACTIVO</b></center>\n";
		    }
			}
			
			$this->salida .= "<table width=\"90%\" align=\"center\">\n";
			$this->salida .= "	<tr>\n";
			$this->salida .= "		<td align=\"center\" id='lll'>\n";
			$this->salida .= "			<form name=\"volver\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "			</form>\n";
			$this->salida .= "		</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "</table>\n";
			$this->salida .= ThemeCerrarTabla();
			
		}
		/********************************************************************************************************
		* Forma donde se muestra la informacion necesaria para glosar una cuenta 
		*********************************************************************************************************/
		function FormaMostrarInformacionGlosarCuenta()
		{
			$this->MostrarInformacionGlosarCuenta();
			$estilo = "class=\"modulo_table_list_title\" style=\"text-align:left;text-indent: 6pt\" ";
			
			$this->salida .= ThemeAbrirTabla("INFORMACIÓN CUENTA");
			$this->salida .= "	<script>\n";
			$this->salida .= "		function mOvr(src,clrOver)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			src.style.background = clrOver;\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function mOut(src,clrIn)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			src.style.background = clrIn;\n";
			$this->salida .= "		}\n";
			$this->salida .= "	</script>\n";
			$this->salida .= "	<table align=\"center\" cellpading=\"0\"  width=\"50%\" border=\"0\" class=\"modulo_table_list\">\n";
			$this->salida .= "		<tr $estilo>\n";
			$this->salida .= "			<td width=\"15%\">Nº CUENTA</td>\n";
			$this->salida .= "			<td class=\"modulo_list_claro\">\n";
			$this->salida .= "				".$this->Cuenta."\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr $estilo>\n";
			$this->salida .= "			<td >PLAN</td>\n";
			$this->salida .= "			<td class=\"modulo_list_claro\" colspan=\"3\" width=\"40%\">\n";
			$this->salida .= "				".$this->DatosCuenta['plan_descripcion']."\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table><br>\n";

			$Filas = $this->ObtenerCargosCuenta();
			
			if(sizeof($Filas) > 0)
			{
				$this->salida .= "	<table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";		
				$this->salida .= "		<tr class=\"modulo_table_list_title\" height=\"17\">\n";
				$this->salida .= "			<td width=\"15%\"><b>FECHA</b></td>\n";
				$this->salida .= "			<td width=\"15%\"><b>TRANSACCIÓN</b></td>\n";
				$this->salida .= "			<td width=\"15%\"><b>CARGO</b></td>\n";
				$this->salida .= "			<td width=\"15%\"><b>TARIFARIO</b></td>\n";
				$this->salida .= "			<td width=\"40%\"><b>DESCRIPCIÓN</b></td>\n";
				$this->salida .= "		</tr>";
				
				$Agrupado = "";
				for($i=0; $i< sizeof($Filas);$i++)
				{
					$marca = "";
					if($i % 2 == 0)
					{
						$estilo='modulo_list_oscuro'; $background = "#CCCCCC";
					}
					else
					{
					 	$estilo='modulo_list_claro'; 	$background = "#DDDDDD";
					}
					
					$Celdas = $Filas[$i];
					
					if(strlen($Celdas['descripcion']) > 80)
					{
						$marca = " title =\"".$Celdas['descripcion']."\" ";
						$Celdas['descripcion'] = substr($Celdas['descripcion'],0,80)."...";
					}
					
					if($Celdas['agrupado'] != $Agrupado)
					{
						$ActoQx = $this->ObtenerActoQuirurgico($Celdas['transaccion'],$Celdas['agrupado']);
						if($Celdas['agrupado']) $ActoQx = "PROCEDIMIENTO QUIRÚRGICO: ".$ActoQx;
						
						$estilo1 = "class=\"hc_table_submodulo_title\" style=\"text-align:center;font-size:10px;text-indent: 0pt\"";
						$this->salida .= "		<tr $estilo1 height=\"17\"><td colspan=\"10\">$ActoQx</td></tr>\n";
					}
					$Agrupado = $Celdas['agrupado'];
												
					$this->salida .= "		<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
					$this->salida .= "			<td align=\"center\">".$Celdas['registro']."</td>\n";
					$this->salida .= "			<td align=\"center\">".$Celdas['transacion']."</td>\n";
					$this->salida .= "			<td align=\"center\">".$Celdas['cargo_cups']."</td>\n";
					$this->salida .= "			<td align=\"center\">".$Celdas['tarifario_id']."</td>\n";
					$this->salida .= "			<td align=\"justify\" $marca>".$Celdas['descripcion']."</td>\n";
					$this->salida .= "		</tr>\n";
				}
				$this->salida .= "	</table><br>\n";		
			}
			
			$Filas2 = $this->ObtenerInsumosCuenta();
			
			if(sizeof($Filas2) > 0)
			{
				$this->salida .= "	<table width=\"80%\" align=\"center\" class=\"modulo_table_list\">\n";		
				$this->salida .= "		<tr class=\"modulo_table_list_title\" height=\"17\">\n";
				$this->salida .= "			<td width=\"20%\"><b>CODIGO PRODUCTO</b></td>\n";
				$this->salida .= "			<td width=\"20%\"><b>CANTIDAD</b></td>\n";
				$this->salida .= "			<td width=\"60%\"><b>DESCRIPCION</b></td>\n";
				$this->salida .= "		</tr>";
				
				for($i=0; $i< sizeof($Filas2);$i++)
				{
					if($i % 2 == 0)
					{
						$estilo='modulo_list_oscuro'; $background = "#CCCCCC";
					}
					else
					{
					 	$estilo='modulo_list_claro'; 	$background = "#DDDDDD";
					}
					
					$Celdas = $Filas2[$i];
					$marca = "";
					if(strlen($Celdas['descripcion']) > 100)
					{
						$marca = " title =\"".$Celdas['descripcion']."\" ";
						$Celdas['descripcion'] = substr($Celdas['descripcion'],0,98)."...";
					}
							
					$this->salida .= "		<tr class=\"".$estilo."\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
					$this->salida .= "			<td align=\"right\"  >".$Celdas['codigo_producto']."</td>\n";
					$this->salida .= "			<td align=\"right\"  >".$Celdas['cantidad']."</td>\n";
					$this->salida .= "			<td align=\"justify\">".$Celdas['descripcion']."</td>\n";
					$this->salida .= "		</tr>\n";
				}
				$this->salida .= "	</table><br>\n";		
			}
			
				
			$this->salida .= "		<table width=\"70%\" align=\"center\"><tr>\n";
			$this->salida .= "				<form name=\"volver\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "					<td align=\"center\"><input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$this->salida .= "				</form>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/********************************************************************************* 
		* Funcion que presenta una forma de informacion al usario sobre lo que ha acabado 
		* de ocurrir con la accion que realizo 
		* 
		* @return boolean 
		**********************************************************************************/
		function FormaInformacion()
		{
			$this->MostrarInformacion();
			$this->salida .= ThemeAbrirTabla('INFORMACIÓN');
			$this->salida .= "	<form name=\"formaInformacion\" action=\"".$this->action2."\" method=\"post\">\n";
			$this->salida .= "		<table align=\"center\" width=\"50%\" class=\"modulo_table_list\">\n";
			$this->salida .= "			<tr><td class=\"label\" colspan=\"3\" align=\"center\" ><br>";
			$this->salida .= "				".$this->Informacion."<br>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "		<table align=\"center\" width=\"60%\">\n";
			$this->salida .= "			<tr><td align=\"center\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
			$this->salida .= "			</td></form>\n";
			$this->salida .= "		<form name=\"cancelar\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Cancelar\">\n";
			$this->salida .= "			</td></form>\n";
			$this->salida .= "		</tr></table>\n";
			$this->salida .= "	\n";
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
		/********************************************************************************* 
		* Funcion que presenta una forma de informacion al usario sobre lo que ha acabado 
		* de ocurrir con la accion que realizo 
		* 
		* @return boolean 
		**********************************************************************************/
		function FormaAnularIngreso()
		{
			if(!$this->AnularIngresoBD()) return false;
			$this->salida .= ThemeAbrirTabla('INFORMACIÓN');
			$this->salida .= "	<form name=\"formaInformacion\" action=\"".$this->action1."\" method=\"post\">\n";
			$this->salida .= "		<table align=\"center\" width=\"50%\" class=\"modulo_table_list\">\n";
			$this->salida .= "			<tr><td class=\"label\" colspan=\"3\" align=\"center\" ><br>";
			$this->salida .= "				".$this->Informacion."<br>\n";
			$this->salida .= "			</td></tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "		<table align=\"center\" width=\"60%\">\n";
			$this->salida .= "			<tr><td align=\"center\">\n";
			$this->salida .= "				<input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
			$this->salida .= "			</td></form>\n";
			$this->salida .= "		</tr></table>\n";
			$this->salida .= "	\n";
			$this->salida .= ThemeCerrarTabla();
			
			return true;
		}
	}
?>