<?php
	/********************************************************************************* 
 	* $Id: ClaseHTML.class.php,v 1.5 2006/02/01 21:01:37 hugo Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Hugo F. Manrique. 
	* @version   $Revision: 1.5 $  
	* @package   ClaseHTML 
	* 
	* Clase en la que se ha implementado un paginador 
 	**********************************************************************************/
	class ClaseHTML
	{
		function ClaseHTML()
		{
			return true;
		}
		/********************************************************************** 
		* Funcion que realiza un paginado de los resultados de la consulta 
		* cuando crea el paginador, adiciona un a variable al url del action 
		* llamada offset, en la cual se guarda el valor de la pagina siguiente 
		* si la hay, la pgina anterior si la hay, la ultima pagina si la hay 
		* y la primera pagina si la hay.
		* 
		* @param  TotalRegistros Numero de registros totales que hay 
		* @param  pagina 		 Numero de pagina en la que esta actualmente 
		* @param  action 		 url al cual pasaria el paginador
		* @return string 
		***********************************************************************/
		function ObtenerPaginado($TotalRegistros,$pagina,$action,$limite=null){
		    
		    if($action != "")
		    {
			    $TotalRegistros = intval($TotalRegistros);
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
				    
					$TablaPaginado .="<table align=\"center\" cellspacing=\"3\"><tr>\n";
					if($NumeroPaginas > 1)
					{
						$TablaPaginado .= "		<td class=\"label\" bgcolor=\"#D3DCE3\">Páginas:</td>\n";
						if($pagina > 1)
						{
						    $TablaPaginado .= "		<td class=\"label\" bgcolor=\"#D3DCE3\">\n";
						    $TablaPaginado .= "			<a href=\"".$action."&offset=1\" title=\"primero\"><img src=\"".GetThemePath()."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
						    $TablaPaginado .= "		</td><td class=\"label\" bgcolor=\"#D3DCE3\">\n";
						    $TablaPaginado .= "			<a href=\"".$action."&offset=".($pagina-1)."\" title=\"anterior\"><img src=\"".GetThemePath()."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
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
						        $TablaPaginado .="		<td bgcolor=\"#D3DCE3\" align=\"center\"><b>".$i."</b></td>\n";
						    }
						    else
						    {
						        $TablaPaginado .="		<td bgcolor=\"#DDDDDD\" align=\"center\"><a href=\"".$action."&offset=".$i."\">".$i."</a></td>\n";
						    }
						    $columnas++;
						}
					}
					if($pagina <  $NumeroPaginas )
					{
					    $TablaPaginado .= "			<td class=\"label\" bgcolor=\"#D3DCE3\">\n";
					    $TablaPaginado .= "				<a href=\"".$action."&offset=".($pagina+1)."\" title=\"siguiente\"><img src=\"".GetThemePath()."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
					    $TablaPaginado .= "			</td><td class=\"label\" bgcolor=\"#D3DCE3\">\n";
					    $TablaPaginado .= "				<a href=\"".$action."&offset=".$NumeroPaginas."\" title=\"ultimo\"><img src=\"".GetThemePath()."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
					    $TablaPaginado .= "			</td>\n";
					    $columnas +=2;
					}
					$TablaPaginado .= "		<tr><td class=\"label\"  colspan=".$columnas." align=\"center\">\n";
					$TablaPaginado .= "			Página&nbsp;".$pagina." de ".$NumeroPaginas."</td>\n";
					$TablaPaginado .= "		</tr></table>\n";
			    }
		  	}
		    else 
		    {
		    	$TablaPaginado .="<table align=\"center\" cellspacing=\"3\"><tr>\n";
		    	$TablaPaginado .= "		<tr><td class=\"label_error\" align=\"center\">\n";
				$TablaPaginado .= "			NO HAY URL\n";
				$TablaPaginado .= "		</td></tr>\n";
				$TablaPaginado .= "</table>\n";
		    }
		    return $TablaPaginado;
		}	
	}
?>