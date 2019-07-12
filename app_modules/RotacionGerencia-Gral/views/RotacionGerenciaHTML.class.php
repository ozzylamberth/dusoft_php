<?php
	/**
	* @package IPSOFT-SIIS
	* @version $Id: RotacionGerenciaHTML.class.php,v 1.0 
	* @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres
	*/

	IncludeClass("ClaseHTML");
  IncludeClass("ClaseUtil");

	class RotacionGerenciaHTML
	{
	/**
		* Constructor de la clase
	*/
	function  RotacionGerenciaHTML()
	{}
	/*
	* Funcion donde se crea la forma para el menu la Rotacion Productos
          * @param array $action vector que contiene los link de la aplicacion
          * @return string $html retorna la cadena con el codigo html de la pagina
       
	*/
			function FormaMenu($action)
		{
			$html  = ThemeAbrirTabla('MENU ROTACION DE PRODUCTOS POR EMPRESA');
			$ctl = AutoCarga::factory("ClaseUtil");
			$html .= $ctl->RollOverFilas();
			$html .= "<center>\n";
			$html .= "<fieldset class=\"fieldset\" style=\"width:35%\">\n";
			$html .= "<table width=\"98%\" class=\"modulo_table_list\" border=\"0\" align=\"center\" >\n";
			$html .= "  <tr class=\"formulacion_table_list\" >\n";
			$html .= "     <td align=\"center\">MENU\n";
			$html .= "     </td>\n";
			$html .= "  </tr>\n";
			$html .= "  <tr  class=\"normal_10AN\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
			$html .= "      <td  align=\"center\">\n";
			$html .= "        <a href=\"".$action['rotacion']."\">ROTACION DE PRODUCTOS</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "	</table>\n";
			$html .= "</fieldset><br>\n";
			$html .= "</center>\n";
			$html .= "<table align=\"center\">\n";
			$html .= "<br>";
			$html .= "  <tr>\n";
			$html .= "      <td align=\"center\" class=\"label_error\">\n";
			$html .= "        <a href=\"".$action['volver']."\">VOLVER</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= ThemeCerrarTabla();
			return $html;
		}
	  /* Funcion que  Contiene la Forma  Para Seleccionar la fecha de Inicio y Fecha Final de la Rotacion 
		* @param array $action vector que contiene los link de la aplicacion
    * @return string $html retorna la cadena con el codigo html de la pagina
	  */
    function FormaGenerarRotacion($action,$empresa,$farmacias)
		{
				$ctl = AutoCarga::factory("ClaseUtil"); 
				$html .= $ctl->IsDate("-");
				$html .= $ctl->AcceptDate("-");
				$html .= ThemeAbrirTabla('PERIODO DE TIEMPO DE ROTACION ');
			
				$html .= "<script>\n";
				$html .= "  function ValidarDtos(frms)\n";
				$html .= "  {\n";
				$html .= "    if(!IsDate(frms.fecha_inicio.value))\n";
				$html .= "    {\n";
				$html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR LA FECHA DE INICIO ';\n";
				$html .= "      return;\n";
				$html .= "    } \n";
				$html .= "    if(!IsDate(frms.fecha_final.value))\n";
				$html .= "    {\n";
				$html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR LA FECHA FINAL';\n";
				$html .= "      return;\n";
				$html .= "    } \n";
				$html .= "	    f = frms.fecha_inicio.value.split('-')\n";
				$html .= "	    f1 = new Date(f[2]+'/'+ f[1]+'/'+ f[0]); \n";
				$html .= "	    f = frms.fecha_final.value.split('-')\n";
				$html .= "	    f2 = new Date(f[2]+'/'+f[1]+'/'+f[0]);\n";
				$html .= "	    if(f1 >= f2 )\n";
				$html .= "	    {\n";
				$html .= "        document.getElementById('error').innerHTML = 'LA FECHA INICIAL NO PUEDE SER MAYOR O IGUAL A LA FECHA FINAL ';\n";
				$html .= "        return;\n";
				$html .= "      } \n";
				$html .= "    if(frms.check[0].checked) \n";
				$html .= "    { \n";
        $html .= "	    if(frms.empresa_id.value == '-1' )\n";
				$html .= "	    {\n";
				$html .= "        document.getElementById('error').innerHTML = 'SE DEBE SELECCIONAR UNA EMPRESA';\n";
				$html .= "        return;\n";
				$html .= "      } \n";
				$html .= "      else \n";
				$html .= "      {\n";
        $html .= "		    flag = false;\n";
        $html .= "		    for(i=0; i<frms.length; i++)\n";
        $html .= "		    {\n";
        $html .= "			    if(frms[i].type == 'checkbox' && frms[i].checked)\n";
        $html .= "				    flag = true;\n";
        $html .= "		    }\n";
        $html .= "	      if(!flag)\n";
				$html .= "	      {\n";
				$html .= "          document.getElementById('error').innerHTML = 'SE DEBE SELECCIONAR AL MENOS UNA FARMACIA';\n";
				$html .= "          return;\n";
				$html .= "        } \n";
				$html .= "      } \n";
			  $html .= "      frms.action=\"".$action['generar_general']."\";\n";
			  $html .= "    }\n";
			  $html .= "    frms.submit();\n";
				$html .= "  }\n";   
				$html .= "  function Habilitar(valor)\n";   
				$html .= "  {\n";   
				$html .= "    document.periodo.empresa_id.disabled = !valor;\n";   
				$html .= "  }\n";   
				$html .= "</script>\n";
				$html .= "<form name=\"periodo\" id=\"periodo\" action=\"".$action['generar']."\"  method=\"post\" >\n";
				$html .= "  <table  width=\"55%\" align=\"center\" border=\"0\" class=\"modulo_table_list\" >\n";
				$html .= "    <tr class=\"formulacion_table_list\">\n";
				$html .= "		  <td  width=\"30%\" >FECHA INICIAL:</td>\n";
				$html .= "		  <td width=\"15%\" align=\"left\" class=\"modulo_list_claro\" >\n";
				$html .= "		    <input type=\"text\" class=\"input-text\" name=\"fecha_inicio\"   id=\"fecha_inicio\" size=\"20\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"\"  >\n";
				$html .= "		  </td>\n";
				$html .= "      <td  width=\"15%\" class=\"modulo_list_claro\" >\n";
				$html .= "				".ReturnOpenCalendario('periodo','fecha_inicio','-')."\n";
				$html .= "		  </td>\n";
				$html .= "    </tr >\n";
				$html .= "    <tr class=\"formulacion_table_list\">\n";
				$html .= "		  <td  width=\"30%\" >FECHA FINAL:</td>\n";
				$html .= "		  <td width=\"15%\" align=\"left\" class=\"modulo_list_claro\" >\n";
				$html .= "		    <input type=\"text\" class=\"input-text\" name=\"fecha_final\"   id=\"fecha_final\" size=\"20\" maxlength=\"10\" onkeypress=\"return acceptDate(event)\" value=\"\"  >\n";
				$html .= "		  </td>\n";
				$html .= "      <td  width=\"15%\" class=\"modulo_list_claro\" >\n";
				$html .= "				".ReturnOpenCalendario('periodo','fecha_final','-')."\n";
				$html .= "		  </td>\n";
				$html .= "    </tr >\n";
				$html .= "    <tr class=\"modulo_table_list_title\">\n";
				$html .= "      <td colspan=\"3\" >TIPOS DE ROTACION</td>\n";
				$html .= "    </tr >\n";
				$html .= "    <tr class=\"formulacion_table_list\" >\n";
				$html .= "		  <td colspan=\"2\">ROTACION GENERAL</td>\n";
				$html .= "      <td class=\"modulo_list_claro\">\n";
        $html .= "        <input type=\"radio\" name=\"check\" id=\"check\" value=\"1\"  onclick=\"Habilitar(true)\">";       
				$html .= "		  </td>\n";
				$html .= "    </tr>\n";
				$html .= "    <tr class=\"formulacion_table_list\">\n";
				$html .= "		  <td colspan=\"2\">ROTACION LABORATORIO</td>\n";
				$html .= "      <td class=\"modulo_list_claro\">\n";
        $html .= "        <input type=\"radio\" name=\"check\" id=\"check\" value=\"2\" checked onclick=\"Habilitar(false)\">\n";       
				$html .= "		  </td>\n";
				$html .= "    </tr >\n";
				$html .= "    <tr class=\"formulacion_table_list\" >\n";
				$html .= "		  <td colspan=\"2\" >ROTACION MOLECULA</td>\n";
				$html .= "      <td class=\"modulo_list_claro\">\n";
        $html .= "        <input type=\"radio\" name=\"check\" id=\"check\" value=\"3\" onclick=\"Habilitar(false)\">  ";
				$html .= "		  </td>\n";
				$html .= "    </tr >\n";
				$html .= "    <tr class=\"formulacion_table_list\" >\n";
				$html .= "		  <td colspan=\"2\" >ROTACION PRODUCTO</td>\n";
				$html .= "      <td class=\"modulo_list_claro\">\n";
        $html .= "        <input type=\"radio\" name=\"check\" id=\"check\" value=\"4\" onclick=\"Habilitar(false)\">";
				$html .= "		  </td>\n";
				$html .= "    </tr >\n";
			  $html .= "    <tr class=\"formulacion_table_list\" >\n";
				$html .= "		  <td colspan=\"2\" >ROTACION INSUMOS</td>\n";
				$html .= "      <td class=\"modulo_list_claro\">\n";
        $html .= "        <input type=\"radio\" name=\"check\" id=\"check\" value=\"5\" onclick=\"Habilitar(false)\">  ";       
				$html .= "		  </td>\n";
				$html .= "    </tr >\n";
        $html .= "    <tr class=\"formulacion_table_list\">\n";
        $html .= "      <td>EMPRESAS</td>\n";
        $html .= "      <td colspan=\"2\" class=\"modulo_list_claro\" >\n";
        $html .= "        <select name=\"empresa_id\" class=\"select\" onchange=\"xajax_ListaFarmacias(xajax.getFormValues('periodo'))\" disabled>\n";
        $html .= "          <option value=\"-1\">---SELECCIONAR---</option>\n";
        foreach($farmacias as $key => $dtl)
        $html .= "          <option value=\"".$dtl['empresa_id']."\">".$dtl['razon_social']."</option>\n";
        $html .= "        </select>\n";
        $html .= "      </td>\n";
        $html .= "    </tr >\n";
        $html .= "    <tr>\n";
				$html .= "      <td colspan=\"3\"><div id=\"farmacias\"></div></td>\n";
				$html .= "    </tr>\n";
				$html .= "</table>\n";
        $html .= "<br>";
        $html .= "<div align=\"center\" class=\"label_error\" id='error'></div> ";
				$html .= "<table  width=\"25%\" align=\"center\" border=\"0\" >\n";
				$html .= "  <tr>\n";
				$html .= "	  <td  colspan=\"10\"  align='center'>\n";
				$html .= "		  <input class=\"input-submit\" type=\"button\" name=\"Buscar\" value=\"Generar Rotacion\"  onclick=\"ValidarDtos(document.periodo)\" >\n";
				$html .= "		</td>\n";
				$html .= "	</tr>\n";
				$html .= "</table>\n";
				$html .= "<br>";
				$html .= "<table align=\"center\" width=\"50%\">\n";
				$html .= "  <tr>\n";
				$html .= "    <td align=\"center\">\n";
				$html .= "      <a href=\"".$action['volver']."\"  class=\"label_error\">\n";
				$html .= "        VOLVER\n";
				$html .= "      </a>\n";
				$html .= "    </td>\n";
				$html .= "  </tr>\n";
				$html .= "</table>\n";
				$html .= ThemeCerrarTabla();
				return $html;
		}
  /*  	 Funcion que  Contiene la Forma  de Generar rotacion  por laboratorio y/o todos los laboratorios que han tenido movimiento
        * @param array $action vector que contiene los link de la aplicacion
        * @return string $html retorna la cadena con el codigo html de la pagina
	*/
	
		function FormaGenerarRotacionLaboratorio($action,$empresa,$meses,$fechai,$fechaf,$FechaInicial_,$FechaFinal,$medicamentos_d,$Laboratorio_f,$clase_id)
		{
            $porcentaje_rotacion=ModuloGetVar("","","rotacion_porcentaje");
            $ctl = AutoCarga::factory("ClaseUtil"); 
            $html .= $ctl->IsDate("-");
            $html .= $ctl->AcceptDate("-");
            $html  = $ctl->IsNumeric();
            $html .= $ctl->AcceptNum(false);
            $mdl = AutoCarga::factory("RotacionGerenciaSQL", "classes", "app", "RotacionGerencia");

            $url=ModuloGetURL("app", "RotacionGerencia", "controller", "GenerarRotacionMedicamentos",array("fecha_inicio"=>$fechai,"fecha_final"=>$fechaf));
            $html .= "<script>\n";
            $html .= "  function EnviarDatos(num)\n";
            $html .= "  {\n";
            $html .= "    xajax_IngresarResgistros(xajax.getFormValues('envios'),num)\n";
            $html .= "  }\n";
            $html .= "  function UrlRotaciona()\n";
            $html .= "  {\n";
            $html .= "    window.location='".$url."';\n";
            $html .= "  }\n";
            $html .= "  function ValidarInformacion()\n";
            $html .= "  {\n";
            $html .= "    xajax_ValidarInformacionPre('".$empresa."')\n";
            $html .= "  }\n";
            $html .= "</script>\n";
            $html .= ThemeAbrirTabla('ROTACION - LABORATORIO');
            $html .= $ctl->RollOverFilas();
            $html .= "<form name=\"FormaRotacion_x_Laboratorio\" id=\"FormaRotacion_x_Laboratorio\" method=\"post\"  action=\"".$action['consulta']."\">\n";
            $html .= "			<table  class=\"modulo_table_list\"  width=\"30%\" align=\"CENTER\" border=\"0\"   >";
            $html .= "         <tr align=\"left\" class=\"formulacion_table_list\">\n";
            $html .= "		          	<td align=\"left\"   ><b>LABORATORIOS</b></td>\n";
            $html .= "			            <td  align=\"left\" class=\"modulo_list_claro\" >\n";
            $html .= "					            <select name=\"clase_id\" class=\"select\" >\n";
            $html .= "                        	<option value = '-1'>--  SELECCIONE --</option>\n";
            $csk = "";
            foreach($Laboratorio_f as $indice => $valor)
            {
                  if($valor['clase_id']==$clase_id)
                  $sel = "selected";
                  else   $sel = "";
                  $html .= "  <option value=\"".$valor['clase_id']."\" ".$sel.">".$valor['descripcion']."-".$valor['clase_id']."</option>\n";
            }
            $html .= "                </select>\n";
            $html .= "					  	  </td>\n";
            $html .= "	 </tr>\n";
            $html .= "</table>\n";
            $html .= "  <table width=\"90%\"  border=\"0\"  align=\"center\">";
            $html .= "	  <tr  align=\"center\">\n";
            $html .= "      <td >\n";
            $html .= "		  <input type=\"hidden\" name=\"cantidad_registros\" id=\"cantidad_registros\" value=\"".$i."\">";
            $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"CONSULTAR\" >\n";
            $html .= "      </td>\n";
            $html .= "  </tr>\n";
            $html .= "	</table><br>\n";
            $html .= "	</form> ";
            $html .= "<center>\n";
            $html .= "<fieldset class=\"fieldset\" style=\"width:100%\">\n";
            $html .= "<form name=\"FormaRotacionLaboratorio\" id=\"FormaRotacionLaboratorio\" method=\"post\"  action=\"".$action['guardar']."\">\n";
            $html .= "  <table width=\"100%\" class=\"modulo_table_list\" border=\"0\"  align=\"center\">";
            $html .= "	  <tr  align=\"center\" class=\"formulacion_table_list\" >\n";
            $html .= "      <td rowspan=\"2\" width=\"10%\">CODIGO </td>\n";
            $html .= "      <td rowspan=\"2\" width=\"20%\">LABORATORIO </td>\n";
            $html .= "      <td  rowspan=\"2\"  width=\"35%\">MEDICAMENTOS</td>\n";
            $fecha_inicial=$FechaInicial_;
            $variableDias=31;
            for($k=0;$k<$meses;$k++)
            {
                  $FechaInicial2=$fecha_inicial;
                  $FechaProxima_="";
                  $FechaI_ = explode("-", $FechaInicial2);
                  $Fecha_= $FechaI_[2]."-".$FechaI_[1]."-".$FechaI_[0];
                  $fecha___=$FechaI_[2]."-".$FechaI_[1];
                  list($a,$m,$d)=split("-", $Fecha_);
                  $FechaProxima_ = date("Y-m-d",(mktime(0,0,0, $m,($d+$variableDias),$a)));
                  $FechaDatos= explode("-", $FechaFinal);
                  $Fecha_F= $FechaDatos[2]."-".$FechaDatos[1]."-".$FechaDatos[0];
                  $html .= "      <td  colspan=\"2\" align=\"center\">".$fecha___." </td>\n";
                                     
                  $FechaSoloMesAno = explode("-", $Fecha_);
                  $fecha__=$FechaSoloMesAno[0]."-".$FechaSoloMesAno[1];
                  $FechaPeriodo[$k]=$fecha__;
                  
                  $FinInicial = explode("-", $FechaProxima_);
                  $FechaTrans= $FinInicial[2]."-".$FinInicial[1]."-".$FinInicial[0];
                  $fecha_inicial=$FechaTrans;
                  $FechaProxima_="";
             }
            $html .= "      <td rowspan=\"2\" width=\"5%\" align=\"center\">STOCK</td>\n";
            $html .= "      <td rowspan=\"2\" width=\"5%\" align=\"center\">%PROM</td>\n";
            $html .= "      <td rowspan=\"2\" width=\"5%\" align=\"center\">PEDIDO PROVEEDOR</td>\n";

            $html .= "<tr class=\"formulacion_table_list\"> ";
            for($j=0;$j<$meses;$j++)
            {
                  $html .= "      <td width=\"2%\">ING</td>\n";
                  $html .= "      <td width=\"2%\">EGR</td>\n";
            }
            $html .= "</tr> ";
            $html .= "</tr> ";
            $i=0;
            $contador=0;
            if(!empty($medicamentos_d))
            {
			
                  foreach($medicamentos_d as $key => $descripcion)
                {
                      $existencia=0;
			
                      foreach($descripcion as $key2 => $dtl)
                      {
					
                              foreach($dtl as $key3 => $dtll)
                              {
                                    $est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
                                    $bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
                                    $html .= "  <tr  class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\"  >\n";
                                    $html .= "      <td align=\"left\">".$key." </td>\n";
                                    $html .= "      <td align=\"left\">".$key2." </td>\n";
                                    $html .= "      <td align=\"left\">".$key3."</td>\n";
                                    $sumaE=0;
                                    for($j=0;$j<$meses;$j++)
                                    {  
                                          $sumaE=$sumaE +($dtll[$FechaPeriodo[$j]]['egreso']);
                                          if($dtll[$FechaPeriodo[$j]]['periodo']!="")
                                          {
                                              
                                                  $html .= "      <td align=\"left\">".FormatoValor($dtll[$FechaPeriodo[$j]]['ingreso'])."</td>\n";
                                                  $html .= "      <td align=\"left\">".FormatoValor($dtll[$FechaPeriodo[$j]]['egreso'])."</td>\n";
                                                  $existencia=$dtll[$FechaPeriodo[$j]]['existencia'];
                                           }
                                          else
                                          {
                                                   $existencia=$dtll['']['existencia'];
												  $html .= "      <td align=\"left\" class=\"modulo_list_oscuro\" >--</td>\n";
                                                  $html .= "      <td align=\"left\" class=\"modulo_list_oscuro\" >--</td>\n";
                                           }

                                    }
                                    $gastoE=($sumaE/$meses);
                                    $porce=$gastoE/100;
                                    $sugerido_pedido="";
                                    $porcentaje_pedido=0;
                                    $porcentaje_pedido=($gastoE/$existencia)*100;
                                                      
                                    if($porcentaje_pedido>$porcentaje_rotacion)
                                    $sugerido_pedido = Abs($existencia-$gastoE);
                                      
                                    $html .= "      <td align=\"left\"  style=\"background:#D8BFD8\" >".FormatoValor($existencia)."</td>\n";
                                    $html .= "      <td align=\"left\"  >".FormatoValor($porce,4)."%</td>\n";
                                    $html .="        <td  aling=\"left\">";
                                    $html .="  <input  class=\"input-text\"  style=\"width:60%\" type=\"text\" name=\"txtcantidad".$i."\"  id=\"txtcantidad".$i."\"    value=\"".intval($sugerido_pedido)."\"  size=\"5\" onkeypress=\"return acceptNum(event)\" maxlength=\"12\" >";
                                    $html .=" <input type=\"checkbox\" name=\"".$i."\" id=\"".$i."\"   value=\"".$key."\" > </td>  ";       
                                    $html .= "<center>\n";
                                    $i++;
                                  
                              }
                      }
			
                          $contador++;
                          $html .= "  </tr>\n";
                         /*	/*reimprimir encabezado*/
                        if($contador==15)
                        {
                                $fecha___ = "";
                                $fecha__="";
                                $FechaI_="";
                                $Fecha_F="";
                                $Fecha_="";
                                $FechaSoloMesAno="";
                                $FechaTrans_="";
                                $FechaIniciall2="";
                                $FechaProxima_ll="";
                                $FechaDatos="";
                                $FinInicial="";
                                $html .= "	  <tr  align=\"center\" class=\"formulacion_table_list\" >\n";
                                $html .= "      <td rowspan=\"2\" width=\"10%\">CODIGO </td>\n";
                                $html .= "      <td rowspan=\"2\" width=\"20%\">LABORATORIO </td>\n";
                                $html .= "      <td  rowspan=\"2\"  width=\"35%\">MEDICAMENTOS</td>\n";
                            
                                if($FechaInicial_!=$fechai)
                                {
                                    $FechaInicial_=$fechai;
                                }
                                $variableDias=31;
                                for($k=0;$k<$meses;$k++)
                                {

                                      $FechaIniciall2=$FechaInicial_;
                                      $FechaProxima_ll="";
                                      $FechaI_ = explode("-", $FechaIniciall2);
                                      $Fecha_= $FechaI_[2]."-".$FechaI_[1]."-".$FechaI_[0];
                                      $fecha___=$FechaI_[2]."-".$FechaI_[1];
                                      list($a,$m,$d)=split("-", $Fecha_);
                                      $FechaProxima_ll = date("Y-m-d",(mktime(0,0,0, $m,($d+$variableDias),$a)));
                                      $FechaDatos= explode("-", $FechaFinal);
                                      $Fecha_F= $FechaDatos[2]."-".$FechaDatos[1]."-".$FechaDatos[0];

                                      $html .= "      <td  colspan=\"2\" align=\"center\">".$fecha___." </td>\n";
                                                           
                                        $FechaSoloMesAno = explode("-", $Fecha_);
                                        $fecha__=$FechaSoloMesAno[0]."-".$FechaSoloMesAno[1];

                                        $FinInicial = explode("-", $FechaProxima_ll);
                                        $FechaTrans_= $FinInicial[2]."-".$FinInicial[1]."-".$FinInicial[0];
                                        $FechaInicial_=$FechaTrans_;
                                        $FechaProxima_ll="";
                                }
                            $html .= "      <td rowspan=\"2\" width=\"5%\" align=\"center\">STOCK</td>\n";
                            $html .= "      <td rowspan=\"2\" width=\"5%\" align=\"center\">%PROM</td>\n";
                            $html .= "      <td rowspan=\"2\" width=\"5%\" align=\"center\">PEDIDO PROVEEDOR</td>\n";
                            $html .= "<tr class=\"formulacion_table_list\"> ";
                            for($j=0;$j<$meses;$j++)
                            {
                                    $html .= "      <td width=\"2%\">ING</td>\n";
                                    $html .= "      <td width=\"2%\">EGR</td>\n";
                            }
                            $html .= "</tr> ";
                            $html .= "</tr> ";
                            $contador =0;
                        }
                }
				
                $html .= "</table>";
                $html .= "<br>";
                $html .= "  <table width=\"10%\"  border=\"0\"  align=\"right\">";
                $html .= "	  <tr  align=\"center\">\n";
                $html .= "      <td >\n";
                $html .= "		  <input type=\"hidden\" name=\"cantidad_registros\" id=\"cantidad_registros\" value=\"".$i."\">";
                $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"SOLICITAR\" >\n";
                $html .= "      </td>\n";
                $html .= "  </tr>\n";
                $html .= "	</table>\n";
            
          }else
        {
            $html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA GENERAR LA ROTACION</center><br>\n";
        }
				$html .= "</fieldset><br>\n";
				$html .= "</center>\n";
				$html .= " <br>";
				$html .= "<table align=\"center\" width=\"50%\">\n";
				$html .= "  <tr>\n";
				$html .= "    <td align=\"center\">\n";
				$html .= "      <a href=\"".$action['volver']."\"  class=\"label_error\">\n";
				$html .= "        VOLVER\n";
				$html .= "      </a>\n";
				$html .= "    </td>\n";
				$html .= "  </tr>\n";
				$html .= "</table>\n";
				$html .= ThemeCerrarTabla();
				return $html;
		}
	/*
		* Funcion donde se crea una Forma con una Ventana con capas para mostrar informacion
		* en pantalla
		* @param int $tmn Tamaño que tendra la ventana
		* @return string
    */
		function CrearVentana($tmn,$Titulo)
		{
			$html .= "<script>\n";
			$html .= "  var contenedor = 'Contenedor';\n";
			$html .= "  var titulo = 'titulo';\n";
			$html .= "  var hiZ = 4;\n";
			$html .= "  function OcultarSpan()\n";
			$html .= "  { \n";
			$html .= "    try\n";
			$html .= "    {\n";
			$html .= "      e = xGetElementById('Contenedor');\n";
			$html .= "      e.style.display = \"none\";\n";
			$html .= "    }\n";
			$html .= "    catch(error){}\n";
			$html .= "  }\n";
			$html .= "  function MostrarSpan()\n";
			$html .= "  { \n";
			$html .= "    try\n";
			$html .= "    {\n";
			$html .= "      e = xGetElementById('Contenedor');\n";
			$html .= "      e.style.display = \"\";\n";
			$html .= "      Iniciar();\n";
			$html .= "    }\n";
			$html .= "    catch(error){alert(error)}\n";
			$html .= "  }\n";
			$html .= "  function MostrarTitle(Seccion)\n";
			$html .= "  {\n";
			$html .= "    xShow(Seccion);\n";
			$html .= "  }\n";
			$html .= "  function OcultarTitle(Seccion)\n";
			$html .= "  {\n";
			$html .= "    xHide(Seccion);\n";
			$html .= "  }\n";
			$html .= "  function Iniciar()\n";
			$html .= "  {\n";
			$html .= "    contenedor = 'Contenedor';\n";
			$html .= "    titulo = 'titulo';\n";
			$html .= "    ele = xGetElementById('Contenido');\n";
			$html .= "    xResizeTo(ele,".$tmn.", 'auto');\n";
			$html .= "    ele = xGetElementById(contenedor);\n";
			$html .= "    xResizeTo(ele,".$tmn.", 'auto');\n";
			$html .= "    xMoveTo(ele, xClientWidth()/4, xScrollTop()+20);\n";
			$html .= "    ele = xGetElementById(titulo);\n";
			$html .= "    xResizeTo(ele,".($tmn - 20).", 20);\n";
			$html .= "    xMoveTo(ele, 0, 0);\n";
			$html .= "    xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$html .= "    ele = xGetElementById('cerrar');\n";
			$html .= "    xResizeTo(ele,20, 20);\n";
			$html .= "    xMoveTo(ele,".($tmn - 20).", 0);\n";
			$html .= "  }\n";
			$html .= "  function myOnDragStart(ele, mx, my)\n";
			$html .= "  {\n";
			$html .= "    window.status = '';\n";
			$html .= "    if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
			$html .= "    else xZIndex(ele, hiZ++);\n";
			$html .= "    ele.myTotalMX = 0;\n";
			$html .= "    ele.myTotalMY = 0;\n";
			$html .= "  }\n";
			$html .= "  function myOnDrag(ele, mdx, mdy)\n";
			$html .= "  {\n";
			$html .= "    if (ele.id == titulo) {\n";
			$html .= "      xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
			$html .= "    }\n";
			$html .= "    else {\n";
			$html .= "      xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
			$html .= "    }  \n";
			$html .= "    ele.myTotalMX += mdx;\n";
			$html .= "    ele.myTotalMY += mdy;\n";
			$html .= "  }\n";
			$html .= "  function myOnDragEnd(ele, mx, my)\n";
			$html .= "  {\n";
			$html .= "  }\n";
			$html.= "function Cerrar(Elemento)\n";
			$html.= "{\n";
			$html.= "    capita = xGetElementById(Elemento);\n";
			$html.= "    capita.style.display = \"none\";\n";
			$html.= "}\n";
			$html .= "</script>\n";
			$html .= "<div id='Contenedor' class='d2Container' style=\"display:none;z-index:4\">\n";
			$html .= "  <div id='titulo' class='draggable' style=\" text-transform: uppercase;text-align:center;\">".$Titulo."</div>\n";
			$html .= "  <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
			$html .= "  <div id='Contenido' class='d2Content'>\n";
			$html .= "  </div>\n";
			$html .= "</div>\n";
			$html .= "</script>\n";
			$html .= "<div id='Contenedor2' class='d2Container' style=\"display:none;z-index:4\">\n";
			$html .= "  <div id='titulo2' class='draggable' style=\" text-transform: uppercase;text-align:center;\">".$Titulo."</div>\n";
			$html .= "  <div id='cerrar2' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan()\" title=\"Cerrar\" style=\"font-size:9px;\">X</a></div><br><br>\n";
			$html .= "  <div id='Contenido2' class='d2Content'>\n";
			$html .= "  </div>\n";
			$html .= "</div>\n";
            return $html;
        }  
   
      /*	 Funcion que  Contiene la Forma  del Mensaje cuando se realiza la solicitud de gerencia
		* @param array $action vector que contiene los link de la aplicacion
          * @return string $html retorna la cadena con el codigo html de la pagina
	*/
	   function FormaMensajeSolcitud_($action,$datos_empresa,$datos)
		{
			
			$ctl  = AutoCarga::factory("ClaseUtil"); 
			$html .= $ctl->IsDate("-");
			$html .= $ctl->AcceptDate("-");
			$html .= $ctl->IsNumeric();
			$html .= $ctl->AcceptNum(false);
       $html .= $ctl->RollOverFilas();
    		$html .= ThemeAbrirTabla('MENSAJE-DESCRIPCION DE LO SOLICITADO POR ROTACION');
		   	$html .= " <table width=\"70%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "       <tr class=\"formulacion_table_list\">\n";
			$html .= "             <td align=\"center\">\n";
			$html .= "               SE GENERO LA SOLCITUD PARA LA EMPRESA :</td> ";
			$html .= "     			 <td align=\"left\" class=\"modulo_list_oscuro\" >".$datos_empresa['descripcion1']."</td>\n";
			$html .= "      </tr>\n";
			$html .= "      <tr class=\"formulacion_table_list\">\n";
			$html .= "                      <td align=\"center\">\n";
			$html .= "                        BODEGA : </td>";
			$html .= "     					 <td align=\"left\" class=\"modulo_list_oscuro\" >".$datos_empresa['descripcion4']."</td>\n";
			$html .= "      </tr>\n";
		    $html .= "</table><br>\n";
		
			if(!empty($datos))
			{
				$html .= "<form name=\"FormaModificar\" id=\"FormaModificar\" method=\"post\"   action=\"".$action['guardar']."\" >\n";
				$html .= "<center> ";
				$html .= "<fieldset class=\"fieldset\" style=\"width:80%\" >\n";
				$html .= "  <table width=\"100%\" class=\"modulo_table_list\" border=\"0\"  align=\"center\">";
				$html .= "	  <tr  align=\"center\" class=\"formulacion_table_list\" >\n";
				$html .= "      <td  width=\"10%\">CODIGO </td>\n";
				$html .= "      <td    width=\"55%\">MEDICAMENTO</td>\n";
				$html .= "      <td    width=\"10%\">CANTIDAD</td>\n";
				$html .= "      <td    width=\"5%\">OP</td>\n";
				$html .= "  </tr>\n";
				$i=0;
				foreach($datos as $key => $datos_d)
				{   
					$est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
					$bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
					$html .= "  <tr  class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\"  >\n";
					$html .= "      <td   align=\"left\">".$datos_d['codigo_producto']."</td>\n";
					$html .= "      <td    align=\"left\">".$datos_d['producto']."</td>\n";
					$html .= "      <td align=\"left\">";
					$html .="       <input  class=\"input-text\"  style=\"width:100%\" type=\"text\" name=\"txtcantidad".$i."\"  id=\"txtcantidad".$i."\"    value=\"".FormatoValor($datos_d['sum'])."\"  size=\"5\" onkeypress=\"return acceptNum(event)\" maxlength=\"12\" >";
					$html .="        </td>\n";
					$html .= "      <td align=\"left\">";
					$html .="       <input type=\"checkbox\" name=\"".$i."\" id=\"".$i."\"   value=\"".$datos_d['codigo_producto']."\" > </td>  ";       
					$html .="       </td>\n";	
					$html .= " </tr> ";
						$i++;
					}
					
					$html .= "</table>";
					$html .= "</fieldset>\n";
					$html .= "</center>\n";
					$html .= "	<table   width=\"30%\" align=\"right\" border=\"0\"   >";
					$html .= "  <tr>\n";
					$html .= "	      <td  colspan=\"10\"  align='center'>\n";
					$html .= "		  <input type=\"hidden\" name=\"valor2\" id=\"valor2\" value=\"1\">";
					$html .= "		  <input type=\"hidden\" name=\"cantidad_registros\" id=\"cantidad_registros\" value=\"".$i."\">";
					$html .= "        <input class=\"input-submit\" type=\"submit\" class=\"input-text\" name=\"btnCrear\"     value=\"MODIFICAR\" >\n";
					$html .= " 		 </td>\n";
					$html .= "	</tr>\n";
					$html .= "</table><br>\n";
					
			}
			$html .= "<table align=\"center\">\n";
			$html .= "<br>";
			$html .= "  <tr>\n";
			$html .= "      <td align=\"center\" class=\"label_error\">\n";
			$html .= "        <a href=\"".$action['volver']."\">VOLVER</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= ThemeCerrarTabla();
			return $html;
		}
    /*   Funcion que  Contiene la Forma  de Generar rotacion por producto
		* @param array $action vector que contiene los link de la aplicacion
        * @return string $html retorna la cadena con el codigo html de la pagina
	*/
	
		function FormaGenerarRotacionProducto($action,$empresa,$meses,$fechai,$fechaf,$FechaInicial_,$FechaFinal,$medicamentos_d)
		{
              $porcentaje_rotacion=ModuloGetVar("","","rotacion_porcentaje");
              $ctl = AutoCarga::factory("ClaseUtil"); 
              $html .= $ctl->IsDate("-");
              $html .= $ctl->AcceptDate("-");

              $html  = $ctl->IsNumeric();
              $html .= $ctl->AcceptNum(false);
              $mdl = AutoCarga::factory("RotacionGerenciaSQL", "classes", "app", "RotacionGerencia");

              $url=ModuloGetURL("app", "RotacionGerencia", "controller", "GenerarRotacionMedicamentos",array("fecha_inicio"=>$fechai,"fecha_final"=>$fechaf));
              $html .= "<script>\n";
              $html .= "  function EnviarDatos(num)\n";
              $html .= "  {\n";
              $html .= "    xajax_IngresarResgistros(xajax.getFormValues('envios'),num)\n";
              $html .= "  }\n";
              $html .= "  function UrlRotaciona()\n";
              $html .= "  {\n";
              $html .= "    window.location='".$url."';\n";
              $html .= "  }\n";
              $html .= "  function ValidarInformacion()\n";
              $html .= "  {\n";
              $html .= "    xajax_ValidarInformacionPre('".$empresa."')\n";
              $html .= "  }\n";
              $html .= "</script>\n";
              $html .= ThemeAbrirTabla('ROTACION - PRODUCTOS');
              $html .= $ctl->RollOverFilas();
              $html .= "<center>\n";
              $html .= "<fieldset class=\"fieldset\" style=\"width:100%\">\n";
              $html .= "<form name=\"FormaRotacionLaboratorio\" id=\"FormaRotacionLaboratorio\" method=\"post\"  action=\"".$action['guardar']."\">\n";
              $html .= "  <table width=\"100%\" class=\"modulo_table_list\" border=\"0\"  align=\"center\">";
              $html .= "	  <tr  align=\"center\" class=\"formulacion_table_list\" >\n";
              $html .= "      <td rowspan=\"2\" width=\"10%\">CODIGO </td>\n";
              $html .= "      <td rowspan=\"2\" width=\"20%\">MOLECULAS </td>\n";
              $html .= "      <td  rowspan=\"2\"  width=\"35%\">MEDICAMENTOS</td>\n";
              $fecha_inicial=$FechaInicial_;
              $variableDias=31;
              for($k=0;$k<$meses;$k++)
              {

                    $FechaInicial2=$fecha_inicial;
                    $FechaProxima_="";
                    $FechaI_ = explode("-", $FechaInicial2);
                    $Fecha_= $FechaI_[2]."-".$FechaI_[1]."-".$FechaI_[0];
                    $fecha___=$FechaI_[2]."-".$FechaI_[1];
                    list($a,$m,$d)=split("-", $Fecha_);
                    $FechaProxima_ = date("Y-m-d",(mktime(0,0,0, $m,($d+$variableDias),$a)));
                    $FechaDatos= explode("-", $FechaFinal);
                    $Fecha_F= $FechaDatos[2]."-".$FechaDatos[1]."-".$FechaDatos[0];
                    $html .= "      <td  colspan=\"2\" align=\"center\">".$fecha___." </td>\n";
                    $FechaSoloMesAno = explode("-", $Fecha_);
                    $fecha__=$FechaSoloMesAno[0]."-".$FechaSoloMesAno[1];
                    $FechaPeriodo[$k]=$fecha__;
                    $FinInicial = explode("-", $FechaProxima_);
                    $FechaTrans= $FinInicial[2]."-".$FinInicial[1]."-".$FinInicial[0];
                    $fecha_inicial=$FechaTrans;
                    $FechaProxima_="";
          }
                $html .= "      <td rowspan=\"2\" width=\"5%\" align=\"center\">STOCK</td>\n";
                $html .= "      <td rowspan=\"2\" width=\"5%\" align=\"center\">%PROM</td>\n";
                $html .= "      <td rowspan=\"2\" width=\"5%\" align=\"center\">PEDIDO PROVEEDOR</td>\n";
                $html .= "<tr class=\"formulacion_table_list\"> ";
                for($j=0;$j<$meses;$j++)
                {
                     $html .= "      <td width=\"2%\">ING</td>\n";
                     $html .= "      <td width=\"2%\">EGR</td>\n";
                }
               $html .= "</tr> ";
               $html .= "</tr> ";
               $i=0;
               $contador=0;
            if(!empty($medicamentos_d))
            {
			
                      foreach($medicamentos_d as $key => $descripcion)
                      {
                                  $existencia=0;
			
                                  foreach($descripcion as $key2 => $dtl)
                                  {
					
                                          foreach($dtl as $key3 => $dtll)
                                          {
				
                                                  $est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
                                                  $bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
                                                  $html .= "  <tr  class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\"  >\n";
                                                  $html .= "      <td align=\"left\">".$key." </td>\n";
                                                  $html .= "      <td align=\"left\">".$key2." </td>\n";
                                                  $html .= "      <td align=\"left\">".$key3."</td>\n";
                                                  $sumaE=0;
                                                  for($j=0;$j<$meses;$j++)
                                                  {  
                                                    $sumaE=$sumaE +($dtll[$FechaPeriodo[$j]]['egreso']);
                                                    if($dtll[$FechaPeriodo[$j]]['periodo']!="")
                                                    {
                                                          $html .= "      <td align=\"left\">".FormatoValor($dtll[$FechaPeriodo[$j]]['ingreso'])."</td>\n";
                                                          $html .= "      <td align=\"left\">".FormatoValor($dtll[$FechaPeriodo[$j]]['egreso'])."</td>\n";
                                                          $existencia=$dtll[$FechaPeriodo[$j]]['existencia'];
                                                    }
                                                    else
                                                    {
													  $existencia=$dtll['']['existencia'];
                                                      $html .= "      <td align=\"left\" class=\"modulo_list_oscuro\" >--</td>\n";
                                                      $html .= "      <td align=\"left\" class=\"modulo_list_oscuro\" >--</td>\n";
                                                    }

                                                  }
                                              $gastoE=($sumaE/$meses);
                                              $porce=$gastoE/100;
                                              
                                            /*Formula*/
                                            $sugerido_pedido="";
                                            $porcentaje_pedido=0;

                                            $porcentaje_pedido=($gastoE/$existencia)*100;
                                                
                                            if($porcentaje_pedido>$porcentaje_rotacion)
                                            $sugerido_pedido = Abs($existencia-$gastoE);
                                          
                                            /*Fin Formula*/

                                            $html .= "      <td align=\"left\"  style=\"background:#D8BFD8\" >".FormatoValor($existencia)."</td>\n";
                                            $html .= "      <td align=\"left\"  >".FormatoValor($porce,4)."%</td>\n";
                                            $html .="        <td  aling=\"left\">";
                                            $html .="  <input  class=\"input-text\"  style=\"width:60%\" type=\"text\" name=\"txtcantidad".$i."\"  id=\"txtcantidad".$i."\"    value=\"".intval($sugerido_pedido)."\"  size=\"5\" onkeypress=\"return acceptNum(event)\" maxlength=\"12\" >";
                                            $html .=" <input type=\"checkbox\" name=\"".$i."\" id=\"".$i."\"   value=\"".$key."\" > </td>  ";       
                                            $html .= "<center>\n";
                                            $i++;
							
                                          }	
                                  }
			
                                  $contador++;
                                  $html .= "  </tr>\n";
								                 if($contador==15)
                                  {
                                          $fecha___ = "";
                                          $fecha__="";
                                          $FechaI_="";
                                          $Fecha_F="";
                                          $Fecha_="";
                                          $FechaSoloMesAno="";
                                          $FechaTrans_="";
                                          $FechaIniciall2="";
                                          $FechaProxima_ll="";
                                          $FechaDatos="";
                                          $FinInicial="";
                                          $html .= "	  <tr  align=\"center\" class=\"formulacion_table_list\" >\n";
                                          $html .= "      <td rowspan=\"2\" width=\"10%\">CODIGO </td>\n";
                                          $html .= "      <td rowspan=\"2\" width=\"20%\">MOLECULAS </td>\n";
                                          $html .= "      <td  rowspan=\"2\"  width=\"35%\">MEDICAMENTOS</td>\n";
                                                  
                              
                                          if($FechaInicial_!=$fechai)
                                          {
                                              $FechaInicial_=$fechai;
                                          }
                                        
                                            $variableDias=31;
                                            for($k=0;$k<$meses;$k++)
                                            {
                                                
                                              $FechaIniciall2=$FechaInicial_;
                                              $FechaProxima_ll="";
                                              $FechaI_ = explode("-", $FechaIniciall2);
                                              $Fecha_= $FechaI_[2]."-".$FechaI_[1]."-".$FechaI_[0];
                                                $fecha___=$FechaI_[2]."-".$FechaI_[1];
                                              list($a,$m,$d)=split("-", $Fecha_);
                                              $FechaProxima_ll = date("Y-m-d",(mktime(0,0,0, $m,($d+$variableDias),$a)));
                                              $FechaDatos= explode("-", $FechaFinal);
                                              $Fecha_F= $FechaDatos[2]."-".$FechaDatos[1]."-".$FechaDatos[0];
                                              
                                              $html .= "      <td  colspan=\"2\" align=\"center\">".$fecha___." </td>\n";
                                                                 
                                              $FechaSoloMesAno = explode("-", $Fecha_);
                                              $fecha__=$FechaSoloMesAno[0]."-".$FechaSoloMesAno[1];
                                              /*$FechaPeriodo[$k]=$fecha__;*/
                                              
                                              $FinInicial = explode("-", $FechaProxima_ll);
                                              $FechaTrans_= $FinInicial[2]."-".$FinInicial[1]."-".$FinInicial[0];
                                              $FechaInicial_=$FechaTrans_;
                                              $FechaProxima_ll="";
                                                      }
                                                      $html .= "      <td rowspan=\"2\" width=\"5%\" align=\"center\">STOCK</td>\n";
                                            $html .= "      <td rowspan=\"2\" width=\"5%\" align=\"center\">%PROM</td>\n";
                                            $html .= "      <td rowspan=\"2\" width=\"5%\" align=\"center\">PEDIDO PROVEEDOR</td>\n";
                                            
                                            $html .= "<tr class=\"formulacion_table_list\"> ";
                                            for($j=0;$j<$meses;$j++)
                                            {
                                               $html .= "      <td width=\"2%\">ING</td>\n";
                                               $html .= "      <td width=\"2%\">EGR</td>\n";
                                                      }
                                             $html .= "</tr> ";
                                             $html .= "</tr> ";
                                             $contador =0;
                                  }
				
		
			    	          }
				
                $html .= "</table>";
                $html .= "<br>";
                $html .= "  <table width=\"10%\"  border=\"0\"  align=\"right\">";
                $html .= "	  <tr  align=\"center\">\n";
                $html .= "      <td >\n";
                $html .= "		  <input type=\"hidden\" name=\"cantidad_registros\" id=\"cantidad_registros\" value=\"".$i."\">";
                $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"SOLICITAR\" >\n";
                $html .= "      </td>\n";
                $html .= "  </tr>\n";
                $html .= "	</table>\n";
			
          }else
          {
            $html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA GENERAR LA ROTACION</center><br>\n";
          }
				$html .= "</fieldset><br>\n";
				$html .= "</center>\n";
				$html .= " <br>";
				$html .= "<table align=\"center\" width=\"50%\">\n";
				$html .= "  <tr>\n";
				$html .= "    <td align=\"center\">\n";
				$html .= "      <a href=\"".$action['volver']."\"  class=\"label_error\">\n";
				$html .= "        VOLVER\n";
				$html .= "      </a>\n";
				$html .= "    </td>\n";
				$html .= "  </tr>\n";
				$html .= "</table>\n";
				$html .= ThemeCerrarTabla();
				return $html;
      }
	 /*   Funcion que  Contiene la Forma  de Generar  por insumos de la empresa
		* @param array $action vector que contiene los link de la aplicacion
			* @return string $html retorna la cadena con el codigo html de la pagina
		*/
      function FormaGenerarRotacionInsumos($action,$empresa,$meses,$fechai,$fechaf,$FechaInicial_,$FechaFinal,$insumos,$t_insumos)
      {
            $porcentaje_rotacion=ModuloGetVar("","","rotacion_porcentaje");
            $ctl = AutoCarga::factory("ClaseUtil"); 
            $html .= $ctl->IsDate("-");
            $html .= $ctl->AcceptDate("-");
            $html  = $ctl->IsNumeric();
            $html .= $ctl->AcceptNum(false);
            $html .= ThemeAbrirTabla('ROTACION DE PRODUCTOS-INSUMOS');
            $html .= $ctl->RollOverFilas(); 
            $mdl = AutoCarga::factory("RotacionGerenciaSQL", "classes", "app", "RotacionGerencia");

            $html .= "<form name=\"FormaRotacion_x_Laboratorio\" id=\"FormaRotacion_x_Laboratorio\" method=\"post\"  action=\"".$action['consulta']."\">\n";
            $html .= "			<table  class=\"modulo_table_list\"  width=\"30%\" align=\"CENTER\" border=\"0\"   >";
            $html .= "         <tr align=\"left\" class=\"formulacion_table_list\">\n";
            $html .= "		          	<td align=\"left\"   ><b>GRUPOS INSUMOS</b></td>\n";
            $html .= "			            <td  align=\"left\" class=\"modulo_list_claro\" >\n";
            $html .= "					            <select name=\"grupo_id\" class=\"select\" >\n";
            $html .= "                        	<option value = '-1'>--  SELECCIONE --</option>\n";
            $csk = "";
            foreach($t_insumos as $indice => $valor)
            {
                if($valor['grupo_id']==$request['grupo_id'])
                $sel = "selected";
                else   $sel = "";
                $html .= "  <option value=\"".$valor['grupo_id']."\" ".$sel.">".$valor['descripcion']."</option>\n";
            }
              $html .= "                </select>\n";
              $html .= "					  	  </td>\n";
              $html .= "	 </tr>\n";
              $html .= "</table>\n";
              $html .= "  <table width=\"90%\"  border=\"0\"  align=\"center\">";
              $html .= "	  <tr  align=\"center\">\n";
              $html .= "      <td >\n";

              $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"CONSULTAR\" >\n";
              $html .= "      </td>\n";
              $html .= "  </tr>\n";
              $html .= "	</table><br>\n";
              $html .= "	</form> ";
              $html .= "<center>\n";
              $html .= "<fieldset class=\"fieldset\" style=\"width:100%\">\n";
              $html .= "<form name=\"FormaRotacionLaboratorio\" id=\"FormaRotacionLaboratorio\" method=\"post\"  action=\"".$action['guardar']."\">\n";
              $html .= "  <table width=\"100%\" class=\"modulo_table_list\" border=\"0\"  align=\"center\">";
              $html .= "	  <tr  align=\"center\" class=\"formulacion_table_list\" >\n";
              $html .= "      <td rowspan=\"2\" width=\"10%\">CODIGO </td>\n";
              $html .= "      <td  rowspan=\"2\"  width=\"35%\">INSUMOS</td>\n";

              $fecha_inicial=$FechaInicial_;
              $variableDias=31;
              for($k=0;$k<$meses;$k++)
             {
                  $FechaInicial2=$fecha_inicial;
                  $FechaProxima_="";
                  $FechaI_ = explode("-", $FechaInicial2);
                  $Fecha_= $FechaI_[2]."-".$FechaI_[1]."-".$FechaI_[0];
                  $fecha___=$FechaI_[2]."-".$FechaI_[1];
                  list($a,$m,$d)=split("-", $Fecha_);
                  $FechaProxima_ = date("Y-m-d",(mktime(0,0,0, $m,($d+$variableDias),$a)));
                  $FechaDatos= explode("-", $FechaFinal);
                  $Fecha_F= $FechaDatos[2]."-".$FechaDatos[1]."-".$FechaDatos[0];

                  $html .= "      <td  colspan=\"2\" align=\"center\">".$fecha___." </td>\n";

                  $FechaSoloMesAno = explode("-", $Fecha_);
                  $fecha__=$FechaSoloMesAno[0]."-".$FechaSoloMesAno[1];
                  $FechaPeriodo[$k]=$fecha__;

                  $FinInicial = explode("-", $FechaProxima_);
                  $FechaTrans= $FinInicial[2]."-".$FinInicial[1]."-".$FinInicial[0];
                  $fecha_inicial=$FechaTrans;
                  $FechaProxima_="";
             }
            $html .= "      <td rowspan=\"2\" width=\"5%\" align=\"center\">STOCK</td>\n";
            $html .= "      <td rowspan=\"2\" width=\"5%\" align=\"center\">%PROM</td>\n";
            $html .= "      <td rowspan=\"2\" width=\"5%\" align=\"center\">PEDIDO PROVEEDOR</td>\n";
            $html .= "<tr class=\"formulacion_table_list\"> ";
            for($j=0;$j<$meses;$j++)
            {
                    $html .= "      <td width=\"2%\">ING</td>\n";
                    $html .= "      <td width=\"2%\">EGR</td>\n";
             }
                 $html .= "</tr> ";
                 $html .= "</tr> ";
                 $i=0;
                $contador=0;
                if(!empty($insumos))
                {
                        foreach($insumos as $key => $descripcion)
                      {
                          $existencia=0;
                                    foreach($descripcion as $key2 => $dtl)
                                    {
                                              $est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
                                              $bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
                                              $html .= "  <tr  class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\"  >\n";
                                              $html .= "      <td align=\"left\">".$key." </td>\n";
                                              $html .= "      <td align=\"left\">".$key2."</td>\n";
                                              $sumaE=0;
                                              for($j=0;$j<$meses;$j++)
                                              {  
                                                  $sumaE=$sumaE +($dtl[$FechaPeriodo[$j]]['egreso']);
                                                  if($dtl[$FechaPeriodo[$j]]['periodo']!="")
                                                  {
                                                    $html .= "      <td align=\"left\">".FormatoValor($dtl[$FechaPeriodo[$j]]['ingreso'])."</td>\n";
                                                    $html .= "      <td align=\"left\">".FormatoValor($dtl[$FechaPeriodo[$j]]['egreso'])."</td>\n";
                                                    $existencia=$dtl[$FechaPeriodo[$j]]['existencia'];
                                                  }
                                                  else
                                                  {
                                                    $existencia=$dtl['']['existencia'];
													$html .= "      <td align=\"left\" class=\"modulo_list_oscuro\" >--</td>\n";
                                                    $html .= "      <td align=\"left\" class=\"modulo_list_oscuro\" >--</td>\n";
                                                  }

                                             }
                                          $gastoE=($sumaE/$meses);
                                          $porce=$gastoE/100;
                                          $sugerido_pedido="";
                                          $porcentaje_pedido=0;
                                          $porcentaje_pedido=($gastoE/$existencia)*100;

                                          if($porcentaje_pedido>$porcentaje_rotacion)
                                          $sugerido_pedido = Abs($existencia-$gastoE);
                                 
                                        $html .= "      <td align=\"left\"  style=\"background:#D8BFD8\" >".FormatoValor($existencia)."</td>\n";
                                        $html .= "      <td align=\"left\"  >".FormatoValor($porce,4)."%</td>\n";
                                        $html .="        <td  aling=\"left\">";
                                        $html .="  <input  class=\"input-text\"  style=\"width:60%\" type=\"text\" name=\"txtcantidad".$i."\"  id=\"txtcantidad".$i."\"    value=\"".intval($sugerido_pedido)."\"  size=\"5\" onkeypress=\"return acceptNum(event)\" maxlength=\"12\" >";
                                        $html .=" <input type=\"checkbox\" name=\"".$i."\" id=\"".$i."\"   value=\"".$key."\" > </td>  ";       
                                        $html .= "<center>\n";
                                        $i++;
                                    
                                    }
			
                                $contador++;
                                $html .= "  </tr>\n";
                              
                                if($contador==15)
                               {
                                      $fecha___ = "";
                                      $fecha__="";
                                      $FechaI_="";
                                      $Fecha_F="";
                                      $Fecha_="";
                                      $FechaSoloMesAno="";
                                      $FechaTrans_="";
                                      $FechaIniciall2="";
                                      $FechaProxima_ll="";
                                      $FechaDatos="";
                                      $FinInicial="";
                                      $html .= "	  <tr  align=\"center\" class=\"formulacion_table_list\" >\n";
                                      $html .= "      <td rowspan=\"2\" width=\"10%\">CODIGO </td>\n";
                                      $html .= "      <td  rowspan=\"2\"  width=\"35%\">INSUMOS</td>\n";
                                      if($FechaInicial_!=$fechai)
                                      {
                                            $FechaInicial_=$fechai;
                                      }
                                    $variableDias=31;
                                    for($k=0;$k<$meses;$k++)
                                    {
                                              $FechaIniciall2=$FechaInicial_;
                                              $FechaProxima_ll="";
                                              $FechaI_ = explode("-", $FechaIniciall2);
                                              $Fecha_= $FechaI_[2]."-".$FechaI_[1]."-".$FechaI_[0];
                                              $fecha___=$FechaI_[2]."-".$FechaI_[1];
                                              list($a,$m,$d)=split("-", $Fecha_);
                                              $FechaProxima_ll = date("Y-m-d",(mktime(0,0,0, $m,($d+$variableDias),$a)));
                                              $FechaDatos= explode("-", $FechaFinal);
                                              $Fecha_F= $FechaDatos[2]."-".$FechaDatos[1]."-".$FechaDatos[0];

                                              $html .= "      <td  colspan=\"2\" align=\"center\">".$fecha___." </td>\n";

                                              $FechaSoloMesAno = explode("-", $Fecha_);
                                              $fecha__=$FechaSoloMesAno[0]."-".$FechaSoloMesAno[1];

                                              $FinInicial = explode("-", $FechaProxima_ll);
                                              $FechaTrans_= $FinInicial[2]."-".$FinInicial[1]."-".$FinInicial[0];
                                              $FechaInicial_=$FechaTrans_;
                                              $FechaProxima_ll="";
                                    }
                                    
                                    $html .= "      <td rowspan=\"2\" width=\"5%\" align=\"center\">STOCK</td>\n";
                                    $html .= "      <td rowspan=\"2\" width=\"5%\" align=\"center\">%PROM</td>\n";
                                    $html .= "      <td rowspan=\"2\" width=\"5%\" align=\"center\">PEDIDO PROVEEDOR</td>\n";
                                    $html .= "<tr class=\"formulacion_table_list\"> ";
                                    for($j=0;$j<$meses;$j++)
                                    {
                                       $html .= "      <td width=\"2%\">ING</td>\n";
                                       $html .= "      <td width=\"2%\">EGR</td>\n";
                                     }
                                     $html .= "</tr> ";
                                     $html .= "</tr> ";
                                     $contador =0;
                                    }
					
                      }
                    $html .= "</table>";
                    $html .= "<br>";
                    $html .= "  <table width=\"10%\"  border=\"0\"  align=\"right\">";
                    $html .= "	  <tr  align=\"center\">\n";
                    $html .= "      <td >\n";
                    $html .= "		  <input type=\"hidden\" name=\"cantidad_registros\" id=\"cantidad_registros\" value=\"".$i."\">";
                    $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"SOLICITAR\" >\n";
                    $html .= "      </td>\n";
                    $html .= "  </tr>\n";
                    $html .= "	</table>\n";
                
          }else
        {
            $html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA GENERAR LA ROTACION</center><br>\n";
          }
            $html .= "</fieldset><br>\n";
            $html .= "</center>\n";
            $html .= " <br>";
            $html .= "<table align=\"center\" width=\"50%\">\n";
            $html .= "  <tr>\n";
            $html .= "    <td align=\"center\">\n";
            $html .= "      <a href=\"".$action['volver']."\"  class=\"label_error\">\n";
            $html .= "        VOLVER\n";
            $html .= "      </a>\n";
            $html .= "    </td>\n";
            $html .= "  </tr>\n";
            $html .= "</table>\n";
            $html .= ThemeCerrarTabla();
            return $html;
	    }
   /*   Funcion que  Contiene la Forma  de Generar rotacion por Molecula 
		*    @param array $action vector que contiene los link de la aplicacion
        * @return string $html retorna la cadena con el codigo html de la pagina
	*/
		function FormaGenerarRotacionMolecula($action,$empresa,$meses,$fechai,$fechaf,$FechaInicial_,$FechaFinal,$medicamentos_d,$FormatoFechaI,$FormatoFechaF,$bodega_id,$moleculas)
		{
                $porcentaje_rotacion=ModuloGetVar("","","rotacion_porcentaje");
                $ctl = AutoCarga::factory("ClaseUtil"); 
                $html .= $ctl->IsDate("-");
                $html .= $ctl->AcceptDate("-");
                $html  = $ctl->IsNumeric();
                $html .= $ctl->AcceptNum(false);
                $html .= ThemeAbrirTabla('ROTACION DE PRODUCTOS-MOLECULAS');
                $html .= $ctl->RollOverFilas(); 
                $mdl = AutoCarga::factory("RotacionGerenciaSQL", "classes", "app", "RotacionGerencia");
				
				
				
                $option = "<option value=\"-1\">--- TODOS ---</option>";
				foreach($moleculas as $llave => $value)
				{
				$option .= "<option value=\"".$value['cod_principio_activo']."\">".$value['descripcion']."</option>";
				}
				
				$html .= "<center>\n";
				$html .= "<form name=\"forma_consulta\" id=\"forma_consulta\" method=\"POST\" action=\"".$action['consulta']."\">";
				$html .= "	<fieldset class=\"fieldset\" style=\"width:60%\">\n";
				$html .= "		<legend>SELECCIONAR MOLECULA</legend>";
				$html .= "			<table class=\"modulo_table_list\" width=\"100%\">";
				$html .= "				<tr>";
				$html .= "					<td>";
				$html .= "						<select style=\"width:100%\" class=\"select\" name=\"cod_principio_activo\" id=\"cod_principio_activo\">";
				$html .= "							".$option;
				$html .= "						</select>";
				$html .= "					</td>";
				$html .= "					<td>";
				$html .= "						<input type=\"submit\" value=\"GENERAR ROTACION MOLÉCULA\" class=\"input-submit\">";
				$html .= "					</td>";
				$html .= "				</tr>";
				$html .= "			</table>";
				$html .= "		</fieldset>";
                $html .= "</center>\n";
				$html .= "</form>";
				
				$html .= "	<br>";
                $html .= "<center>\n";
                $html .= "<fieldset class=\"fieldset\" style=\"width:100%\">\n";
                $html .= "<form name=\"FormaRotacionLaboratorio\" id=\"FormaRotacionLaboratorio\" method=\"post\"  action=\"".$action['guardar']."\">\n";
                $html .= "  <table width=\"100%\" class=\"modulo_table_list\" border=\"0\"  align=\"center\">";
                $html .= "	  <tr  align=\"center\" class=\"formulacion_table_list\" >\n";
                $html .= "      <td rowspan=\"2\" width=\"5%\">MOLECULA </td>\n";
                $html .= "      <td  rowspan=\"2\"  width=\"30%\">DESCRIPCION MOLECULA</td>\n";
                $html .= " <input type=\"hidden\" name=\"fecha_inicio\" id=\"fecha_inicio\"   value=\"".$fechai."\" >";
                $html .= " <input type=\"hidden\" name=\"fecha_fin\" id=\"fecha_fin\"   value=\"".$fechaf."\" >";
                $fecha_inicial=$FechaInicial_;
                $variableDias=31;
                for($k=0;$k<$meses;$k++)
                {
                                
                              $FechaInicial2=$fecha_inicial;
                              $FechaProxima_="";
                              $FechaI_ = explode("-", $FechaInicial2);
                              $Fecha_= $FechaI_[2]."-".$FechaI_[1]."-".$FechaI_[0];
                                $fecha___=$FechaI_[2]."-".$FechaI_[1];
                              list($a,$m,$d)=split("-", $Fecha_);
                              $FechaProxima_ = date("Y-m-d",(mktime(0,0,0, $m,($d+$variableDias),$a)));
                              $FechaDatos= explode("-", $FechaFinal);
                              $Fecha_F= $FechaDatos[2]."-".$FechaDatos[1]."-".$FechaDatos[0];
                              
                              $html .= "      <td  colspan=\"2\" align=\"center\">".$fecha___." </td>\n";
                                                 
                              $FechaSoloMesAno = explode("-", $Fecha_);
                              $fecha__=$FechaSoloMesAno[0]."-".$FechaSoloMesAno[1];
                              $FechaPeriodo[$k]=$fecha__;
                              
                              $FinInicial = explode("-", $FechaProxima_);
                              $FechaTrans= $FinInicial[2]."-".$FinInicial[1]."-".$FinInicial[0];
                              $fecha_inicial=$FechaTrans;
                              $FechaProxima_="";
                    }
                  $html .= "      <td rowspan=\"2\" width=\"5%\" align=\"center\">STOCK</td>\n";
                  $html .= "      <td rowspan=\"2\" width=\"5%\" align=\"center\">%PROM</td>\n";
                  $html .= "      <td rowspan=\"2\" width=\"5%\" align=\"center\">PEDIDO PROVEEDOR</td>\n";
                  $html .= "<tr class=\"formulacion_table_list\"> ";
                  for($j=0;$j<$meses;$j++)
                {
            
                       $html .= "      <td width=\"2%\">ING</td>\n";
                       $html .= "      <td width=\"2%\">EGR</td>\n";
                 }
                 $html .= "</tr> ";
                 $html .= "</tr> ";
               
   	            $i=0;
                $contador=0;
                if(!empty($medicamentos_d))
              {
			
                    foreach($medicamentos_d as $key => $descripcion)
                    {
                              $existencia=0;
								
                            foreach($descripcion as $key2 => $dtl)
                            {
							/*print_r($dtl);
							print_r("<br>...........................<br>");*/
                                                  $est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
                                                  $bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
                                                  $html .= "  <tr  class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\"  >\n";
                                                  $html .= "      <td align=\"left\">".$key." </td>\n";
                                                  $html .= " <input type=\"hidden\" name=\"molecula".$i."\" id=\"molecula".$i."\"   value=\"".$key."\" >";
                                                  $html .= " <input type=\"hidden\" name=\"descripcion".$i."\" id=\"descripcion".$i."\"   value=\"".$key2."\" >";
                                                  
                                                  $html .= "      <td align=\"left\">".$key2." </td>\n";
							                                    $sumaE=0;
																/*print_r($dtl);
								print_r("<br>-------------------------- -------------------- ----------------<br>");*/
                                                  for($j=0;$j<$meses;$j++)
                                                  {  
                                                          $sumaE=$sumaE +($dtl[$FechaPeriodo[$j]]['egreso']);
                                                          if($dtl[$FechaPeriodo[$j]]['periodo']!="")
                                                          {
                                                                      
                                                                          $html .= "      	<td align=\"left\">".FormatoValor($dtl[$FechaPeriodo[$j]]['ingreso'])."";
																		  $html .= "			<input type=\"hidden\" name=\"sw_generico".$i."\" id=\"sw_generico".$i."\" value=\"".$dtl[$FechaPeriodo[$j]]['sw_generico']."\"> ";
																		  $html .= "			<input type=\"hidden\" name=\"unidad_id".$i."\" id=\"unidad_id".$i."\" value=\"".$dtl[$FechaPeriodo[$j]]['unidad_id']."\"> ";
																		  $html .= "		</td>\n";
                                                                          $html .= "      	<td align=\"left\">".FormatoValor($dtl[$FechaPeriodo[$j]]['egreso'])."</td>\n";
                                                                          $existencia=$dtl[$FechaPeriodo[$j]]['existencia'];
                                                          }
                                                          else
                                                            {
                                                                $existencia=$dtl['']['existencia'];
																$html .= "			<input type=\"hidden\" name=\"sw_generico".$i."\" id=\"sw_generico".$i."\" value=\"".$dtl['']['sw_generico']."\"> ";
																$html .= "			<input type=\"hidden\" name=\"unidad_id".$i."\" id=\"unidad_id".$i."\" value=\"".$dtl['']['unidad_id']."\"> ";
																$html .= "      <td align=\"left\" class=\"modulo_list_oscuro\" >--</td>\n";
                                                                $html .= "      <td align=\"left\" class=\"modulo_list_oscuro\" >--</td>\n";
                                                            }

                                                    }
                                    $gastoE=($sumaE/$meses);
                                    $porce=$gastoE/100;
                                    
                                    /*Formula*/
                                    $sugerido_pedido="";
                                    $porcentaje_pedido=0;

                                    $porcentaje_pedido=($gastoE/$existencia)*100;

                                    if($porcentaje_pedido>$porcentaje_rotacion)
                                    $sugerido_pedido = Abs($existencia-$gastoE);

                                    /*Fin Formula*/

                                  $html .= "      <td align=\"left\"  style=\"background:#D8BFD8\" >".FormatoValor($existencia)."</td>\n";
                                  $html .= "      <td align=\"left\"  >".FormatoValor($porce,4)."%</td>\n";
                                  $html .="        <td  aling=\"left\">";
                                  $html .="  <input  class=\"input-text\"  style=\"width:60%\" type=\"text\" name=\"txtcantidad".$i."\"  id=\"txtcantidad".$i."\"    value=\"".intval($sugerido_pedido)."\"  size=\"5\" onkeypress=\"return acceptNum(event)\" maxlength=\"12\" >";
                                  $html .=" <input type=\"checkbox\" name=\"".$i."\" id=\"".$i."\"   value=\"".$key."\" > </td>  ";       
                                  $html .= "<center>\n";
                                    $i++;
                            }
			
                                      $contador++;
                                      $html .= "  </tr>\n";
                                              
                                      /*reimprimir encabezado*/
                                      if($contador==15)
                                      {
                                                  $fecha___ = "";
                                                  $fecha__="";
                                                  $FechaI_="";
                                                  $Fecha_F="";
                                                  $Fecha_="";
                                                  $FechaSoloMesAno="";
                                                  $FechaTrans_="";
                                                  $FechaIniciall2="";
                                                  $FechaProxima_ll="";
                                                  $FechaDatos="";
                                                  $FinInicial="";
                                                  $html .= "	  <tr  align=\"center\" class=\"formulacion_table_list\" >\n";
                                                  $html .= "      <td rowspan=\"2\" width=\"10%\">MOLECULA </td>\n";
                                                  $html .= "      <td rowspan=\"2\" width=\"20%\">DESCRIPCION MOLECULA </td>\n";
                                                   
                                            
                                                  if($FechaInicial_!=$fechai)
                                                  {
                                                      $FechaInicial_=$fechai;
                                                  }
                                                
                                                    $variableDias=31;
                                                  for($k=0;$k<$meses;$k++)
                                                {
                                                
                                                            $FechaIniciall2=$FechaInicial_;
                                                            $FechaProxima_ll="";
                                                            $FechaI_ = explode("-", $FechaIniciall2);
                                                            $Fecha_= $FechaI_[2]."-".$FechaI_[1]."-".$FechaI_[0];
                                                            $fecha___=$FechaI_[2]."-".$FechaI_[1];
                                                            list($a,$m,$d)=split("-", $Fecha_);
                                                            $FechaProxima_ll = date("Y-m-d",(mktime(0,0,0, $m,($d+$variableDias),$a)));
                                                            $FechaDatos= explode("-", $FechaFinal);
                                                            $Fecha_F= $FechaDatos[2]."-".$FechaDatos[1]."-".$FechaDatos[0];

                                                            $html .= "      <td  colspan=\"2\" align=\"center\">".$fecha___." </td>\n";

                                                            $FechaSoloMesAno = explode("-", $Fecha_);
                                                            $fecha__=$FechaSoloMesAno[0]."-".$FechaSoloMesAno[1];


                                                            $FinInicial = explode("-", $FechaProxima_ll);
                                                            $FechaTrans_= $FinInicial[2]."-".$FinInicial[1]."-".$FinInicial[0];
                                                            $FechaInicial_=$FechaTrans_;
                                                            $FechaProxima_ll="";
                                                 }
                                             
                                                  $html .= "      <td rowspan=\"2\" width=\"5%\" align=\"center\">STOCK</td>\n";
                                                  $html .= "      <td rowspan=\"2\" width=\"5%\" align=\"center\">%PROM</td>\n";
                                                  $html .= "      <td rowspan=\"2\" width=\"5%\" align=\"center\">PEDIDO PROVEEDOR</td>\n";

                                                    $html .= "<tr class=\"formulacion_table_list\"> ";
                                                    for($j=0;$j<$meses;$j++)
                                                    {
                                                       $html .= "      <td width=\"2%\">ING</td>\n";
                                                       $html .= "      <td width=\"2%\">EGR</td>\n";
                                                     }
                                                     $html .= "</tr> ";
                                                     $html .= "</tr> ";
                                                     $contador =0;
                                      }
			
                  }
				
                $html .= "</table>";
                $html .= "<br>";
                $html .= "  <table width=\"10%\"  border=\"0\"  align=\"right\">";
                $html .= "	  <tr  align=\"center\">\n";
                $html .= "      <td >\n";
                $html .= "		  <input type=\"hidden\" name=\"cantidad_registros\" id=\"cantidad_registros\" value=\"".$i."\">";
                $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"SOLICITAR\" >\n";
                $html .= "      </td>\n";
                $html .= "  </tr>\n";
                $html .= "	</table>\n";
					
                }else
                {
                  $html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA GENERAR LA ROTACION</center><br>\n";
                }
                $html .= "</fieldset><br>\n";
                $html .= "</center>\n";
                $html .= " <br>";
                $html .= "<table align=\"center\" width=\"50%\">\n";
                $html .= "  <tr>\n";
                $html .= "    <td align=\"center\">\n";
                $html .= "      <a href=\"".$action['volver']."\"  class=\"label_error\">\n";
                $html .= "        VOLVER\n";
                $html .= "      </a>\n";
                $html .= "    </td>\n";
                $html .= "  </tr>\n";
                $html .= "</table>\n";
                $html .= ThemeCerrarTabla();
               return $html;
          }
	 /*   Funcion que  Contiene la Forma  de Generar rotacion General 
		* @param array $action vector que contiene los link de la aplicacion
        * @return string $html retorna la cadena con el codigo html de la pagina
	*/
	/*   	function FormaGenerarRotacionGeneral2($action,$empresa,$meses,$fechai,$fechaf,$Productos,$FechaInicial_,$FechaFinal,$Farmacias,$FormatoFechaI,$FormatoFechaF,$clase_id)
		{
				
				$ctl = AutoCarga::factory("ClaseUtil"); 
				$html .= $ctl->IsDate("-");
				$html .= $ctl->AcceptDate("-");
				
				$html  = $ctl->IsNumeric();
				$html .= $ctl->AcceptNum(false);
				$url=ModuloGetURL("app", "RotacionGerencia", "controller", "GenerarRotacionMedicamentos",array("fecha_inicio"=>$fechai,"fecha_final"=>$fechaf));
				$html .= "<script>\n";
				$html .= "  function EnviarDatos(num)\n";
				$html .= "  {\n";
				$html .= "    xajax_IngresarResgistros(xajax.getFormValues('envios'),num)\n";
				$html .= "  }\n";
				$html .= "  function UrlRotaciona()\n";
				$html .= "  {\n";
				$html .= "    window.location='".$url."';\n";
				$html .= "  }\n";
				$html .= "  function ValidarInformacion()\n";
				$html .= "  {\n";
				$html .= "    xajax_ValidarInformacionPre('".$empresa."')\n";
				$html .= "  }\n";
				$html .= "</script>\n";
				$html .= ThemeAbrirTabla('ROTACION - GENERAL');
				$html .= $ctl->RollOverFilas();
				
				$html .= "<center>\n";
				$html .= "<fieldset class=\"fieldset\" style=\"width:99%\">\n";
				$html .= "<form name=\"FormaRotacionLaboratorio\" id=\"FormaRotacionLaboratorio\" method=\"post\"  action=\"".$action['guardar']."\">\n";
				
				$html .= "  <table width=\"100%\" class=\"modulo_table_list\" border=\"0\"  align=\"left\">";
			   	$html .= "	  <tr  align=\"center\" class=\"formulacion_table_list\" >\n";
				$html .= "      <td rowspan=\"4\" width=\"2%\">CODIGO </td>\n";
				$html .= "      <td  rowspan=\"4\" >DESCRIPCION</td>\n";
							
				$html .= "	  <tr  align=\"center\" class=\"formulacion_table_list\" >\n";
				$mdl = AutoCarga::factory("RotacionGerenciaSQL", "classes", "app", "RotacionGerencia");
				$numF=count($Farmacias);
				$MesesD=$meses * 2;
			
				for($contador=0;$contador<$numF;$contador++)
				{
					$html .= "      <th  COLSPAN=\"".$MesesD."\">".$Farmacias[$contador]['razon_social']." \n";
				}
				 		  
				$html .= "      <td  COLSPAN=\"".$meses."\"  class=\"formulacion_table_list\"  rowspan=\"2\" align=\"center\">TOTAL EGRESOS</td>\n";
				$numF=count($Farmacias);
				$MesesDob=$meses * 2;
				for($contador=0;$contador<$numF;$contador++)
				{
				$html .= "      <th rowspan=\"2\" COLSPAN=\"".$MesesDob."\">STOCK \n";
			
				}

				$html .= "      <td   class=\"formulacion_table_list\" rowspan=\"2\" align=\"center\">STOCK</td>\n";
				//$html .= "      <td   class=\"formulacion_table_list\"  rowspan=\"2\" align=\"center\">STOCK</td>\n";
				$html .= "      <td   class=\"formulacion_table_list\"  rowspan=\"2\" align=\"center\">PROM</td>\n";
				$html .= "      <td   class=\"formulacion_table_list\"  rowspan=\"2\" align=\"center\">PEDIDO</td>\n";
			
				$html .= "	  <tr  align=\"center\" class=\"formulacion_table_list\" >\n";
                $nuF=count($Farmacias);				
			   $variableDias=31;
			 
			   $FechaInicial=$FechaInicial_;
			   
				for($cont=0;$cont<$nuF;$cont++)
				{ 
					$FechaInicial2=" ";
					$FechaInicial=$FechaInicial_;	
                    for($kon=0;$kon<$meses;$kon++)
					{
				   
				    
				        $FechaInicial2=$FechaInicial;
					
						$FechaProxima_="";
						$FechaI_ = explode("-", $FechaInicial2);
						$Fecha_= $FechaI_[2]."-".$FechaI_[1]."-".$FechaI_[0];
						list($a,$m,$d)=split("-", $Fecha_);
						$FechaProxima_ = date("Y-m-d",(mktime(0,0,0, $m,($d+$variableDias),$a)));
						$FechaDatos= explode("-", $FechaFinal);
						$Fecha_F= $FechaDatos[2]."-".$FechaDatos[1]."-".$FechaDatos[0];
            
						$html .= "      <td  colspan=\"2\">".$Fecha_." </td>\n";
                            
						$FechaSoloMesAno = explode("-", $Fecha_);
						$fecha__=$FechaSoloMesAno[0]."-".$FechaSoloMesAno[1];
						$FechaPeriodo[$kon]=$fecha__;
						
						$FinInicial = explode("-", $FechaProxima_);
						$FechaTrans= $FinInicial[2]."-".$FinInicial[1]."-".$FinInicial[0];
						$FechaInicial=$FechaTrans;
						$FechaProxima_="";
                  
					}
				}
				$html .= "	  <tr  title=\"producto\" align=\"center\" class=\"formulacion_table_list\" >\n";
				$nF=count($Farmacias);	
				$filas=$nF * $meses;
				$col=1; 
		
				for($con=0;$con<$filas;$con++)
				{  
                    for($ko=0;$ko<$col;$ko++)
				   {
				   			   
						$html .= "      <th >ING\n";
						$html .= "      <th  >EGR\n";
					
				   }
					   
				
				 }
			
			   $variableDias=31;
			    for($kc=0;$kc<$meses;$kc++)
				{
						$FechaInicial3=$FechaInicial_;
						$FechaProxima_="";
						$FechaI_ = explode("-", $FechaInicial3);
						$Fecha_= $FechaI_[2]."-".$FechaI_[1]."-".$FechaI_[0];
						list($a,$m,$d)=split("-", $Fecha_);
						$FechaProxima_ = date("Y-m-d",(mktime(0,0,0, $m,($d+$variableDias),$a)));
						$FechaDatos= explode("-", $FechaFinal);
						$Fecha_F= $FechaDatos[2]."-".$FechaDatos[1]."-".$FechaDatos[0];
            
						$html .= "      <td   class=\"formulacion_table_list\"  align=\"center\">".$Fecha_." </td>\n";
                      
          
						$FechaSoloMesAno = explode("-", $Fecha_);
						$fecha__=$FechaSoloMesAno[0]."-".$FechaSoloMesAno[1];
						$FechaPeriodo[$kc]=$fecha__;
            
						$FinInicial = explode("-", $FechaProxima_);
						$FechaTrans= $FinInicial[2]."-".$FinInicial[1]."-".$FinInicial[0];
						$FechaInicial_=$FechaTrans;
						$FechaProxima_="";
            	}
			
			       $numF=count($Farmacias);
				$MesesDo=$meses * 2;
		
				for($contador=0;$contador<$numF;$contador++)
				{				
					$html .= "      <th  COLSPAN=\"".$MesesDo."\">".$Farmacias[$contador]['razon_social']." \n";
				}
						
				$html .= "      <td   class=\"formulacion_table_list\"   align=\"center\">TOTAL FARMAC</td>\n";
				$html .= "      <td   class=\"formulacion_table_list\"  align=\"center\">BODEGA</td>\n";
				$html .= "      <td  class=\"formulacion_table_list\"   width=\"5%\" align=\"center\">%</td>\n";
				$html .= "      <td  class=\"formulacion_table_list\"   width=\"5%\" align=\"center\">PROVEEDOR</td>\n";
			if(!empty($Productos))
            {			
				 $numF=count($Farmacias);
				$pconta=0;
				 foreach($Productos as $key => $descripcion)
				{
				
						$est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
						$bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
                        $html .= "  <tr  title=\"".$descripcion['codigo_producto']." -".$descripcion['producto']."  \" class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\"  >\n";
                        $html .= "      <td   align=\"left\">".$descripcion['codigo_producto']."  </td>\n";
						$html .= "      <td align=\"left\">".$descripcion['producto']."  </td>\n";
						
						
							$filas=$numF * $meses;
					
							foreach($Farmacias as $viv => $det_F)
							{
								$detalle_productos=$mdl->Rotacion_General_x_farmacia($FormatoFechaI,$FormatoFechaF,$det_F['empresa_id'],$descripcion['codigo_producto']);
				 
							  for($q=0;$q<$meses;$q++)
								{      
										if(!empty($detalle_productos))
										{
										     
												foreach($detalle_productos as $dpf => $dpfd)
												{
												    $sumaE=$sumaE +($dpfd['egreso']);
													if($FechaPeriodo[$q]==$dpfd['fecha_registro'])
													{
														$arra[$FechaPeriodo[$q]]['egreso'] += $dpfd['egreso'];
														$html .= "      <td align=\"left\"><b>".FormatoValor($dpfd['ingreso'])."</b></td>\n";
														$html .= "      <td align=\"left\"><b>".FormatoValor($dpfd['egreso'])."</b></td>\n";	
													}
													else
													{
														$html .= "      <td align=\"left\" class=\"modulo_list_oscuro\" >--</td>\n";
														$html .= "      <td align=\"left\" class=\"modulo_list_oscuro\" >--</td>\n";
													}
																				
												}
										}
										else
											{
													$html .= "      <td align=\"left\" class=\"modulo_list_oscuro\" >--</td>\n";
													$html .= "      <td align=\"left\" class=\"modulo_list_oscuro\" >--</td>\n";
											}
							
								}
							}	
								$acomulador=0;
								for($r=0;$r<$meses;$r++)
								{      
										$arra[$FechaPeriodo[$r]]['egreso'];
										if(!empty($arra[$FechaPeriodo[$r]]))
										{
											$html .= "      <td  align=\"left\" ><b>".$arra[$FechaPeriodo[$r]]['egreso']."</b></td>\n";
											$acomulador=$acomulador + $arra[$FechaPeriodo[$r]]['egreso'];
										}
										else
										{
											$html .= "      <td align=\"left\" class=\"modulo_list_oscuro\" >--</td>\n";
										}
									$arra[$FechaPeriodo[$r]]="";	
								}
								
								$acomula=0;
							foreach($Farmacias as $viv => $det_Fa)
							{
								$stock_x_Farmacia=$mdl->Consulta_Total_Existencias($det_Fa['empresa_id'],$descripcion['codigo_producto']);
								if(!empty($stock_x_Farmacia))
								{
								    if(!empty($stock_x_Farmacia[0]['existencias']))
									{
										$html .= "      <td    COLSPAN=\"".$MesesDo."\" align=\"left\" ><b>".FormatoValor($stock_x_Farmacia[0]['existencias'])."</b></td>\n";
								        $acomula=$acomula + $stock_x_Farmacia[0]['existencias'];
									}else
									{
										$html .= "      <td  class=\"modulo_list_oscuro\"  COLSPAN=\"".$MesesDo."\" align=\"left\" ><b>--</b></td>\n";
									
									}
								
								}else
								{
										$html .= "      <td   class=\"modulo_list_oscuro\" COLSPAN=\"".$MesesDo."\" align=\"left\" ><b>--</b></td>\n";
								
								}
							}
							
							$html .= "      <td    align=\"left\" ><b>".$acomula."</b></td>\n";
                            $stock_x_Empresa=$mdl->Consulta_Total_Existencias($empresa,$descripcion['codigo_producto']);
                         	if(!empty($stock_x_Empresa))
								{
								    if(!empty($stock_x_Empresa[0]['existencias']))
									{
										$html .= "      <td    align=\"left\" ><b>".FormatoValor($stock_x_Empresa[0]['existencias'])."</b></td>\n";
								     
									}else
									    {
								    	  $html .= "      <td  class=\"modulo_list_oscuro\" align=\"left\" ><b>--</b></td>\n";
									    }
								    }else
									{
									$html .= "      <td   class=\"modulo_list_oscuro\" align=\"left\" ><b>--</b></td>\n";
									}
									$promedio_A=$acomulador/$meses;
									$pocent=$promedio_A/100;
												
									$html .= "      <td align=\"left\"  ><b>".FormatoValor($pocent,1)."%</b></td>\n";

									$html .="        <td  aling=\"left\">";
									$html .="  <input  class=\"input-text\"  style=\"width:60%\" type=\"text\" name=\"txtcantidad".$pconta."\"  id=\"txtcantidad".$pconta."\"    value=\"\"  size=\"5\" onkeypress=\"return acceptNum(event)\" maxlength=\"12\" >";
									$html .=" <input type=\"checkbox\" name=\"".$pconta."\" id=\"".$pconta."\"   value=\"".$descripcion['codigo_producto']."\" > </td>  ";       

									$pconta++;
						 
				}
					$html .= "</table>";
					$html .= "<br>";
					$html .= "  <table width=\"30%\"  border=\"0\"  align=\"right\">";
					$html .= "	  <tr  align=\"right\">\n";
					$html .= "      <td >\n";

					$html .= "		  <input type=\"hidden\" name=\"cantidad_registros\" id=\"cantidad_registros\" value=\"".$pconta."\">";
					$html .= "        <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\" >\n";
					$html .= "      </td>\n";
					$html .= "  </tr>\n";
					$html .= "	</table>\n";
			}else
			{
					$html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA GENERAR LA ROTACION</center><br>\n";
			}
				$html .= "</fieldset><br>\n";
				$html .= "</center>\n";
				$html .= " <br>";
				$html .= "<table align=\"center\" width=\"50%\">\n";
				$html .= "  <tr>\n";
				$html .= "    <td align=\"center\">\n";
				$html .= "      <a href=\"".$action['volver']."\"  class=\"label_error\">\n";
				$html .= "        VOLVER\n";
				$html .= "      </a>\n";
				$html .= "    </td>\n";
				$html .= "  </tr>\n";
				$html .= "</table>\n";
				$html .= ThemeCerrarTabla();
				return $html;
		}
*/
    /*   Funcion que  Contiene la Forma  de Generar rotacion General 
		* @param array $action vector que contiene los link de la aplicacion
        * @return string $html retorna la cadena con el codigo html de la pagina
	*/
		 	function FormaGenerarRotacionGeneral($action,$empresa,$meses,$fechai,$fechaf,$Productos,$FechaInicial_,$FechaFinal,$Farmacias,$FormatoFechaI,$FormatoFechaF,$clase_id,$bodega_id,$request)
		{
				$porcentaje_rotacion=ModuloGetVar("","","rotacion_porcentaje");
				$ctl = AutoCarga::factory("ClaseUtil"); 
				$html .= $ctl->IsDate("-");
				$html .= $ctl->AcceptDate("-");
				
				$html  = $ctl->IsNumeric();
				$html .= $ctl->AcceptNum(false);
				$url=ModuloGetURL("app", "RotacionGerencia", "controller", "GenerarRotacionMedicamentos",array("fecha_inicio"=>$fechai,"fecha_final"=>$fechaf));
				$html .= "<script>\n";
				$html .= "  function EnviarDatos(num)\n";
				$html .= "  {\n";
				$html .= "    xajax_IngresarResgistros(xajax.getFormValues('envios'),num)\n";
				$html .= "  }\n";
				$html .= "  function UrlRotaciona()\n";
				$html .= "  {\n";
				$html .= "    window.location='".$url."';\n";
				$html .= "  }\n";
				$html .= "  function ValidarInformacion()\n";
				$html .= "  {\n";
				$html .= "    xajax_ValidarInformacionPre('".$empresa."')\n";
				$html .= "  }\n";
				$html .= "</script>\n";
				$html .= ThemeAbrirTabla('ROTACION - GENERAL');
				$html .= $ctl->RollOverFilas();
				
				$html .= "<center>\n";
				$html .= "<fieldset class=\"fieldset\" style=\"width:99%\">\n";
				$html .= "<form name=\"FormaRotacionLaboratorio\" id=\"FormaRotacionLaboratorio\" method=\"post\"  action=\"".$action['guardar']."\">\n";
				
				$html .= "  <table width=\"100%\" class=\"modulo_table_list\" border=\"0\"  align=\"left\">";
			   	$html .= "	  <tr  align=\"center\" class=\"formulacion_table_list\" >\n";
				$html .= "      <td rowspan=\"4\" width=\"2%\">CODIGO </td>\n";
				$html .= "      <td  rowspan=\"4\" >DESCRIPCION</td>\n";
							
				$html .= "	  <tr  align=\"center\" class=\"formulacion_table_list\" >\n";
				$mdl = AutoCarga::factory("RotacionGerenciaSQL", "classes", "app", "RotacionGerencia");
				$numF=$request['total_registros'];
				
				$MesesD=($meses * 2 )+ 1;
			
				for($contador=0;$contador<$numF;$contador++)
				{
					if($request[$contador]!="")
					{
					
						$html .= "      <th  COLSPAN=\"".$MesesD."\">".$request['descripcion_'.$contador]." \n";
					}
				}
				 		  
				$html .= "      <td  COLSPAN=\"".$meses."\"  class=\"formulacion_table_list\"  rowspan=\"2\" align=\"center\">TOTAL EGRESOS</td>\n";
				$numF=$request['total_registros'];
			

				$html .= "      <td   class=\"formulacion_table_list\"  rowspan=\"2\" align=\"center\">STOCK </td>\n";
				$html .= "      <td   class=\"formulacion_table_list\"  rowspan=\"2\" align=\"center\">STOCK</td>\n";
				$html .= "      <td   class=\"formulacion_table_list\"  rowspan=\"2\" align=\"center\">PROM</td>\n";
				$html .= "      <td   class=\"formulacion_table_list\"  rowspan=\"2\" align=\"center\">PEDIDO</td>\n";
			
		    	$html .= "	  <tr  align=\"center\" class=\"formulacion_table_list\" >\n";
                $nuF=$request['total_registros'];				
			    $variableDias=31;
			 
			   $FechaInicial=$FechaInicial_;
			   
				for($cont=0;$cont<$nuF;$cont++)
				{ 
				
				   if($request[$cont]!="")
					{
					
					$FechaInicial2=" ";
					$FechaInicial=$FechaInicial_;	
                    for($kon=0;$kon<$meses;$kon++)
					{
				   
				    
				        $FechaInicial2=$FechaInicial;
					
						$FechaProxima_="";
						$FechaI_ = explode("-", $FechaInicial2);
						$Fecha_= $FechaI_[2]."-".$FechaI_[1]."-".$FechaI_[0];
							$fecha___=$FechaI_[2]."-".$FechaI_[1];
						list($a,$m,$d)=split("-", $Fecha_);
						$FechaProxima_ = date("Y-m-d",(mktime(0,0,0, $m,($d+$variableDias),$a)));
						$FechaDatos= explode("-", $FechaFinal);
						$Fecha_F= $FechaDatos[2]."-".$FechaDatos[1]."-".$FechaDatos[0];
            
						$html .= "      <td  colspan=\"2\">".$fecha___." </td>\n";
                            
						$FechaSoloMesAno = explode("-", $Fecha_);
						$fecha__=$FechaSoloMesAno[0]."-".$FechaSoloMesAno[1];
						$FechaPeriodo[$kon]=$fecha__;
						
						$FinInicial = explode("-", $FechaProxima_);
						$FechaTrans= $FinInicial[2]."-".$FinInicial[1]."-".$FinInicial[0];
						$FechaInicial=$FechaTrans;
						$FechaProxima_="";
                  
					}
						$html .= "      <td  ></td>\n";
				}
				}
				$html .= "	  <tr  title=\"producto\" align=\"center\" class=\"formulacion_table_list\" >\n";
				$nF=$request['total_registros'];
                $r_cantidad=0;
				for($konta=0;$konta<$nuF;$konta++)
				{
				    if($request[$konta]!="")
					{
					
				       $r_cantidad=$r_cantidad + 1;
					}
				
				}
		
				
				$filas=$r_cantidad * $meses;
				
				$pendejada = $meses * 2;
				$col=1; 
				$vir=0;
				for($con=0;$con<$filas;$con++)
				{  
               			   
						$html .= "      <th >ING\n";
						$html .= "      <th  >EGRE\n";
				
				if($vir==($meses-1))				
					{
					$html .= "      <th >STOCK\n";
					$vir =0;
					}
					else
						{
						$vir++;
						}
		
				 }
		
			   $variableDias=31;
			    for($kc=0;$kc<$meses;$kc++)
				{
						$FechaInicial3=$FechaInicial_;
						$FechaProxima_="";
						$FechaI_ = explode("-", $FechaInicial3);
						$Fecha_= $FechaI_[2]."-".$FechaI_[1]."-".$FechaI_[0];
						$fecha___=$FechaI_[2]."-".$FechaI_[1];
						list($a,$m,$d)=split("-", $Fecha_);
						$FechaProxima_ = date("Y-m-d",(mktime(0,0,0, $m,($d+$variableDias),$a)));
						$FechaDatos= explode("-", $FechaFinal);
						$Fecha_F= $FechaDatos[2]."-".$FechaDatos[1]."-".$FechaDatos[0];
            
						$html .= "      <td   class=\"formulacion_table_list\"  align=\"center\">".$fecha___." </td>\n";
                      
          
						$FechaSoloMesAno = explode("-", $Fecha_);
						$fecha__=$FechaSoloMesAno[0]."-".$FechaSoloMesAno[1];
						$FechaPeriodo[$kc]=$fecha__;
            
						$FinInicial = explode("-", $FechaProxima_);
						$FechaTrans= $FinInicial[2]."-".$FinInicial[1]."-".$FinInicial[0];
						$FechaInicial_=$FechaTrans;
						$FechaProxima_="";
            	}
			
			
				$MesesDo=$meses * 2;
		
			
           		$html .= "      <td   class=\"formulacion_table_list\"   align=\"center\">TOTAL FARMAC</td>\n";
				$html .= "      <td   class=\"formulacion_table_list\"  align=\"center\">BODEGA</td>\n";
				$html .= "      <td  class=\"formulacion_table_list\"   width=\"5%\" align=\"center\">%</td>\n";
				$html .= "      <td  class=\"formulacion_table_list\"   width=\"5%\" align=\"center\">PROVEEDOR</td>\n";
				$farmacias=$request['total_registros'];
				 print_r($farmacias);
				if(!empty($Productos))
				{	
			  	$pconta=0;
			
				
				foreach($Productos as $key => $descripcion)
				{
				
						$est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
						$bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
                        $html .= "  <tr  title=\"".$descripcion['codigo_producto']." -".$descripcion['producto']."  \" class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\"  >\n";
                        $html .= "      <td   align=\"left\">".$descripcion['codigo_producto']."  </td>\n";
						$html .= "      <td align=\"left\">".$descripcion['producto']."  </td>\n";
						
						$filas=$r_cantidad * $meses;
					
					    for($tcon=0;$tcon<$farmacias;$tcon++)
						{
						
						      print_r("/entro");
						    if($request[$tcon]!="")
							{
							          		
									$detalle_productos=$mdl->Rotacion_General_x_farmacia($FormatoFechaI,$FormatoFechaF,$request[$tcon],$descripcion['codigo_producto']);
							       
                                   print_r("//");
            

								   print_r($detalle_productos);
								   
								        print_r("//");
							}
						
						
						
						}
						
						/*foreach($farmacias as $viv => $det_F)
							{
								$detalle_productos=$mdl->Rotacion_General_x_farmacia($FormatoFechaI,$FormatoFechaF,$det_F['empresa_id'],$descripcion['codigo_producto']);
				 
							  for($q=0;$q<$meses;$q++)
								{      
										if(!empty($detalle_productos))
										{
										     
												foreach($detalle_productos as $dpf => $dpfd)
												{
												    $sumaE=$sumaE +($dpfd['egreso']);
													if($FechaPeriodo[$q]==$dpfd['fecha_registro'])
													{
														$arra[$FechaPeriodo[$q]]['egreso'] += $dpfd['egreso'];
														$html .= "      <td align=\"left\"><b>".FormatoValor($dpfd['ingreso'])."</b></td>\n";
														$html .= "      <td align=\"left\"><b>".FormatoValor($dpfd['egreso'])."</b></td>\n";	
													}
													else
													{
														$html .= "      <td align=\"left\" class=\"modulo_list_oscuro\" >--</td>\n";
														$html .= "      <td align=\"left\" class=\"modulo_list_oscuro\" >--</td>\n";
													}
													
													
												}
										}
										else
											{
													$html .= "      <td align=\"left\" class=\"modulo_list_oscuro\" >--</td>\n";
													$html .= "      <td align=\"left\" class=\"modulo_list_oscuro\" >--</td>\n";
											}
							    /*if(($meses-1)==$q)
								{
								$html .= "      <th  >s\n";
								}*/
							//	}
								/*
								Stock por Farmacia
								*/
								//$acomula=0;
						/*	$stock_x_Empresa=$mdl->Consulta_Total_Existencias($det_F['empresa_id'],$descripcion['codigo_producto']);
                         	if(!empty($stock_x_Empresa))
								{
								    if(!empty($stock_x_Empresa[0]['existencias']))
									{
										$html .= "      <td    style=\"background:#D8BFD8\"   align=\"left\" ><b>".FormatoValor($stock_x_Empresa[0]['existencias'])."</b></td>\n";
								//         $acomula=$acomula + $stock_x_Empresa[0]['existencias'];
									}else
									    {
								    	  $html .= "      <td style=\"background:#E6E6FA\"  class=\"modulo_list_oscuro\" align=\"left\" ><b>--</b></td>\n";
									    }
								 }else
									{
									$html .= "      <td style=\"background:#E6E6FA\"  class=\"modulo_list_oscuro\" align=\"left\" ><b>--</b></td>\n";
									}
								
							}	
								$acomulador=0;
								for($r=0;$r<$meses;$r++)
								{      
										$arra[$FechaPeriodo[$r]]['egreso'];
										if(!empty($arra[$FechaPeriodo[$r]]))
										{
											$html .= "      <td  align=\"left\" ><b>".$arra[$FechaPeriodo[$r]]['egreso']."</b></td>\n";
											$acomulador=$acomulador + $arra[$FechaPeriodo[$r]]['egreso'];
										}
										else
										{
											$html .= "      <td align=\"left\" class=\"modulo_list_oscuro\" >--</td>\n";
										}
									$arra[$FechaPeriodo[$r]]="";	
								}
								
								$acomula=0;
							foreach($Farmacias as $viv => $det_Fa)
							{
								$stock_x_Farmacia=$mdl->Consulta_Total_Existencias($det_Fa['empresa_id'],$descripcion['codigo_producto']);
								$acomula=$acomula + $stock_x_Farmacia[0]['existencias'];
								
							}
							
							$html .= "      <td    align=\"left\" ><b>".$acomula."</b></td>\n";
                            $stock_x_Empresa=$mdl->Consulta_Total_Existencias($empresa,$descripcion['codigo_producto'],$bodega_id);
                         	if(!empty($stock_x_Empresa))
								{
								    if(!empty($stock_x_Empresa[0]['existencias']))
									{
										$html .= "      <td    align=\"left\" ><b>".FormatoValor($stock_x_Empresa[0]['existencias'])."</b></td>\n";
								     
									}else
									    {
								    	  $html .= "      <td  class=\"modulo_list_oscuro\" align=\"left\" ><b>--</b></td>\n";
									    }
								    }else
									{
									$html .= "      <td   class=\"modulo_list_oscuro\" align=\"left\" ><b>--</b></td>\n";
									}
									$promedio_A=$acomulador/$meses;
									$pocent=$promedio_A/100;
												
									$html .= "      <td align=\"left\"  ><b>".FormatoValor($pocent,1)."%</b></td>\n";

									$html .="        <td  aling=\"left\">";
									$html .="  <input  class=\"input-text\"  style=\"width:60%\" type=\"text\" name=\"txtcantidad".$pconta."\"  id=\"txtcantidad".$pconta."\"    value=\"\"  size=\"5\" onkeypress=\"return acceptNum(event)\" maxlength=\"12\" >";
									$html .=" <input type=\"checkbox\" name=\"".$pconta."\" id=\"".$pconta."\"   value=\"".$descripcion['codigo_producto']."\" > </td>  ";       

									$pconta++;*/
						 
				}
					$html .= "</table>";
					$html .= "<br>";
					$html .= "  <table width=\"30%\"  border=\"0\"  align=\"right\">";
					$html .= "	  <tr  align=\"right\">\n";
					$html .= "      <td >\n";

					$html .= "		  <input type=\"hidden\" name=\"cantidad_registros\" id=\"cantidad_registros\" value=\"".$pconta."\">";
					$html .= "        <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\" >\n";
					$html .= "      </td>\n";
					$html .= "  </tr>\n";
					$html .= "	</table>\n";
			}else
			{
					$html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA GENERAR LA ROTACION</center><br>\n";
			}
				$html .= "</fieldset><br>\n";
				$html .= "</center>\n";
				$html .= " <br>";
				$html .= "<table align=\"center\" width=\"50%\">\n";
				$html .= "  <tr>\n";
				$html .= "    <td align=\"center\">\n";
				$html .= "      <a href=\"".$action['volver']."\"  class=\"label_error\">\n";
				$html .= "        VOLVER\n";
				$html .= "      </a>\n";
				$html .= "    </td>\n";
				$html .= "  </tr>\n";
				$html .= "</table>\n";
				$html .= ThemeCerrarTabla();
				return $html;
		}
		
		/*   Funcion que  Contiene la Forma la rotacion del producto que tiene la molecula seleccionada
		     * @param array $action vector que contiene los link de la aplicacion
        * @return string $html retorna la cadena con el codigo html de la pagina
	*/
		function FormaRealizarSolicitud_Moleculas($action,$request,$datos_empresa,$datos)
		{
			$ctl = AutoCarga::factory("ClaseUtil"); 
			$html .= $ctl->IsDate("-");
			$html .= $ctl->AcceptDate("-");

			$html  .= $ctl->IsNumeric();
			$html .= $ctl->AcceptNum(false);
			$html .= ThemeAbrirTabla('ROTACION PRODUCTOS POR MOLECULA SELECCIONADA');

			$html .= "<form name=\"forma\" id=\"forma\" method=\"POST\" action=\"".$action['guardar']."\">";
			$html .= "  <table width=\"80%\" class=\"modulo_table_list\" border=\"0\"  align=\"center\">";
			$html .= "		<tr class=\"formulacion_table_list\">";
			$html .= "			<td>";
			$html .= "				COD";
			$html .= "			</td>";
			$html .= "			<td>";
			$html .= "				DESCRIPCION";
			$html .= "			</td>";
			$html .= "			<td>";
			$html .= "				CANTIDAD";
			$html .= "			</td>";
			$html .= "			<td>";
			$html .= "				OP";
			$html .= "			</td>";
			$html .= "		</tr>";
			$i=0;
			foreach($datos as $key=>$valor)
			{
			$html .= "	<tr class=\"modulo_list_claro\">";
			$html .= "		<td>";
			$html .= "			".$valor['cod_principio_activo'];
			$html .= " 				<input type=\"hidden\" name=\"molecula".$i."\" id=\"molecula".$i."\"   value=\"".$valor['cod_principio_activo']."\" >";
            $html .= " 				<input type=\"hidden\" name=\"descripcion".$i."\" id=\"descripcion".$i."\"   value=\"".$valor['molecula']."\" >";
            $html .= " 				<input type=\"hidden\" name=\"unidad_id".$i."\" id=\"unidad_id".$i."\"   value=\"".$valor['unidad_id']."\" >";
            $html .= " 				<input type=\"hidden\" name=\"sw_generico".$i."\" id=\"sw_generico".$i."\"   value=\"".$valor['sw_generico']."\" >";
			$html .= "		</td>";
			$html .= "		<td>";
			$html .= "			".$valor['molecula'];
			$html .= "		</td>";
			$html .= "		<td>";
			$html .= "			<input type=\"text\" class=\"input-text\" name=\"txtcantidad".$i."\" id=\"txtcantidad".$i."\" value=\"".$valor['cantidad']."\" >";
			$html .= "		</td>";
			$html .= "		<td align=\"center\">";
			$html .= "			<input type=\"checkbox\" class=\"input-checkbox\" name=\"".$i."\" id=\"".$i."\" value=\"".$valor['cod_principio_activo']."\">";
			$html .= "		</td>";
			$html .= "	</tr>";
			$i++;
			}
			$html .= "	<tr class=\"formulacion_table_list\">";
			$html .= "		<td colspan=\"4\">";
			$html .= "			<input type=\"hidden\" id=\"cantidad_registros\" name=\"cantidad_registros\" value=\"".$i."\">";
			$html .= "			<input type=\"submit\" value=\"MODIFICAR SOLICITUD\" class=\"input-submit\">";
			$html .= "		</td>";
			$html .= "	</tr>";
			$html .= "	</table>";
			$html .= "</form>";
			$html .= "<br>";
			$html .= "<table align=\"center\" width=\"50%\">\n";
			$html .= "  <tr>\n";
			$html .= "    <td align=\"center\">\n";
			$html .= "      <a href=\"".$action['volver']."\"  class=\"label_error\">\n";
			$html .= "        VOLVER\n";
			$html .= "      </a>\n";
			$html .= "    </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= ThemeCerrarTabla();
			return $html;
		}
    /**
    *
    */
    function FormaMostrarRotacion($action,$productos,$empresa,$meses,$fechai,$fechaf,$rotacion,$FechaInicial_,$FechaFinal,$Farmacias,$FormatoFechaI,$FormatoFechaF,$clase_id,$bodega_id,$request)
		{
      //$porcentaje_rotacion=ModuloGetVar("","","rotacion_porcentaje");
      $ctl = AutoCarga::factory("ClaseUtil"); 
      $html .= $ctl->AcceptNum(false);
      $url=ModuloGetURL("app", "RotacionGerencia", "controller", "GenerarRotacionMedicamentos",array("fecha_inicio"=>$fechai,"fecha_final"=>$fechaf));

      $html .= ThemeAbrirTabla('ROTACION - GENERAL');		//
      $farmac = sizeof($request['centros']);

      $celdas = sizeof($meses)+4;
      $MesesD = sizeof($meses)+1;
      
      $html .= "<script language='javascript'>\n";
      $html .= "  function ReporteXLS()\n";
      $html .= "  {\n";
      $html .= "    var width=\"400\"\n";
      $html .= "    var height=\"300\"\n";
      $html .= "    var winX=Math.round(screen.width/2)-(width/2);\n";
      $html .= "    var winY=Math.round(screen.height/2)-(height/2);\n";
      $html .= "    var nombre=\"Printer_Mananger\";\n";
      $html .= "    var str =\"width=\"+width+\",height=\"+height+\",left=\"+winX+\",top=\"+winY+\",resizable=no,status=no,scrollbars=yes,location=no\";\n";
      $html .= "    var url =\"".$action['generar_xls']."\";\n";
      $html .= "    window.open(url, nombre, str).focus();\n";
      $html .= "  }\n";
      $html .= "</script>\n\n";
      $html .= "<form name=\"FormaRotacionLaboratorio\" id=\"FormaRotacionLaboratorio\" method=\"post\"  action=\"".$action['guardar']."\">\n";
      $html .= "  <table width=\"".(300+$celdas*60)."px\"  border=\"0\">";
      $html .= "	  <tr  align=\"right\">\n";
      $html .= "      <td >\n";
      $html .= "		    <input type=\"hidden\" name=\"cantidad_registros\" id=\"cantidad_registros\" value=\"".$pconta."\">";
      $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\" >\n";
      $html .= "      </td>\n";
      $html .= "    </tr>\n";
      $html .= "	</table>\n";
	    $html .= "  <table width=\"".(300+$celdas*60)."px\" class=\"normal_10\" style=\"border:thin #FFFFFF\" rules=\"all\" align=\"left\">";
      $htm .= "	  <tr class=\"formulacion_table_list\" >\n";
      $htm .= "      <td rowspan=\"2\"><div style=\"width:300px\">PRODUCTO</div></td>\n";
      
			//foreach($request['centros'] as $key => $dtl)
			//	$html .= "      <td colspan=\"".$MesesD."\" ><div style=\"width:".($MesesD*60)."\">".$request['descripcion'][$key]."</div></td> \n";
       		  
			$htm .= "      <td  colspan=\"".sizeof($meses)."\"><div style=\"width:".(60*sizeof($meses))."px\">TOTAL EGRESOS</div></td>\n";
      $htm .= "      <td  ><div style=\"width:60px\">STOCK</div></td>\n";
      $htm .= "      <td  ><div style=\"width:60px\">STOCK</div></td>\n";
      $htm .= "      <td  ><div style=\"width:60px\">PROM</div></td>\n";
      $htm .= "      <td  ><div style=\"width:60px\">PEDIDO</div></td>\n";
      $htm .= "    </tr>\n";
			$htm .= "	  <tr class=\"formulacion_table_list\" >\n";
      
      //$nuF = sizeof($request['centros']);				
			//$variableDias = 31;
			//$FechaInicial = $FechaInicial_;
			   
      //foreach($request['centros'] as $key => $dtl)
      //{
      //  foreach($meses as $k1 => $df)
			//    $html .= "      <td><div style=\"width:60px\">EGR<br>".$df."</div></td>\n";
			//  $html .= "      <td><div style=\"width:60px\">STOCK</div></td>\n";
      //}
      
      foreach($meses as $k1 => $df)
        $htm .= "      <td><div style=\"width:60px\">".$df."</div></td>\n";
      
      $htm .= "      <td><div style=\"width:60px\">TOTAL FARMAC</div></td>\n";
      $htm .= "      <td><div style=\"width:60px\">BODEGA</div></td>\n";
      $htm .= "      <td><div style=\"width:60px\">%</div></td>\n";
      $htm .= "      <td><div style=\"width:60px\">PROVEE</div></td>\n";
			$htm .= "    </tr>\n";

      $MesesDo=sizeof($meses) * 2;
      //$html .= " <br>\n";
      
      $scp = "";
			if(!empty($productos))
			{	
        foreach($productos as $key => $dtl)
				{
          $est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
          //$bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";
          //$html .= "  <table class=\"normal_10\" width=\"".(300+$celdas*60)."px\" style=\"border:thin #FFFFFF\" rules=\"all\">\n";
          if($i %15 == 0)
            $html .= $htm;
          $i++;
          $html .= "    <tr class=\"".$est."\">\n";
          $html .= "      <td>\n";
          $html .= "        <div style=\"width:300px\">\n";
          $html .= "          <b>".$dtl['codigo_producto']."</b> ".trim($dtl['descripcion_producto'])."\n";
          $html .= "        </div>\n";
          $html .= "      </td>\n";
          $totalEg = array();
          $existencias = 0;
          foreach($request['centros'] as $k2 => $dt)
          {
            foreach($meses as $k1 => $df)
            {
              //$html .= "      <td align=\"right\"><div style=\"width:60px\">".FormatoValor($rotacion[$k2][$df][$dtl['codigo_producto']]['cnt_ingreso']*1)."</div></td>\n";
              //$html .= "      <td align=\"right\"><div style=\"width:60px\">".FormatoValor($rotacion[$k2][$df][$dtl['codigo_producto']]['cnt_egreso']*1)."</div></td>\n";
              
              //$totalEg[$k1]['I'] += $rotacion[$k2][$df][$dtl['codigo_producto']]['cnt_ingreso'];
              $totalEg[$k1] += $rotacion[$k2][$df][$dtl['codigo_producto']]['cnt_egreso'];
            }
            //$html .= "      <td align=\"right\"><div style=\"width:60px\">".FormatoValor($rotacion[$k2][$df][$dtl['codigo_producto']]['existencia']*1)."</div></td>\n";
            $existencias += $rotacion[$k2][$df][$dtl['codigo_producto']]['existencia'];
          }
          foreach($meses as $k1 => $df)
            $html .= "      <td align=\"right\"><div style=\"width:60px\">".FormatoValor($totalEg[$k1]*1)."</div></td>\n";
          
          $html .= "      <td align=\"right\"><div style=\"width:60px\">".FormatoValor($existencias)."</div></td>\n";
          $html .= "      <td align=\"right\"><div style=\"width:60px\">".FormatoValor($dtl['existencia'])."</div></td>\n";
          /*$html .= "      <td align=\"right\"><div style=\"width:60px\"></div></td>\n";*/
          $html .= "      <td align=\"right\"><div style=\"width:60px\">".FormatoValor(($totalEg[$k1]/sizeof($meses))/100)."</div></td>\n";
          $html .= "      <td><div style=\"width:60px\"><input class=\"input-text\" style=\"width:100%\" type=\"text\" name=\"productos[".$dtl['codigo_producto']."]\" id=\"".$dtl['codigo_producto']."\" value=\"\" onkeypress=\"return acceptNum(event)\" maxlength=\"12\"></div></td>";
          $html .= "    </tr>\n";
          //$html .= "  </table>\n";  
				}
		 	  $html .= "  </table>\n"; 
        $html .= "<br>";
        $html .= "  <table width=\"".(300+$celdas*60)."px\">";
        $html .= "	  <tr  align=\"right\">\n";
        $html .= "      <td >\n";
        $html .= "		  <input type=\"hidden\" name=\"cantidad_registros\" id=\"cantidad_registros\" value=\"".$pconta."\">";
        $html .= "        <input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"GUARDAR\" >\n";
        $html .= "      </td>\n";
        $html .= "  </tr>\n";
        $html .= "	</table>\n";
        $scp = "ReporteXLS();";
			}
      else
			{
				$html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA GENERAR LA ROTACION</center><br>\n";
			}
      
      $html .= " <br>";
      $html .= "<table align=\"center\" width=\"50%\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\">\n";
      $html .= "      <a href=\"".$action['volver']."\"  class=\"label_error\">\n";
      $html .= "        VOLVER\n";
      $html .= "      </a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      if($scp != "")
      {
        $html .= "<script>\n";
        $html .= "  ".$scp.";\n";
        $html .= "</script>\n";
      }
      $html .= ThemeCerrarTabla();
      return $html;
		}
    /**
    *
    */
    function FormaMostrarRotacionXLS($action,$productos,$empresa,$meses,$fechai,$fechaf,$rotacion,$FechaInicial_,$FechaFinal,$Farmacias,$FormatoFechaI,$FormatoFechaF,$clase_id,$bodega_id,$request)
		{
      $ctl = AutoCarga::factory("ClaseUtil"); 
      $html .= $ctl->AcceptNum(false);
      $url=ModuloGetURL("app", "RotacionGerencia", "controller", "GenerarRotacionMedicamentos",array("fecha_inicio"=>$fechai,"fecha_final"=>$fechaf));

      $farmac = sizeof($request['centros']);

      $celdas = $farmac*sizeof($meses) +$farmac+sizeof($meses)+4;
      $MesesD = sizeof($meses)+1;
      
	    $html .= "<table width=\"".(400+$celdas*60)."px\" border=\"1\" rules=\"all\" >";
      $html .= "<tr >";
      $html .= "<td rowspan=\"2\" width=\"100px\" >CODIGO</td>";
      $html .= "<td rowspan=\"2\" width=\"300px\" >PRODUCTO</td>";
      
			foreach($request['centros'] as $key => $dtl)
				$html .= "<td colspan=\"".$MesesD."\" style=\"width:".($MesesD*60)."\">".$request['descripcion'][$key]."</td>";
       		  
			$html .= "<td  colspan=\"".sizeof($meses)."\" style=\"width:".(60*sizeof($meses))."px\">TOTAL EGRESOS</td>";
      $html .= "<td style=\"width:60px\">STOCK</td>";
      $html .= "<td style=\"width:60px\">STOCK</td>";
      $html .= "<td style=\"width:60px\">PROM</td>";
      $html .= "<td style=\"width:60px\">PEDIDO</td>";
      $html .= "</tr>\n";
			$html .= "<tr class=\"formulacion_table_list\">";
			   
      foreach($request['centros'] as $key => $dtl)
      {
        foreach($meses as $k1 => $df)
			    $html .= "<td style=\"width:60px\">EGR<br>".$df."</td>";
			  $html .= "<td style=\"width:60px\">STOCK</td>";
      }
      
      foreach($meses as $k1 => $df)
        $html .= "<td style=\"width:60px\">".$df."</td>";
      
      $html .= "<td style=\"width:60px\">TOTAL FARMAC</td>";
      $html .= "<td style=\"width:60px\">BODEGA</td>";
      $html .= "<td style=\"width:60px\">%</td>";
      $html .= "<td style=\"width:60px\">PROVEE</td>";
			$html .= "</tr>";
 			
      $MesesDo=sizeof($meses) * 2;
    	
      foreach($productos as $key => $dtl)
      {
        $html .= "<tr>";
        $html .= "<td style=\"width:100px\">";
        $html .= "<b>".$dtl['codigo_producto']."</b>";
        $html .= "</td>";        
        $html .= "<td style=\"width:300px\">";
        $html .= "".trim($dtl['descripcion_producto'])."";
        $html .= "</td>";
        $totalEg = array();
        $existencias = 0;
        foreach($request['centros'] as $k2 => $dt)
        {
          foreach($meses as $k1 => $df)
          {
            $html .= "<td align=\"right\" style=\"width:60px\">".FormatoValor($rotacion[$k2][$df][$dtl['codigo_producto']]['cnt_egreso']*1)."</td>";
            $totalEg[$k1] += $rotacion[$k2][$df][$dtl['codigo_producto']]['cnt_egreso'];
          }
          $html .= "<td align=\"right\" style=\"width:60px\">".FormatoValor($rotacion[$k2][$df][$dtl['codigo_producto']]['existencia']*1)."</td>";
          $existencias += $rotacion[$k2][$df][$dtl['codigo_producto']]['existencia'];
        }
        foreach($meses as $k1 => $df)
          $html .= "<td align=\"right\" style=\"width:60px\">".($totalEg[$k1]*1)."</td>";
        
        $html .= "<td align=\"right\"><div style=\"width:60px\">".FormatoValor($existencias)."</td>";
        $html .= "<td align=\"right\"><div style=\"width:60px\">".FormatoValor($dtl['existencia'])."</td>";
        $html .= "<td align=\"right\" style=\"width:60px\">".FormatoValor(($totalEg[$k1]/sizeof($meses))/100)."</td>";
        $html .= "<td style=\"width:60px\"></td>";
        $html .= "</tr>\n";
      }
    
      $html .= "  </table>\n";  
      return $html;
		}
	}
?>