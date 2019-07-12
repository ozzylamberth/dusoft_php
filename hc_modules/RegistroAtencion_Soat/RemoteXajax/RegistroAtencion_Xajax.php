<?php
	/**************************************************************************************
	* $Id: RegistroAtencion_Xajax.php,v 1.2 2007/05/11 15:35:06 tizziano Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	*
	* @author Tizziano Perea O.
	**************************************************************************************/
	
	function BusquedaDX($filtroCodigo, $filtroDescripcion, $pag)
	{
		$objResponse = new xajaxResponse();
          $html = BusquedaAvanzadaDx($filtroCodigo, $filtroDescripcion, $pag);
          if(!$html)
		{
			if($_SESSION['EgresoIngreso'] == '1')
				$objResponse->assign("listaE","style.display","none");
               else
	               $objResponse->assign("lista","style.display","none");
		}
		else
		{
			if($_SESSION['EgresoIngreso'] == '1')
               {
                    $objResponse->assign("listaE","style.display","block");
                    $objResponse->assign("listaE","innerHTML",$html);
               }
               else
               {
                    $objResponse->assign("lista","style.display","block");
                    $objResponse->assign("lista","innerHTML",$html);
               }
		}
		return $objResponse;
	}
     
     
     function BusquedaAvanzadaDx($filtroCodigo, $filtroDescripcion, $pag)
     {
          list($dbconn) = GetDBconn();

          if($filtroCodigo)
          {
			$filtroCodigo = strtoupper($filtroCodigo);
          }else{ $filtroCodigo = ""; }
          

          if($filtroDescripcion)
          {
			$filtroDescripcion = strtoupper($filtroDescripcion);
          }else{ $filtroDescripcion = ""; }
     
          $busqueda1 = '';
          $busqueda2 = '';
     
          if ($filtroCodigo)
          {
               $busqueda1 ="WHERE diagnostico_id LIKE '%$filtroCodigo%'";
          }
     
          if ($filtroDescripcion)
          {
               $busqueda2 ="WHERE diagnostico_nombre LIKE '%$filtroDescripcion%'";
          }
     
          if(empty($this->conteo))
          {
               $query = "SELECT count(*)
                         FROM diagnosticos
                         $busqueda1 $busqueda2";
     
               $resulta = $dbconn->Execute($query);
     
               if ($dbconn->ErrorNo() != 0)
               {
                    $this->error = "Error al Cargar el Modulo";
                    $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                    return false;
               }
               list($this->conteo)=$resulta->fetchRow();
          }
          
          if(!$pag)
          {
               $pag = '0';
          }
     
          $query = "SELECT diagnostico_id, diagnostico_nombre
                    FROM diagnosticos
                    $busqueda1 $busqueda2
                    order by diagnostico_id
                    LIMIT ".SessionGetVar("Limite")." OFFSET ".$pag.";";
          
          $resulta = $dbconn->Execute($query);
          if ($dbconn->ErrorNo() != 0)
          {
               $this->error = "Error al Cargar el Modulo";
               $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
               return false;
          }
          $i=0;
          while(!$resulta->EOF)
          {
               $var[$i]=$resulta->GetRowAssoc($ToUpper = false);
               $resulta->MoveNext();
               $i++;
          }
     
          if($this->conteo==='0')
          {
               $this->frmError["MensajeError"]="NINGUN RESULTADO OBTENIDO";
               return false;
          }
          
          if($var)
          {
			$html.="<table  align=\"center\" border=\"0\" width=\"100%\">";
               $html.="<tr class=\"modulo_table_title\">";
			$html.="<td align=\"center\" colspan=\"3\">RESULTADO DE LA BUSQUEDA</td>";
			$html.="</tr>";
			$html.="<tr class=\"hc_table_submodulo_list_title\">";
			$html.="  <td width=\"8%\">CODIGO</td>";
			$html.="  <td width=\"60%\">DIAGNOSTICO</td>";
			$html.="  <td width=\"5%\">OPCION</td>";
			$html.="</tr>";
               foreach($var as $key => $val)
               {
				$codigo = $val['diagnostico_id'];
				$diagnostico = $val['diagnostico_nombre'];
				if( $key % 2){$estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}
				$html.="<tr class=\"$estilo\">";
				$html.="<td align=\"center\" width=\"8%\">$codigo</td>";
				$html.="<td align=\"left\" width=\"60%\">$diagnostico</td>";
                    $html.="<td align=\"center\" width=\"5%\"><input type=\"checkbox\" id=\"opcion$key\" value=\"".$codigo."\" onclick=\"javascript:LlenarVectorDX('".$codigo."', '".$diagnostico."', '');\"></td>";
				$html.="</tr>";
               }
               $html.="<tr class=\"$estilo\">";
			$ky = $key + 1;
               $html.= "<td align=\"center\" colspan=\"2\"><label class=\"label_error\">RETENER DIAGNOSTICOS DE EVOLUCION</label></td>";
               $html.= "<td align=\"center\"><input type=\"checkbox\" id=\"opcion$ky\" value=\"1\" onclick=\"javascript:LlenarVectorDX('', '', '', '1');\"></td>";
			$html.="</tr>";
               $html.="<tr class=\"$estilo\">";
			$html.= "<td align=\"right\" colspan=\"3\"><input class=\"input-submit\" name=\"guardar$pfj\" type=\"submit\" value=\"CERRAR\" onclick=\"javascript:LlenarVectorDX('', '', '1','');\"></td>";
			$html.="</tr>";
               $html.="</table><br>\n";
               
               $html.="".ObtenerPaginadoXajax($this->conteo, $pag, "", $filtroCodigo, $filtroDescripcion);
               $html.="<br>\n";
          }
          return $html;
     }
     
     function ObtenerPaginadoXajax($TotalRegistros, $pagina, $op, $filtroCodigo, $filtroDescripcion)
     {
          $TablaPaginado = "";
          
          if (empty($filtroCodigo))
          {
			$filtroCodigo = "";
          }
          
          if (empty($filtroDescripcion))
          {
			$filtroDescripcion = "";
          }

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
                    $TablaPaginado .= "		<td class=\"label\" bgcolor=\"#D3DCE3\">Paginas:</td>\n";
                    if($pagina > 1)
                    {
                         $TablaPaginado .= "		<td class=\"label\" bgcolor=\"#D3DCE3\">\n";
                         $TablaPaginado .= "			<a href=\"javascript:BusquedaDX('".$filtroCodigo."', '".$filtroDescripcion."', '".$pagina."');\" title=\"primero\"><img src=\"".GetThemePath()."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                         $TablaPaginado .= "		</td><td class=\"label\" bgcolor=\"#D3DCE3\">\n";
                         $TablaPaginado .= "			<a href=\"javascript:BusquedaDX('".$filtroCodigo."', '".$filtroDescripcion."', '".$pagina."');\" title=\"anterior\"><img src=\"".GetThemePath()."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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
                              $TablaPaginado .="		<td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:BusquedaDX('".$filtroCodigo."', '".$filtroDescripcion."', '".$i."');\">".$i."</a></td>\n";
                         }
                         $columnas++;
                    }
               }
               if($pagina <  $NumeroPaginas )
               {
                    $TablaPaginado .= "		<td class=\"label\" bgcolor=\"#D3DCE3\">\n";
                    $TablaPaginado .= "			<a href=\"javascript:BusquedaDX('".$filtroCodigo."', '".$filtroDescripcion."', '".$pagina."');\" title=\"siguiente\"><img src=\"".GetThemePath()."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                    $TablaPaginado .= "		</td><td class=\"label\" bgcolor=\"#D3DCE3\">\n";
                    $TablaPaginado .= "			<a href=\"javascript:BusquedaDX('".$filtroCodigo."', '".$filtroDescripcion."', '".$pagina."');\" title=\"ultimo\"><img src=\"".GetThemePath()."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
                    $TablaPaginado .= "		</td>\n";
                    $columnas +=2;
               }
               $aviso .= "		<tr><td class=\"label\"  colspan=".$columnas." align=\"center\">\n";
               $aviso .= "			Pagina&nbsp;".$pagina." de ".$NumeroPaginas."</td>\n";
               $aviso .= "		</tr>\n";
               
               if($op)
                    $TablaPaginado .= $aviso;
               else
                    $TablaPaginado = $aviso.$TablaPaginado;
          }
          
          $Tabla .= "<table align=\"center\" cellspacing=\"3\" >\n";
          $Tabla .= $TablaPaginado;
          $Tabla .= "</table><br>";

          return $Tabla;
     }
     
     function VectorDX($Vector1, $Vector2, $Evo)
     {
          $objResponse = new xajaxResponse();
          $html = SeleccionDX($Vector1, $Vector2, $Evo);
		if($_SESSION['EgresoIngreso'] == '1')
          {
          	$CapaDatos = "dx_insertarE";
               $CapaEnlace = "enlace1";
          }else
          {
          	$CapaDatos = "dx_insertarI";
               $CapaEnlace = "enlace";
          }
                    
          $objResponse->assign($CapaDatos,"innerHTML",$html);
          $objResponse->assign($CapaEnlace,"style.display","none");
          $objResponse->call("CerrarCapa");
          return $objResponse;
     }
     
     function SeleccionDX($Vector1, $Vector2, $Evo)
     {
          list($dbconn) = GetDBconn();
          
          if($_SESSION['EgresoIngreso'] == '1')
          { $tabla = "hc_sub_registroatencionsoat_diagnosticosegreso"; }
          else
          { $tabla = "hc_sub_registroatencionsoat_diagnosticosingreso"; }
          
          $html.="<table align=\"center\" border=\"0\" width=\"100%\" class=\"hc_table_list\">";
          $html.="<tr class=\"hc_table_submodulo_list_title\">";
          $html.="  <td width=\"8%\">CODIGO</td>";
          $html.="  <td width=\"60%\">DIAGNOSTICO</td>";
          $html.="</tr>";
          for($i=0; $i<sizeof($Vector1); $i++)
          {
               if($i % 2){$estilo='modulo_list_oscuro';}
               else {$estilo='modulo_list_claro';}
               $html.="<tr class=\"$estilo\">";
               $html.="<td align=\"center\" width=\"8%\">".$Vector1[$i]."</td>";
               $html.="<td align=\"left\" width=\"60%\">".$Vector2[$i]."</td>";
               $html.="</tr>";

               if($Vector1[$i])
               {
               	$query = "DELETE FROM $tabla WHERE 
                    		ingreso = ".SessionGetVar("Ingreso")."
                              AND diagnostico_id = '".$Vector1[$i]."';";
               	$query.= "INSERT INTO $tabla VALUES (".SessionGetVar("Ingreso").", 
                    							  '".$Vector1[$i]."');";
                    $dbconn->Execute($query);
                    if ($dbconn->ErrorNo() != 0)
                    {
                         $this->error = "Error al Cargar el Modulo - No se pudo insertar en la tabla - hc_evolucion_descripcion_diagnosticos. $query";
                         $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                         return $this->error;
                    }
               }
               $query = "";
          }
          if($Evo == '1')
          {
               $Diagnosticos = array();
               
               if($_SESSION['EgresoIngreso'] == '1')
               {
               	$Diagnosticos = SessionGetVar("VectorDXEvoE");
               	$tabla = "hc_sub_registroatencionsoat_diagnosticosegreso";     
               }
               else
               {
                    $Diagnosticos = SessionGetVar("VectorDXEvoI");
                    $tabla = "hc_sub_registroatencionsoat_diagnosticosingreso";             
               }

                    
               if($Diagnosticos)
               {
                    for($j=0; $j<sizeof($Diagnosticos); $j++)
                    {
                         if($j % 2){$estilo='modulo_list_oscuro';}
                         else {$estilo='modulo_list_claro';}
                         $html.="<tr class=\"$estilo\">";
                         $html.="<td align=\"center\" width=\"8%\">".$Diagnosticos[$j][diagnostico_id]."</td>";
                         $html.="<td align=\"left\" width=\"60%\">".$Diagnosticos[$j][diagnostico_nombre]."</td>";
                         $html.="</tr>";
                         
                         $query = "INSERT INTO $tabla VALUES (".SessionGetVar("Ingreso").", 
                         							  '".$Diagnosticos[$j][diagnostico_id]."');";
                         $dbconn->Execute($query);
                         if ($dbconn->ErrorNo() != 0)
                         {
                              $this->error = "Error al Cargar el Modulo - No se pudo insertar en la tabla - hc_evolucion_descripcion_diagnosticos. $query";
                              $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
                              return $this->error;
                         }
                         $query = "";
                    }
               }
          }
          $html.="</table><br>\n";
          return $html;
     }
     
     
     function RegistrarVar($tipo)
     {
          $objResponse = new xajaxResponse();
          $_SESSION['EgresoIngreso'] = $tipo;
          return $objResponse;
     }
     
?>