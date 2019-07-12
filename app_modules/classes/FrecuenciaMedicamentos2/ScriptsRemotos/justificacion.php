<?php
	/**************************************************************************************
	* $Id: justificacion.php,v 1.1 2006/08/29 16:50:05 hugo Exp $ 
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
		/********************************************************************************
		*
		*********************************************************************************/
		function BuscarDiagnosticos($arreglo)
		{
			$this->requestoff = $arreglo[2];
			$path = SessionGetVar("rutaimag");
			$diagnostico = $this->Diagnosticos($arreglo[0],$arreglo[1]);
			$action = "document.buscadorfacturas";
			
			$html = "";
			if(sizeof($diagnostico) == 0)
			{
				$html .= "	<center><br><b class=\"label_error\">LA BÚSQUEDA NO ARROJO NINGUN RESULTADO</b></center><br>\n";
			}
			else
			{
				$diag = SessionGetVar("diagnosticos");
				$html .= $this->ObtenerPaginado($arreglo[2],$action,$path,1);
				$html .= "	<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">\n";
				$html .= "		<tr class=\"modulo_table_list_title\" >\n";
				$html .= "			<td width=\"15%\" align=\"center\">CODIGO</td>\n";
				$html .= "			<td width=\"%\" align=\"center\">DIAGNOSTICO</td>\n";
				$html .= "			<td width=\"6%\" align=\"center\">&nbsp;</td>\n";
				$html .= "		</tr>\n";
				
				$i= 0;
				foreach($diagnostico as $key => $resultado)
				{
					$est = 'modulo_list_claro'; $back = "#DDDDDD";
					if($i % 2 == 0)
					{
					  $est = 'modulo_list_oscuro'; $back = "#CCCCCC";
					}
					$i++;
					$chk = "";
					if($diag[$key]) $chk = "checked";
					
					$html .= "		<tr class=\"$est\">\n";
					$html .= "			<td>".$key."</td>\n";
					$html .= "			<td>".$resultado['diagnostico_nombre']."</td>\n";
					$html .= "			<td align=\"center\" ><input type=\"checkbox\" $chk name=\"selfactura\" value=\"".$key."\" onClick=\"AgregarDiagnostico('$key',this.checked)\"></td>\n";
					$html .= "		</tr>\n";
				}
				$html .= "		</table>\n";
			}
			
			$html .= "<table align=\"center\">\n";
			$html .= "	<tr><td height=\"25\"><a href=\"javascript:OcultarSpan('FacturasB')\" class=\"label_error\">CERRAR</a></td></tr>\n";
			$html .= "</table>\n";
			return $html;		
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function AgregarDiagnostico($param)
		{
			$diag = SessionGetVar("diagnosticos");
			$dat = $this->Diagnosticos("","",$param[0]);
			$diag[$param[0]]['diagnostico_id'] = $dat[$param[0]]['diagnostico_id'];
			$diag[$param[0]]['diagnostico_nombre'] = $dat[$param[0]]['diagnostico_nombre'];

			SessionSetVar("diagnosticos",$diag);
			
			return $this->PintarDiagnostico($diag);
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ObtenerLongitud()
		{
			return sizeof(SessionGetVar("diagnosticos"));
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function EliminarDiagnostico($param)
		{
			$diag = SessionGetVar("diagnosticos");
			
			unset($diag[$param[0]]);			
			SessionSetVar("diagnosticos",$diag);
			
			return $this->PintarDiagnostico($diag);
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function PintarDiagnostico($diag)
		{
			$path = SessionGetVar("rutaimag");
			$html .= "					<td colspan = 5 align=\"center\" width=\"63%\">\n";
			if(sizeof($diag) > 0)
			{
				$html .= "						<table  class=\"modulo_table_list\" width=\"100%\" style=\"background:#FFFFFF\">\n";
				foreach($diag as $key => $diagnosticos)
				{
					$html .= "							<tr class=\"modulo_list_claro\">\n";
					$html .= "								<td width=\"10%\">\n";
					$html .= "									<a href=\"javascript:AgregarDiagnostico('$key',false)\">\n";
					$html .= "										<img src=\"".$path."/images/elimina.png\" border=\"0\" width=\"16\" height=\"16\">\n";
					$html .= "									</a>\n";
					$html .= "								</td>\n";
					$html .= "								<td>\n";
					$html .= "									$key - ".$diagnosticos['diagnostico_nombre']."\n";
					$html .= "								</td>\n";
					$html .= "							</tr>\n";
				}
				$html .= "						</table>\n";
			}			
			$html .= "					</td>\n";
			return $html;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function Diagnosticos($codigo,$diagnostico,$codigo2)
		{
	    $sql  = "SELECT diagnostico_id,";
			$sql .= " 			diagnostico_nombre ";
			$where .= "FROM 	diagnosticos ";
			$where .= "WHERE 	TRUE ";
			
			if ($codigo != '')
				$where .= "AND	diagnostico_id ILIKE '$codigo%' ";
			
			if ($codigo2 != '')
				$where .= "AND	diagnostico_id ILIKE '$codigo2' ";
				
			if ($diagnostico != '')
				$where .= "AND		diagnostico_nombre ILIKE '%$diagnostico%' ";
			
			
			if(!$rst = $this->ProcesarSqlConteo("SELECT COUNT(*) $where",10)) return false;

			$sql .= $where;
			$sql .= "ORDER BY diagnostico_id ";
			$sql .= "LIMIT ".$this->limit." OFFSET ".$this->offset." ;";
			
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;

			while (!$rst->EOF)
			{
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
		  }
			$rst->Close();
			return $datos;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ObtenerPaginado($pagina,$action,$path,$op)
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
					
				$estilo = " style=\"font-family: sans_serif, Verdana, helvetica, Arial; font-weight:bold;font-size:11pt;\" "; 

				$TablaPaginado .= "<tr>\n";
				if($NumeroPaginas > 1)
				{
					$TablaPaginado .= "		<td class=\"label\" bgcolor=\"#D3DCE3\">Páginas:</td>\n";
					if($pagina > 1)
					{
						$TablaPaginado .= "		<td class=\"label\" bgcolor=\"#D3DCE3\">\n";
						$TablaPaginado .= "			<a class=\"label_error\" href=\"javascript:CrearVariables(".$action.",'1')\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
						$TablaPaginado .= "		</td><td bgcolor=\"#D3DCE3\">\n";
						$TablaPaginado .= "			<a class=\"label_error\" href=\"javascript:CrearVariables(".$action.",'".($pagina-1)."')\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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
					$TablaPaginado .= "			<a class=\"label_error\" href=\"javascript:CrearVariables(".$action.",'".($pagina+1)."')\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
					$TablaPaginado .= "		</td><td bgcolor=\"#D3DCE3\">\n";
					$TablaPaginado .= "			<a class=\"label_error\"  href=\"javascript:CrearVariables(".$action.",'".$NumeroPaginas."')\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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
			$Tabla .= "</table>";

			return $Tabla;
		}
		/************************************************************************************ 
		* Funcion, donde se inicializan las variables de paginaActual, offset y conteo,
		* importantes a la hora de referenciar al paginador
		* 
		* @param String Cadena que contiene la consulta sql del conteo 
		* @param int numero que define el limite de datos,cuando no se desa el del 
		* 			 usuario,si no se pasa se tomara por defecto el del usuario 
		* @return boolean 
		*************************************************************************************/
		function ProcesarSqlConteo($consulta,$limite=null)
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
			
			if($this->requestoff)
			{
				$this->paginaActual = intval($this->requestoff);
				if($this->paginaActual > 1)
				{
					$this->offset = ($this->paginaActual - 1) * ($this->limit);
				}
			}		
			
			if(!$_REQUEST['registros'])
			{
				if(!$rst = $this->ConexionBaseDatos($consulta))
					return false;
	
				if(!$rst->EOF)
				{
					$this->conteo = $rst->fields[0];
					$rst->MoveNext();
				}
				$rst->Close();
			}
			else
			{
				$this->conteo = $_REQUEST['registros'];
			}
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
			$dbconn->debug=true;
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