<?php
	/**************************************************************************************
	* $Id: Soluciones.php,v 1.1 2006/08/18 20:34:26 hugo Exp $ 
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
			
			$pagina = $arreglo[2];
			$producto = $arreglo[0];
			$pp_activo = $arreglo[1];
			
			$path = SessionGetVar("rutaImagenes");
			$medica = SessionGetVar("MedicamentosSel");
			
			$est = "style=\"text-indent:0pt;\"";
			$action = "document.buscador";
			$medicamentos = $slc->BuscarMedicamentosEspecial($producto,$pp_activo,$pagina);

			$html .= "<div id=\"cerrar\" style=\"width:40px;height:20px;z-index:1\"><a class=\"label_error\" href=\"javascript:OcultarSpan('resultado');\">CERRAR</a></div>";
			$html .= $this->ObtenerPaginado($pagina,$action,$path,$slc,1);
			$html .= "	<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">\n";
			$html .= "		<tr class=\"modulo_table_list_title\" >\n";
			$html .= "			<td $est width=\"7%\">&nbsp;</td>\n";
			$html .= "			<td $est width=\"10%\" style=\"text-indent:0pt;\" align=\"center\" >CÓDIGO</td>\n";
			$html .= "			<td $est width=\"%\" align=\"center\" >PRODUCTO</td>\n";
			$html .= "			<td $est width=\"25%\" align=\"center\" >PRINCIPIO ACTIVO</td>\n";
			$html .= "			<td $est width=\"22%\" style=\"text-indent:0pt;\" align=\"center\" >CONCENTRACIÓN</td>\n";
			$html .= "			<td $est width=\"1%\"  style=\"text-indent:0pt;\" align=\"center\" ></td>\n";
			$html .= "		</tr>\n";			
			
			$datos = array();
		
			for($i=0; $i<sizeof($medicamentos); $i++)
			{
				$est = 'modulo_list_claro'; $back = "#DDDDDD";
				if($i % 2 == 0)
				{
				  $est = 'modulo_list_oscuro'; $back = "#CCCCCC";
				}
				
				$datos[$medicamentos[$i]['codigo_producto']] = $medicamentos[$i];
				
				$prodc = str_replace('"','-',$medicamentos[$i]['producto']);
				
				$clase = "normal_10A"; 
				if($medicamentos[$i]['sw_soluciones'] == '1') $clase = "normal_10AN";
				
				$arrjs = "new Array('".$medicamentos[$i]['codigo_producto']."','".$prodc."','".$medicamentos[$i]['sw_soluciones']."')";
				
				$html .= "		<tr class=\"$est\" onmouseout=mOut(this,\"".$back."\"); onmouseover=mOvr(this,'#FFFFFF'); >\n";
				$html .= "			<td align=\"center\" class=\"$clase\">".$medicamentos[$i]['item']."</td>\n";
				$html .= "			<td align=\"center\" >".$medicamentos[$i]['codigo_producto']."</td>\n";
				$html .= "			<td align=\"left\" class=\"$clase\" >".$medicamentos[$i]['producto']."</td>\n";
				$html .= "			<td align=\"left\" class=\"$clase\" >".$medicamentos[$i]['principio_activo']."</td>\n";
				$html .= "			<td align=\"left\"  >".$medicamentos[$i]['forma']."</td>\n";
				$html .= "			<td align=\"center\" id=\"ad".$medicamentos[$i]['codigo_producto']."\" title=\"ADICIONAR MEDICAMENTO\" >\n";
				if($medica[$medicamentos[$i]['codigo_producto']])
				{
					$html .= "				<a href=\"javascript:DeseleccionarMedicamentos($arrjs);\">\n";
					$html .= "					<img src=\"".$path."/images/checksi.png\" border=\"0\" >\n";				
					$html .= "				<a>\n";
				}
				else
				{
					$html .= "				<a href=\"javascript:SeleccionarMedicamentos($arrjs);\">\n";
					$html .= "					<img src=\"".$path."/images/checkno.png\" border=\"0\" >\n";				
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
		function BuscarMedicamentosClasificar($param)
		{
			$slc = new Soluciones();
			
			$grupo = $param[3];
			$pagina = $param[2];
			$producto = $param[0];
			$pp_activo = $param[1];
			
			$medica = SessionGetVar("MedicamentosClasificar");
			$path = SessionGetVar("rutaImagenes");
			
			$est = "style=\"text-indent:0pt;\"";
			$action = "document.buscadorClasifica";
			$medicamentos = $slc->BuscarMedicamentosEspecial($producto,$pp_activo,$pagina);

			$html .= "<div id=\"cerrar\" style=\"width:40px;height:20px;z-index:1\"><a class=\"label_error\" href=\"javascript:OcultarSpan('solicitud');\">CERRAR</a></div>";
			$html .= $this->ObtenerPaginado($pagina,$action,$path,$slc,1,'CrearVariablesClasificar');
			$html .= "	<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">\n";
			$html .= "		<tr class=\"modulo_table_list_title\" >\n";
			$html .= "			<td $est width=\"7%\">&nbsp;</td>\n";
			$html .= "			<td $est width=\"10%\" style=\"text-indent:0pt;\" align=\"center\" >CÓDIGO</td>\n";
			$html .= "			<td $est width=\"%\" align=\"center\" >PRODUCTO</td>\n";
			$html .= "			<td $est width=\"25%\" align=\"center\" >PRINCIPIO ACTIVO</td>\n";
			$html .= "			<td $est width=\"22%\" style=\"text-indent:0pt;\" align=\"center\" >CONCENTRACIÓN</td>\n";
			$html .= "			<td $est width=\"1%\"  style=\"text-indent:0pt;\" align=\"center\" ></td>\n";
			$html .= "		</tr>\n";			
			
			$datos = array();
		
			for($i=0; $i<sizeof($medicamentos); $i++)
			{
				$est = 'modulo_list_claro'; $back = "#DDDDDD";
				if($i % 2 == 0)
				{
				  $est = 'modulo_list_oscuro'; $back = "#CCCCCC";
				}
				
				$prodc = str_replace('"','-',$medicamentos[$i]['producto']);
				$arrjs = "new Array('".$medicamentos[$i]['codigo_producto']."','$prodc')";
				
				$html .= "		<tr class=\"$est\" onmouseout=mOut(this,\"".$back."\"); onmouseover=mOvr(this,'#FFFFFF'); >\n";
				$html .= "			<td align=\"center\" class=\"normal_10A\">".$medicamentos[$i]['item']."</td>\n";
				$html .= "			<td align=\"center\" >".$medicamentos[$i]['codigo_producto']."</td>\n";
				$html .= "			<td align=\"left\" class=\"normal_10A\" >".$medicamentos[$i]['producto']."</td>\n";
				$html .= "			<td align=\"left\" class=\"normal_10A\" >".$medicamentos[$i]['principio_activo']."</td>\n";
				$html .= "			<td align=\"left\"  >".$medicamentos[$i]['forma']."</td>\n";
				$html .= "			<td align=\"center\" id=\"is".$medicamentos[$i]['codigo_producto']."\" title=\"ADICIONAR MEDICAMENTO\" >\n";
				
				if($medica[$medicamentos[$i]['codigo_producto']])
				{
					$html .= "				<a href=\"javascript:EliminarMedicamentosClasificar($arrjs);\">\n";
					$html .= "					<img src=\"".$path."/images/checksi.png\" border=\"0\" >\n";				
					$html .= "				<a>\n";
				}
				else
				{
					$html .= "				<a href=\"javascript:AdicionarMedicamentosClasificar($arrjs);\">\n";
					$html .= "					<img src=\"".$path."/images/checkno.png\" border=\"0\" >\n";				
					$html .= "				<a>\n";
				}
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
			}
			
			$html .= "		</table><br>\n";
			$html .= $this->ObtenerPaginado($pagina,$action,$path,$slc,2,'CrearVariablesClasificar');

			if(sizeof($medicamentos) == 0)
			{
				$html  = "<div id=\"cerrar\" style=\"width:40px;height:20px;z-index:1\"><a class=\"label_error\" href=\"javascript:OcultarSpan('resultado');\">CERRAR</a></div>";
				$html .= "	<center><b class=\"label_error\">LA BÚSQUEDA NO ARROJO NINGUN RESULTADO</b></center><br>\n";
				$html .= "</div>\n";
			}
			return  $html;
		}
		/*******************************************************************************
		*
		********************************************************************************/
		function SeleccionarMedicamentos($param)
		{
			$codigo = $param[0];
			$nombre = $param[1];
			$solucion =  $param[2];
			$path = SessionGetVar("rutaImagenes");
			
			$medica = array();
			$medica = SessionGetVar("MedicamentosSel");
			
			$medica[$param[0]]['codigo_producto'] = $param[0];
			$medica[$param[0]]['producto'] = $param[1];
			$medica[$param[0]]['sw_soluciones'] = $param[2];
			
			$arrjs = "new Array('".$param[0]."','".$param[1]."','".$param[2]."')";
			
			$html2 .= "				<a href=\"javascript:DeseleccionarMedicamentos($arrjs);\">\n";
			$html2 .= "					<img src=\"".$path."/images/checksi.png\" border=\"0\" >\n";				
			$html2 .= "				<a>\n";
			
			$html = $this->CrearTablaAdcionados($medica);
			
			SessionSetVar("MedicamentosSel",$medica);
			return $html."~".$html2."~".$datos['sw_soluciones']."~".sizeof($medica);
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function DeseleccionarMedicamentos($param)
		{
			$codigo = $param[0];
			$path = SessionGetVar("rutaImagenes");
			
			$medica = array();
			$medica = SessionGetVar("MedicamentosSel");
			
			$datos = $medica[$param[0]];
			
			$arrjs = "new Array('".$param[0]."','".$datos['producto']."','".$datos['sw_soluciones']."')";
			
			unset($medica[$param[0]]);
			$html = $this->CrearTablaAdcionados($medica);
			$html2 .= "				<a href=\"javascript:SeleccionarMedicamentos($arrjs);\">\n";
			$html2 .= "					<img src=\"".$path."/images/checkno.png\" border=\"0\" >\n";				
			$html2 .= "				<a>\n";
			
			SessionSetVar("MedicamentosSel",$medica);
			return $html."~".$html2."~".$datos['sw_soluciones']."~".sizeof($medica);
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function CrearTablaAdcionados($codigos,$func = 'DeseleccionarMedicamentos')
		{
			if(sizeof($codigos) == 0)
			{
				$html .= "			<font class=\"normal_10AN\">NO HAY MEDICAMENTOS SELECCIONADOS</font>\n";
				return $html;
			}
				
			$path = SessionGetVar("rutaImagenes");
			
			$html .= " <table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\" style=\"background:#FFFFFF\">\n";
			$html .= "	<tr class=\"modulo_table_list_title\">\n";
			$html .= "  	<td align=\"center\" colspan=\"2\">MEDICAMENTOS SELECCIONADOS</td>\n";
			$html .= "	</tr>\n";
			
			foreach($codigos as $key => $datos)
			{
				$clase = "normal_10A";
				$image = "checkS.gif";
				if($datos['sw_soluciones'] == '1') 
				{
					$clase = "normal_10AN";
					$image = "checksi.png";
				}
								
				$html .= "		<tr class=\"modulo_list_claro\">\n";		
				$html .= "			<td width=\"1%\">\n";
				$html .= "				<a title=\"ELIMINAR MEDICAMENTO\" href=\"javascript:$func(new Array('".$datos['codigo_producto']."'));\">\n";
				$html .= "					<img src=\"".$path."/images/$image\" border=\"0\" height='15' width='15'>\n";				
				$html .= "				<a>\n";
				$html .= "			</td>\n";
				$html .= "			<td width=\"%\" class=\"$clase\">".$datos['producto']."</td>\n";
				$html .= "		</tr>\n";
			}
			$html .= "	</table>\n";
			
			return $html;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ObtenerPaginado($pagina,$action,$path,$slc,$op,$func='CrearVariables')
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
						$TablaPaginado .= "			<a href=\"javascript:$func(".$action.",'1')\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
						$TablaPaginado .= "		</td><td class=\"label\" bgcolor=\"#D3DCE3\">\n";
						$TablaPaginado .= "			<a href=\"javascript:$func(".$action.",'".($pagina-1)."')\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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
							$TablaPaginado .="		<td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:$func(".$action.",'".$i."')\">".$i."</a></td>\n";
						}
						$columnas++;
					}
				}
				if($pagina <  $NumeroPaginas )
				{
					$TablaPaginado .= "		<td class=\"label\" bgcolor=\"#D3DCE3\">\n";
					$TablaPaginado .= "			<a href=\"javascript:$func(".$action.",'".($pagina+1)."')\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
					$TablaPaginado .= "		</td><td class=\"label\" bgcolor=\"#D3DCE3\">\n";
					$TablaPaginado .= "			<a href=\"javascript:$func(".$action.",'".$NumeroPaginas."')\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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
		/************************************************************************************
		*
		*************************************************************************************/
		function CrearSoluciones($param)
		{
			$slc = new Soluciones();
			$medica = SessionGetVar("MedicamentosSel");
			$rst = $slc->CrearSolucion($medica,$param[0],$param[1]);
			
			$html = '';
			if($rst)
			{
				$html .= "			<font class=\"normal_10AN\">NO HAY MEDICAMENTOS SELECCIONADOS</font>\n";
				SessionDelVar("MedicamentosSel");
			}
			else
				$html .= "			<font class=\"label_error\">".$slc->frmError['MensajeError']."</font>\n";
			return $html;
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function CrearGrupoClasificacion($param)
		{
			$slc = new Soluciones();
			$rst = $slc->IngresarGrupoClasificacion($param[0],$param[1]);
			if($rst)
			{
				$soluciong = $slc->GruposMedicamentosSoluciones();
				
				$html .= "	<select name=\"grupos\" class=\"select\" onChange='ImprimirL(document.clasificacionSoluciones)'>\n";
				$html .= "		<option value=\"-1\">----SELECCIONAR-----</option>";
				$nombre = strtoupper($param[0]);
				for($i=0; $i<sizeof($soluciong); $i++)
				{
					$sel = "";			
					if($nombre == $soluciong[$i]['descripcion']) $sel = "selected";
					$html .= "		<option value=\"".$soluciong[$i]['grupo_id']."*".$soluciong[$i]['sw_soluciones']."\" $sel>".$soluciong[$i]['descripcion']."</option>";
				}
				$html .= "	</select>\n";
			}
			else
			{
				$html .= "			<font class=\"label_error\">".$slc->frmError['MensajeError']."</font>\n";
			}
			return $html;
		}
		/*******************************************************************************
		*
		********************************************************************************/
		function AdicionarMedicamentosClasificar($param)
		{
			$codigo = $param[0];
			$nombre = $param[1];
			$solucion =  $param[2];
			$path = SessionGetVar("rutaImagenes");
			
			$medica = array();
			$medica = SessionGetVar("MedicamentosClasificar");
			
			$medica[$param[0]]['codigo_producto'] = $param[0];
			$medica[$param[0]]['producto'] = $param[1];
			
			$arrjs = "new Array('".$param[0]."','".$param[1]."')";
			
			$html2 .= "				<a href=\"javascript:EliminarMedicamentosClasificar($arrjs);\">\n";
			$html2 .= "					<img src=\"".$path."/images/checksi.png\" border=\"0\" >\n";				
			$html2 .= "				<a>\n";
			
			$html = $this->CrearTablaAdcionados($medica,'EliminarMedicamentosClasificar');
			
			SessionSetVar("MedicamentosClasificar",$medica);
			return $html."~".$html2."~".sizeof($medica);
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function EliminarMedicamentosClasificar($param)
		{
			$codigo = $param[0];
			$path = SessionGetVar("rutaImagenes");
			
			$medica = array();
			$medica = SessionGetVar("MedicamentosClasificar");
			
			$datos = $medica[$param[0]];
			
			$arrjs = "new Array('".$param[0]."','".$datos['producto']."')";
			
			unset($medica[$param[0]]);
			$html = $this->CrearTablaAdcionados($medica,'EliminarMedicamentosClasificar');
			$html2 .= "				<a href=\"javascript:SeleccionarMedicamentos($arrjs);\">\n";
			$html2 .= "					<img src=\"".$path."/images/checkno.png\" border=\"0\" >\n";				
			$html2 .= "				<a>\n";
			
			SessionSetVar("MedicamentosClasificar",$medica);
			return $html."~".$html2."~".sizeof($medica);
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function CrearMedicamentosAsociados($param)
		{
			$medica1 = array();
			$medica1 = SessionGetVar("MedicamentosClasificar");
			$anteriores = SessionGetVar("MedicamentosAnteriores");
			foreach($anteriores as $key => $datos)
			{
				unset($medica1[$key]);
			}
			
			$slc = new Soluciones();
			$grupo = $param[0];
			
			$medica = array();
			$medica = $slc->BuscarMedicamentosGrupo($grupo);
			
			foreach($medica1 as $key2 => $datos)
			{
				$medica[$key2]['producto'] = $datos['producto'];
				$medica[$key2]['codigo_producto'] = $datos['codigo_producto'];
			}
			
			$html = $this->CrearTablaAdcionados($medica,'EliminarMedicamentosClasificar');
			
			SessionDelVar("MedicamentosAnteriores");			
			SessionSetVar("MedicamentosClasificar",$medica);
			SessionSetVar("MedicamentosAnteriores",$medica);
			return $html."~".sizeof($medica);
		}
		/************************************************************************************
		*
		*************************************************************************************/
		function CrearAsociacionMedicamentos($param)
		{
			$rst = true;
			$slc = new Soluciones();
			$html = "";

			$nuevos = SessionGetVar("MedicamentosClasificar");
			$anteri = SessionGetVar("MedicamentosAnteriores");
			
			foreach($anteri as $key => $datos )
			{
				if($datos['codigo_producto'] == $nuevos[$key]['codigo_producto'])
				{
					unset($anteri[$key]);
					unset($nuevos[$key]);
				}
			}
			if(sizeof($nuevos) > 0)
				$rst = $slc->IngresarAsociacionGrupo($nuevos,$param[0]);
				
			if(sizeof($anteri) > 0 && $rst)
				$rst = $slc->EliminarAsociacionGrupo($anteri,$param[0]);
			
			if(!$rst)
			{
				$html .= "			<font class=\"label_error\">".$slc->frmError['MensajeError']."</font>\n";
			}
			else
			{
				$html .= "			<font class=\"normal_10AN\">NO HAY MEDICAMENTOS SELECCIONADOS</font>\n";
				SessionDelVar("MedicamentosClasificar");
			}
			return $html;
		}
	}
	$oRS = new procesos_admin( array( 'ActivarMenu'));
	$oRS->action();	
?>