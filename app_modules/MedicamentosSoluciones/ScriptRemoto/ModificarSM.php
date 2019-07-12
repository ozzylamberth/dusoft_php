<?php
	/**************************************************************************************
	* $Id: ModificarSM.php,v 1.2 2006/08/18 21:42:48 hugo Exp $ 
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
		function BuscarMedicamentos($arreglo)  
		{
			$slc = new Soluciones();
			$path = SessionGetVar("rutaImagenes");
			$pagina = $arreglo[2];
			$producto = $arreglo[0];
			$pp_activo = $arreglo[1];
			
			print_r($arreglo);
			
			$est = "style=\"text-indent:0pt;\"";
			$action = "document.buscador";
			$medicamentos = $slc->BuscarMedicamentos($producto,$pp_activo,$pagina);

			$html .= "<div id=\"cerrar\" style=\"width:40px;height:20px;z-index:1\"><a class=\"label_error\" href=\"javascript:OcultarSpan('resultado');\">CERRAR</a></div>";
			$html .= $this->ObtenerPaginado($pagina,$action,$path,$slc,1);
			$html .= "	<table align=\"center\" border=\"0\" width=\"98%\" class=\"modulo_table_list\">\n";
			$html .= "		<tr class=\"modulo_table_list_title\" >\n";
			$html .= "			<td $est width=\"4%\">&nbsp;</td>\n";
			$html .= "			<td $est width=\"10%\" style=\"text-indent:0pt;\" align=\"center\" >CÓDIGO</td>\n";
			$html .= "			<td $est width=\"%\" align=\"center\" >PRODUCTO</td>\n";
			$html .= "			<td $est width=\"25%\" align=\"center\" >PRINCIPIO ACTIVO</td>\n";
			$html .= "			<td $est width=\"22%\" style=\"text-indent:0pt;\" align=\"center\" >CONCENTRACIÓN</td>\n";
			$html .= "			<td $est width=\"1%\"  style=\"text-indent:0pt;\" align=\"center\" ></td>\n";
			$html .= "		</tr>\n";			
			
			$datos = array();
			$codigos = array();
			$codigos = SessionGetVar("CodigosAdd");
			
			for($i=0; $i<sizeof($medicamentos); $i++)
			{
				$est = 'modulo_list_claro'; $back = "#DDDDDD";
				if($i % 2 == 0)
				{
				  $est = 'modulo_list_oscuro'; $back = "#CCCCCC";
				}
				
				$datos[$medicamentos[$i]['codigo_producto']] = $medicamentos[$i];
				
				$prodc = str_replace('"','-',$medicamentos[$i]['producto']);
				echo $prodc;
				$html .= "		<tr class=\"$est\" onmouseout=mOut(this,\"".$back."\"); onmouseover=mOvr(this,'#FFFFFF'); >\n";
				$html .= "			<td align=\"center\" class=\"normal_10A\">".$medicamentos[$i]['item']."</td>\n";
				$html .= "			<td align=\"center\">".$medicamentos[$i]['codigo_producto']."</td>\n";
				$html .= "			<td align=\"left\" class=\"normal_10A\" >".$medicamentos[$i]['producto']."</td>\n";
				$html .= "			<td align=\"left\"  >".$medicamentos[$i]['principio_activo']."</td>\n";
				$html .= "			<td align=\"left\"  >".$medicamentos[$i]['forma']."</td>\n";
				$html .= "			<td align=\"center\" id=\"ad".$medicamentos[$i]['codigo_producto']."\" title=\"ADICIONAR MEDICAMENTO\" >\n";
				if(sizeof($codigos[$medicamentos[$i]['codigo_producto']]) == 0)
				{
					$html .= "				<a href=\"javascript:AdicionarMedicamentos('".$medicamentos[$i]['codigo_producto']."','".$prodc."','".$pagina."".$i."');\">\n";
					$html .= "					<img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"15\" height=\"15\">\n";				
					$html .= "				<a>\n";
				}
				else
				{
					$html .= "				<a href=\"javascript:ExtraerMedicamentos('".$medicamentos[$i]['codigo_producto']."','".$prodc."','".$pagina."".$i."');\">\n";
					$html .= "					<img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"15\" height=\"15\">\n";				
					$html .= "				<a>\n";
				}
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
			}
			
			$html .= "		</table><br>\n";
			$html .= $this->ObtenerPaginado($pagina,$action,$path,$slc,2);

			if(sizeof($medicamentos) == 0)
			{
				$html  = "<div id=\"cerrar\" style=\"width:40px;height:20px;z-index:1\"><a class=\"label_error\" href=\"javascript:OcultarSpan('resultado');\">CERRAR</a></div>";
				$html .= "	<center><b class=\"label_error\">LA BÚSQUEDA NO ARROJO NINGUN RESULTADO</b></center><br>\n";
				$html .= "</div>\n";
			}
      
			return  $html;
		}
		/********************************************************************************
		*
		********************************************************************************/
		function AdicionarMedicamentosSeleccionados($param)
		{
			$codigo = $param[0];
			$nombre = $param[1];
			$celda =  $param[2];
			$path = SessionGetVar("rutaImagenes");
			
			$codigos = array();
			$codigos = SessionGetVar("CodigosAdd");
			$codigos[$param[0]]['codigo'] = $param[0];
			$codigos[$param[0]]['nombre'] = $param[1];
			
			$html2 .= "				<a href=\"javascript:ExtraerMedicamentos('".$codigo."','".$nombre."','".$celda."');\">\n";
			$html2 .= "					<img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"15\" height=\"15\">\n";				
			$html2 .= "				<a>\n";
			
			$html = $this->CrearTablaAdcionados($codigos);
			
			SessionSetVar("CodigosAdd",$codigos);
			return $html."~".$html2;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ExtraerMedicamentosSeleccionados($param)
		{
			$codigo = $param[0];
			$nombre = $param[1];
			$celda =  $param[2];
			$path = SessionGetVar("rutaImagenes");
			
			$codigos = array();
			$codigos = SessionGetVar("CodigosAdd");
			
			unset($codigos[$param[0]]);
			
			$html = $this->CrearTablaAdcionados($codigos);
			$html2 .= "				<a href=\"javascript:AdicionarMedicamentos('".$codigo."','".$nombre."','".$celda."');\">\n";
			$html2 .= "					<img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"15\" height=\"15\">\n";				
			$html2 .= "				<a>\n";
			
			SessionSetVar("CodigosAdd",$codigos);
			return $html."~".$html2;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function AgregarPlantilla($param)
		{
			$plantilla = array();
			$plantilla = SessionGetVar("PlantillaAdd");
			$path = SessionGetVar("rutaImagenes");
			$plantilla[$param[0]]['hc_modulo'] = $param[0];
			$plantilla[$param[0]]['descripcion'] = $param[1];
			
			$html .= "	<a title=\"ELIMINAR PLANTILLA\" href=\"javascript:EliminarPlantilla('".$param[0]."','".$param[1]."');\">\n";
			$html .= "		<img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"14\" height=\"14\">\n";				
			$html .= "	</a>\n";
			
			SessionSetVar("PlantillaAdd",$plantilla);
			
			return $html;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function EliminarPlantilla($param)
		{
			$plantilla = array();
			$plantilla = SessionGetVar("PlantillaAdd");
			$path = SessionGetVar("rutaImagenes");
			unset($plantilla[$param[0]]);
			
			$html .= "	<a title=\"ADICIONAR PLANTILLA\" href=\"javascript:AgregarPlantilla('".$param[0]."','".$param[1]."');\">\n";
			$html .= "		<img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"14\" height=\"14\">\n";				
			$html .= "	</a>\n";
			
			SessionSetVar("PlantillaAdd",$plantilla);
			
			return $html;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function AgregarPlantillaSolucion($param)
		{
			$plantilla = array();
			$plantilla = SessionGetVar("PlantillaAddM");
			$path = SessionGetVar("rutaImagenes");
			$plantilla[$param[0]]['hc_modulo'] = $param[0];
			$plantilla[$param[0]]['descripcion'] = $param[1];
			
			$html .= "	<a title=\"ELIMINAR PLANTILLA\" href=\"javascript:EliminarPlantillaM('".$param[0]."','".$param[1]."');\">\n";
			$html .= "		<img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"14\" height=\"14\">\n";				
			$html .= "	</a>\n";
			
			SessionSetVar("PlantillaAddM",$plantilla);
			
			return $html;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function EliminarPlantillaSolucion($param)
		{
			$plantilla = array();
			$plantilla = SessionGetVar("PlantillaAddM");
			$path = SessionGetVar("rutaImagenes");
			unset($plantilla[$param[0]]);
			
			$html .= "	<a title=\"ADICIONAR PLANTILLA\" href=\"javascript:AgregarPlantillaM('".$param[0]."','".$param[1]."');\">\n";
			$html .= "		<img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"14\" height=\"14\">\n";				
			$html .= "	</a>\n";
			
			SessionSetVar("PlantillaAddM",$plantilla);
			
			return $html;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ObtenerPaginado($pagina,$action,$path,$slc,$op)
		{
			$TotalRegistros = $slc->conteo;
			$TablaPaginado = "";
				
			if($limite == null)
			{
				$uid = UserGetUID();
	     	$LimitRow = intval(GetLimitBrowser());
			}
			else
			{
				$LimitRow = $limite;
			}
			if ($TotalRegistros > 0)
			{
				$columnas = 1;
				$NumeroPaginas = intval($TotalRegistros/$LimitRow);
				if($TotalRegistros%$LimitRow > 0)
				{
					$NumeroPaginas++;
				}
						
				$Inicio = $pagina;
				if($NumeroPaginas - $pagina < 9 )
				{
					$Inicio = $NumeroPaginas - 9;
				}
				else if($pagina > 1)
				{
					$Inicio = $pagina - 1;
				}
				
				if($Inicio <= 0)
				{
					$Inicio = 1;
				}
					
				$estilo = " style=\"font-family: Lucida Sans Unicode,sans_serif, Verdana, helvetica, Arial; font-size:15px;\" "; 

				$TablaPaginado .= "<tr>\n";
				if($NumeroPaginas > 1)
				{
					$TablaPaginado .= "		<td class=\"label\" bgcolor=\"#D3DCE3\">Páginas:</td>\n";
					if($pagina > 1)
					{
						$TablaPaginado .= "		<td class=\"label\" bgcolor=\"#D3DCE3\">\n";
						$TablaPaginado .= "			<a href=\"javascript:CrearVariables(".$action.",'1')\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
						$TablaPaginado .= "		</td><td class=\"label\" bgcolor=\"#D3DCE3\">\n";
						$TablaPaginado .= "			<a href=\"javascript:CrearVariables(".$action.",'".($pagina-1)."')\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
						$TablaPaginado .= "		</td>\n";
						$columnas +=2;
					}
					$Fin = $NumeroPaginas + 1;
					if($NumeroPaginas > 10)
					{
						$Fin = 10 + $Inicio;
					}
						
					for($i=$Inicio; $i< $Fin ; $i++)
					{
						if ($i == $pagina )
						{
							$TablaPaginado .="		<td class=\"modulo_list_oscuro\" $estilo align=\"center\"><b>".$i."</b></td>\n";
						}
						else
						{
							$TablaPaginado .="		<td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:CrearVariables(".$action.",'".$i."')\">".$i."</a></td>\n";
						}
						$columnas++;
					}
				}
				if($pagina <  $NumeroPaginas )
				{
					$TablaPaginado .= "		<td class=\"label\" bgcolor=\"#D3DCE3\">\n";
					$TablaPaginado .= "			<a href=\"javascript:CrearVariables(".$action.",'".($pagina+1)."')\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
					$TablaPaginado .= "		</td><td class=\"label\" bgcolor=\"#D3DCE3\">\n";
					$TablaPaginado .= "			<a href=\"javascript:CrearVariables(".$action.",'".$NumeroPaginas."')\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
					$TablaPaginado .= "		</td>\n";
					$columnas +=2;
				}
				$aviso .= "		<tr><td class=\"label\"  colspan=".$columnas." align=\"center\">\n";
				$aviso .= "			Página&nbsp;".$pagina." de ".$NumeroPaginas."</td>\n";
				$aviso .= "		</tr>\n";
				
				if($op == 2)
				{
					$TablaPaginado .= $aviso;
				}
				else
				{
					$TablaPaginado = $aviso.$TablaPaginado;
				}
			}
			
			$Tabla .= "<table align=\"center\" cellspacing=\"3\" >\n";
			$Tabla .= $TablaPaginado;
			$Tabla .= "</table><br>";

			return $Tabla;
		}
		/******************************************************************************************
		*
		*******************************************************************************************/
		function BuscarMedicamentosPlantillas($param)
		{
			$slc = new Soluciones();
			
			$plantillas = $slc->ObtenerPlantillasAsociadas($param[0]);
			$datos = $slc->ObtenerPlantillas();
			$codigos = $slc->ObtenerMedicamentosGrupo($param[0]);
			
			$html0 = $this->CrearTablaAdcionados($codigos);
			$html1 = $this->CrearPlantillas($datos,$plantillas,$param[0]);
			
			SessionSetVar("CodigosAdd",$codigos);
			SessionSetVar("CodigosAsociados",$codigos);
			SessionSetVar("PlantillaAdd",$plantillas);
			SessionSetVar("PlantillaAsociadas",$plantillas);
			
			return $html1."~".$html0;
		}
		/**********************************************************************************************
		*
		***********************************************************************************************/
		function CrearPlantillas($datos,$plantillas,$opcion,$fc)
		{
			$path = SessionGetVar("rutaImagenes");
			$html = "";
			if($opcion == '-1')
			{
				$html  = "	<tr class=\"modulo_list_claro\">\n";
				$html .= "  	<td colspan=\"6\" align=\"center\" class=\"normal_10AN\" width=\"100%\">PLANTILLAS</td>\n";
				$html .= "	</tr>\n";
			}
			else
			{
				$html .= "	<tr class=\"modulo_table_list_title\">\n";
				$html .= "  	<td align=\"center\" colspan=\"6\">PLANTILLAS</td>\n";
				$html .= "	</tr>\n";
				
				for($i=0; $i<sizeof($datos); $i=$i+3)
				{
					$img = "checkno.png";
					$fnc = "AgregarPlantilla".$fc;
					$ttl = "ADICIONAR PLANTILLA";
					if($plantillas[$datos[$i]['hc_modulo']]) 
					{
						$img = "checksi.png";
						$fnc = "EliminarPlantilla".$fc;
						$ttl = "ELIMINAR PLANTILLA";
					}
					$html .= "	<tr class=\"modulo_list_claro\">\n";
					$html .= "		<td width=\"1%\" id=\"$fc".$datos[$i]['hc_modulo']."\">\n";
					$html .= "			<a title=\"$ttl\" href=\"javascript:$fnc('".$datos[$i]['hc_modulo']."','".$datos[$i]['descripcion']."');\">\n";
					$html .= "				<img src=\"".$path."/images/$img\" border=\"0\" width=\"14\" height=\"14\">\n";				
					$html .= "			<a>\n";
					$html .= "		</td>\n";
					$html .= "		<td width=\"32%\" class=\"normal_10AN\">".ucwords($datos[$i]['descripcion'])."</td>\n";
					
					if($datos[$i+1]['descripcion'])
					{
						$img = "checkno.png";
						$fnc = "AgregarPlantilla".$fc;
						$ttl = "ADICIONAR PLANTILLA";
						if($plantillas[$datos[$i+1]['hc_modulo']]) 
						{
							$img = "checksi.png";
							$fnc = "EliminarPlantilla".$fc;
							$ttl = "ELIMINAR PLANTILLA";
						}						
						$html .= "		<td width=\"1%\" id=\"$fc".$datos[$i+1]['hc_modulo']."\">\n";
						$html .= "			<a title=\"$ttl\" href=\"javascript:$fnc('".$datos[$i+1]['hc_modulo']."','".$datos[$i+1]['descripcion']."');\">\n";
						$html .= "				<img src=\"".$path."/images/$img\" border=\"0\" width=\"14\" height=\"14\">\n";				
						$html .= "			<a>\n";
						$html .= "		</td>\n";
						$html .= "		<td width=\"32%\" class=\"normal_10AN\">".ucwords($datos[$i+1]['descripcion'])."</td>\n";
						if($datos[$i+2]['descripcion'])
						{
							$img = "checkno.png";
							$fnc = "AgregarPlantilla".$fc;
							$ttl = "ADICIONAR PLANTILLA";
							if($plantillas[$datos[$i+2]['hc_modulo']]) 
							{
								$img = "checksi.png";
								$fnc = "EliminarPlantilla".$fc;
								$ttl = "ELIMINAR PLANTILLA";
							}								
							$html .= "		<td width=\"1%\" id=\"$fc".$datos[$i+2]['hc_modulo']."\">\n";
							$html .= "			<a title=\"$ttl\" href=\"javascript:$fnc('".$datos[$i+2]['hc_modulo']."','".$datos[$i+2]['descripcion']."');\">\n";
							$html .= "				<img src=\"".$path."/images/$img\" border=\"0\" width=\"14\" height=\"14\">\n";				
							$html .= "			<a>\n";
							$html .= "		</td>\n";
							$html .= "		<td width=\"34%\" class=\"normal_10AN\">".ucwords($datos[$i+2]['descripcion'])."</td>\n";
						}
						else
						{
							$html .= "		<td colspan=\"2\"></td>\n";
						}
					}
					else
					{
						$html .= "		<td colspan=\"4\"></td>\n";
					}
					$html .= "	</tr>\n";
				}
			}
			return $html;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function CrearTablaAdcionados($codigos)
		{
			if(sizeof($codigos) == 0)
			{
				$html .= "			<font class=\"normal_10AN\">NO HAY MEDICAMENTOS ADICIONADOS</font>\n";
				return $html;
			}
				
			$path = SessionGetVar("rutaImagenes");
			
			$html .= " <table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\" style=\"background:#FFFFFF\">\n";
			$html .= "	<tr class=\"modulo_table_list_title\">\n";
			$html .= "  	<td align=\"center\" colspan=\"2\">MEDICAMENTOS ADICIONADOS</td>\n";
			$html .= "	</tr>\n";
			
			foreach($codigos as $key => $datos)
			{
				$html .= "		<tr class=\"modulo_list_claro\">\n";		
				$html .= "			<td width=\"1%\">\n";
				$html .= "				<a title=\"ELIMINAR MEDICAMENTO\" href=\"javascript:ExtraerMedicamentos('".$datos['codigo']."','".$datos['nombre']."','XY');\">\n";
				$html .= "					<img src=\"".$path."/images/checkS.gif\" border=\"0\" width=\"15\" height=\"15\">\n";				
				$html .= "				<a>\n";
				$html .= "			</td>\n";
				$html .= "			<td width=\"%\" class=\"normal_10AN\">".$datos['nombre']."</td>\n";
				$html .= "		</tr>\n";
			}
			$html .= "	</table>\n";
			
			return $html;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function ModificarGrupoMedicamento($param)
		{
			$slc = new Soluciones();
			
			$path = SessionGetVar("rutaImagenes");
			$codnuevos = SessionGetVar("CodigosAdd");
			$codasocia = SessionGetVar("CodigosAsociados");
			$plnuevas = SessionGetVar("PlantillaAdd");
			$plasocia = SessionGetVar("PlantillaAsociadas");
			
			foreach($codnuevos as $key => $datos)
			{
				if($datos['codigo'] == $codasocia[$key]['codigo'])
				{
					unset($codasocia[$key]);
					unset($codnuevos[$key]);
				}
			}
			
			foreach($plnuevas as $key => $datos)
			{
				if($datos['hc_modulo'] == $plasocia[$key]['hc_modulo'])
				{
					unset($plnuevas[$key]);
					unset($plasocia[$key]);
				}
			}
			
			$rst = true;
			if(sizeof($codnuevos) > 0)
				$rst = $slc->IngresarMedicamentosGrupos($codnuevos,$param[0]);
			
			if($rst && sizeof($codasocia))
				$slc->EliminarMedicamentosGrupos($codasocia,$param[0]);
			
			if($rst && sizeof($plnuevas))
				$slc->IngresarPlantillasGrupo($plnuevas,$param[0]);
				
			if($rst && sizeof($plasocia))
				$slc->EliminarPlantillasGrupo($plasocia,$param[0]);
			
			$html = "";
			if($rst)
			{
				SessionDelVar("CodigosAdd");
				SessionDelVar("CodigosAsociados");
				SessionDelVar("PlantillaAdd");
				SessionDelVar("PlantillaAsociadas");
			
				$html  = $this->CrearPlantillas(array(),array(),'-1');
				$html .= "~".$this->CrearTablaAdcionados(array());
			}
			return $slc->frmError['MensajeError']."~".$html;
		}
		/**********************************************************************************************
		*
		***********************************************************************************************/
		function BuscarSolucionesPlantillas($param)
		{
			$slc = new Soluciones();
			
			$plantillas = $slc->ObtenerPlantillasSoluciones($param[0]);
			$soluciones = $slc->ObtenerInformacionSolucion($param[0]);
			$datos = $slc->ObtenerPlantillas();

			$html1 = "SOLUCIONES ADICIONADAS"; 
			$html = $this->CrearPlantillas($datos,$plantillas,$param[0],'M');
			
			if(sizeof($soluciones) > 0)
				$html1 = $this->CrearTablaGruposSoluciones($soluciones);
			
			
			SessionSetVar("PlantillaAddM",$plantillas);
			SessionSetVar("PlantillaMAsociada",$plantillas);
			return $slc->frmError['MensajeError']."~".$html."~".$html1;
		}
		/*************************************************************************************
		*
		**************************************************************************************/
		function ModificarPlantillaSolucion($param)
		{
			$slc = new Soluciones();
			$plnuevas = SessionGetVar("PlantillaAddM");
			$plasocia = SessionGetVar("PlantillaMAsociada");
			$solucion = SessionGetVar("SolucionesElimina");
			
			$rst = true;
			
			foreach($plnuevas as $key => $datos)
			{
				if($datos['hc_modulo'] == $plasocia[$key]['hc_modulo'])
				{
					unset($plnuevas[$key]);
					unset($plasocia[$key]);
				}
			}

			if(sizeof($plnuevas) > 0)
				$rst = $slc->IngresarPlantillasSolucion($plnuevas,$param[0]);
				
			if(sizeof($plasocia) > 0 && $rst)
				$rst = $slc->EliminarPlantillasSolucion($plasocia,$param[0]);
			
			if(sizeof($solucion) > 0 && $rst)
				$rst = $slc->EliminarSoluciones($solucion,$param[0]);
			
			$html = '';
			if($rst)
			{
				SessionDelVar("PlantillaAddM");
				SessionDelVar("PlantillaMAsociada");
				SessionDelVar("SolucionesElimina");
				$html  = $this->CrearPlantillas(array(),array(),'-1');
			}
			return $slc->frmError['MensajeError']."~".$html;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function CrearTablaGruposSoluciones($soluciones)
		{
			$path = SessionGetVar("rutaImagenes");
			
			$html .= "<table align=\"center\" border=\"0\" width=\"100%\" style=\"background:#FFFFFF\" class=\"modulo_table_list\">\n";
			$html .= "	<tr class=\"modulo_table_list_title\">\n";
			$html .= "  	<td align=\"center\" colspan=\"2\">SOLUCIONES ASOCIADOS</td>\n";
			$html .= "	</tr>\n";

			$k = 0;
			$datos = array();
			foreach($soluciones as $key => $subnivel)
			{
				$html1  = "		<td onMouseOut=\"OcultarTitle('S$k');\" onMouseOver=\"MostrarTitle('S$k');\">\n";
				$html1 .= "			<table class=\"normal_10AN\" height=\"16\">\n";
				$html1 .= "				<tr>\n";
				$html1 .= "					<td>$key</td>\n";
				$html1 .= "					<td valign=\"top\">\n";
				$html1 .= "						<div class=\"GrupoMezclas\" name=\"S$k\" id=\"S$k\" >COMPONENTES:\n";
				$html1 .= "							<ul class=\"Lista1\">\n";

				foreach($subnivel as $key2 => $subnivel1)
				{
					$html1 .= "								<li class=\"Mezclas\">".ucwords($subnivel1['producto'])."</li>\n";
					
					$html0  = "			<td width=\"1%\" id=\"sc".$subnivel1['mezcla_id']."\">\n";
					$html0 .= "				<a title=\"ELIMINAR SOLUCIONES\" href=\"javascript:ExtraerSolucionesAsociadas(new Array('".$subnivel1['mezcla_id']."'));\">\n";
					$html0 .= "					<img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"15\" height=\"15\">\n";				
					$html0 .= "				<a>\n";
					$html0 .= "			</td>\n";
					
					$datos[$subnivel1['mezcla_id']] = $subnivel1;
				}
				$html1 .= "							</ul>\n";
				$html1 .= "						</div>\n";
				$html1 .= "  				</td>\n";
				$html1 .= "  			</tr>\n";
				$html1 .= "  		</table>\n";
				$html1 .= "  	</td>\n";
				$html1 .= "	</tr>\n";

				$html .= "	<tr class=\"modulo_list_claro\">\n";
				$html .= $html0." ".$html1; 
				$k++;
			}
			$html .= "</table>\n";
			
			SessionSetVar("Soluciones",$datos);
			SessionDelVar("SolucionesElimina");
			return $html;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ExtraerSolucionesAsociadas($param)
		{
			$solucion = SessionGetVar("Soluciones");
			$slelimina = SessionGetVar("SolucionesElimina");
			
			$slelimina[$param[0]] = $solucion[$param[0]];
			SessionSetVar("SolucionesElimina",$slelimina);
			
			$path = SessionGetVar("rutaImagenes");
			$html .= "				<a title=\"ELIMINAR SOLUCIONES\" href=\"javascript:AdicionarSolucionesAsociadas(new Array('".$solucion[$param[0]]['mezcla_id']."'));\">\n";
			$html .= "					<img src=\"".$path."/images/checksi.png\" border=\"0\" width=\"15\" height=\"15\">\n";				
			$html .= "				<a>\n";
			
			return $html;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function AdicionarSolucionesAsociadas($param)
		{
			$solucion = SessionGetVar("SolucionesElimina");
			
			$path = SessionGetVar("rutaImagenes");
			$html .= "				<a title=\"ELIMINAR SOLUCIONES\" href=\"javascript:ExtraerSolucionesAsociadas(new Array('".$solucion[$param[0]]['mezcla_id']."'));\">\n";
			$html .= "					<img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"15\" height=\"15\">\n";				
			$html .= "				<a>\n";
			
			unset($solucion[$param[0]]);
			SessionSetVar("SolucionesElimina",$solucion);
			
			return $html;
		}
	}
	$oRS = new procesos_admin( array( 'ActivarMenu'));
	$oRS->action();	
?>