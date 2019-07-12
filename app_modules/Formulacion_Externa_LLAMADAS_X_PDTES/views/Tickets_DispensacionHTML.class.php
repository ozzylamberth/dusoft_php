<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: Tickets_DispensacionHTML.class.php,v 1.2 2010/04/09 19:48:52 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Vista: Tickets_DispensacionHTML
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.2 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
	class Tickets_DispensacionHTML	
	{
    /**
    * Constructor de la clase
    */
    function Tickets_DispensacionHTML(){}
    /**
    *
    * @return string
    */
    function Forma($action,$request,$lista,$Tipo_Id_paciente,$conteo, $pagina,$bodegas_doc_id)
    {
          $ctl = AutoCarga::factory("ClaseUtil");
       
          $html  = $ctl->LimpiarCampos();
          $html .= $ctl->RollOverFilas();
          $html .= $ctl->AcceptDate('/');
        $obje = AutoCarga::factory('Formulacion_ExternaSQL', '', 'app', 'Formulacion_Externa');
          $select = "<select style=\"width:40%\" name=\"buscador[tipo_id_paciente]\" id=\"tipo_id_paciente\" class=\"select\">";
          $select .= " <option value=\"\">--- TODOS ---</option>";
          foreach($Tipo_Id_paciente as $key=>$valor)
          {
                if($_REQUEST['buscador']['tipo_id_paciente']==$valor['tipo_id_paciente'])
                {
                    $selected =" selected ";
                }
              else
              {
                      $selected =" ";
              }
              $select .= "<option $selected value=\"".$valor['tipo_id_paciente']."\">".$valor['tipo_id_paciente']."-".$valor['descripcion']."</option>";
          }
          $select .= "</select>";
          $style = "style=\"border-top: 1px solid #aaaaaa;border-left: 1px solid #aaaaaa;border-right: 1px solid #aaaaaa;border-bottom: 1px solid #aaaaaa;margin: 0 0 1em 0;clear:both;color:#333;text-align:center\"";
        
          $html .= ThemeAbrirTabla('TICKETS DE DISPENSACION');
          $html .= "<form name=\"productos\" action=\"".$action['buscar']."\" method=\"post\">\n";
          $html .= "  <table width=\"65%\" align=\"center\">\n";
          $html .= "    <tr>\n";
          $html .= "      <td>\n";
          $html .= "	      <fieldset class=\"fieldset\">\n";
          $html .= "          <legend class=\"normal_10AN\">BUSCADOR AVANZADO</legend>\n";
          $html .= "		      <table width=\"100%\" class=\"label\" $style>\n";
          $html .= "            <tr>";
          $html .= "              <td class=\"formulacion_table_list\"\">No- FORMULA</td>\n";
          $html .= "                <td align=\"left\" ><input type=\"text\" name=\"buscador[formula_papel]\" id=\"formula_papel\" class=\"input-text\" value=\"".$request['formula_papel']."\" style=\"width:60%\"></td>\n";
          $html .= "            </tr>";
          $html .= "            <tr>";
          $html .= "              <td class=\"formulacion_table_list\">IDENTIFICACION DEL PACIENTE</td>\n";
          $html .= "                <td align=\"left\">".$select."<input type=\"text\" name=\"buscador[paciente_id]\" id=\"paciente_id\" class=\"input-text\" value=\"".$request['paciente_id']."\" style=\"width:60%\"></td>\n";
          $html .= "            </tr>";
          $html .= "            <tr>";
          $html .= "              <td class=\"formulacion_table_list\">NOMBRE DEL PACIENTE</td>\n";
          $html .= "                <td align=\"left\" ><input type=\"text\" name=\"buscador[nombre_paciente]\" id=\"nombre_paciente\" class=\"input-text\" value=\"".$request['nombre_paciente']."\" style=\"width:60%\"></td>\n";
          $html .= "            </tr>";
          $html .= "			      <tr>\n";
          
          if(!empty($bodegas_doc_id))
          {
              $html .= "				      <td class=\"label\" align=\"center\" colspan=\"3\">\n";
              $html .= "					      <input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
              $html .= "					      <input type=\"button\" class=\"input-submit\" name=\"limpiar\" value=\"Limpiar Campos\" onClick=\"LimpiarCampos(document.productos)\">\n";
              $html .= "				      </td>\n";
           }else
           {
                  $html .= "				      <td class=\"label_error\" align=\"center\" colspan=\"3\">\n";
                  $html .= "				      NO EXISTE UN DOCUMENTO PARAMETRIZADO PARA REALIZAR LA DISPENSACION </td>\n";


           }
         $html .= "			      </tr>\n";
          $html .= "		      </table>\n";
          $html .= "	      </fieldset>\n";
          $html .= "	    </td>\n";
          $html .= "	  </tr>\n";
          $html .= "	</table>\n";
          $html .= "</form>\n";
          if(!empty($lista))
          {
        
              $html .= "	<table align=\"center\" border=\"0\" width=\"90%\" class=\"modulo_table_list\">\n";
              $html .= "		<tr class=\"formulacion_table_list\" >\n";
              $html .= "			<td width=\"5%\">#FORMULA</td>\n";
              $html .= "			<td width=\"5%\">FECHA FORMULA</td>\n";
              $html .= "			<td width=\"15%\">PACIENTE</td>\n";
              $html .= "			<td width=\"4%\">TICKET DISPENSADOS</td>\n";
              $html .= "			<td width=\"4%\">  TICKET PENDIENTES</td>\n";
               $html .= "			<td width=\"2%\"> PENDIENTES</td>\n";
              
              $html .= "		</tr>\n";
              $reporte = new GetReports();
              foreach($lista as $k1 => $dtl)
              {
                        $mostrar = $reporte->GetJavaReport('app','Formulacion_Externa','MedicamentoDispensadosFormulacionExterna',
                        array("formula_id"=>$dtl['formula_id'],"paciente_id"=>$dtl['paciente_id'],"opc"=>"1"),
                        array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                        $funcion = $reporte->GetJavaFunction();

                        $mostrar_ = $reporte->GetJavaReport('app','Formulacion_Externa','MedicamentoPendienteFormulacionExterna',
                        array("formula_id"=>$dtl['formula_id']),
                        array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
                        $funcion2 = $reporte->GetJavaFunction();

                    	$pendientes=$obje->Medicamentos_Pendientes_Esm($dtl['formula_id']);
             
                    $est = ($est == 'modulo_list_oscuro')?'modulo_list_claro':'modulo_list_oscuro'; 
                    $bck = ($bck == "#CCCCCC")? "#DDDDDD":"#CCCCCC";

                    $html .= "		<tr class=\"".$est."\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
                    $html .= "			<td ><u><b>".$dtl['formula_papel']."</b></u></td>\n";
                    $html .= "			<td >".$dtl['fecha_formula']."</td>\n";
                    $html .= "			<td>(".$dtl['tipo_id_paciente']."-".$dtl['paciente_id'].")-".$dtl['nombre_paciente']."</td>\n";
                    $html .= "				<td align=\"center\" >\n";
                    $html .= "				".$mostrar."\n";
                    $html .= "					<a href=\"javascript:$funcion\" class=\"label_error\"  title=\"TICKET DE DISPENSADOS\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0' >\n";
                    $html .= "					</a></center>\n";
                    $html .= "			</td>\n";	
                    if(!empty($pendientes))
                    {
                          $html .= "				<td align=\"center\" >\n";
                          $html .= "				".$mostrar_."\n";
                          $html .= "					<a href=\"javascript:$funcion2\" class=\"label_error\"  title=\"PENDIENTES\"><img src=\"".GetThemePath()."/images/cargosin.png\" border='0' >\n";
                          $html .= "					</a></center>\n";
                          $html .= "			</td>\n";	

                          $html .= "      <td  align=\"center\" class=\"label_error\">\n";
                          $html .= "        <a href=\"".$action['pendiente'].URLRequest($dtl)."\">\n";
                          $html .= "          <img border=\"0\"  title=\"Pendientes\" src=\"".GetThemePath()."/images/pparamedin.png\">\n";
                          $html .= "        </a>\n";
						  //added 01082012
						  $html .= "        <a href=\"".$action['entregas_farmacia'].URLRequest($dtl)."\">\n";
						  $html .= "          <img border=\"0\"  title=\"Entrega en Farmacia:datos adicionales\" src=\"".GetThemePath()."/images/banco.png\">\n";
                          $html .= "        </a>\n";
						  //
                          $html .= "      </td>\n";
                    }else
                    {
                          $html .= "				<td align=\"center\" >\n";
                          $html .= "					<img src=\"".GetThemePath()."/images/cargos.png\" border='0' >\n";
                          $html .= "					</center>\n";
                          $html .= "			</td>\n";
                          
                          $html .= "      <td  align=\"center\" >\n";
                          $html .= "          <img border=\"0\"  title=\"Pendientes\" src=\"".GetThemePath()."/images/pparamed.png\">\n";
                          $html .= "      </td>\n";

                    
                    
                    }
                    }
                    $html .= "		</tr>\n";
        
                    $html .= "		</table>\n";
                    $html .= "		<br>\n";
                    $pgn = AutoCarga::factory("ClaseHTML");
                    $html .= "		".$pgn->ObtenerPaginado($conteo,$pagina,$action['paginador']);
      }
      else if(!empty($request))
      {
        $html .= "<center>\n";
        $html .= "  <label class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</label>\n";
        $html .= "</center>\n";
      }
      $html .= "	<table width=\"90%\" align=\"center\">\n";
			$html .= "		<tr><td align=\"center\">\n";
			$html .= "			<form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">\n";
			$html .= "				<input type=\"submit\" class=\"input-submit\" value=\"Volver\">\n";
			$html .= "			</form>\n";
			$html .= "		</td></tr>\n";
			$html .= "	</table>\n";
      $html .= ThemeCerrarTabla();

      return $html;
    }
         
         
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
      //Mostrar Span
	  $html .= "  function MostrarSpan()\n";
      $html .= "  { \n";
      $html .= "    try\n";
      $html .= "    {\n";
      $html .= "      e = xGetElementById('Contenedor');\n";
      $html .= "      e.style.display = \"\";\n";
      $html .= "      Iniciar();\n";
      $html .= "    }\n";
      $html .= "    catch(error){alert(\"vaya\"+error)}\n";
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
      //En ese espacio se visualiza la informacion extraida de la base de datos.
      $html .= "  </div>\n";
      $html .= "</div>\n";

 
      return $html;
    }    
 


    
    /* REGISTRO DE LLAMADAS AL PACIENTE POR PENDIENTES QUE SE ENTREGARAN EN FARMACIA */
	//function FormaPendientesEntregaFarmacia($action,$request,$datos,$paciente,$Cabecera_Formulacion,$Cabecera_Formulacion_AESM,$request,$Cabecera_Formulacion_AEM,$Datos_Fueza,$Datos_Ad,$ESM_pac,$opcion,$dix_r,$medi_form,$formula_id)                           
	function FormaPendientesEntregaFarmacia($action,$formula_id)                           
     {
            $ctl = AutoCarga::factory("ClaseUtil");
            //$obje = AutoCarga::factory("DispensacionESMSQL", "classes", "app", "DispensacionESM");
            $obje = AutoCarga::factory("Formulacion_ExternaSQL", "classes", "app", "Formulacion_Externa");
            $style = "style=\"border-top: 1px solid #aaaaaa;border-left: 1px solid #aaaaaa;border-right: 1px solid #aaaaaa;border-bottom: 1px solid #aaaaaa;margin: 0 0 1em 0;clear:both;color:#333;text-align:left\"";
            $html  = $ctl->RollOverFilas();


                $html .= " <script> \n";
                
                $html .= " function mOvr(src,clrOver)
                            {
                                    src.style.background = clrOver;
                            }
                            function mOut(src,clrIn)
                            {
                                    src.style.background = clrIn;
                            }";

                $html .= " function max(e){  ";
                $html .= "      tecla = (document.all) ? e.keyCode : e.which; ";
                $html .= "      if (tecla==8) return true;";
                $html .= "      if (tecla==13) return false;";
                $html .= " }";
                
                // $html .= " function recogerTeclaBus(evt) ";
                // $html .= " {";
                // $html .= "var keyCode = document.layers ? evt.which : document.all ? evt.keyCode : evt.keyCode;   ";
                // $html .= "var keyChar = String.fromCharCode(keyCode);";
                // $html .= "if(keyCode==13)";
                // $html .= "{   ";
                // $html .= "   xajax_BuscarProducto2(xajax.getFormValues('buscador'),'".$formula_id."',1); ";
                // $html .= "}";
                // $html .= " }   ";
				
                // $html .= "	function ValidarCantidad(campo,valor,cant_sol,capa)\n";
                // $html .= "	{\n";
                // $html .= "		document.getElementById(campo).style.background='';\n";
                // $html .= "		document.getElementById('error').innerHTML='';\n";
                // $html .= "		if(isNaN(valor) || parseFloat(valor) > parseFloat(cant_sol) || parseFloat(valor)<=0 || valor=='')\n";
                // $html .= "		{\n";
                // $html .= "			document.getElementById(campo).value='';\n";
                // $html .= "			document.getElementById(campo).style.background='#ff9595';\n";
                // $html .= "			document.getElementById('error').innerHTML='<center>CANTIDAD NO VALIDA</center>';\n";
                // $html .= "			document.getElementById(capa).style.display=\"none\"\n";
                // $html .= "		}\n";
                // $html .= "		else{\n";
                // $html .= "			document.getElementById(capa).style.display=\"\"\n";
                // $html .= "		}\n";
                // $html .= "	}\n";

                // $html .= "	function Recargar_informacion(bodega_otra)\n";
                // $html .= "	{\n";
                // $html .= " document.buscador.bodega.value=bodega_otra; ";
                // $html .= "  xajax_BuscarProducto2(xajax.getFormValues('buscador'),'".$formula_id."',1);  ";
                // $html .= " }\n";
                
                $html .= " function ValidarDatos(frm){\n";
                $html .= "    if(frm.nomcontacto.value==\"\")\n";
                $html .= "    {\n";
                $html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR EL NOMBRE DE LA PERSONA CON QUIEN HABLO TELEFONICAMENTE';\n";
                $html .= "      frm.nomcontacto.focus();";
                $html .= "      return;\n";
                $html .= "    }\n";
                $html .= "    if(frm.parentezcocontacto.value==\"\")\n";
                $html .= "    {\n";
                $html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR EL PARENTEZCO DE LA PERSONA CON EL PACIENTE';\n";
                $html .= "      frm.parentezcocontacto.focus();";
                $html .= "      return;\n";
                $html .= "    }\n";
                $html .= "    if(frm.observaciones.value==\"\")\n";
                $html .= "    {\n";
                $html .= "      document.getElementById('error').innerHTML = 'DEBE INGRESAR UN RESUMEN DE LA LLAMADA';\n";
                $html .= "      frm.observaciones.focus();";
                $html .= "      return;\n";
                $html .= "    }\n";
                $html .= "    frm.submit();\n";
                $html .= " }\n";
                
                $html .=" </script>\n";

                // $html.= " <script>" ;
                // $html.= " xajax_MostrarProductox2('".$formula_id."'); ";
                // $html.= " </script>" ;

                $html .= ThemeAbrirTabla('REGISTRO DE LLAMADAS A PACIENTES POR MEDICAMENTOS PENDIENTES');

                $html .= "<table border=\"0\" width=\"98%\" align=\"center\" >\n";
                $html .= "	<tr>\n";
                $html .= "		<td>\n";
                $html .= "			<fieldset class=\"fieldset\">\n";
                $html .= "				<legend class=\"normal_10AN\">INFORMACION DE LA FORMULA</legend>\n";
                $html .= "				<table width=\"100%\" cellspacing=\"2\">\n";
                $html .= "					<tr>\n";
                $html .= "						<td align=\"center\">\n";
                $html .= "							<table width=\"98%\" class=\"label\" $style>\n";

                $html .= "								<tr >\n";

                $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FECHA DE REGISTRO</td>\n";
                $html .= "									<td width=\"10%\" align=\"right\">".$Cabecera_Formulacion['fecha_registro']."\n";
                $html .= "									</td>\n";



                $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FECHA DE FORMULA</td>\n";
                $html .= "									<td width=\"10%\" align=\"right\">".$Cabecera_Formulacion['fecha_formula']."\n";
                $html .= "									</td>\n";


                $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >FORMULA No</td>\n";
                $html .= "									<td width=\"10%\" align=\"right\">".$Cabecera_Formulacion['formula_papel']."\n";
                $html .= "									</td>\n";


                $html .= "									<td  class=\"formulacion_table_list\" >HORA</td>\n";
                $html .= "									<td >".$Cabecera_Formulacion['hora_formula']."\n";
                $html .= "									</td> \n";

                $html .= "								</tr>\n";
                $html .= "							</table>\n";


                $html .= "							<table width=\"98%\" class=\"label\" $style>\n";
				
                $html .= "								<tr>\n";
                $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >TIPO FORMULA</td>\n";
                $html .= "									<td colspan=\"2\">".$Cabecera_Formulacion['descripcion_tipo_formula']."\n";
                $html .= "									</td>\n";
                $html .= "								</tr>\n";

                $html .= "								<tr>\n";
                // $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >TIPO EVENTO</td>\n";
                // $html .= "									<td colspan=\"2\">	".$Cabecera_Formulacion['descripcion_tipo_evento']."\n";
                // $html .= "									</td>\n";
                $html .= "								</tr>\n";


                $html .= "							</table>\n";

                $html .= "					<tr>\n";
                $html .= "						<td align=\"center\">\n";
                $html .= "							<table  width=\"98%\" class=\"label\" $style>\n";
                $html .= "								<tr>\n";
                $html .= "									<td style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">IDENTIFICACION</td>\n";
                $html .= "									<td colspan=\"3\">\n";
                $html .= "										".$request['tipo_id_paciente']." ".$request['paciente_id']."\n";
                $html .= "									</td>\n";
                $html .= "									<td  style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\">NOMBRE COMPLETO</td>\n";
                $html .= "									<td  > ".$Cabecera_Formulacion['nombre_paciente']."\n";

                $html .= "									</td>\n";

                $html .= "								</tr>\n";

                if($Cabecera_Formulacion['sexo_id']=='M')
                {
                 $sexo='MASCULINO';

                }else
                {
                $sexo='FEMENINO';

                }
                list($anio,$mes,$dias) = explode(":",$Cabecera_Formulacion['edad']);

                if($anio!=0)
                {

                 $edad_t='AÑOS';
                 $edad=$anio;
                }
                if($anio==0 and $mes!=0)
                {
                  $edad_t='MES';
                   $edad=$mes;
                }
                else
                {
                    if($anio==0 and $mes==0)
                    {
                        $edad_t='DIAS';
                        $edad=$dias;
                    }

                }	

                $html .= "								<tr>\n";
                $html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">EDAD</td>\n";
                $html .= "									<td >".$edad." &nbsp; $edad_t \n";
                $html .= "									</td>\n";
                $html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">SEXO</td>\n";
                $html .= "									<td align=\"left\">".$sexo."\n";
                $html .= "									</td>\n";
                $html .= "								</tr>\n";	
                //$html .= " <input type=\"hidden\" name=\"tipo_fuerza\" id=\"tipo_fuerza\" value=\"".$Datos_Fueza['tipo_fuerza_id']."\">";

                // if(empty($Datos_Fueza))
                // {

                    // $fuerza .= "									<td align=\"left\" class=\"label_error\"> NO TIENE UNA FUERZA ASOCIADA\n";
                    // $fuerza .= "									</td>\n";			
                    
                // }
                // else
                // {
                    // $fuerza .= "									<td>".$Datos_Fueza['descripcion']."\n";
                    // $fuerza .= "									</td>\n";			
                // }

                // $html .= "								<tr>\n";
                // $html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">FUERZA</td>\n";
                // $html .= "								".$fuerza;
                // $html .= "								</tr>\n";	

                $html .= "								<tr>\n";
                $html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">TIPO PLAN</td>\n";
                $html .= "									<td>".$Datos_Ad['tipo_plan']."\n";
                $html .= "									</td>\n";
                $html .= "									<td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">TIPO VINCULACION</td>\n";
                $html .= "									<td>".$Datos_Ad['vinculacion']."\n";
                $html .= "									</td>\n";
                $html .= "								</tr>\n";	

                // $html .= "								<tr>\n";
                // $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >ESTABLECIMIENTO DE SANIDAD MILITAR  </td>\n";
                // $html .= "									<td colspan=\"2\">\n";
                // $html .= "									".$ESM_pac['tipo_id_tercero']." ".$ESM_pac['tercero_id']."  &nbsp; &nbsp;".$ESM_pac['nombre_tercero']."\n";
                // $html .= "									</td>\n";
                // $html .= "								</tr>\n";

                if($opcion=='0')
                {

                        // $html .= "								<tr>\n";
                        // $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PUNTO DE ATENCION  </td>\n";
                        // $html .= "									<td colspan=\"2\">".$Cabecera_Formulacion['esm_tipo_id_tercero']."  &nbsp; ".$Cabecera_Formulacion['esm_tercero_id']."  &nbsp; ".$Cabecera_Formulacion['esm_atendio']."\n";
                        // $html .= "									</td>\n";
                        // $html .= "								</tr>\n";

                        $html .= "								<tr >\n";
                        $html .= "									<td width=\"50%\" colspan=\"2\" style=\"text-align:left;text-indent:8pt\" class=\"formulacion_table_list\" >PROFESIONAL</td>\n";
                        $html .= "									<td colspan=\"3\">".$Cabecera_Formulacion['tipo_id_tercero']."  &nbsp; ".$Cabecera_Formulacion['tercero_id']."  &nbsp;  ".$Cabecera_Formulacion['profesional']." (".$Cabecera_Formulacion['descripcion_profesional'].")\n";
                        $html .= "						            </td>\n";
                        $html .= "								</tr>\n";
                }
                        $html .= "							</table>\n";
                        $html .= "					<tr>\n";
                        $html .= "						<td align=\"center\">\n";
                        $html .= "						<td>\n";
                        $html .= "					</tr>\n";
                        $html .= "					<tr>\n";
                        $html .= "						<td align=\"center\">\n";
                        $html .= "						<td>\n";
                        $html .= "					</tr>\n";
                        $html .= "					<tr>\n";
                        $html .= "						<td align=\"center\">\n";
                        $html .= "							<table   width=\"98%\" class=\"label\" $style>\n";
                        $html .= "								<tr>\n";
                        $html .= "									<td  colspan=\"8\" style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\">MEDICAMENTOS SOLICITADOS</td>\n";
                        $html .= "								</tr>\n";
                        $html .= "								<tr>\n";
                        $html .= "									<td  colspan=\"2\"  style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\">CODIGO</td>\n";
                        $html .= "									<td   colspan=\"2\" style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\">MEDICAMENTO</td>\n";
                        $html .= "									<td   colspan=\"2\" style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\">CANTIDAD PENDIENTE</td>\n";
                        //*$html .= "									<td   colspan=\"2\" style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\">RESUELTO</td>\n";				
                        $html .= "									</td>\n";

                        $html .= "</tr>";
                        $est = " "; $back = " ";

                        $html .= "		<form name=\"formaPendientesResueltos\" id=\"formPendienteResuelto\" method=\"post\">\n";
                        for($i=0;$i<sizeof($medi_form);$i++)
                        {
                                    $html .= "  <tr   ".$est." onmouseout=mOut(this,\"#E6E6E6\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
                                    $html .= "   <td align=\"center\" colspan=\"2\">".$medi_form[$i]['codigo_medicamento']." </td>";
                                    $html .= "   <td align=\"center\" colspan=\"2\">".$medi_form[$i]['descripcion_prod']." </td>";
                                    $html .= "   <td colspan=\"2\" align=\"center\" width=\"23%\">".$medi_form[$i]['total']."</td>";
                                    
                                    $html .= "  </tr>";
                        }	
                        $html .= "		</form>";
                        $html .= "							</table>\n";
                        $html .= "					</tr>\n";
                        $html .= "					<tr>\n";
                        $html .= "						<td align=\"center\">\n";
                        $html .= "						</td>\n";
                        $html .= "					</tr>\n";
                        
                 
                        $html .= "      <tr>";
                        $html .= "          <td>";
                        
                        $html .="<div align=\"center\" class=\"label_error\" id='error'></div> ";
                        
                        $html .= "<form name=\"llamadapaciente\" id=\"llamadapaciente\" action=\"".$action['guardarllamada']."\" method=\"post\">\n";
                        $html .= "  <div id='error_doc' class='label_error' style=\"text-transform: uppercase; text-align:center;\"></div>\n";
                        $html .= "  <div id=\"ventana1\">\n";
                        $html .= "   <table width=\"98%\" align=\"center\" class=\"modulo_table_list\">\n";
                        $html .= "     <tr >\n";
                        $html .= "        <td class=\"formulacion_table_list\" colspan=\"6\">REGISTRO DE DATOS DE LA LLAMADA</td>";

                        $empresa = SessionGetVar("DatosEmpresaAF");
                        $html .= "          <input type=\"hidden\" id=\"orden_requisicion_id\" name=\"orden_requisicion_id\" value=\"".$Cabecera_Formulacion['formula_id']."\">\n";
                        $html .= "          <input type=\"hidden\" id=\"empresa_id\" name=\"empresa_id\" value=\"".$empresa['empresa_id']."\">\n";
                        $html .= "          <input type=\"hidden\" id=\"centro_utilidad\" name=\"centro_utilidad\" value=\"".$empresa['centro_utilidad']."\">\n";
                        $html .= "          <input type=\"hidden\" id=\"bodega\" name=\"bodega\" value=\"".$empresa['bodega']."\">\n";
                        $html .= "          <input type=\"hidden\" id=\"formula_id\" name=\"formula_id\" value=\"".$Cabecera_Formulacion['formula_id']."\">\n";

                        $html .= "     </tr>\n";
                        
                        $html .= "      <tr>\n";
                        $html .= "          <td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">NOMBRE CONTACTO</td>\n";
                        $html .= "          <td >\n";
                        $html .= "              <input type='text' name='nomcontacto' id='nomcontacto' class=\"input-text\" size='51' maxlength='50' value=''>\n";
                        $html .= "          </td>\n";
                        $html .= "          <td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">PARENTEZCO CON EL PACIENTE</td>\n";
                        $html .= "          <td >\n";
                        $html .= "              <input type='text' name='parentezcocontacto' id='parentezcocontacto' class=\"input-text\" size='26' maxlength='25' value=''>\n";
                        $html .= "          </td>\n";
                        $html .= "      </tr>\n";
                        
                        $html .= "      <tr>\n";
                        $html .= "          <td class=\"formulacion_table_list\" style=\"text-align:left;text-indent:8pt\">OBSERVACIONES</td>\n";
                        $html .= "          <td colspan=\"3\" align=\"left\" class=\"modulo_list_claro\">";
                        $html .= "              <textarea  onkeypress=\"return max(event)\" name=\"observaciones\" id=\"observaciones\" rows=\"2\" style=\"width:100%\"></textarea>\n";
                        $html .= "      </tr>\n";
                        
                        $html .= "      <tr>\n";
                        $html .= "          <td align='center' colspan=4>\n";
                        $html .= "              <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnGuardar\" value=\"GUARDAR\" onclick=\"ValidarDatos(document.llamadapaciente)\">\n";
                        $html .= "          </td>\n";
                        $html .= "      </tr>\n";

                        $html .= "   </table>";
                        $html .= "  </div>";
                        $html .= "</form>\n";
                        
                        $html .= "          </td>";
                        $html .= "      </tr>";

                        $html .= "  <tr>\n";
                        $html .= "      <td align=\"center\">\n";
                        $html .= "          <table width=\"98%\" class=\"label\" $style>\n";
                        $html .= "              <tr>\n";
                        $html .= "                  <td colspan=\"6\" style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\">HISTORIAL DE LLAMADAS</td>\n";
                        $html .= "              </tr>\n";
                        $html .= "		<tr>\n";
                        $html .= "                  <td style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\">ITEM</td>\n";
                        $html .= "                  <td style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\">NOMBRE CONTACTO</td>\n";
                        $html .= "                  <td style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\">PARENTEZCO CONTACTO</td>\n";
                        $html .= "                  <td style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\">OBSERVACION</td>\n";
                        $html .= "                  <td style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\">FECHA</td>\n";
                        $html .= "                  <td style=\"text-align:center;text-indent:8pt\" class=\"formulacion_table_list\">USUARIO</td>\n";
                        $html .= "                  </td>\n";
                        $html .= "              </tr>\n";
                       
                        $historial=$obje->Consultar_Historial_Llamadas($Cabecera_Formulacion['formula_id']);
                        
                        $html .= "  <form name=\"formaPendientesResueltos\" id=\"formPendienteResuelto\" method=\"post\">\n";
                        for($i=0;$i<sizeof($historial);$i++)
                        {
                            $html .= "  <tr   ".$est." onmouseout=mOut(this,\"#E6E6E6\"); onmouseover=\"mOvr(this,'#FFFFFF');\" >\n";
                            $html .= "  <td align=\"center\" >".$historial[$i]['llamada_id']." </td>";
                            $html .= "  <td align=\"center\" >".$historial[$i]['contacto_nombre']." </td>";
                            $html .= "  <td align=\"center\" >".$historial[$i]['contacto_parentezco']."</td>";
                            $html .= "  <td align=\"center\" ><textarea name='obs' id='obs' rows=\"2\" style=\"width:100%\" readOnly>".$historial[$i]['observacion']."</textarea></td>";
                            $html .= "  <td align=\"center\" >".$historial[$i]['fecha']." </td>";
                            $html .= "  <td align=\"center\" >".$historial[$i]['usuario']."</td>";

                            $html .= "  </tr>";
                        }	
                        $html .= "  </form>";
                        
                        $html .= "          </table>\n";
                        $html .= "      </td>\n";
                        $html .= "  </tr>\n";

                        $html .= "				</table>\n";
                        $html .= "			</fieldset>\n";
                        $html .= "		</td>\n";
                        $html .= "	</tr>\n";
                        
                        $html .= "</table>\n";
       
                $html.= " <br>";

                $html .= "<table align=\"center\">\n";
                $html .= "<br>";
                $html .= "  <tr>\n";
                $html .= "      <td align=\"center\" class=\"label_error\">\n";
                $html .= "        <a href=\"".$action['iraPendientes']"\">VOLVER</a>\n";
                $html .= "      </td>\n";
                $html .= "  </tr>\n";
                $html .= "</table>\n";

                $html .= ThemeCerrarTabla();
                return $html;
     }
                


 
  }
?>