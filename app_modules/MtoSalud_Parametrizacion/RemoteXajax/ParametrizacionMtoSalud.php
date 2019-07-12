<?php


function BuscarCargos($filtros,$pagina,$cargos_cant)
{
        $path = SessionGetVar("rutaImagenes");
				$objResponse = new xajaxResponse();				      
        //$car = AutoCarga::factory("UsuariosPer","", "app","UV_Afiliaciones_Admin");
        $cargos = array();
				
				$car = AutoCarga::factory("ParametrizacionMtoSalud","", "app","MtoSalud_Parametrizacion");
        if($cargos_cant==0)
        {
            $cargos_cant = $car->GetCargosApoyos($filtros, $count=true, $limit=false, $offset=false);
        }
				
        $limit=10;
        $offset=($pagina-1)*$limit;
				if(!empty($filtros))
        $cargos = $car->GetCargosApoyos($filtros, $count=null, $limit, $offset);
				
				$html .= "                 <BR><BR>\n";             
        if(!empty($cargos))
        {
            $html .= "          <table border=\"0\" width=\"60%\" align=\"center\" class=\"formulacion_table_list\">\n";
            $html .= "              <tr class=\"formulacion_table_list\">\n";
            $html .= "                <td width='20%'  align=\"center\">\n";
            $html .= "                  CARGO";
            $html .= "                </td>\n";
            $html .= "                <td width='75%'  align=\"center\">\n";
            $html .= "                	DESCRIPCION";
            $html .= "                </td>\n";
            $html .= "                <td width='5%'  align=\"center\">&nbsp;</td>\n";
            $html .= "              </tr>\n";
            for($i=0;$i<count($cargos);$i++)
            {                
                $html .= "                    <tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
                $html .= "                       <td class=\"normal_10AN\" align=\"center\">\n";
                $html .= "                       ".$cargos[$i]['cargo'];
                $html .= "                       </td>\n";
                $html .= "                      <td align=\"center\">\n";
                $html .= "                       ".$cargos[$i]['descripcion'];
                $html .= "                       </td>\n";
                $html .= "                      <td align=\"left\">\n";
                $html .= "                       <input type=\"radio\" name=\"cargo\" value=\"".$cargos[$i]['cargo']."\">";
                $html .= "                      </td>\n";  
								$html .= "          					</tr>\n";              
            }
            $html .= "            </table>\n";

            $html .= "".ObtenerPaginador($pagina,$path,$cargos_cant,$op);
            
        }
        else
        {
            $html .= "                 <table width=\"100%\" align=\"center\">\n";
            $html .= "                    <tr class=\"label_error\">\n";
            $html .= "                       <td width=\"100%\" align=\"center\">\n";
            $html .= "                       NO SE ENCONTRARON RESULTADOS CON ESE CRITERIO DE BUSQUEDA";
            $html .= "                      </td>\n";
            $html .= "                   </tr>\n";
            $html .= "                 </table>\n";
            
        }
        $objResponse->assign("resultado_cargos","innerHTML",$html);
        return $objResponse;
    }
		
		 function ObtenerPaginador($pagina,$path,$slc,$op)
    {

      
      //echo "io";
      $TotalRegistros = $slc;
      $TablaPaginado = "";
        
      if($limite == null)
      {
        $LimitRow = 10;
      }
      else
      {
        $LimitRow = 10;
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
        elseif($pagina > 1)
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
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">P�inas:</td>\n";
          if($pagina > 1)
          {
            $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
                                                                                         //  xajax_ObtenerDocumentosBodegaFinal(empresa_id,centro_utilidad,bodega,usuario_id,clas_documento,tipos_documento);
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarCargosA('1','".$slc."');\" title=\"primero\"><img src=\"".$path."/images/primero.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
            $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarCargosA('".($pagina-1)."','".$slc."');\" title=\"anterior\"><img src=\"".$path."/images/anterior.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
            $TablaPaginado .= "   </td>\n";
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
              $TablaPaginado .="    <td class=\"modulo_list_oscuro\" $estilo align=\"center\"><b>".$i."</b></td>\n";
            }
            else
            {
              $TablaPaginado .="    <td class=\"modulo_list_claro\" $estilo align=\"center\"><a href=\"javascript:BuscarCargosA('".$i."','".$slc."');\">".$i."</a></td>\n";
            }
            $columnas++;
          }
        }
        if($pagina <  $NumeroPaginas )
        {
          $TablaPaginado .= "   <td class=\"label\" bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\" href=\"javascript:BuscarCargosA('".($pagina+1)."','".$slc."');\" title=\"siguiente\"><img src=\"".$path."/images/siguiente.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td><td bgcolor=\"#D3DCE3\">\n";
          $TablaPaginado .= "     <a class=\"label_error\"  href=\"javascript:BuscarCargosA('".($NumeroPaginas)."','".$slc."');\" title=\"ultimo\"><img src=\"".$path."/images/ultimo.png\" border=\"0\" width=\"15\" height=\"15\"></a>\n";
          $TablaPaginado .= "   </td>\n";
          $columnas +=2;
        }
        $aviso .= "   <tr><td class=\"label\"  colspan=".$columnas." align=\"center\">\n";
        $aviso .= "     P�ina&nbsp;".$pagina." de ".$NumeroPaginas."</td>\n";
        $aviso .= "   </tr>\n";
        
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


?>