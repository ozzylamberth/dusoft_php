<?php
	/**************************************************************************************
	* $Id: EliminarSM.php,v 1.1 2006/08/18 20:34:08 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* @author Hugo F. Manrique
	**************************************************************************************/	
	
	$VISTA = "HTML";
	$_ROOT = "../../../";
	
	include "../../../classes/rs_server/rs_server.class.php";
	include	"../../../includes/enviroment.inc.php";
	include "../../../app_modules/MedicamentosSoluciones/classes/Soluciones.class.php";
	
	class procesos_admin extends rs_server
	{
		/********************************************************************************
		*
		*********************************************************************************/
		function EliminarGrupoMedicaento($param)
		{
			$slc = new Soluciones();
			$rst = $slc->EliminarGrupoMedicamentos($param[0]);

			if($rst)
				$html1 = "<b class='label_mark'>EL GRUPO SE HA ELIMINADO CORRECTAMENTE</b>";
			else
				$html1 = "<b class='label_error'>".$slc->frmError['MensajeError']."</b>";
	
			$medicag = $slc->ObtenerGruposMedicamentos();
			$html .= "		<select name=\"grupos\" class=\"select\" onChange='EliminarGrupo(document.grupomedicamento)'>\n";
			$html .= "			<option value=\"-1\">----SELECCIONAR-----</option>";
			foreach($medicag as $key => $datos)
			{
				$html .= "			<option value=\"".$datos['grupo_id']."\">".$datos['descripcion']."</option>";
			}
			$html .= "		</select>\n";
			return $html1."~".$html;
		}
		/******************************************************************************************
		*
		*******************************************************************************************/
		function BuscarInformacionGrupo($param)
		{
			$slc = new Soluciones();
			
			$plantillas = $slc->ObtenerPlantillasAsociadas($param[0]);
			$codigos = $slc->ObtenerMedicamentosGrupo($param[0]);
			
			$html .= "<table width=\"100%\">\n";
			if($param[0] != '-1')
			{	
				$html .= "	<tr class=\"modulo_list_claro\">\n";
				$html .= "		<td valign=\"top\">".$this->CrearTablaAdcionados($codigos)."</td>\n";
				$html .= "	</tr>\n";
			}
			
			$html .= "	<tr class=\"modulo_list_claro\">\n";
			$html .= "		<td valign=\"top\">".$this->CrearPlantillas($plantillas,$param[0])."</td>\n";
			$html .= "	</tr>\n";

			$html .= "</table>\n";
			
			return $html;
		}
		/**********************************************************************************************
		*
		***********************************************************************************************/
		function CrearPlantillas($plantillas,$opcion)
		{
			$html .= "<table align=\"center\" border=\"0\" style=\"background:#FFFFFF\" width=\"100%\" class=\"modulo_table_list\">\n";
			if($opcion == '-1')
			{
				$html  = "	<tr class=\"modulo_list_claro\">\n";
				$html .= "  	<td colspan=\"6\" align=\"center\" class=\"normal_10AN\" width=\"100%\">INFORMACIÓN</td>\n";
				$html .= "	</tr>\n";
			}
			else
			{
				if(sizeof($plantillas) > 0)
				{
					$html .= "	<tr class=\"modulo_table_list_title\">\n";
					$html .= "  	<td align=\"center\" colspan=\"6\">PLANTILLAS ASOCIADAS</td>\n";
					$html .= "	</tr>\n";
					foreach($plantillas as $key => $datos)
					{
						$html .= "	<tr class=\"modulo_list_claro\">\n";
						$html .= "		<td width=\"32%\" class=\"normal_10AN\">".ucwords($datos['descripcion'])."</td>\n";
						$html .= "	</tr>\n";
					}
				}
				else
				{
					$html  = "	<tr class=\"modulo_list_claro\">\n";
					$html .= "  	<td colspan=\"6\" align=\"center\" class=\"normal_10AN\" width=\"100%\">NO HAY PLANTILLAS ASOCIADAS</td>\n";
					$html .= "	</tr>\n";
				}
			}
			$html .= "</table>\n";
			return $html;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function CrearTablaAdcionados($codigos)
		{
			if(sizeof($codigos) == 0)
			{
				$html .= " <table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\" style=\"background:#FFFFFF\">\n";
				$html .= "	<tr class=\"modulo_list_claro\">\n";
				$html .= "  	<td align=\"center\" colspan=\"2\">\n";
				$html .= "			<b class=\"normal_10AN\">NO HAY MEDICAMENTOS ASOCIADOS</b>\n";
				$html .= "  	</td>\n";
				$html .= "  </tr>\n";
				$html .= " </table>\n";
				return $html;
			}
				
			$path = SessionGetVar("rutaImagenes");
			
			$html .= " <table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\" style=\"background:#FFFFFF\">\n";
			$html .= "	<tr class=\"modulo_table_list_title\">\n";
			$html .= "  	<td align=\"center\" colspan=\"2\">MEDICAMENTOS ASOCIADOS</td>\n";
			$html .= "	</tr>\n";
			
			foreach($codigos as $key => $datos)
			{
				$nombre = $datos['nombre'];
				if(!$datos['nombre']) $nombre = $datos['producto'];
				
				$html .= "		<tr class=\"modulo_list_claro\">\n";
				$html .= "			<td width=\"%\" class=\"normal_10AN\">".$nombre."</td>\n";
				$html .= "		</tr>\n";
			}
			$html .= "	</table>\n";
			
			return $html;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function CrearTablaGruposSoluciones($soluciones)
		{
			$html .= "<table align=\"center\" border=\"0\" width=\"100%\" style=\"background:#FFFFFF\" class=\"modulo_table_list\">\n";
			$html .= "	<tr class=\"modulo_table_list_title\">\n";
			$html .= "  	<td align=\"center\">SOLUCIONES ASOCIADOS</td>\n";
			$html .= "	</tr>\n";

			$k = 0;
			$datos = array();
			foreach($soluciones as $key => $subnivel)
			{
				$html .= "	<tr class=\"modulo_list_claro\">\n";
				$html .= "		<td onMouseOut=\"OcultarTitle('S$k');\" onMouseOver=\"MostrarTitle('S$k');\">\n";
				$html .= "			<table class=\"normal_10AN\" height=\"16\">\n";
				$html .= "				<tr>\n";
				$html .= "					<td>$key</td>\n";
				$html .= "					<td valign=\"top\">\n";
				$html .= "						<div class=\"GrupoMezclas\" name=\"S$k\" id=\"S$k\" >COMPONENTES:\n";
				$html .= "							<ul class=\"Lista1\">\n";

				foreach($subnivel as $key2 => $subnivel1)
				{
					$html .= "								<li class=\"Mezclas\">".ucwords($subnivel1['producto'])."</li>\n";
					$datos[$subnivel1['mezcla_id']] = $subnivel1;
				}
				$html .= "							</ul>\n";
				$html .= "						</div>\n";
				$html .= "  				</td>\n";
				$html .= "  			</tr>\n";
				$html .= "  		</table>\n";
				$html .= "  	</td>\n";
				$html .= "	</tr>\n";
				$k++;
			}
			$html .= "</table>\n";
			
			SessionSetVar("Soluciones",$datos);
			return $html;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function BuscarInformacionSoluciones($param)
		{
			$slc = new Soluciones();
			$plantillas = $slc->ObtenerPlantillasSoluciones($param[0]);
			$soluciones = $slc->ObtenerInformacionSolucion($param[0]);
			
			if(sizeof($soluciones) > 0 || sizeof($plantillas))
			{
				$html .= "<table width=\"100%\">\n";
				$html .= "	<tr class=\"modulo_list_claro\">\n";
				$html .= "		<td valign=\"top\">".$this->CrearTablaGruposSoluciones($soluciones)."</td>\n";
				$html .= "	</tr>\n";
				$html .= "	<tr class=\"modulo_list_claro\">\n";
				$html .= "		<td valign=\"top\">".$this->CrearPlantillas($plantillas,$param[0])."</td>\n";
				$html .= "	</tr>\n";
				$html .= "</table>\n";
			}
			else
			{
				$html = "INFORMACIÓN";
			}
			return $html;
		}
		/***************************************************************************************
		*
		****************************************************************************************/
		function EliminarGrupoSoluciones($param)
		{
			$slc = new Soluciones();
			$solucion = SessionGetVar("Soluciones");
			$rst = $slc->EliminarGrupoSoluciones($param[0],$solucion);

			if($rst)
				$html1 = "<b class='label_mark'>EL GRUPO SE HA ELIMINADO CORRECTAMENTE</b>";
			else
				$html1 = "<b class='label_error'>".$slc->frmError['MensajeError']."</b>";
				
			$html .= "	<select name=\"gruposol\" class=\"select\" onChange='InformacionSoluciones(document.gruposolucion)'>\n";
			$html .= "		<option value=\"-1\">----SELECCIONAR-----</option>\n";
			
			$solug = $slc->ObtenerGruposSoluciones();
			foreach($solug as $key => $datos)
			{
				$html .= "		<option value=\"".$datos['grupo_mezcla_id']."\">".$datos['descripcion']."</option>\n";
			}
			$html .= "	</select>\n";
			return $html1."~".$html;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function BuscarInformacionSolucionS($param)
		{
			$slc = new Soluciones();
			$medicamentos = $slc->BuscarMedicamentosGrupo($param[0]);
			
			$html = "INFORMACIÓN";
			
			if(sizeof($medicamentos) > 0)
				$html = $this->CrearTablaAdcionados($medicamentos);
			
			
			return $html;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function EliminarSolucionesS($param)
		{
			$slc = new Soluciones();
			$rst = $slc->EliminarGruposMedicamentosSoluciones($param[0]);

			if($rst)
				$html1 = "<b class='label_mark'>EL GRUPO SE HA ELIMINADO CORRECTAMENTE</b>";
			else
				$html1 = "<b class='label_error'>".$slc->frmError['MensajeError']."</b>";
	
			$soluciong = $slc->GruposMedicamentosSoluciones();
			
			$html .= "	<select name=\"grupos\" class=\"select\" onChange='EliminarSolucionesS(document.clasificacionSoluciones)'>\n";
			$html .= "		<option value=\"-1\">----SELECCIONAR-----</option>";
			
			for($i=0; $i<sizeof($soluciong); $i++)
			{
				$html .= "		<option value=\"".$soluciong[$i]['grupo_id']."\">".$soluciong[$i]['descripcion']."</option>";
			}
			$html .= "	</select>\n";
			
			return $html1."~".$html;
		}
	}
	$oRS = new procesos_admin( array( 'ActivarMenu'));
	$oRS->action();	
?>