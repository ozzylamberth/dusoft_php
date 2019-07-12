<?php
	/**************************************************************************************
	* $Id: buscadorHtml.php,v 1.7 2006/08/29 16:49:42 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* @author Hugo F. Manrique
	**************************************************************************************/	
	$VISTA = "HTML";
	$_ROOT = "../../../";
	include  "../../../classes/rs_server/rs_server.class.php";
	include	 "../../../includes/enviroment.inc.php";
	class procesos_admin extends rs_server
	{
		function CrearResultado($arreglo)  
		{
			$producto = str_replace("\'","",$arreglo[0]);
			$pp_activo = str_replace("\'","",$arreglo[1]);
			$bodega = str_replace("\'","",$arreglo[2]);
			$pagina = str_replace("\'","",$arreglo[3]);
			$path = str_replace("\'","",$arreglo[4]);
			
			$est = "style=\"text-indent:0pt;\"";
			$action = "'$producto','$pp_activo','$bodega'";
			$medicamentos = $this->BuscarMedicamentos($producto,$pp_activo,$bodega,$pagina);
			
			$html .= $this->ObtenerPaginado($pagina,$action,$path);
			$html .= "	<table align=\"center\" border=\"0\" width=\"98%\" class=\"modulo_table_list\">\n";
			$html .= "		<tr class=\"modulo_table_title\" >\n";
			$html .= "			<td $est width=\"%\">&nbsp;</td>\n";
			$html .= "			<td $est width=\"10%\" style=\"text-indent:0pt;\" align=\"center\" >CÓDIGO</td>\n";
			$html .= "			<td $est width=\"28%\" align=\"center\" >PRODUCTO</td>\n";
			$html .= "			<td $est width=\"25%\" align=\"center\" >PRINCIPIO ACTIVO</td>\n";
			$html .= "			<td $est width=\"22%\" style=\"text-indent:0pt;\" align=\"center\" >CONCENTRACIÓN</td>\n";
			$html .= "			<td $est width=\"8%\" align=\"center\" >EXIS</td>\n";
			$html .= "			<td $est width=\"%\"  style=\"text-indent:0pt;\" align=\"center\" ></td>\n";
			$html .= "		</tr>\n";
			
			$datos = array();
			if(SessionIsSetVar("MedicamentosRecetaSeleccionados"))
				$datos = SessionGetVar("MedicamentosRecetaSeleccionados");
				
			for($i=0; $i<sizeof($medicamentos); $i++)
			{
				$est = 'modulo_list_claro'; $back = "#DDDDDD";
				if($i % 2 == 0)
				{
				  $est = 'modulo_list_oscuro'; $back = "#CCCCCC";
				}
				
				$datos[$medicamentos[$i]['codigo_producto']] = $medicamentos[$i];
				
				$html .= "		<tr class=\"$est\" onmouseout=mOut(this,\"".$back."\"); onmouseover=mOvr(this,'#FFFFFF'); >\n";
				$html .= "			<td align=\"center\">".$medicamentos[$i]['item']."</td>\n";
				$html .= "			<td align=\"center\">".$medicamentos[$i]['codigo_producto']."</td>\n";
				$html .= "			<td align=\"left\"  >".$medicamentos[$i]['producto']."</td>\n";
				$html .= "			<td align=\"left\"  >".$medicamentos[$i]['principio_activo']."</td>\n";
				$html .= "			<td align=\"left\"  >".$medicamentos[$i]['forma']."</td>\n";
				$html .= "			<td align=\"left\"  >".$medicamentos[$i]['existencia']."</td>\n";
				$html .= "			<td align=\"center\" title=\"BORRAR MEDICAMENTO\" >\n";
				$html .= "				<a href=\"javascript:crearReceta('".$medicamentos[$i]['codigo_producto']."','0','$path');OcultarSpan('Busqueda')\">\n";
				$html .= "					<img src=\"".$path."/images/checkno.png\" border=\"0\" width=\"15\" height=\"15\">\n";				
				$html .= "				<a>\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
			}

			SessionSetVar("MedicamentosRecetaSeleccionados",$datos);
			
			$html .= "		</table><br>\n";
			$html .= $this->ObtenerPaginado($pagina,$action,$path);

			if(sizeof($medicamentos) == 0) $html = "<center><b class=\"label\">NO SE ENCONTRARON DATOS</b></center>\n";
      return  $html;
		}
		/********************************************************************************
		*
		*********************************************************************************/		
		function CrearReceta($arreglo)  
		{
			$codigo = str_replace("\'","",$arreglo[0]);
			$opcion = str_replace("\'","",$arreglo[1]);
			$path = str_replace("\'","",$arreglo[2]);
			
			$codigos = SessionGetVar("CodigosRecetaSeleccionados"); 
			
			if($opcion == '1')
				unset($codigos[$codigo]);
			else
				$codigos[$codigo] = $codigo;
			
			SessionSetVar("CodigosRecetaSeleccionados",$codigos);
			SessionSetVar("RutaImagenes2",$path);

			$html = $this->CrearVisualizacion();
			if(sizeof($codigos) == 0) $html = "";
			$cadena = implode('~',$codigos)."*".$html; 
      return  $cadena;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function CrearRecetaPrevia($param)
		{
			$path = str_replace("\'","",$param[0]);
			SessionSetVar("RutaImagenes2",$path);
			$codigos = SessionGetVar("CodigosRecetaSeleccionados");
			
			print_r($codigos);
			
			$html = $this->CrearVisualizacion();
			$cadena = implode('~',$codigos)."*".$html;
			return  $cadena;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function CrearVisualizacion()
		{
			$path = SessionGetVar("RutaImagenes2");
			$datos = SessionGetVar("MedicamentosRecetaSeleccionados");
			$codigos = SessionGetVar("CodigosRecetaSeleccionados");
			print_r($datos);
			$est = "style=\"text-indent:0pt;\" ";
				
			$html .= "	<table align=\"center\" border=\"0\" width=\"98%\" class=\"modulo_table_list\">\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "  		<td align=\"center\" colspan=\"8\" height=\"17\">ADICIÓN DE MEDICAMENTOS</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr class=\"modulo_table_list_title\" >\n";
			$html .= "			<td $est width=\"3%\"></td>\n";
			$html .= "			<td $est width=\"32%\" align=\"center\">PRODUCTO</td>\n";
			$html .= "			<td $est width=\"30%\" align=\"center\">PRINCIPIO ACTIVO</td>\n";
			$html .= "			<td $est width=\"18%\" align=\"center\" colspan=\"2\">DOSIS</td>\n";
			$html .= "			<td $est width=\"16%\" align=\"center\" colspan=\"2\">CANTIDAD</td>\n";
			$html .= "			<td $est width=\"1%\" align=\"center\" ></td>\n";
			$html .= "		</tr>\n";
			
			foreach($codigos as $key )
			{
				$clase = "class=\"formulacion_table_list\" ";
				$funcion = "crearRecetaLiq";
				if($datos[$key]['sw_soluciones'] == '0')
				{
					$clase = "class=\"modulo_list_claro\" style=\"font-weight: bold\" ";
					$funcion = "crearReceta";
				}
				
				$html .= "		<tr $clase valign=\"top\">\n";
				$html .= "			<td align=\"center\">".$datos[$key]['item']."</td>\n";
				$html .= "			<td align=\"left\"  >".$datos[$key]['producto']."</td>\n";
				$html .= "			<td align=\"left\"  >".$datos[$key]['principio_activo']."</td>\n";
				
				$html .= "			<td align=\"right\" width=\"5%\"><input type=\"text\" class='input-text' name=\"dosisnumerica\" id=\"dosisnumerica\" onkeypress=\"return acceptNum(event);\" size=\"4\"></td>\n";
				$html .= "			<td align=\"left\" >".$this->ObtenerCombo($key,$datos[$key]['unidad_dosificacion'],$datos[$key]['codigo_producto'],$est)."</td>\n";
				
				$html .= "			<td align=\"right\" width=\"5%\"><input type=\"text\" class='input-text' name=\"cantidad\" id=\"cantidad\" onkeypress=\"return acceptNum(event);\" size=\"4\"></td>\n";
				$html .= "			<td align=\"left\" >".$datos[$key]['umm']."</td>\n";
				
				$html .= "			<td align=\"center\" title=\"ELIMINAR MEDICAMENTO\" class=\"modulo_list_claro\" >\n";
				$html .= "				<input type=\"hidden\" name=\"sw_solucion\" id=\"sw_solucion\" value=\"".$datos[$key]['sw_soluciones']."\">";
				$html .= "				<input type=\"hidden\" name=\"producto\" id=\"producto\" value=\"".$datos[$key]['producto']."\">";
				$html .= "				<input type=\"hidden\" name=\"codproducto\" id=\"codproducto\" value=\"".$datos[$key]['codigo_producto']."\">";

				$html .= "				<a href=\"javascript:$funcion('".$datos[$key]['codigo_producto']."','1','$path')\">\n";
				$html .= "					<img src=\"".$path."/images/delete.gif\" border=\"0\">\n";				
				$html .= "				<a>\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
			}
			$html .= "		</table>\n";
			
			return $html;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function BuscarMedicamentos($producto,$principio_activo,$bodega,$pagina)
		{	
			$where = "";
			
			$sql .= "SELECT CASE WHEN ME.sw_pos = 1 THEN 'POS'";
			$sql .= "						 ELSE 'NO POS' END AS item,";
			$sql .= "				IM.codigo_producto, ";
			$sql .= "				IM.descripcion as producto, ";
			$sql .= "				ME.concentracion_forma_farmacologica,	";
			$sql .= "				ME.unidad_medida_medicamento_id,";
			$sql .= "				ME.factor_conversion, ";
			$sql .= "				ME.factor_equivalente_mg,";
			$sql .= "				IA.descripcion AS principio_activo,";
			$sql .= "				IF.descripcion AS forma,";
			$sql .= "				IF.unidad_dosificacion,";
			$sql .= "				IU.descripcion AS umm, ";
			$sql .= "				IF.cod_forma_farmacologica, ";
			$sql .= "				COALESCE(SE.sw_soluciones,'0') AS sw_soluciones ";
			
			if(!$bodega)
			{
				$where .= "FROM 	inventarios_productos IM, ";
			}
			else
			{
				$sql .= "					,BC.existencia ";
				$where .= "FROM 	inventarios_productos IM LEFT JOIN ";
				$where .= "				hc_bodegas_consultas BC ";
				$where .= "				ON(BC.bodega_unico='".$bodega."') ";
				$where .= "				LEFT JOIN existencias_bodegas EB ";
				$where .= "				ON(	EB.empresa_id = BC.empresa_id AND ";
				$where .= "					EB.centro_utilidad = BC.centro_utilidad AND ";
				$where .= "					EB.bodega = BC.bodega AND ";
				$where .= "					IM.codigo_producto = BC.codigo_producto ";
				$where .= "				),";
			}
			
			$where .= "				medicamentos ME LEFT JOIN inv_unidades_medida_medicamentos IU ";
			$where .= "				ON(ME.unidad_medida_medicamento_id = IU.unidad_medida_medicamento_id) ";
			$where .= "				LEFT JOIN ";
			$where .= "				(	SELECT	HM.sw_soluciones, ";
			$where .= "									HD.codigo_medicamento ";
			$where .= "					FROM		hc_formulacion_hospitalaria_grupos_medicamentos_mezclas HM,";
			$where .= "									hc_formulacion_hospitalaria_grupos_medicamentos_mezclas_d HD ";
			$where .= "					WHERE		HM.grupo_id = HD.grupo_id) AS SE ";
			$where .= "				ON(ME.codigo_medicamento = SE.codigo_medicamento), ";
			$where .= "				inventarios IT,  ";
			$where .= "				inv_med_cod_principios_activos IA,  ";
			$where .= "				inv_med_cod_forma_farmacologica IF  ";
			$where .= "WHERE	IM.codigo_producto = ME.codigo_medicamento ";
			$where .= "AND 		ME.cod_principio_activo = IA.cod_principio_activo ";
			$where .= "AND 		ME.cod_forma_farmacologica = IF.cod_forma_farmacologica ";
			$where .= "AND 		IM.estado = '1' ";
			$where .= "AND 		IT.estado = '1' ";
			$where .= "AND 		IT.empresa_id = '".SessionGetVar("EmpresaHc")."' ";
			$where .= "AND 		IT.codigo_producto = IM.codigo_producto ";
			
			if ($producto != '') $where .= "AND		IM.descripcion ILIKE '%".$producto."%'";
			if ($principio_activo != '') $where .= "AND 		IA.descripcion ILIKE '%".$principio_activo."%'";
			
			$this->ProcesarSqlConteo("SELECT COUNT(*) $where",null,$pagina);
			
			$sql .= $where;
			$sql .= "ORDER BY IM.codigo_producto ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset;
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$retorno = array();
			while(!$rst->EOF)
			{
				$retorno[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $retorno;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ObtenerPaginado($pagina,$action,$path)
		{
			$TotalRegistros = $this->conteo;
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

				$TablaPaginado .="<table align=\"center\" cellspacing=\"3\" ><tr>\n";
				if($NumeroPaginas > 1)
				{
					$TablaPaginado .= "		<td class=\"label\" bgcolor=\"#D3DCE3\">Páginas:</td>\n";
					if($pagina > 1)
					{
						$TablaPaginado .= "		<td class=\"label\" bgcolor=\"#D3DCE3\">\n";
						$TablaPaginado .= "			<a href=\"javascript:crearBusqueda(".$action.",1,'$path')\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
						$TablaPaginado .= "		</td><td class=\"label\" bgcolor=\"#D3DCE3\">\n";
						$TablaPaginado .= "			<a href=\"javascript:crearBusqueda(".$action.",".($pagina-1).",'$path')\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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
							$TablaPaginado .="		<td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:crearBusqueda(".$action.",".$i.",'$path')\">".$i."</a></td>\n";
						}
						$columnas++;
					}
				}
				if($pagina <  $NumeroPaginas )
				{
					$TablaPaginado .= "		<td class=\"label\" bgcolor=\"#D3DCE3\">\n";
					$TablaPaginado .= "			<a href=\"javascript:crearBusqueda(".$action.",".($pagina+1).",'$path')\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
					$TablaPaginado .= "		</td><td class=\"label\" bgcolor=\"#D3DCE3\">\n";
					$TablaPaginado .= "			<a href=\"javascript:crearBusqueda(".$action.",".$NumeroPaginas.",'$path')\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
					$TablaPaginado .= "		</td>\n";
					$columnas +=2;
				}
				$TablaPaginado .= "		<tr><td class=\"label\"  colspan=".$columnas." align=\"center\">\n";
				$TablaPaginado .= "			Página&nbsp;".$pagina." de ".$NumeroPaginas."</td>\n";
				$TablaPaginado .= "		</tr></table>\n";
			}
			return $TablaPaginado;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function IngresarMezcla($arreglo)
		{
			$datos = explode("*",$arreglo[2]);
			
			$medicamentoid = "";
			list($dbconn) = GetDBconn();

      $dbconn->BeginTrans();
			
			$sql  = "SELECT NEXTVAL('hc_formulacion_hospitalaria_mezclas_mezcla_id_seq') ";
			
			$rst = $dbconn->Execute($sql);
      if ($dbconn->ErrorNo() != 0) return '';
			
			if(!$rst->EOF)	$medicamentoid = $rst->fields[0];
      
			$sql  = "INSERT INTO hc_formulacion_hospitalaria_mezclas ";
			$sql .= "				(mezcla_id,";
			$sql .= "				 descripcion ) ";
			$sql .= "VALUES (";
			$sql .= "				 ".$medicamentoid.",";
			$sql .= "				'".$arreglo[1]."' ";
			$sql .= "				);";
			
			$sql .= "INSERT INTO hc_formulacion_hospitalaria_mezclas_grupos_d( ";
			$sql .= "					grupo_mezcla_id,";
			$sql .= "					mezcla_id) ";
			$sql .= "VALUES	(";
			$sql .= "				 ".$arreglo[0].", ";
			$sql .= "				 ".$medicamentoid." ";
			$sql .= "				);";
			
			$rst = $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$dbconn->RollbackTrans();
				return '';
			}
			$sql = "";
			for($i=0; $i<sizeof($datos)-1; $i++)
			{
				$detalle = explode("#",$datos[$i]);
				$sql .= "INSERT INTO hc_formulacion_hospitalaria_mezclas_d( ";
				$sql .= "				mezcla_id,";
				$sql .= "				codigo_medicamento,";
				$sql .= "				cantidad,";
				$sql .= " 			sw_solucion ) ";
				$sql .= "VALUES (";
				$sql .= "				 ".$medicamentoid.", ";
				$sql .= "				'".$detalle[0]."', ";
				$sql .= "				 ".$detalle[1].", ";
				$sql .= "				'".$detalle[2]."' ";
				$sql .= "				);";
			}
			
			$rst = $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0)
			{
				$dbconn->RollbackTrans();
				return '';
			}
			
			$dbconn->CommitTrans();

			$html = "";
			$mezclas = $this->Mezclas();
				
			$path = str_replace("../","",$arreglo[3]);
			foreach($mezclas as $key => $subnivel)
			{
				$html .= "					<p class=\"GrupoMedicamentos\" onclick=\"OcultarCapasNuevas('$key');\" >\n";
				$html .= "    				<a href=\"#\" title=\"$title\">\n";
				$html .= "							<img name =\"ImgHistoriaActual\" src=\"".$path."/images/infor.png\" height=\"10\" border=\"0\">$key</a>\n";
				$html .= "						<div name=\"$key\" id=\"$key\" style=\"display:none;width:280px;\">\n";
				$html .= "							<ul class=\"Lista1\">\n";

				foreach($subnivel as $key2 => $subnivel1)
				{							
					$html .= "							<li class=\"Medicamentos\" onMouseOut=\"xHide('$key2');PosicionarCapa('$key2',-50,+13)\" onMouseOver=\"PosicionarCapa('$key2',50,-13);xShow('$key2');\">\n";
					$html .= "								<a class=\"SubMenuM\" href='#'>$key2</a>\n";
					$html .= "									<div class=\"GrupoMezclas\" name=\"$key2\" id=\"$key2\">\n";
					$html .= "										COMPONENTES:\n";
					$html .= "										<ul class=\"Lista1\">\n";
					foreach($subnivel1 as $key3 => $subnivel2)
					{
						$html .= "											<li class=\"Mezclas\">".ucwords($subnivel2['producto'])."</li>\n";
					}				
					$html .= "										</ul>\n";
					$html .= "									</div>\n";
					$html .= "							</li>\n";
				}
				$html .= "							</ul>\n";
				$html .= "						</div>\n";
				$html .= "    			</p>\n";
			}
		
			$html .= "					<p class=\"GrupoMedicamentos\">\n";
			$html .= "    				<a href=\"javascript:CrearMezcla();OcultarSpan('Mezclas')\">\n";
			$html .= "							<img name =\"ImgHistoriaActual\" src=\"".$path."/images/pmodificar.png\" border=\"0\">\n";
			$html .= "								NUEVA RECETA</a>\n";
			$html .= "    			</p>\n";
			
			return $html;
		}
		/********************************************************************
		*
		*********************************************************************/  
		function Mezclas()
		{
			$sql .= "SELECT	CASE WHEN ME.sw_pos = 1 THEN 'POS' ";
			$sql .= "				ELSE 'NO POS' END AS item, ";
			$sql .= "				HD.codigo_medicamento AS codigo_producto,";
			$sql .= "				ID.descripcion AS producto, ";
			$sql .= "				IA.descripcion AS principio_activo, ";
			$sql .= "				ME.concentracion_forma_farmacologica AS cff, ";
			$sql .= "				ME.unidad_medida_medicamento_id AS ummi,";
			$sql .= "				IM.descripcion AS umm, ";
			$sql .= "				IF.descripcion AS forma, ";
			$sql .= " 			IF.unidad_dosificacion, ";
			$sql .= "				IF.cod_forma_farmacologica,";
			$sql .= "				HM.descripcion AS mezcla, ";
			$sql .= "				HM.mezcla_id, ";
			$sql .= "				HH.descripcion AS categoria ";
			$sql .= "FROM		hc_formulacion_hospitalaria_mezclas HM,";
			$sql .= "				hc_formulacion_hospitalaria_mezclas_d HD,";
			$sql .= "				hc_formulacion_hospitalaria_mezclas_grupos HH,";
			$sql .= "				hc_formulacion_hospitalaria_mezclas_grupos_d HG,";
			$sql .= "				inv_med_cod_principios_activos AS IA, ";
			$sql .= "				inventarios_productos AS ID, ";
			$sql .= "				inv_med_cod_forma_farmacologica AS IF, ";
			$sql .= "				medicamentos ME LEFT JOIN inv_unidades_medida_medicamentos IM ";
			$sql .= "				ON(ME.unidad_medida_medicamento_id = IM.unidad_medida_medicamento_id) ";
			$sql .= "WHERE 	HD.codigo_medicamento = ID.codigo_producto ";
			$sql .= "AND 		ID.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND 		ME.cod_principio_activo = IA.cod_principio_activo ";
			$sql .= "AND 		ID.estado = '1' ";
			$sql .= "AND		HM.mezcla_id = HD.mezcla_id ";
			$sql .= "AND		HD.codigo_medicamento = ME.codigo_medicamento ";
			$sql .= "AND		HG.grupo_mezcla_id = HH.grupo_mezcla_id ";
			$sql .= "AND		HM.mezcla_id = HG.mezcla_id ";
			$sql .= "AND 		IF.cod_forma_farmacologica = ME.cod_forma_farmacologica ";
			$sql .= "ORDER BY categoria,mezcla ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$i=0;
			$medicamentos = array();
			while (!$rst->EOF)
			{
				$medicamentos[$rst->fields[11]][$rst->fields[9]][$i] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
				$i++;
			}

			$rst->Close();
			return $medicamentos;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function RecetarSolucion($param)
		{
			$html = "";
			$sql .= "SELECT NEXTVAL('seq_hc_formulacion_num_mezcla') ";
			
			list($dbconn)=GetDBConn();
			$dbconn->debug=true;
			$dbconn->BeginTrans();
			$rst = $dbconn->Execute($sql);
			
			if($dbconn->ErrorNo() != 0)
			{
				$dbconn->RollbackTrans();
				$html = "ERROR DB : " . $dbconn->ErrorMsg();
				return $html;
			}
			
			$seq = $rst->fields[0];
			
			$sql  = "INSERT INTO hc_formulacion_mezclas_eventos (";
			$sql .= "			num_mezcla,";
    	$sql .= "			ingreso ,";
    	$sql .= "			evolucion_id ,";
    	$sql .= "			usuario_id ,";
    	$sql .= "			fecha_registro,";
    	$sql .= "			sw_estado ,";
    	$sql .= "			observacion,";
    	$sql .= "			volumen_infusion,";
    	$sql .= "			unidad_volumen,";
    	$sql .= "			cantidad ";
			$sql .= ")";
			$sql .= "VALUES(";
			$sql .= "			 ".$seq.",";
    	$sql .= "			 ".SessionGetVar("IngresoHc").",";
    	$sql .= "			 ".SessionGetVar("EvolucionHc").",";
    	$sql .= "			 ".UserGetUID().",";
    	$sql .= "			NOW(),";
    	$sql .= "			'".$param[4]."',";
    	$sql .= "			'".$param[3]."',";
    	$sql .= "			 ".$param[1].",";
    	$sql .= "			'".$param[2]."',";
    	$sql .= "			 ".$param[0]." ";
			$sql .= ")";
			
			$rst = $dbconn->Execute($sql);

			if($dbconn->ErrorNo() != 0)
			{
				$dbconn->RollbackTrans();
				$html = "ERROR DB : " . $dbconn->ErrorMsg();
				return $html;
			}
			$html .= "$sql <br>";
			$sql = "";
			$productos = explode("*",$param[6]);

			for($i=0; $i<sizeof($productos)-1; $i++)
			{
				$datos = explode ("#",$productos[$i]);

				$sql .= "INSERT INTO hc_formulacion_mezclas_detalle( ";
				$sql .= "			num_mezcla,";
    		$sql .= "			codigo_producto,";
    		$sql .= "			sw_solucion,";
    		$sql .= "			cantidad, ";
    		$sql .= "			dosis, ";
    		$sql .= "			unidad_dosificacion ";
				$sql .= ")";
				$sql .= "VALUES( ";
				$sql .= "			 ".$seq.",";
    		$sql .= "			'".$datos[0]."',";
    		$sql .= "			'".$datos[1]."',";
    		$sql .= "			 ".$datos[4]." ,";
    		$sql .= "			 ".$datos[2]." ,";
    		$sql .= "			'".$datos[3]."' ";
				$sql .= ");";
			}

 			$rst = $dbconn->Execute($sql);
			
			if($dbconn->ErrorNo() != 0)
			{
				$dbconn->RollbackTrans();
				$html = "ERROR DB : " . $dbconn->ErrorMsg();
				return $html;
			} 
			$dbconn->CommitTrans();
			
			$this->ConsultarFormulacionSolucion($seq);
			$html = $this->CrearMedicamentos();
			return $html;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function CrearMedicamentos()
		{
			$html = "";
			$path = SessionGetVar("RutaImagenes");
			
			$est0 = "style=\"text-indent:2pt;text-align:left;font-size:10pt;\" ";
			$est1 = "style=\"text-indent:2pt;text-align:left;font-size:7pt;\" ";
			
			$clasesjs .= "new Array('formulacion_table_list_suspendido','formulacion_table_list',";
			$clasesjs .= "'formulacion_table_list_oscuro','formulacion_table_list_claro','label','label2')";
				
			$documentos = SessionGetVar("MedicamentosFormulados");
			
			$cl1 = array(	"formulacion_table_list","modulo_list_claro","formulacion_table_list_oscuro",
										"formulacion_table_list_suspendido","formulacion_table_list_claro",
										"hc_table_submodulo_list_title","modulo_table_list_title","label","label2");
			$cl2 = array(	"formulacion_table_list_suspendido","modulo_list_claro","formulacion_table_list_claro",
										"formulacion_table_list","formulacion_table_list_oscuro","hc_table_submodulo_list_title",
										"formulacion_table_list_suspendido","label2","label");
			$img1 = array ("historia_actual_osc.gif","pactivo.png");
			$img2 = array ("historia_actual_cla.gif","pinactivo.png");
			
			$clases = array("1"=>$cl1,"2"=>$cl2);
			$imagenes = array("1"=>$img1,"2"=>$img2);
			
			$html .= "	<table align=\"center\" border=\"0\" width=\"98%\" class=\"modulo_table_list\">\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "  		<td align=\"center\">PLAN DE MEDICAMENTOS</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr>\n";
			$html .= "			<td><br>\n";
			$est0 = "style=\"text-indent:2pt;text-align:left;font-size:11px;\" ";
			$est1 = "style=\"text-indent:2pt;text-align:left;font-size:9px;\" ";
			$estilos = "style=\"border-bottom-width:0px;border-left-width:1px;border-right-width:0px;border-top-width:0px;border-style: solid;\""; 
			
			foreach($documentos as $key=>$datos)
			{
				if($datos['activar'] == "1")
				{
					$clasesjs  = "new Array('".$clases[$datos['sw_estado']][3]."','".$clases[$datos['sw_estado']][0]."'";
					$clasesjs .= ",'".$clases[$datos['sw_estado']][2]."','".$clases[$datos['sw_estado']][4]."',";
					$clasesjs .= "'".$clases[$datos['sw_estado']][7]."','".$clases[$datos['sw_estado']][8]."')";
					
					if($datos['sw_estado'] == '2')
					{
						$clasesjs  = "new Array('".$clases[$datos['sw_estado']][0]."','".$clases[$datos['sw_estado']][3]."',";
						$clasesjs .= "'".$clases[$datos['sw_estado']][4]."','".$clases[$datos['sw_estado']][2]."',";
						$clasesjs .= "'".$clases[$datos['sw_estado']][8]."','".$clases[$datos['sw_estado']][7]."')";
					}
					
					$html .= "<div id=\"CapaFormula".$key."\">\n";
					$html .= "	<table id=\"Bordex".$key."\" align=\"center\" border=\"0\" width=\"100%\" class=\"".$clases[$datos['sw_estado']][2]."\">\n";
					$html .= "		<tr id=\"Formulacion0x".$key."\" class=\"".$clases[$datos['sw_estado']][0]."\">\n";
					$html .= "  		<td width=\"84%\">\n";
					$html .= "				<table id=\"Formulacion1x".$key."\" class=\"".$clases[$datos['sw_estado']][0]."\" >\n";
					$html .= "					<tr >\n";
					$html .= "						<td $est0 >".$datos['producto']."</td>\n";
					$html .= "						<td valign=\"bottom\" id=\"Formulacion2x".$key."\" $est1> (".$datos['principio_activo'].")</td>\n";
					$html .= "					</tr>\n";
					$html .= "				</table>\n";
					$html .= "			</td>\n";
					$html .= "			<td width=\"4%\" align=\"center\" >\n";
					$html .= "				<a href=\"javascript:EditarFormulacion('".$datos['codigo_producto']."','".$key."',".$datos['sw_estado'].")\"  title=\"EDITAR\">\n";
					$html .= "					<img name =\"Editar\" height=\"18\" src=\"".$path."/images/edita.png\" border=\"0\" >\n";
					$html .= "				</a>\n";
					$html .= "			</td>\n";
					$html .= "			<td width=\"4%\" align=\"center\">\n";
					$html .= "				<a href=\"javascript:VisualizarHistorial('".$datos['codigo_producto']."')\"  title=\"HISTORIAL\">\n";
					$html .= "					<img name =\"Historial".$key."\" height=\"18\"  src=\"".$path."/images/HistoriaClinica1/".$imagenes[$datos['sw_estado']][0]."\" border=\"0\">\n";
					$html .= "				</a>\n";
					$html .= "			</td>\n";
					$html .= "			<td width=\"4%\" align=\"center\">\n";
					$html .= "				<a href=\"javascript:DatosActuales('".$key."',$clasesjs,'".$datos['codigo_producto']."',".$datos['sw_estado'].");Iniciar('".$datos['producto']."');\" >\n";
					$html .= "					<img width=\"16\" height=\"18\" title=\"SUSPENDER MEDICAMENTO\" src=\"".$path."/images/".$imagenes[$datos['sw_estado']][1]."\" border=\"0\" name=\"Suspender".$key."\" >\n";
					$html .= "				</a>\n";
					$html .= "			</td>\n";
					$html .= "			<td width=\"4%\" align=\"center\" >\n";
					$html .= "				<a href=\"javascript:Finalizar('".$datos['codigo_producto']."','".$key."',".$datos['sw_estado'].",'".$datos['producto']."')\"  title=\"FINALIZAR MEDICAMENTO\">\n";
					$html .= "					<img name =\"Finalizar\" height=\"18\" src=\"".$path."/images/HistoriaClinica1/cerrar_claro.gif\" border=\"0\" >\n";
					$html .= "				</a>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
					$html .= "		<tr class=\"".$clases[$datos['sw_estado']][1]."\">\n";
					$html .= "			<td colspan=\"5\">\n";
					$html .= "				<table width=\"100%\">\n";
					$html .= "					<tr>\n";
					$html .= "						<td width=\"60%\" valign=\"top\">\n";
					$html .= "							<table id=\"Formulacion3x1".$key."\" class=\"".$clases[$datos['sw_estado']][7]."\" >\n";
					$html .= "								<tr>\n";
					$html .= "									<td >VIA DE ADMINISTRACIÓN: </td>\n";
					$html .= "									<td colspan=\"3\">".$datos['nombre']."</td>\n";
					$html .= "								</tr>\n";
					$html .= "								<tr >\n";
					$html .= "									<td >DOSIS</td>\n";
					$html .= "									<td align=\"right\">".intval($datos['dosis'])."</td><td>".$datos['unidad_dosificacion']."</td>\n";
					$html .= "									<td align=\"left\">".$datos['frecuencia']."</td>\n";
					$html .= "								</tr>\n";				
					$html .= "								<tr >\n";
					$html .= "									<td >CANTIDAD</td>\n";
					$html .= "									<td align=\"right\">".intval($datos['cantidad'])."</td><td colspan=\"2\">".$datos['umm']."</td>\n";
					$html .= "								</tr>\n";
					$html .= "							</table>\n";
					$html .= "						</td>\n";
					$html .= "						<td width=\"40%\" valign=\"top\" $estilos>\n";
					$html .= "							<table align=\"center\" id=\"Formulacion3x2".$key."\" class=\"".$clases[$datos['sw_estado']][7]."\">\n";
					$html .= "								<tr>\n";
					$html .= "									<td align=\"center\">FORMULÓ:</td>\n";
					$html .= "								</tr>\n";
					$html .= "								<tr>\n";
					$html .= "									<td style=\" font-weight:normal\">".$datos['med_formula']."</td>\n";
					$html .= "								</tr>\n";
					
					$usuariohc = UserGetUID();
					if($datos['sw_confirmacion_formulacion'] == '0' && $datos['usuario_id'] == $usuariohc)
					{
						$arr = "'".$datos['codigo_producto']."','".$datos['num_reg_formulacion']."'";
						$html .= "								<tr>\n";
						$html .= "									<td id=\"confirmacion".$datos['codigo_producto']."\" align=\"center\"><a href=\"javascript:IniciarConfirmacion('".$datos['producto']."',$arr);MostrarCapas('Confirmacion')\" class=\"normal_10AN\">CONFIRMAR</a></td>\n";
						$html .= "								</tr>\n";
					}
					
					if($datos['sw_requiere_autorizacion_no_pos'] == 'S' && !$datos['justificacion_no_pos_id'])
					{
						$html .= "								<tr>\n";
						$html .= "									<td id=\"justificacion".$datos['codigo_producto']."\" align=\"center\"><a href=\"javascript:Justificar('".$datos['codigo_producto']."','".$datos['justificacion_no_pos_id']."')\" class=\"normal_10AN\">JUSTIFICAR</a></td>\n";
						$html .= "								</tr>\n";					
					}
					
					if($datos['sw_requiere_autorizacion_no_pos'] == 'S' && $datos['justificacion_no_pos_id'])
					{
						$html .= "								<tr>\n";
						$html .= "									<td id=\"justificacion".$datos['codigo_producto']."\" align=\"center\"><a href=\"javascript:Justificar('".$datos['codigo_producto']."','".$datos['justificacion_no_pos_id']."')\" class=\"normal_10AN\">VER JUSTIFICACIÓN</a></td>\n";
						$html .= "								</tr>\n";					
					}
					
					if($datos['sw_requiere_autorizacion_no_pos'] == 'P' )
					{
						$html .= "								<tr>\n";
						$html .= "									<td id=\"justificacion".$datos['codigo_producto']."\" align=\"center\"><b class=\"normal_10AN\">MEDICAMENTO NO POS A PETICION DEL PACIENTE</B></td>\n";
						$html .= "								</tr>\n";					
					}

					if($datos['sw_requiere_autorizacion_no_pos'] == 'N' )
					{
						$html .= "								<tr>\n";
						$html .= "									<td id=\"justificacion".$datos['codigo_producto']."\" align=\"center\"><b class=\"normal_10AN\">MEDICAMENTO POS</b></td>\n";
						$html .= "								</tr>\n";					
					}	
					
					$html .= "							</table>\n";
					$html .= "						</td>\n";
					$html .= "					</tr>\n";	
					$html .= "				</table>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";

					if($datos['observacion'] != "")
					{
						$html .= "		<tr class=\"".$clases[$datos['sw_estado']][1]."\">\n";
						$html .= "			<td colspan=\"5\">\n";
						$html .= "				<table width=\"100%\" id=\"Formulacion5x".$key."\" class=\"".$clases[$datos['sw_estado']][7]."\">\n";
						$html .= "					<tr>\n";
						$html .= "						<td valign=\"top\" width=\"30%\">\n";
						$html .= "							OBSERVACIONES E INDICACIONES DE SUMINISTRO</td>\n";
						$html .= "						</td>\n";
						$html .= "						<td valign=\"top\" width=\"70%\" align=\"justify\" colspan=\"4\" >\n";
						$html .= "							".$datos['observacion']."\n";
						$html .= "						</td>\n";
						$html .= "					</tr>\n";
						$html .= "				</table>\n";
						$html .= "			</td>\n";
						$html .= "		</tr>\n";
					}
					
					$html .= "	</table><br>";
					$html .= "</div>\n";
				}
			}
			
			$soluciones = SessionGetVar("SolucionesFormuladas");
			print_r($soluciones);
			$j = 0;
			$est0 = "style=\"text-indent:2pt;font-size:11px;\" ";
			$est1 = "style=\"text-indent:2pt;font-size:9px;\" ";
			foreach($soluciones as $key=> $nivel1)
			{
				if($nivel1[0]['activar'] == "1")
				{
					$html .= "<div id=\"CapaSolucion".$j."\">\n";
					$html .= "	<table align=\"center\" border=\"0\" width=\"100%\" class=\"".$clases[$nivel1[0]['sw_estado']][2]."\">\n";
					$html .= "		<tr id=\"Solucion1".$j."\" class=\"".$clases[$nivel1[0]['sw_estado']][0]."\">\n";
					$html .= "  		<td width=\"84%\">\n";
					$html .= "				<table id=\"Solucion2".$j."\" class=\"".$clases[$nivel1[0]['sw_estado']][0]."\" >\n";
					$html .= "					<tr >\n";
					$html .= "						<td valign=\"bottom\" $est0 >SOLUCION</td>\n";
					$html .= "					</tr>\n";
					$html .= "				</table>\n";
					$html .= "			</td>\n";
					
					if(SessionGetVar("tipoProfesionalhc") == '1')
					{
						$html .= "			<td width=\"4%\" align=\"center\" >\n";
						$html .= "				<a href=\"javascript:CrearEdicion('CapaSolucion".$j."','".$key."',".$nivel1[0]['sw_estado'].")\"  title=\"EDITAR\">\n";
						$html .= "					<img name =\"Editar\" height=\"18\" src=\"".$path."/images/edita.png\" border=\"0\" >\n";
						$html .= "				</a>\n";
						$html .= "			</td>\n";
					}
					$html .= "			<td width=\"4%\" align=\"center\">\n";
					$html .= "				<a href=\"javascript:VerHistorial(new Array('".$key."'))\"  title=\"HISTORIAL\">\n";
					$html .= "					<img name =\"HistorialS".$j."\" height=\"18\"  src=\"".$path."/images/HistoriaClinica1/".$imagenes[$nivel1[0]['sw_estado']][0]."\" border=\"0\">\n";
					$html .= "				</a>\n";
					$html .= "			</td>\n";
					$html .= "			<td width=\"4%\" align=\"center\">\n";
					$html .= "				<a href=\"javascript:DatosActuales(".$j.",$clasesjs,'".$key."',".$nivel1[0]['sw_estado'].");IniciarS('SOLUCION');\" >\n";
					$html .= "					<img name =\"SuspenderS".$j."\" width=\"16\" height=\"18\" title=\"SUSPENDER SOLUCION\" src=\"".$path."/images/".$imagenes[$nivel1[0]['sw_estado']][1]."\" border=\"0\">\n";
					$html .= "				</a>\n";
					$html .= "			</td>\n";
					$html .= "			<td width=\"4%\" align=\"center\" >\n";
					$html .= "				<a href=\"javascript:FinalizarS('".$key."','".$j."')\"  title=\"FINALIZAR SOLUCION\">\n";
					$html .= "					<img name =\"Finalizar\" height=\"18\" src=\"".$path."/images/HistoriaClinica1/cerrar_claro.gif\" border=\"0\" >\n";
					$html .= "				</a>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
					$html .= "		<tr >\n";
					$html .= "			<td colspan=\"5\">\n";
					$html .= "				<table id=\"Solucion0".$j."\"  class=\"".$clases[$nivel1[0]['sw_estado']][0]."\" width=\"100%\">\n";
					foreach($nivel1 as $key0=> $nivel2)
					{
						if($nivel2['sw_solucion'] == '1') 
						{
							$html .= "					<tr>\n";
							$html .= "						<td valign=\"bottom\" align=\"left\"  $est0 width=\"75%\">".$nivel2['producto']." <font $est1>(".$nivel2['principio_activo'].")</font></td>\n";
							$html .= "						<td valign=\"bottom\" align=\"right\" $est1 width=\"10%\">".$nivel2['dosis']."</td>\n";
							$html .= "						<td valign=\"bottom\" align=\"left\"  $est1 width=\"15%\">".$nivel2['unidad_dosificacion']."</td>\n";
							$html .= "					</tr>\n";
						}
					}
					$html .= "				</table>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
						
					$html .= "		<tr>\n";
					$html .= "			<td colspan=\"5\" class=\"modulo_list_oscuro\">\n";
					$html .= "				<table id=\"Solucion3".$j."\"  class=\"".$clases[$nivel1[0]['sw_estado']][7]."\" width=\"100%\">\n";
					$key3 = 0;
					foreach($nivel1 as $key1=> $nivel2)
					{
						if($nivel2['sw_solucion'] == '0') 
						{
							$html .= "					<tr>\n";
							$html .= "						<td valign=\"bottom\" align=\"left\"  $est0 width=\"75%\">".$nivel2['producto']." <font $est1>(".$nivel2['principio_activo'].")</font></td>\n";
							$html .= "						<td valign=\"bottom\" align=\"right\" $est1 width=\"10%\">".$nivel2['dosis']."</td>\n";
							$html .= "						<td valign=\"bottom\" align=\"left\"  $est1 width=\"15%\">".$nivel2['unidad_dosificacion']."</td>\n";
							$html .= "					</tr>\n";
							$key3 = $key1;
						}
					}
					$html .= "				</table>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
					
	 				$html .= "		<tr class=\"".$clases[$nivel1[0]['sw_estado']][1]."\">\n";
					$html .= "			<td colspan=\"5\">\n";
					$html .= "				<table width=\"100%\">\n";
					$html .= "					<tr>\n";
					$html .= "						<td width=\"60%\" valign=\"top\">\n";
					$html .= "							<table id=\"Solucion41".$j."\" class=\"".$clases[$nivel1[0]['sw_estado']][7]."\" >\n";
					$html .= "								<tr>\n";
					$html .= "									<td >CANTIDAD TOTAL </td>\n";
					$html .= "									<td >".$nivel2['cantidad']."</td><td colspan=\"2\"><b>Unidad(es)</b></td>\n";
					$html .= "								</tr>\n";
					$html .= "								<tr >\n";
					$html .= "									<td >VOLUMEN DE INFUSIÓN</td>\n";
					$html .= "									<td align=\"right\">".$nivel2['volumen_infusion']."</td><td colspan=\"2\">".$nivel2['unidad_volumen']."</td>\n";
					$html .= "								</tr>\n";
					$html .= "							</table>\n";
					$html .= "						</td>\n";
					$html .= "						<td width=\"40%\" valign=\"top\" $estilos>\n";
					$html .= "							<table align=\"center\" id=\"Solucion42".$j."\" class=\"".$clases[$nivel1[0]['sw_estado']][7]."\" >\n";
					$html .= "								<tr>\n";
					$html .= "									<td align=\"center\">FORMULÓ:</td>\n";
					$html .= "								</tr>\n";
					$html .= "								<tr>\n";
					$html .= "									<td style=\" font-weight:normal\">".$nivel1[$key1]['med_formula']."</td>\n";
					$html .= "								</tr>\n";
					$html .= "							</table>\n";
					$html .= "						</td>\n";
					$html .= "					</tr>\n";	
					$html .= "				</table>\n";
					
					
					$html .= "			</td>\n";
					$html .= "		</tr>\n"; 
				
					if($nivel2['observacion'] != "")
					{
						$html .= "		<tr class=\"".$clases[$nivel1[0]['sw_estado']][1]."\" >\n";
						$html .= "			<td colspan=\"5\" width=\"100%\" >\n";
						$html .= "				<table width=\"100%\" id=\"Solucion5".$j."\" class=\"".$clases[$nivel1[0]['sw_estado']][7]."\">\n";
						$html .= "					<tr>\n";
						$html .= "						<td valign=\"top\" width=\"30%\">\n";
						$html .= "							OBSERVACIONES E INDICACIONES DE SUMINISTRO:</td>\n";
						$html .= "						</td>\n";
						$html .= "						<td valign=\"top\" width=\"70%\" align=\"justify\" colspan=\"4\" >\n";
						$html .= "							".$nivel2['observacion']."\n";
						$html .= "						</td>\n";
						$html .= "					</tr>\n";
						$html .= "				</table>\n";
						$html .= "			</td>\n";
						$html .= "		</tr>\n";
					}
						
					$html .= "	</table><br>";
					$html .= "</div>\n";
					$j++;
				}
			}
			
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table><br>";
				
			return $html;
		}
		/*******************************************************************************
		*
		********************************************************************************/
		function ConsultarFormulacionSolucion($nummezcla)
    {
	    $sql  = "SELECT FM.num_mezcla, ";			
			$sql .= "				FM.volumen_infusion, ";
			$sql .= "				FM.unidad_volumen, ";
			$sql .= "				FM.cantidad, ";
			$sql .= "				FM.observacion, ";
			$sql .= "				FM.sw_estado, ";
			$sql .= "				FD.codigo_producto,";
			$sql .= "				FD.sw_solucion, ";
			$sql .= "				FD.cantidad as cmedicamento, ";
	    $sql .= "				ID.descripcion AS producto, ";
			$sql .= "				IA.descripcion AS principio_activo, ";
			$sql .= "				IM.descripcion AS umm, ";
			$sql .= "				CASE WHEN ME.sw_pos = 1 THEN 'POS'";
			$sql .= "						 ELSE 'NO POS' END AS item, ";
			$sql .= "				SU.nombre AS med_formula, ";
			$sql .= "				FD.dosis, ";
			$sql .= "				FD.unidad_dosificacion, ";
			$sql .= "				SU.usuario_id ";
			$sql .= "FROM 	hc_formulacion_mezclas FM,";
			$sql .= "				hc_formulacion_mezclas_detalle FD,";
			$sql .= "				inventarios_productos ID, ";
			$sql .= "				inv_med_cod_principios_activos AS IA,";
			$sql .= "				medicamentos ME LEFT JOIN inv_unidades_medida_medicamentos IM ";
			$sql .= "				ON(ME.unidad_medida_medicamento_id = IM.unidad_medida_medicamento_id), ";
			$sql .= "				hc_formulacion_mezclas_eventos FH, ";
			$sql .= "				system_usuarios SU ";
			$sql .= "WHERE	ID.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND 		ME.cod_principio_activo = IA.cod_principio_activo ";
			$sql .= "AND 		FD.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND 		FD.num_mezcla = FM.num_mezcla ";
			$sql .= "AND		FM.num_mezcla = ".$nummezcla." ";
			$sql .= "AND		FM.ingreso = ".SessionGetVar("IngresoHc")." ";
			$sql .= "AND		FH.num_reg = FM.num_reg ";
			$sql .= "AND 		FH.usuario_id = SU.usuario_id ";
			$sql .= "ORDER BY FM.sw_estado,FD.sw_solucion DESC ";
			
 			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$datos = SessionGetVar("SolucionesFormuladas");
			while (!$rst->EOF)
			{		
				$datos[$nummezcla][$rst->fields[6]] = $rst->GetRowAssoc($ToUpper = false);
				$datos[$nummezcla][0]['sw_estado'] = $rst->fields[5];
				
				if($rst->fields[5] == '1')	$datos[$nummezcla][0]['activar'] = "1";
				$rst->MoveNext();
			}
			SessionSetVar("SolucionesFormuladas",$datos);
			
			return true;
		}
		/********************************************************************************
		* Creacion del campo para seleccionar las unidades de dosificacikon, si hay mas 
		* de una o mostrar la informacion de la unidad de dosificacion si hay una, creando
		* un campo hidden
		*
		* @return string
		*********************************************************************************/
		function ObtenerCombo($key,$unidad,$codigo,$est)
		{			
			$datos = array();
			if(!$unidad)
			{
				$vias_id = $this->ObtenerVias($codigo);
				
				$sql .= "SELECT DISTINCT unidad_dosificacion ";
				$sql .= "FROM  	hc_unidades_dosificacion_vias_administracion ";
				$sql .= "WHERE	via_administracion_id IN (".$vias_id.") ";
				
				if(!$rst = $this->ConexionBaseDatos($sql)) return false;

				while (!$rst->EOF)
				{
					$datos[] = $rst->GetRowAssoc($ToUpper = false);
					$rst->MoveNext();
				}
			}
			else
			{
				$datos[0]['unidad_dosificacion'] = $unidad;
			}
			
			$dosis = "";
			if(sizeof($datos) == 1)
			{
				$dosis .= "		<input type=\"hidden\" id=\"dosisunidad\" name=\"dosisunidad\" value=\"".$datos[0]['unidad_dosificacion']."\">\n";
				$dosis .= "		<b $est>".$datos[0]['unidad_dosificacion']."</b>\n";
			}
			else
			{
				$dosis .= "			<select class=\"select\" id=\"dosisunidad\" name=\"dosisunidad\">\n";
				$dosis .= "				<option value=\"0\">-----SELECCIONAR-----</option>\n";
				for($i = 0; $i< sizeof($datos); $i++ )
				{
					$dosis .= "				<option value=\"".$datos[$i]['unidad_dosificacion']."\">".$datos[$i]['unidad_dosificacion']."</option>\n";
				}
				$dosis .= "			</select>\n";
			}
			
			return $dosis;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ObtenerVias($codigo)
		{
			$sql .= "SELECT * ";
			$sql .= "FROM 	inv_medicamentos_vias_administracion ";
			$sql .= "WHERE 	codigo_medicamento = '".$codigo."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			if(!$rst->EOF)
			{
				$sql  = "SELECT HA.via_administracion_id,  ";
				$sql .= "				HA.nombre ";
				$sql .= "FROM 	inv_medicamentos_vias_administracion IA, ";
				$sql .= "				hc_vias_administracion HA ";
				$sql .= "WHERE	HA.via_administracion_id = IA.via_administracion_id ";
				$sql .= "AND 		IA.codigo_medicamento = '".$codigo."' ";
			}
			else
			{
				$sql  = "SELECT HA.via_administracion_id,  ";
				$sql .= "				HA.nombre ";
				$sql .= "FROM 	hc_vias_administracion HA ";
			}
			
			if(!$rstm = $this->ConexionBaseDatos($sql)) return false;
			
			$vias_id = "";
			while (!$rstm->EOF)
			{
				$vias_id .= "'".$rstm->fields{0}."' ";
				$rstm->MoveNext();
			}
			
			$vias_id = str_replace(" ",",",trim($vias_id));

			return $vias_id;
		}
		/********************************************************************************
		* Funcion, donde se inicializan las variables de paginaActual, offset y conteo,
		* importantes a la hora de referenciar al paginador
		* 
		* @param String Cadena que contiene la consulta sql del conteo 
		* @param int numero que define el limite de datos,cuando no se desa el del 
		* 			 usuario,si no se pasa se tomara por defecto el del usuario 
		* @return boolean 
		*********************************************************************************/
		function ProcesarSqlConteo($consulta,$limite=null,$offset=null)
		{
			$this->offset = 0;
			$this->paginaActual = 1;
			if($limite == null)
			{
				$this->limit = GetLimitBrowser();
			}
			else
			{
				$this->limit = $limite;
			}
			
			if($offset)
			{
				$this->paginaActual = intval($offset);
				if($this->paginaActual > 1)
				{
					$this->offset = ($this->paginaActual - 1) * ($this->limit);
				}
			}		

			if(!$result = $this->ConexionBaseDatos($consulta))
				return false;

			if(!$result->EOF)
			{
				$this->conteo = $result->fields[0];
				$result->MoveNext();
			}
			$result->Close();
			return true;
		}
		/********************************************************************************
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la 
		* consulta sql 
		* 
		* @param string sentencia sql a ejecutar 
		* @return rst 
		*********************************************************************************/
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			//$dbconn->debug=true;
			$rst = $dbconn->Execute($sql);
				
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				return false;
			}
			return $rst;
		}
	}
	$oRS = new procesos_admin( array( 'ActivarMenu'));
	$oRS->action();	
?>