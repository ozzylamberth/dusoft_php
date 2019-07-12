<?php
	/**************************************************************************************
	* $Id: app_FacturacionMovimientos_userclasses_HTML.php,v 1.2 2007/05/24 22:29:51 hugo Exp $
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* $Revision: 1.2 $ 	
	* @author Hugo Freddy Manrique Arango
	***************************************************************************************/
	class app_FacturacionMovimientos_userclasses_HTML extends app_FacturacionMovimientos_user
	{
		function app_FacturacionMovimientos_userclasses_HTML(){}
		/**************************************************************************************
		* @return boolean
		***************************************************************************************/
		function FormaMain()
		{
			$this->Main();
			$url[0] = 'app';
			$url[1] = 'FacturacionMovimientos';
			$url[2] = 'user';
			$url[3] = 'FormaMenuMovimientos';
			$url[4] = 'Movimiento';
			$arreglo[0] = 'EMPRESA';
			$this->salida = gui_theme_menu_acceso('MOVIMIENTOS DE FACTURACION',$arreglo,$this->empresas,$url,ModuloGetURL('system','Menu'));

			return true;
		}
		/**************************************************************************************
		* @return boolean
		***************************************************************************************/
		function FormaMenuMovimientos()
		{
			$this->MenuMovimientos();
			IncludeClass('MovimientosHTML','','app','FacturacionMovimientos');
			
			$this->salida  = ThemeAbrirTabla('MENU');
			$this->salida .= MovimientosHTML::FormaEncabezado($this->empresas);
			$this->salida .= "<br>\n";
			$this->salida .= "<table border=\"0\" width=\"56%\" align=\"center\" >\n";
			$this->salida .= "	<tr>\n";
			$this->salida .= "		<td>\n";
			$this->salida .= "			<table border=\"0\" width=\"100%\" align=\"center\" class=\"formulacion_table_list\">\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td align=\"center\" class=\"formulacion_table_list\" ><b style=\"color:#ffffff\">MENÚ</td>\n";
			$this->salida .= "				</tr>\n";
			foreach($this->menu as $key => $grupo)
			{
				$this->salida .= "				<tr>\n";
				$this->salida .= "					<td class=\"modulo_list_claro\" align=\"center\" height=\"18\">\n";
				$this->salida .= "						<a href=\"".$this->action['siguiente'].URLRequest(array("grupo"=>$grupo))."\"><b>".$grupo['grupo']." DE FACTURAS</b></a>\n";
				$this->salida .= "					</td>\n";
				$this->salida .= "				</tr>\n";
			}
			$this->salida .= "			</table>\n";
			$this->salida .= "		</td>\n";
			$this->salida .= "	</tr>\n";
			$this->salida .= "	<tr>\n";
			$this->salida .= "		<td align=\"center\"><br>\n";
			$this->salida .= "			<form name=\"form\" action=\"".$this->action['volver']."\" method=\"post\">";
			$this->salida .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
			$this->salida .= "			</form>";
			$this->salida .= "		</td>";
			$this->salida .= "	</tr>";
			$this->salida .= "</table>";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/**************************************************************************************
		* @return boolean
		***************************************************************************************/
		function FormaMostrarFacturas()
		{
			$this->MostrarFacturas();
			IncludeClass('Movimientos','','app','FacturacionMovimientos');
			IncludeClass('MovimientosHTML','','app','FacturacionMovimientos');
			
			$mvh = new MovimientosHTML();
			$file = "./app_modules/FacturacionMovimientos/RemoteXajax/movimientos.php";
			$this->SetXajax(array("MarcarFactura","EvaluarFacturasAsignadas","AgregarUsuariosGrupo","EvaluarFacturasConfirmar","EliminarFacturasSelecciondas"),$file);
					
			$this->salida  = ThemeAbrirTabla($this->grupos['grupo']." DE FACTURAS");
			$this->salida .= $mvh->FormaEncabezado($this->empresas)."<br>\n";
			$this->salida .= $mvh->FormaBuscadorFacturas($this->empresas['empresa_id'],$this->grupos,$this->request['buscador'],$this->action);
			$this->salida .= $mvh->FormaAsignarFactura($this->empresas['empresa_id'],$this->grupos,$this->action,$this->request,$this->facturas); 
			$this->salida .= "<table align=\"center\">\n";
			$this->salida .= "	<tr>\n";
			$this->salida .= "		<td align=\"center\"><br>\n";
			$this->salida .= "			<form name=\"form\" action=\"".$this->action['volver']."\" method=\"post\">";
			$this->salida .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
			$this->salida .= "			</form>";
			$this->salida .= "		</td>";
			$this->salida .= "	</tr>";
			$this->salida .= "</table>";
			$this->salida .= ThemeCerrarTabla();
			return true;
		}
		/**************************************************************************************
		* @return boolean
		***************************************************************************************/
		function FormaIngresarMovimientos()
		{
			$this->IngresarMovimientos();
			
			IncludeClass('MovimientosHTML','','app','FacturacionMovimientos');
			$this->salida = MovimientosHTML::FormaMensaje("MENSAJE","center",$this->action,$this->frmError['MensajeError']);
			return true;
		}
	}
?>