<?php
	/**
	* $Id: Creditos.php,v 1.2 2010/03/12 18:41:36 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* @author Hugo F. Manrique
	*/	
	
	$VISTA = "HTML";
	$_ROOT = "../../../";
	
	include "../../../classes/rs_server/rs_server.class.php";
	include	"../../../includes/enviroment.inc.php";
	include "../../../app_modules/FacturacionNotaCreditoAjuste/classes/NotasCredito.class.php";
	include "../../../app_modules/FacturacionNotaCreditoAjuste/classes/NotasDebitoHTML.class.php";
	
	class procesos_admin extends rs_server
	{
		function CrearNotaCredito($param)  
		{
			$html = "";
			$empresa = $_SESSION['NotasAjuste']['empresa'];
			$tercero = SessionGetVar("DatosTercero");
			$usuario = UserGetUID();
			if($param[3] == 0) $param[3] = "NULL";
			
			$nc = new NotasCredito();
			$rst = $nc->CrearNotaCredito($empresa,$usuario,$param[0],$param[1],$param[2],$tercero,$param[3]);
			if($rst)
			{
				$Notas = $nc->ObtenerNotasCreditos($usuario,$tercero,$empresa);	
				$ndhtml = new NotasDebitoHTML();
				$html .= $ndhtml->CrearListadoNotasCredito($Notas,"FormaCrearCuerpoNotasCredito","Credito");
			}
			
			return $html."~".$nc->frmError['MensajeError'];
		}
		/*********************************************************************************
		*
		**********************************************************************************/
		function EliminarNotaCredito($param)
		{
			$html = "";
			$nc = new NotasCredito();
			$rst = $nc->EliminarNotaDC($param[0],$param[1],null);
			
			if($rst)
			{
				$Notas = $nc->ObtenerNotasCreditos(UserGetUID(),SessionGetVar("DatosTercero"),$_SESSION['NotasAjuste']['empresa'],"D");	
				$ndhtml = new NotasDebitoHTML();
				$html .= $ndhtml->CrearListadoNotasCredito($Notas,"FormaCrearCuerpoNotasCredito","Credito");
			}
			
			return $html."~".$nc->frmError['MensajeError'];
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function AdicionarConcepto($param)
		{
			$html = "";
			$dep = "NULL";
			$trid = "NULL";
			$trdc = "NULL";

			if($param[3] != 0) $dep = "'".$param[3]."'";
			
      $param[4] = str_replace("\\'","",$param[4]);
      $param[4] = str_replace("'","",$param[4]);
      
			if($param[4] != "undefined")
			{
				$trid = "'".str_replace("\\'","",$param[5])."'";
				$trdc = "'".str_replace("\\'","",$param[4])."'";
			}
			
			$id = SessionGetVar("TmpIdentificador");
			$empresa = $_SESSION['NotasAjuste']['empresa'];
			
			$nc = new NotasCredito();
			
			$con = str_replace("\\'","",$param[0]);
			$nat = str_replace("\\'","",$param[1]);
			
      $trid = str_replace("'","",$trid);
      $con = str_replace("'","",$con);
      $nat = str_replace("'","",$nat);
      
			$rst = $nc->AdicionarConceptosCredito($con,$param[2],$nat,$dep,$trid,$trdc,$empresa,$id);
			
			if($rst)
			{
				$ndhtml = new NotasDebitoHTML();
				$conceptos = $nc->ObtenerConceptosAdicionados(SessionGetVar("TmpIdentificador"),$empresa);
				$html = $ndhtml->CrearListaConceptos($conceptos);
			}
			return $html."~".$nc->frmError['MensajeError']."~".$rst;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function EliminarConceptos($param)
		{
			$id = SessionGetVar("TmpIdentificador");
			$empresa = $_SESSION['NotasAjuste']['empresa'];
			
			$nc = new NotasCredito();			
			
			$rst = $nc->EliminarConceptosCredito($param[0],$param[1],$id);
			
			if($rst)
			{
				$ndhtml = new NotasDebitoHTML();
				$conceptos = $nc->ObtenerConceptosAdicionados(SessionGetVar("TmpIdentificador"),$empresa);
				$html = $ndhtml->CrearListaConceptos($conceptos);
			}
			return $html."~".$nc->frmError['MensajeError']."~".$rst;
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function ActualizarInformacion($param)
		{
			$id = SessionGetVar("TmpIdentificador");
			$empresa = $_SESSION['NotasAjuste']['empresa'];
			
			$nc = new NotasCredito();			
			$rst = $nc->ActualizarInformacion($id,$empresa,$param[0]);

			return $nc->frmError['MensajeError'];
		}
		/*********************************************************************************
		*
		**********************************************************************************/
		function CerrarNotaCredito($param)
		{
			$nc = new NotasCredito();
			$empresa = $_SESSION['NotasAjuste']['empresa'];
			
			$doc = ModuloGetVar('app','FacturacionNotaCreditoAjuste','notacredito_'.$empresa);
			$rst = $nc->CerrarNotaCredito($param[0],$empresa,$doc,$param[2]);
			
			if($rst)
			{
				$Notas = $nc->ObtenerNotasCreditos(UserGetUID(),SessionGetVar("DatosTercero"),$_SESSION['NotasAjuste']['empresa']);	
				$ndhtml = new NotasDebitoHTML();
				$html .= $ndhtml->CrearListadoNotasCredito($Notas,"FormaCrearCuerpoNotasCredito","Credito");
			}
			$nota = explode("~",$nc->frmError['MensajeError']);
			SessionSetVar("PrefijoNotaCredito",$nota[1]);
			SessionSetVar("NumeroNotaCredito",$nota[2]);
			
			$funcion = SessionGetVar("FuncionImprimir");
			
			$mensaje .= "<table border=\"0\" width=\"100%\" align=\"center\" >\n";
			$mensaje .= "	<tr>\n";
			$mensaje .= "			<td align=\"center\" class=\"normal_10AN\">\n";
			$mensaje .= "				".$nota[0]."<br>SE GENERO LA NOTA CREDITO Nº ".$nota[1]." ".$nota[2]."\n";;
			$mensaje .= "			</td>\n";
			$mensaje .= "	</tr>\n";
			$mensaje .= "	<tr>\n";
			$mensaje .= "			<td align=\"center\">\n";
			$mensaje .= "				<input type=\"button\" class=\"input-submit\" value=\"Imprimir Nota Credito\" onclick=\"$funcion\">\n";
			$mensaje .= "			</td>\n";
			$mensaje .= "	</tr>\n";
			$mensaje .= "</table><br>\n";
			
			return $html."~".$mensaje."~".$nc->frmError['MensajeError'];
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function AnularNotaCredito($param)
		{
			$empresa = $_SESSION['NotasAjuste']['empresa'];
			
			$nc = new NotasCredito();
			$rst = $nc->AnularNotaCredito($param[0],$param[1],$empresa,$param[2],$param[3],UserGetUID());
			
			if($rst)
				$html = "SE HA ANULADO";
			else
				$html = "NO SE PUDO ANULAR <br>".$nc->frmError['MensajeError'];
				
			$mensaje .= "<table border=\"0\" width=\"100%\" align=\"center\" >\n";
			$mensaje .= "	<tr>\n";
			$mensaje .= "			<td align=\"center\" class=\"normal_10AN\">\n";
			$mensaje .= "				<br>LA NOTA CREDITO Nº ".$param[0]." ".$param[1]." $html\n";;
			$mensaje .= "			</td>\n";
			$mensaje .= "	</tr>\n";
			$mensaje .= "</table><br>\n";
			return $mensaje; 
		}
		/**********************************************************************************
		*
		***********************************************************************************/
		function BuscarFacturas($param)
		{
			$nc = new NotasCredito();
			$path = SessionGetVar("rutaimag");
			$facturas = $nc->ObtenerFacturas($param[0],$param[1],$_SESSION['NotasAjuste']['empresa'],SessionGetVar("DatosTercero"),$param[2]);
			
			$action = "document.buscadorfacturas";
			
			$html = "";
			if(sizeof($facturas) == 0)
			{
				$html .= "	<center><br><b class=\"label_error\">LA BÚSQUEDA NO ARROJO NINGUN RESULTADO</b></center><br>\n";
			}
			else
			{
				$html .= $this->ObtenerPaginado($param[2],$action,$path,$nc,1);
				$html .= "	<table align=\"center\" border=\"0\" width=\"80%\" class=\"modulo_table_list\">\n";
				$html .= "		<tr class=\"modulo_table_list_title\" >\n";
				$html .= "			<td width=\"21%\" align=\"center\">FACTURA</td>\n";
				$html .= "			<td width=\"21%\" align=\"center\">F. FACTURA</td>\n";
				$html .= "			<td width=\"26%\" align=\"center\">SALDO</td>\n";
				$html .= "			<td width=\"26%\" align=\"center\">VALOR</td>\n";
				$html .= "			<td width=\"6%\" align=\"center\">&nbsp;</td>\n";
				$html .= "		</tr>\n";
							
				for($i=0; $i<sizeof($facturas); $i++)
				{
					$est = 'modulo_list_claro'; $back = "#DDDDDD";
					if($i % 2 == 0)
					{
					  $est = 'modulo_list_oscuro'; $back = "#CCCCCC";
					}

					$html .= "		<tr class=\"$est\" onmouseout=mOut(this,\"".$back."\"); onmouseover=mOvr(this,'#FFFFFF'); >\n";
					$html .= "			<td>".$facturas[$i]['prefijo']." ".$facturas[$i]['factura_fiscal']."</td>\n";
					$html .= "			<td align=\"center\">".$facturas[$i]['fecha']."</td>\n";
					$html .= "			<td align=\"right\"	>$".formatoValor($facturas[$i]['saldo'])."</td>\n";
					$html .= "			<td align=\"right\" >$".formatoValor($facturas[$i]['total_factura'])."</td>\n";
					$html .= "			<td align=\"center\" ><input type=\"radio\" name=\"selfactura\" value=\"".$facturas[$i]['prefijo']."-".$facturas[$i]['factura_fiscal']."\" onClick=\"FacturaSeleccionada('".$facturas[$i]['prefijo']."','".$facturas[$i]['factura_fiscal']."')\"></td>\n";
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
	}
	$oRS = new procesos_admin( array('ActivarMenu'));
	$oRS->action();	
?>