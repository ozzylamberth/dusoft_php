<?php
  /******************************************************************************
  * $Id: AgregarCargosHTML.class.php,v 1.7 2010/11/29 14:05:53 hugo Exp $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.7 $ 
	* 
	* @autor
  ********************************************************************************/
  IncludeClass('AgregarCargos','','app','Cuentas');
  IncludeClass('app_Cuentas_user','','app','Cuentas');
  
	class AgregarCargosHTML
	{
		function AgregarCargosHTML(){}
		function SetStyle($campo)
		{
					if ($this->frmError[$campo] || $campo=="MensajeError"){
						if ($campo=="MensajeError"){
							return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
						}
						return ("label_error");
					}
				return ("label");
		}
		/**********************************************************************************
		* 
		* 
		* @return array 
		***********************************************************************************/
		function FormaAgregarCargos($EmpresaId,$CU,$PlanId,$Cuenta,$mensaje,$TipoId,$PacienteId,$Nivel,$Ingreso,$Fecha)
		{
			if($EmpresaId AND $CU)
			{
				SessionSetVar('Empresa',$EmpresaId);
				SessionSetVar('Cutilidad',$CU);
				SessionSetVar('Cuenta',$Cuenta);
				SessionSetVar('Plan',$PlanId);
				SessionSetVar('TipoId',$TipoId);
				SessionSetVar('PacienteId',$PacienteId);
				SessionSetVar('Nivel',$Nivel);
				SessionSetVar('Ingreso',$Ingreso);
				SessionSetVar('Fecha',$Fecha);
			}
			else
			{
				$EmpresaId = SessionGetVar('Empresa');
				$CU = SessionGetVar('Cutilidad');
				$Cuenta = SessionGetVar('Cuenta');
				$PlanId = SessionGetVar('Plan');
				$TipoId = SessionGetVar('TipoId');
				$PacienteId = SessionGetVar('PacienteId');
				$Nivel = SessionGetVar('Nivel');
				$Ingreso = SessionGetVar('Ingreso');
				$Fecha = SessionGetVar('Fecha');
			}
      
			UNSET($_SESSION['CUENTAS']['ADD_CARGOS']);
			UNSET($_SESSION['CUENTAS']['ADD_CARGOS_VARIABLES']);
			UNSET($_SESSION['TMP_DATOS']['Cuenta']);
      $VISTA='HTML';
      $html .= "<SCRIPT>";
			$html .= "	var cont = 0;"; 
      $html .= "    function Contar()\n";
      $html .= "    {\n";
      $html .= "       cont++;\n";
      $html .= "      // alert(document.getElementById('cantidad').value);\n";
      $html .= "    }\n";  
      $html .= "    function VerificarDatos()\n";
      $html .= "    {\n";
			$html .= "			cont--;";
      $html .= "        if(cont == 0){\n";
      $html .= "         	ELE = document.getElementById('cargosseleccionados'); \n";
      $html .= "          ELE.style.display=\"none\"; \n";
      $html .= "         	tmp = document.getElementById('guardar'); \n";
      $html .= "          tmp.style.display=\"none\"; \n";
      $html .= "        }\n"; 
      $html .= "    }\n";  
			$html .= "function chequeoTotal(frm,x){";
			$html .= "  if(x==true){";
			$html .= "    for(i=0;i<frm.elements.length;i++){";
			$html .= "      if(frm.elements[i].type=='checkbox'){";
			$html .= "        frm.elements[i].checked=true";
			$html .= "      }";
			$html .= "    }";
			$html .= "  }else{";
			$html .= "    for(i=0;i<frm.elements.length;i++){";
			$html .= "      if(frm.elements[i].type=='checkbox'){";
			$html .= "        frm.elements[i].checked=false";
			$html .= "      }";
			$html .= "    }";
			$html .= "  }";
			$html .= "}";
			$html .= "	function CargarPagina(href,valor) {\n";
			$html .= "		var url=href;\n";
			$html .= "		location.href=url+'&bodega='+valor;\n";
			$html .= "	}\n\n";
			$html .= "		function AsignarValorCargos(v,descripcion,sw_descripcion)\n";
			$html .= "		{\n";
			$html .= "			document.getElementById('codigo').value = v;\n";
			$html .= "			document.getElementById('descripcion').value = descripcion;\n";
			$html .= "			if(sw_descripcion == '1')\n";
			$html .= "			{\n";
			$html .= "			document.getElementById('lista_descripcion').style.display = '';\n";
			$html .= "			}else{\n";
			$html .= "			document.getElementById('lista').style.display = '';\n";
			$html .= "			}\n";
			$html .= "		}\n";
			$html .= "		function mOvr(src,clrOver)\n";
			$html .= "		{\n";
			$html .= "			src.style.background = clrOver;\n";
			$html .= "		}\n";
			$html .= "		function mOut(src,clrIn)\n";
			$html .= "		{\n";
			$html .= "			src.style.background = clrIn;\n";
			$html .= "		}\n";
			$html .= "  function Iniciar()\n";
			$html .= "  {\n";        
			$html .= "    document.getElementById('titulo').innerHTML = '<center>CAMBIAR COSTO Y CANTIDAD</center>';\n";
			$html .= "    document.getElementById('error').innerHTML = '';\n";                
			$html .= "    contenedor = 'd2Container';\n";
			$html .= "    titulo = 'titulo';\n";
			$html .= "    ele = xGetElementById('d2Container');\n";
			$html .= "    xResizeTo(ele,600, 'auto');\n";
			$html .= "    xMoveTo(ele, xClientWidth()/5, xScrollTop()+24);\n";
			$html .= "    ele = xGetElementById('titulo');\n";
			$html .= "    xResizeTo(ele,580, 20);\n";
			$html .= "    xMoveTo(ele, 0, 0);\n";
			$html .= "    xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$html .= "    ele = xGetElementById('cerrar');\n";
			$html .= "    xResizeTo(ele,20,20);\n";
			$html .= "    xMoveTo(ele,580, 0);\n";
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
			$html .= "  function MostrarSpan(Seccion)\n";
			$html .= "  { \n";
			$html .= "    e = xGetElementById(Seccion);\n";
			$html .= "    e.style.display = \"\";\n";
			$html .= "  }\n";
			$html .= "  function Cerrar()\n";
			$html .= "  { \n";
			$html .= "    e = xGetElementById('d2Container');\n";
			$html .= "    e.style.display = \"none\";\n";
			$html .= "  }\n";
			$html .= "  function MostrarVentana()\n";
			$html .= "  { \n";
			$html .= "    e = xGetElementById('d2Container');\n";
			$html .= "    e.style.display = \"block\";\n";
			$html .= "    e = xGetElementById('d2Contents');\n";
			$html .= "    e.style.display = \"block\";\n";
			$html .= "  }\n";			
			$html .= "</SCRIPT>";

			$ventana.= "  <div id='d2Container' class='d2Container' style=\"display:none\">\n";
			$ventana.= "  <div id='titulo' class='draggable' style=\" text-transform: uppercase;\"></div>\n";
			$ventana.= "  <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('d2Container')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$ventana.= "  <div id='error' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
			$ventana.= "  <div id='d2Contents'>\n";
			$ventana.= "  </div>";
			$ventana.= "  </div>";        
			$html   .=$ventana;

			//$html .= ThemeAbrirTabla('AGREGAR CARGOS A LA CUENTA');
			$Departamentos = $this->LlamaDepartamentos($EmpresaId,$CU); 
			//$accion = ModuloGetURL('app','EE_AdministracionMedicamentos','user','AgregarInsumos_A_Paciente',array("conteo"=>$_REQUEST['conteo'],"Of"=>$_REQUEST['Of'],"paso"=>$_REQUEST['paso'],"datos_estacion"=>$datos_estacion,"datosPaciente"=>$datosPaciente));
      
			$html.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$html .= $this->SetStyle("MensajeError");
			$html .="<tr class=\"modulo_table_list_claro\">";
			$html .="  <td align=\"center\" colspan=\"5\" class='label_error'>$mensaje</td>";
			$html .="</tr>";
			$html.="</table>";
			//FORMA DATOS AGREGADOS A CARGOS
			$html .="<form name=\"Formabuscargos\" method=\"post\">";
			$html .="<div id='cargosseleccionados' style=\"display:none\">";
			$html .="<table id=\"tablacargosseleccionados\" align=\"center\" border=\"0\" width=\"90%\" class=\"modulo_table_list\">";
			$html .="<tr class=\"modulo_table_list_title\">";
			$html .="  <td align=\"center\" colspan=\"7\">CARGOS SELECCIONADOS</td>";
			$html .="</tr>";
			$html .="<tr class='modulo_list_claro'>";
			$html .="  <td align=\"center\" width=\"15%\">Dpto</td>";
			$html .="  <td align=\"center\" width=\"13%\">Cargo</td>";
			$html .="  <td align=\"center\" width=\"50%\">Descripci&oacute;n</td>";
			$html .="  <td align=\"center\" width=\"3\">Cant</td>";
			$html .="  <td align=\"center\" width=\"15\">Precio</td>";
			$html .="  <td align=\"center\" width=\"3\">Total</td>";
			$html .="  <td align=\"center\" width=\"5%\">Elim</td>";
			$html .="</tr>";
			$html .="</table>";
			$html .="</div>";
			$html .="</form>";
			//FIN FORMA DATOS AGREGADOS A CARGOS 
 
			//BOTON GUARDAR
			$html .= "<br><table align=\"center\" width=\"40%\" border=\"0\">";
			$html .= "<tr>";

			$html .= "<td align=\"center\">";
			$html .= "<div id='guardar' style=\"display:none\">";
			$html .= "  <table align=\"center\" width=\"40%\" border=\"0\">";
			$html .= "  <tr>";
			$html .= "    <td align=\"center\">";
			$accion1 = ModuloGetURL('app','Cuentas','user','LlamaInsertarCargos',array('obj'=>&$obj,'EmpresaId'=>$EmpresaId,'CU'=>$CU,'PlanId'=>$PlanId,'Cuenta'=>$Cuenta));
			$html .= "     <form name=\"formainsert\" action=\"$accion1\" method=\"post\">";
			$html .= "       <input type=\"submit\" name=\"GUARDAR\" value=\"GUARDAR\" class=\"input-submit\">";
			$html .= "     </form>";
			$html .= "    </td>";
			$html .= "  </tr>";
			$html .= "  </table>";
			$html .= "</div>";
			$html .= "</td>";
			$html .= "</tr>";
			$html .= "</table>";
			//FIN BOTON GUARDAR
      
      $fechaIngresoEgreso= $this->LlamaFechasIngresoEgreso($Ingreso);
      $fechaActual = date("Y-m-d H:i:s");
      $fechaActualFComparar = strtotime($fechaActual);
      ///$fechaAlertaFComparar = strtotime($planempr[$i]['fecha_alerta_contrato']);
      $fechaIngresoFComparar = strtotime($fechaIngresoEgreso[0]['fecha_ingreso']);  
      
      $mensajeNoAdicionCargos = "";
      if($fechaActualFComparar<=$fechaIngresoFComparar)
        $mensajeNoAdicionCargos = "LA FECHA DE REGISTRO DEL CARGO NO PUEDE SER MENOR A LA FECHA DE INGRESO DEL PACIENTE.";
                    
      if(!empty($mensajeNoAdicionCargos))
      {
        $html .= "<div id='errorConsultarCargosFechas' style=\"display:block\">";
        $html .= "    <label class=\"label_error\" >".$mensajeNoAdicionCargos."</label>\n";    
        $html .= "</div>";      
      }
      
      if(empty($mensajeNoAdicionCargos))  
      {
        $html .= "<div id='errorConsultarCargosFechas' style=\"display:none\">";
        $html .= "</div>";      

        $html.="<form name=\"Formabuscar\" method=\"post\">";
        $html.="<br><table name=\"buscargos\" align=\"center\" border=\"0\" width=\"95%\" class=\"modulo_table_list_title\">";
        $html.="<tr class=\"modulo_table_list_title\">";
        $html.="  <td align=\"center\" colspan=\"5\">BUSCADOR CARGOS</td>";
        $html.="</tr>";
    
        $html.="<tr class=\"hc_table_submodulo_list_title\">";
        //$html.="<td width=\"5%\">DEPARTAMENTO</td>";
        $html.="<td width=\"10%\">DEPARTAMENTO:";
                  
        $html.="<select name=departamento class='select'>";
        for($i=0;$i<sizeof($Departamentos);$i++)
        {
              if($Departamentos[$i][departamento]==$_REQUEST['departamento'])
              {
                  $html.="<option value=".$Departamentos[$i][departamento]." selected>".$Departamentos[$i][descripcion]."</option>";
              }
              else
              {
                  $html.="<option value=".$Departamentos[$i][departamento].">".$Departamentos[$i][descripcion]."</option>";
              }
        }
        $html.="</select>";
        $html.="</td>";
        //PROFESIONALES
        //$html .= "                <td class=\"label\">PROFESIONAL: </td>";
        $html .= "                 <td class=\"label\">PROFESIONAL: <select name=\"MedInt\" id=\"MedInt\" class=\"select\">";
        $html .= "                     <option value=\"\">-------SELECCIONE-------</option>";
        $pro=$this->LlamaProfesionales();
        for($i=0; $i<sizeof($pro); $i++)
        {
                if($pro[$i][tipo_id_tercero]."||".$pro[$i][tercero_id]==$_REQUEST['MedInt'])
                {  $html .=" <option value=\"".$pro[$i][tipo_id_tercero]."||".$pro[$i][tercero_id]."\" selected>".$pro[$i][nombre]."</option>";  }
                else
                {  $html .=" <option value=\"".$pro[$i][tipo_id_tercero]."||".$pro[$i][tercero_id]."\">".$pro[$i][nombre]."</option>";  }
        }
        $html .= "                 </select></td>";
      //FECHA CARGO
        $html .= "<td class=\"".$this->SetStyle("FechaCargo")."\">FECHA CARGO: <input type=\"text\" name=\"FechaCargo\" id=\"FechaCargo\" value=\"".date("d/m/Y")."\" size=\"10\" class=\"input-text\" onFocus=\"this.select();\" onChange=\"validarFechaCargo('m.m')\"  onKeyUp=\"setDate(this,'DD/MM/YYYY','es')\" readonly>&nbsp;&nbsp;".ReturnOpenCalendario('Formabuscar','FechaCargo','/')."";
        $html .="</tr>";
        $html.="<tr class=\"hc_table_submodulo_list_title\">";
        $html .= "<td width=\"22%\" align='center'>C&oacute;digo";
        $html .= "<div>\n";
        //$html .= "  <input type='text' class='input-text' name = 'codigo' id=\"codigo\" size=\"20\" maxlength=\"30\" onkeyup=\"xajax_reqObtenerDatosCargos(document.getElementById('codigo').value,'$EmpresaId','$CU',document.Formabuscargos.departamento.value)\">";
        $html .= "  <input type='text' class='input-text' name = 'codigo' id=\"codigo\" size=\"20\" maxlength=\"30\">";
        $html .= "    <div style=\"position: relative;width: 100%;\">\n";
        $html .= "       <div id=\"lista\" style=\"width:100%\" class=\"fill\"></div>\n";
        $html .= "    </div>\n";
        $html .= "</div>\n";
        $html .= "</td>";
        $html .= "<td width=\"50%\" align='center'>Descripci&oacute;n";
        $html .= "<div>\n";
        //$html .= "  <input type='text' class='input-text' name = 'descripcion' id=\"descripcion\" size=\"70\" maxlength=\"80\" onkeyup=\"xajax_reqObtenerDatosCargos(document.getElementById('descripcion').value,'$EmpresaId','$CU',document.Formabuscargos.departamento.value,'1')\">&nbsp&nbsp;Cant.<input type='text' class='input-text' name=\"cantidad\" id=\"cantidad\" size=\"3\" maxlength=\"3\" value=\"1\">" ;
        $html .= "  <input type='text' class='input-text' name = 'descripcion' id=\"descripcion\" size=\"70\" maxlength=\"80\" >" ;
        $html .= "    <div style=\"position: relative;width: 100%;\">\n";
        $html .= "       <div style=\"width:100%\" id=\"lista_descripcion\" class=\"fill\"></div>\n";
        $html .= "    </div>\n";     
        $html .= "</div>\n";
        $html .= "<td  width=\"6%\" align=\"center\"><input name= 'adicionar' type=\"button\" value=\"BUSCAR\" onClick=\"xajax_reqBuscarDatosCargos('$EmpresaId','$CU','$PlanId','$Cuenta',document.getElementById('codigo').value,document.getElementById('descripcion').value,document.Formabuscar.departamento.value,document.Formabuscar.departamento[document.Formabuscar.departamento.selectedIndex].text,document.Formabuscar.MedInt.value,document.getElementById('FechaCargo').value,'".$fechaIngresoEgreso[0]['fecha_ingreso']."','".$fechaIngresoEgreso[0]['fecha_registro']."','".$Ingreso."')\"></td>";
        $html .="</tr>";
        $html .="<tr class=\"modulo_table_list_title\">";
        $html .="</tr>";
        $html .="</table>";
        $html .="</form>";
      }
		//FORMA DATOS AGREGADOS A seleccionar
			$html .= "<form name=\"FormabuscargosSel\" method=\"post\">";
			$html .="<div id='cargosbusqueda' style=\"display:none\">";
			$html .="<br><table id=\"cargos1\" align=\"center\" border=\"0\" width=\"90%\" class=\"modulo_table_list\">";
			$html .="<tr class=\"modulo_table_list_title\">";
			$html .="  <td align=\"center\" colspan=\"5\">RESULTADO DE LA BUSQUEDA</td>";
			$html .="</tr>";
			$html .="<tr class='modulo_list_claro'>";
			$html .="  <td align=\"center\" width=\"15%\">Dpto</td>";
			$html .="  <td align=\"center\" width=\"13%\">Cargo</td>";
			$html .="  <td align=\"center\" width=\"60%\">Descripci&oacute;n</td>";
			//$html .="  <td align=\"center\" width=\"15%\">Precio</td>";
			$html .="  <td align=\"center\" width=\"3\">Cant</td>";
			$html .="  <td align=\"center\" width=\"5%\">Sel</td>";
			$html .="</tr>";
			$html .="</table>";
			$html .="<table id=\"tablacargosbusqueda\" align=\"center\" border=\"0\" width=\"90%\" class=\"modulo_table_list\">";
			$html .="</table>";
			$html .="</div>";
			$html .= "</form>";
		//FIN FORMA DATOS AGREGADOS A CARGOS    

		/*	$accion1 = ModuloGetURL('app','Cuentas','user','LlamaInsertarCargos',array('obj'=>&$obj,'EmpresaId'=>$EmpresaId,'CU'=>$CU,'PlanId'=>$PlanId,'Cuenta'=>$Cuenta));
			//$accion1 = ModuloGetURL('app','Facturacion','user','LlamaGuardarTodosCargos',array('obj'=>&$obj,'EmpresaId'=>$EmpresaId,'CU'=>$CU,'PlanId'=>$PlanId,'Cuenta'=>$Cuenta));

			$html .= "<br><br><table align=\"center\" width=\"40%\" border=\"0\">";
			$html .= "<tr>";

			$html .= "<td align=\"center\">";
			$html .= "<div id='guardar' style=\"display:none\">";
			$html .= "  <table align=\"center\" width=\"40%\" border=\"0\">";
			$html .= "  <tr>";
			$html .= "    <td align=\"center\">";
			$html .= "     <form name=\"formainsert\" action=\"$accion1\" method=\"post\">";
			$html .= "       <input type=\"submit\" name=\"GUARDAR\" value=\"GUARDAR\" class=\"input-submit\">";
			$html .= "     </form>";
			$html .= "    </td>";
			$html .= "  </tr>";
			$html .= "  </table>";
			$html .= "</div>";
			$html .= "</td>";
	
			$accion2 = SessionGetVar('AccionVolverCargos');
			$html .= "<form name=\"volver\" method=\"post\" action=\"$accion2\">";
	
			$html .= "<td align=\"center\">";
			$html .= "<input type=\"submit\" name=\"volver\" value=\"VOLVER\" class=\"input-submit\">";
			$html .= "</form>";
			$html .= "</td>";
			$html .= "</tr>";
			$html .= "</table>";*/
			//$html .= ThemeCerrarTablaSubModulo();
			return $html;
		}
    

		function LlamaDepartamentos($Empresa,$Centro)
		{
			IncludeClass('AgregarCargos','','app','Cuentas');
			$fact = new AgregarCargos();
			$dat = $fact->Departamentos($Empresa,$Centro);
			return $dat;
			
		}

		function LlamaFechasIngresoEgreso($ingreso)
		{
			IncludeClass('AgregarCargos','','app','Cuentas');
			$fact = new AgregarCargos();
			$dat = $fact->FechaIngresoEgreso($ingreso);
			return $dat;
		}

		function LlamaProfesionales()
		{
			IncludeClass('AgregarCargos','','app','Cuentas');
			$fact = new AgregarCargos();
			$dat = $fact->Profesionales();
			return $dat;
			
		}

		/**
		* Determina el tipo de rips del cargo para pedir los datos necesarios para el rips
		* solo los almacena al tmp de cuenta detalle si ingresa los datos necesarios del rips
		* Los llamados y asignaciones a los valores de la session $_SESSION['TMP_DATOS'] son
		* utilizados solo en este metodo
		* @param String cargos_cups
		* @return Array Datos
		*/

		function PideDatosAdicionalesRips(&$obj,$CUtilidad,$cargos_cups,$tarifario_id,$EmpresaId,$arreglo,$Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var='',$ValEmpresa,$Cobertura)
		{
        if(!$EmpresaId) $EmpresaId = $_REQUEST['EmpresaId'];
        
        if($_REQUEST[cargos_cups] AND empty($cargos_cups))
					$cargos_cups=$_REQUEST[cargos_cups];
				
				$html='';
				$accion = 'pidedatos';
				$mensaje_tmp = $mensaje;
				if(!empty($_REQUEST['datos']))
					$accion = $_REQUEST['datos'];
        
        $cut = $_REQUEST['CU'];
        if(!$cut) $cut = $_REQUEST['CUtilidad'];
                
				if(!empty($_SESSION['TMP_DATOS']['Cuenta']))
				{
            $tarifario_id=$_SESSION['TMP_DATOS']['tarifario_id'];
						$arreglo=$_SESSION['TMP_DATOS']['arreglo'];$Cuenta=$_SESSION['TMP_DATOS']['Cuenta'];$TipoId=$_SESSION['TMP_DATOS']['TipoId'];
						$PacienteId=$_SESSION['TMP_DATOS']['PacienteId'];$Nivel=$_SESSION['TMP_DATOS']['Nivel'];$PlanId=$_SESSION['TMP_DATOS']['PlanId'];
						$Ingreso=$_SESSION['TMP_DATOS']['Ingreso'];$Fecha=$_SESSION['TMP_DATOS']['Fecha'];$mensaje=$_SESSION['TMP_DATOS']['mensaje'];
						$D=$_SESSION['TMP_DATOS']['D'];$var=$_SESSION['TMP_DATOS']['var'];$ValEmpresa=$_SESSION['TMP_DATOS']['ValEmpresa'];
						$Cobertura=$_SESSION['TMP_DATOS']['Cobertura'];
						$obj = $_SESSION['TMP_DATOS']['obj'];$_REQUEST['CU']=$_SESSION['TMP_DATOS']['CU'];
						$_REQUEST['EmpresaId'] = $_SESSION['TMP_DATOS']['EmpresaId'];
				}
				else
				{
						$_SESSION['TMP_DATOS']['tarifario_id']=$tarifario_id ;$_SESSION['TMP_DATOS']['EmpresaId']= $_REQUEST['EmpresaId'];
						$_SESSION['TMP_DATOS']['arreglo']= $arreglo;$_SESSION['TMP_DATOS']['Cuenta']=$Cuenta ;$_SESSION['TMP_DATOS']['TipoId']= $TipoId;
						$_SESSION['TMP_DATOS']['PacienteId']= $PacienteId;$_SESSION['TMP_DATOS']['Nivel']=$Nivel ;$_SESSION['TMP_DATOS']['PlanId']= $PlanId;
						$_SESSION['TMP_DATOS']['Ingreso']= $Ingreso;$_SESSION['TMP_DATOS']['Fecha']= $Fecha;$_SESSION['TMP_DATOS']['mensaje']= $mensaje;
						$_SESSION['TMP_DATOS']['D']= $D;$_SESSION['TMP_DATOS']['var']= $var;$_SESSION['TMP_DATOS']['ValEmpresa']= $ValEmpresa;
						$_SESSION['TMP_DATOS']['Cobertura']= $Cobertura;
						$_SESSION['TMP_DATOS']['obj'] = &$obj;$_SESSION['TMP_DATOS']['CU']= $_REQUEST['CU'];
				}
				$_REQUEST['PlanId']=$PlanId;
				if (IncludeClass("rips"))
				{
							$rips = new rips;
							$ConsultaTipoRips = ModuloGetVar('app','Facturacion_Fiscal','ConsultaTipoRips');
							$datos_cups = $rips->GetDatosCups($cargos_cups);
							$tipo_rips = $rips->GetTipoRips($ConsultaTipoRips,$datos_cups[tipo_cargo],$datos_cups[grupo_tipo_cargo],$datos_cups[grupo_tarifario_id],$datos_cups[subgrupo_tarifario_id],$tarifario_id,$cargos_cups,$_REQUEST['EmpresaId']);
							
							$viasingreso       = $rips->GetViasIngreso();
							$_SESSION['TMP_DATOS']['datos_cups'] = $datos_cups;
							$_SESSION['TMP_DATOS']['tipo_rips'] = $tipo_rips;
							if($accion == 'pidedatos')
							{
                unset($_REQUEST);
                $sw_dato_complementario     = $rips->GetSwDatosComplementarios($_REQUEST['EmpresaId'],$cargos_cups);
                
                switch ($tipo_rips)
                {
                    case 'AC':
                    {
                      $html = $this->FormaPideDatosAdicionalesRipsAC(&$obj,$_REQUEST['EmpresaId'],$cut,$cargos_cups,$mensaje_tmp,$sw_dato_complementario,$viasingreso,$Cuenta);
                      break;
                    }
                    case 'AP':
                    {
                      $html = $this->FormaPideDatosAdicionalesRipsAP($EmpresaId,$cut,$cargos_cups,$Cuenta);
                      break;
                    }
                    case 'AT':
                    {
                      $html = $this->FormaPideDatosAdicionalesRipsAT($EmpresaId,$cut,$cargos_cups,$Cuenta,$datos_cups);
                      break;
                    }
                    case 'AU':
                    {
                      $html = $this->FormaPideDatosAdicionalesRipsAU($EmpresaId,$cut,$cargos_cups,$Cuenta);
                      break;
                    }
                    case 'AH':
                    {
                      $html = $this->FormaPideDatosAdicionalesRipsAH($EmpresaId,$cargos_cups,$datos_cups,$viasingreso);
                      break;
                    }
                    default:
                    {
                      //break;
                      return 'sin_tipo_rips';
                    }
                }//fin switch
							}
							elseif($accion == 'adiciona')
							{
									$validacion = $this->validaInformacionRips($tipo_rips,$_REQUEST[dato_complementario]);
									if($validacion)
									{
											$_REQUEST[numerodecuenta] = $Cuenta;
	
											$inserta_rips = $rips->InsertaRipsCuentasDetalle($cargos_cups);
											if($inserta_rips)
											{   //NO SE INSERTA EN TmpCuentasDetalle
                        $_REQUEST['CU'] = $cut;
															$mensaje="LOS DATOS DEL CARGO SE GUARDARON PARA RIPS(rips_cuentas_detalle).";
															unset($_SESSION['TMP_DATOS']);
															IncludeLib("funciones_facturacion");
															$insertar_tmp = InsertarTmpCuentasDetalle($EmpresaId,$cut,$Cuenta,$PlanId,$arreglo);
															if(!empty($insertar_tmp))
															{
																	$mensaje.=" - (tmp_cuentas_detalle).";
																	unset($_SESSION['TMP_DATOS']);
															}
															else
															{
																	$mensaje.=" - (no se guandaron en tmp_cuentas_detalle).";
																	$html = $this->FormaAgregarCargos(&$obj,$_REQUEST['EmpresaId'],$cut,$PlanId,$Cuenta,$mensaje);
																	unset($_SESSION['TMP_DATOS']);
																	return $html;
															}
															return $arreglo;
											}
											else
											{
													$mensaje="ERROR: OCURRIO UN ERROR AL INSERTAR EN rips_cuentas_detalle.";
													unset($_SESSION['TMP_DATOS']);
											}
											$this->PideDatosAdicionalesRips(&$obj,$cargos_cups,$tarifario_id,$EmpresaId,$arreglo,$Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var,$ValEmpresa,$Cobertura);
											unset($_SESSION['TMP_DATOS']);
									}
									else
									{
											$_REQUEST['datos'] = 'pidedatos';
											$mensaje="ERROR: TODOS LOS DATOS PARA RIPS SON OBLIGATORIOS PARA AGREGAR UN CARGO.";
											//$html = $this->PideDatosAdicionalesRips($cargos_cups,$tarifario_id,$EmpresaId,$arreglo,$Cuenta,$TipoId,$PacienteId,$Nivel,$PlanId,$Ingreso,$Fecha,$mensaje,$D,$var,$ValEmpresa,$Cobertura);
											$html = $this->FormaAgregarCargos(&$obj,$_REQUEST['EmpresaId'],$cut,$PlanId,$Cuenta,$mensaje);
									}
									return $html;
							}
				}
				else
				{
          $html = "No se pudo cargar la clase RIPS";
				}
				return $html;
		}
		/**
		*FormaVariasEquivalencias
		*/
		function FormaVariasEquivalencias($Departamento,$Servicio,$CargoCups,$nombre,$equi,$TipoId,$PacienteId,$Cuenta,$Nivel,$PlanId,$Fecha,$Ingreso,$cantidad,$FechaCargo,$profesional,$EmpresaId,$CUtilidad,$mensaje)
		{
				//$Nombres=$this->BuscarNombresPaciente($TipoId,$PacienteId);
				//$Apellidos=$this->BuscarApellidosPaciente($TipoId,$PacienteId);
				$html = ThemeAbrirTabla('AGREGAR CARGOS A LA CUENTA No. '.$Cuenta.' '.$Nombres.' '.$Apellidos);
				$html .= $this->EncabezadoEmpresa($EmpresaId,$CUtilidad);
				$argu = array('Transaccion'=>$Transaccion,'Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso);
				$html .= "       <br><table border=\"0\" width=\"90%\" align=\"center\">";
				//$html .= $this->SetStyle("MensajeError");
				$html .= "          <tr align=\"center\"><td><label class=\"label_error\">$mensaje</label></td></tr>";
				$html .= "       </table>";
				$html .= "     <br><table border=\"0\" width=\"80%\" align=\"center\" class=\"normal_10\">";
				$html .= "          <tr><td colspan=\"5\"><label class=\"label_mark\">EL CARGO CUPS </label><label class=\"label_error\">($CargoCups) $nombre </label><label class=\"label_mark\">TIENE VARIAS EQUIVALENCIAS:</label></td></tr>";
				$html .= "          <tr><td colspan=\"5\">&nbsp;</td></tr>";
				$html .= "      <tr class=\"modulo_table_list_title\">";
				$html .= "        <td>TARIFARIO</td>";
				$html .= "        <td>CARGO</td>";
				$html .= "        <td>DESCRIPCION</td>";
				$html .= "        <td>PRECIO</td>";
				$html .= "        <td></td>";
				$html .= "      </tr>";
				//cambio lorena porque se cae el programa cuando mandaban este vector por request
				$_SESSION['FACTURACION']['VECTOR_EQUIVALENCIAS']=$equi;
				//fin cambio
				$accion=ModuloGetURL('app','Cuentas','user','LlamaInsertarCargoTmpEquivalencias',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso,'cups'=>$CargoCups,'descripcion'=>$nombre,'departamento'=>$Departamento,'servicio'=>$Servicio,'cantidad'=>$cantidad,'fechacar'=>$FechaCargo,'profesional'=>$profesional,'EmpresaId'=>$EmpresaId,'CUtilidad'=>$CUtilidad));
				$html .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				for($i=0; $i<sizeof($equi); $i++)
				{
						if( $i % 2) $estilo='modulo_list_oscuro';
						else $estilo='modulo_list_claro';
						$html .= "     <tr class=\"$estilo\">";
						$html .= "        <td align=\"center\">".$equi[$i][tarifario_id]."</td>";
						$html .= "        <td align=\"center\">".$equi[$i][cargo]."</td>";
						$html .= "        <td>".$equi[$i][descripcion]."</td>";
						$html .= "        <td align=\"center\">".FormatoValor($equi[$i][precio])."</td>";
						$html .= "        <td align=\"center\"><input type = checkbox name= cargo".$equi[$i][tarifario_id]."".$equi[$i][cargo]." value=\"".$equi[$i][tarifario_id]."||".$equi[$i][cargo]."||".$equi[$i][descripcion]."||".$CargoCups."\"></td>";
						$html .= "      </tr>";
				}
				$html .= "     </table>";
				$html .= "     <table border=\"0\" width=\"50%\" align=\"center\">";
				$html .= "          <tr>";
				$html .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"ACEPTAR\"></td>";
				$html .= "</form>";
				//$accion=ModuloGetURL('app','Facturacion','user','Cargos',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
				$accion = ModuloGetURL('app','Cuentas','user','LlamaFormaAgregarCargos',array('Cuenta'=>$Cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$PlanId,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
				$html .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
				$html .= "              <td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"CANCELAR\"></td>";
				$html .= "</form>";
				$html .= "          </tr>";
				$html .= "     </table>";
				$html .= ThemeCerrarTabla();
				return $html;
		}

		/**
		* Valida que ingresen los datos necesarios para los rips
		*/
		function validaInformacionRips($tipo_rips,$sw_dato_complementario)
		{
				$filtroAH = '';
				$filtroAU = '';
				$filtroAN = '';
				switch ($tipo_rips)
				{
						case 'AC':
						{
												$sw_valida = '0';
												if(!empty($_REQUEST['ac_fechaconsulta']) AND !empty($_REQUEST['ac_tipofinalidad'])
																AND !empty($_REQUEST['ac_causaexterna']) AND !empty($_REQUEST['ac_diagnostico'])
																AND !empty($_REQUEST['ac_tipodiagnostico']) AND !empty($_REQUEST['autorizacion']) )
												{
														$sw_valida = '1';
														if($sw_dato_complementario[sw_ah] == '1')
														{
																if( !empty($_REQUEST['ah_ViaIngreso']) AND !empty($_REQUEST['ah_fechaingreso'])
																				AND !empty($_REQUEST['ah_causaexterna']) AND !empty($_REQUEST['ah_diagnosticoingreso'])
																				AND !empty($_REQUEST['ah_diagnosticosalida']) AND !empty($_REQUEST['ah_estadosalida'])
																				AND !empty($_REQUEST['ah_fechasalida']) )
																$sw_valida = '1'; else $sw_valida = '0';
														}
														elseif($sw_dato_complementario[sw_au] == '1')
														{
																if( !empty($_REQUEST['au_fechaingreso']) AND !empty($_REQUEST['au_horarioingreso'])
																				AND !empty($_REQUEST['au_causaexterna']) AND !empty($_REQUEST['au_DiagnosticoSalida'])
																				AND !empty($_REQUEST['au_destinosalida']) AND !empty($_REQUEST['au_estadosalida'])
																				AND !empty($_REQUEST['au_fechasalida']) AND !empty($_REQUEST['au_horariosalida']) )
																$sw_valida = '1'; else $sw_valida = '0';
														}
								//                              elseif($sw_dato_complementario[sw_an] == '1')
								//                              {
								//                                  if( !empty($_REQUEST['']) AND !empty($_REQUEST[''])
								//                                          AND !empty($_REQUEST['']) AND !empty($_REQUEST[''])
								//                                          AND !empty($_REQUEST['']) AND !empty($_REQUEST[''])
								//                                          AND !empty($_REQUEST['']) AND !empty($_REQUEST['']) )
								//                                  $sw_valida = '1'; else $sw_valida = '0';
								//                              }
												}
												if($sw_valida == '1')
														return true;
												else
														return false;
												break;
						}
						case 'AP':
						{
												if(!empty($_REQUEST['ap_fechaprocedimiento']) AND !empty($_REQUEST['ap_ambitoprocedimiento'])
																AND !empty($_REQUEST['ap_finalidadprocedimiento']) AND !empty($_REQUEST['autorizacion']) )
												{
														return true;
												}
												else
												{
														return false;
												}
												break;
						}
						case 'AT':
						{
												if(!empty($_REQUEST['at_tiposervicio']) AND !empty($_REQUEST['autorizacion']) )
												{
														return true;
												}
												else
												{
														return false;
												}
												break;
						}
						case 'AM':
						{
												if(!empty($_REQUEST['am_tipomedicamento']) AND !empty($_REQUEST['autorizacion']) )
												{
														return true;
												}
												else
												{
														return false;
												}
												break;
						}
						case 'AU':
						{
												$_REQUEST['au_diagnosticosalida'] = $_REQUEST['codigo'];
												if(!empty($_REQUEST['autorizacion']) AND !empty($_REQUEST['au_fechaingreso'])
																AND !empty($_REQUEST['au_horarioingreso']) AND !empty($_REQUEST['au_minuteroingreso'])
																AND !empty($_REQUEST['au_causaexterna']) AND !empty($_REQUEST['au_diagnosticosalida'])
																AND !empty($_REQUEST['au_destinosalida']) AND !empty($_REQUEST['au_estadosalida'])
																AND !empty($_REQUEST['au_fechasalida']) AND !empty($_REQUEST['au_horariosalida'])
																AND !empty($_REQUEST['au_minuterosalida']) )
												{
														return true;
												}
												else
												{
														return false;
												}
												break;
						}
						case 'AH':
						{
												if( !empty($_REQUEST['ah_ViaIngreso']) AND !empty($_REQUEST['ah_fechaingreso'])
																				AND !empty($_REQUEST['ah_causaexterna']) AND !empty($_REQUEST['ah_diagnosticoingreso'])
																				AND !empty($_REQUEST['ah_diagnosticosalida']) AND !empty($_REQUEST['ah_estadosalida'])
																				AND !empty($_REQUEST['ah_fechasalida']) AND !empty($_REQUEST['ah_horarioingreso'])
																				AND !empty($_REQUEST['ah_minuteroingreso']) AND !empty($_REQUEST['ah_horariosalida'])
																				AND !empty($_REQUEST['ah_minuterosalida']))
												{
														return true;
												}
												else
												{
														return false;
												}
												break;
						}
						default:
						{
												return false;
												break;
						}
				}//fin switch
				return true;
		}

    /**
    *FormaPideDatosAdicionalesRipsAC
    */
    function FormaPideDatosAdicionalesRipsAC(&$obj,$EmpresaId,$cu,$cargos_cups,$mensaje,$sw_dato_complementario,$viasingreso)
    {
        $titulocampos = "&titulo[0]=CODIGO&titulo[1]=DESCRIPCION";
        $nombrecampossql = "&campossql[0]=diagnostico_id&campossql[1]=diagnostico_nombre";
        $action7  = "classes/BuscadorConsulta/BuscadorHtml.class.php?buscador=diagnosticos&forma=datosadicionalesrips";
        $action7 .= $nombrecampos.$nombrecampossql.$titulocampos;

        $tiposfinalidad    = $this->LlamaConsultaTiposFinalidad();
        $tiposcausaexterna = $this->LlamaConsultaCausaExterna();
        $tiposdiagnostico  = $this->LlamaConsultaDiagnostico();
        $html = ThemeAbrirTabla("ADICION INFORMACION NECESARIA PARA RIPS CON EL CARGO(AC): ".$cargos_cups);
        $html .= $this->EncabezadoEmpresa($EmpresaId,$cu);
        $html .= "<BR>";
        $html .= "<table align=\"center\">";
        $html .= $this->SetStyle("MensajeError");
        $html .= "</table>";

        $html .= "  <p class=\"label_error\" align=\"center\">$mensaje</p>";
        $accion = ModuloGeturl("app","Cuentas","user","LlamaPideDatosAdicionalesRips",array('dato_complementario'=>$sw_dato_complementario,'cargos_cups'=>$cargos_cups,'obj'=>&$obj,'EmpresaId'=>$EmpresaId,'CU'=>$cu));
        $html .= "<form name=\"datosadicionalesrips\" action=\"$accion\" method=\"post\">\n";
        $html .= "\n<script>\n";
        $html .= "var rem=\"\";\n";
        $html .= "  function abrir(campo1,campo2){\n";
        $html .= "      var nombre='';\n";
        $html .= "      var url2='';\n";
        $html .= "      var str='';\n";
        $html .= "      var ALTO=screen.height;\n";
        $html .= "      var ANCHO=screen.width;\n";
        $html .= "      nombre=\"buscador_General\";\n";
        $html .= "      str =\"ANCHO,ALTO,resizable=no,status=no,scrollbars=yes\";\n";
        $html .= "      url2 = '$action7&campos[0]='+campo1+'&campos[1]='+campo2;\n";
        $html .= "      rem = window.open(url2, nombre, str);\n";
        $html .= "  }\n";
        $html .= "</script>\n";
        $html .= "  <input type=\"hidden\" name=\"datos\" value = 'adiciona'>";
        $html .= "  <input type=\"hidden\" name=\"sw_dato_complementario\" value = '$sw_dato_complementario'>";
        $html .= "  <input type=\"hidden\" name=\"Cuenta\" value=\"".$_SESSION['TMP_DATOS']['Cuenta']."\">";
        $html .= "  <input type=\"hidden\" name=\"cargo\" value=\"$cargos_cups\">";
        $html .= "  <fieldset><legend class=\"field\">DATOS RIPS NECESARIOS POR SER UN SERVICIO</legend> ";
        $html .= "  <table width=\"80%\" align=\"center\" class=\"label\" >\n";
        $html .= "      <tr>\n";
        $html .= "          <td width=\"25%\">FECHA DE CONSULTA:</td>\n";
        $html .= "          <td width=\"25%\"><input type=\"text\" name=\"ac_fechaconsulta\" value=\"".$_REQUEST[ac_fechaconsulta]."\" size=\"10\" class=\"input-text\">\n";
        $html .= "          ".ReturnOpenCalendario('datosadicionalesrips','ac_fechaconsulta','/')."</td>\n";
        $html .= "          <td width=\"25%\">CODIGO DE PROCEDIMIENTO:</td>\n";
        $html .= "          <td width=\"25%\">".$cargos_cups."</td>\n";
        $html .= "      </tr>\n";
        $html .= "      <tr>\n";
        $html .= "          <td width=\"25%\">FINALIDAD CONSULTA:</td>\n";
        $html .= "          <td width=\"25%\"><select name=\"ac_tipofinalidad\" class=\"select\">";
    $html .= "          <option value=\"\" selected>-------SELECCIONE-------</option>";
        foreach($tiposfinalidad as $tiposF => $tipos)
        {
            $html .="     <option value=\"".$tipos['tipo_finalidad_id']."\" >".substr($tipos['detalle'],0,50)."</option>";
        }
    $html .= "</select></td>";

        $html .= "          <td width=\"25%\">CAUSA EXTERNA:</td>\n";
        $html .= "          <td width=\"25%\"><select name=\"ac_causaexterna\" class=\"select\">";
    $html .= "          <option value=\"\" selected>-------SELECCIONE-------</option>";
        foreach($tiposcausaexterna as $tiposC => $tipos)
        {
            $html .="     <option value=\"".$tipos['causa_externa_id']."\" >".substr($tipos['descripcion'],0,50)."</option>";
        }

    $html .= "</select></td>\n";
        $html .= "      </tr>\n";
        $html .= "      <tr>\n";
        $html .= "          <td width=\"25%\">DIAGNOSTICO PRINCIPAL:</td>\n";
        $html .= "          <td width=\"20%\"  colspan=\"1\">";
        $html .= "              <table width=\"100%\" align=\"center\" class=\"label\" >\n";
        $html .= "                  <tr>\n";
        $html .= "                      <td width=\"20%\"><input type=\"text\" name=\"ac_diagnostico\" value=\"\" size=\"10\" class=\"input-text\"></td>\n";
        $html .= "                      <td width=\"75%\"><input type=\"text\" name=\"ac_diagnostico_descripcion\" value=\"\" size=\"30\" class=\"input-text\"></td>\n";
        $html .= "                      <td width=\"5%\"><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"BUSCAR.\" onclick=abrir('ac_diagnostico','ac_diagnostico_descripcion')></td>\n";
        $html .= "                  </tr>\n";
        $html .= "              </table>";
        $html .= "          </td>";
        $html .= "          <td width=\"25%\">TIPO DIAGNOSTICO PRINCIPAL:</td>\n";
        $html .= "          <td width=\"25%\"><select name=\"ac_tipodiagnostico\" class=\"select\">";
    $html .= "              <option value=\"\">-------SELECCIONE-------</option>";
        $html .= "              <option value=\"1\">IMPRESION DIAGNOSTICA</option>";
        $html .= "              <option value=\"2\">CONFIRMADO NUEVO</option>";
        $html .= "              <option value=\"3\">CONFIRMADO REPETIDO</option>";
        $html .= "          </select></td>\n";
        $html .= "      </tr>\n";
        $html .= "      <tr>\n";
        $html .= "          <td width=\"25%\">NUMERO DE AUTORIZACION</td>\n";
        $html .= "          <td width=\"25%\"><input type=\"text\" name=\"autorizacion\" value=\"1\" size=\"10\" class=\"input-text\"></td>\n";
        $html .= "      </tr>\n";
        $html .= "  </table>\n";
        $html .= "  </fieldset>";
        if($sw_dato_complementario[sw_ah] == '1')
        {
            $html .= "  <fieldset><legend class=\"field\">DATOS RIPS NECESARIOS POR SER DE HOSPITALIZACION</legend> ";
            $html .= "  <table width=\"50%\" align=\"center\" class=\"label\" >\n";
            $html .= "      <tr>\n";
            $html .= "          <td width=\"25%\">VIAS DE INGRESO:</td>\n";
            $html .= "          <td width=\"25%\"><select name=\"ah_ViaIngreso\" class=\"select\">";
            $html .= "          <option value=\"\">-------SELECCIONE-------</option>";
            foreach($viasingreso as $viasI => $vias)
            {
                $html .="     <option value=\"".$vias['via_ingreso_id']."\" selected>".substr($vias['via_ingreso_nombre'],0,50)."</option>";
            }
            $html .= "          </select></td>\n";
            $html .= "      </tr>\n";
            $html .= "      <tr>\n";
            $html .= "          <td width=\"25%\">FECHA DE INGRESO:</td>\n";
            $html .= "          <td width=\"25%\"><input type=\"text\" name=\"ah_fechaingreso\" value=\"\" size=\"10\" class=\"input-text\" readonly>\n";
            $html .= "          ".ReturnOpenCalendario('datosadicionalesrips','ah_fechaingreso','/')."</td>\n";
            $html .= "          <td width=\"25%\">HORA DE INGRESO:</td>\n";
            $html .= "          <td width=\"25%\">\n";
            //pide hora:minutos
            $html .= "      <select name=\"ah_horarioingreso\" class=\"select\">\n";
            $html .= "      <option value=\"-1\">--</option>\n";
            for($i=0;$i<24;$i++)
            {
                if($i<10)
                {
                    if($_POST['ah_horarioingreso']=="0$i")
                    {
                        $html .="<option value=\"0$i\" selected>0$i</option>\n";
                    }
                    else
                    {
                        $html .="<option value=\"0$i\">0$i</option>\n";
                    }
                }
                else
                {
                    if($_POST['ah_horarioingreso']=="$i")
                    {
                        $html .="<option value=\"$i\" selected>$i</option>\n";
                    }
                    else
                    {
                        $html .="<option value=\"$i\">$i</option>\n";
                    }
                }
            }
            $html .= "      </select>\n";
            $html .= " : ";
            $html .= "      <select name=\"ah_minuteroingreso\" class=\"select\">\n";
            $html .= "      <option value=\"-1\">--</option>\n";
            for($i=0;$i<60;$i++)
            {
                if($i<10)
                {
                    if($_POST['ah_minuteroingreso']=="0$i")
                    {
                        $html .="<option value=\"0$i\" selected>0$i</option>\n";
                    }
                    else
                    {
                        $html .="<option value=\"0$i\">0$i</option>\n";
                    }
                }
                else
                {
                    if($_POST['ah_minuteroingreso']=="$i")
                    {
                        $html .="<option value=\"$i\" selected>$i</option>\n";
                    }
                    else
                    {
                        $html .="<option value=\"$i\">$i</option>\n";
                    }
                }
            }
            $html .= "      </select>\n";
            //fin pide hora:minutos
            $html .= "          </td>\n";
            $html .= "      </tr>\n";
            $html .= "      <tr>\n";
/*          $html .= "          <td width=\"25%\">NUMERO DE AUTORIZACION</td>\n";
            $html .= "          <td width=\"25%\"><input type=\"text\" name=\"autorizacion\" value=\"1\" size=\"10\" class=\"input-text\"></td>\n";*/
            $html .= "          <td width=\"25%\">CAUSA EXTERNA:</td>\n";
            $html .= "          <td width=\"25%\"><select name=\"ah_causaexterna\" class=\"select\">";
            $html .= "          <option value=\"\">-------SELECCIONE-------</option>";
            foreach($tiposcausaexterna as $tiposC => $tipos)
            {
                $html .="     <option value=\"".$tipos['causa_externa_id']."\" selected>".substr($tipos['descripcion'],0,50)."</option>";
            }
            $html .= "          </select></td>\n";
            $html .= "      </tr>\n";
            //DIAGNOSTICO INGRESO ah_diagnosticoingreso
            $html .= "          <td width=\"25%\">DIAGNOSTICO INGRESO:</td>\n";
            $html .= "          <td width=\"20%\"  colspan=\"1\">";
            $html .= "              <table width=\"100%\" align=\"center\" class=\"label\" >\n";
            $html .= "                  <tr>\n";
            $html .= "                      <td width=\"20%\"><input type=\"text\" name=\"ah_diagnosticoingreso\" value=\"\" size=\"10\" class=\"input-text\" readonly></td>\n";
            $html .= "                      <td width=\"75%\"><input type=\"text\" name=\"ah_diagnosticoingreso_descripcion\" value=\"\" size=\"30\" class=\"input-text\" readonly></td>\n";
            $html .= "                      <td width=\"5%\"><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"BUSCAR\" onclick=abrir('ah_diagnosticoingreso','ah_diagnosticoingreso_descripcion')></td>\n";
            $html .= "                  </tr>\n";
            $html .= "              </table>";
            $html .= "          </td>";
            $html .= "      </tr>\n";
            //FIN DIAGNOSTICO INGRESO
            //DIAGNOSTICO SALIDA ah_diagnosticosalida
            $html .= "      <tr>\n";
            $html .= "          <td width=\"25%\">DIAGNOSTICO SALIDA:</td>\n";
            $html .= "          <td width=\"20%\"  colspan=\"1\">";
            $html .= "              <table width=\"100%\" align=\"center\" class=\"label\" >\n";
            $html .= "                  <tr>\n";
            $html .= "                      <td width=\"20%\"><input type=\"text\" name=\"ah_diagnosticosalida\" value=\"\" size=\"10\" class=\"input-text\" readonly></td>\n";
            $html .= "                      <td width=\"75%\"><input type=\"text\" name=\"ah_diagnosticosalida_descripcion\" value=\"\" size=\"30\" class=\"input-text\" readonly></td>\n";
            $html .= "                      <td width=\"5%\"><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"BUSCAR\" onclick=abrir('ah_diagnosticosalida','ah_diagnosticosalida_descripcion')></td>\n";
            $html .= "                  </tr>\n";
            $html .= "              </table>";
            $html .= "          </td>";
            //FIN DIAGNOSTICO SALIDA
            $html .= "          <td width=\"25%\">ESTADO SALIDA:</td>\n";
            $html .= "          <td width=\"25%\"><select name=\"ah_estadosalida\" class=\"select\">";
            $html .= "              <option value=\"\">-------SELECCIONE-------</option>";
            $html .= "              <option value=\"1\">VIVO(A)</option>";
            $html .= "              <option value=\"2\">MUERTO(A)</option>";
            $html .= "          </select></td>\n";
            $html .= "      </tr>\n";
            $html .= "      <tr>\n";
            $html .= "          <td width=\"25%\">FECHA SALIDA:</td>\n";
            $html .= "          <td width=\"25%\"><input type=\"text\" name=\"ah_fechasalida\" value=\"\" size=\"10\" class=\"input-text\" readonly>\n";
            $html .= "          ".ReturnOpenCalendario('datosadicionalesrips','ah_fechasalida','/')."</td>\n";
            $html .= "          <td width=\"25%\">HORA SALIDA:</td>\n";
            $html .= "          <td width=\"25%\">\n";
            //pide hora:minutos
            $html .= "      <select name=\"ah_horariosalida\" class=\"select\">";
            $html .= "      <option value=\"-1\">--</option>";
            for($i=0;$i<24;$i++)
            {
                if($i<10)
                {
                    if($_POST['ah_horariosalida']=="0$i")
                    {
                        $html .="<option value=\"0$i\" selected>0$i</option>";
                    }
                    else
                    {
                        $html .="<option value=\"0$i\">0$i</option>";
                    }
                }
                else
                {
                    if($_POST['ah_horariosalida']=="$i")
                    {
                        $html .="<option value=\"$i\" selected>$i</option>";
                    }
                    else
                    {
                        $html .="<option value=\"$i\">$i</option>";
                    }
                }
            }
            $html .= "      </select>";
            $html .= " : ";
            $html .= "      <select name=\"ah_minuterosalida\" class=\"select\">";
            $html .= "      <option value=\"-1\">--</option>";
            for($i=0;$i<60;$i++)
            {
                if($i<10)
                {
                    if($_POST['ah_minuterosalida']=="0$i")
                    {
                        $html .="<option value=\"0$i\" selected>0$i</option>";
                    }
                    else
                    {
                        $html .="<option value=\"0$i\">0$i</option>";
                    }
                }
                else
                {
                    if($_POST['ah_minuterosalida']=="$i")
                    {
                        $html .="<option value=\"$i\" selected>$i</option>";
                    }
                    else
                    {
                        $html .="<option value=\"$i\">$i</option>";
                    }
                }
            }
            $html .= "      </select>";
        //fin pide hora:minutos
            $html .= "</td>\n";
            $html .= "      </tr>\n";
            $html .= "  </table>\n";
            $html .= "  </fieldset>";

        }//fin ah
        if($sw_dato_complementario[sw_au] == '1')
        {
            $html .= "  <fieldset><legend class=\"field\">DATOS RIPS NECESARIOS POR SER DE URGENCIAS</legend> ";
            $html .= "  <table width=\"50%\" align=\"center\" class=\"label\" >\n";
            $html .= "      <tr>\n";
            $html .= "          <td width=\"25%\">FECHA DE INGRESO:</td>\n";
            $html .= "          <td width=\"25%\"><input type=\"text\" name=\"au_fechaingreso\" value=\"\" size=\"10\" class=\"input-text\" readonly>\n";
            $html .= "          ".ReturnOpenCalendario('datosadicionalesrips','au_fechaingreso','/')."</td>\n";
            $html .= "          <td width=\"25%\">HORA DE INGRESO:</td>\n";
            $html .= "          <td width=\"25%\">\n";
            //pide hora:minutos
            $html .= "      <select name=\"au_horarioingreso\" class=\"select\">\n";
            $html .= "      <option value=\"-1\">--</option>\n";
            for($i=0;$i<24;$i++)
            {
                if($i<10)
                {
                    if($_POST['au_horarioingreso']=="0$i")
                    {
                        $html .="<option value=\"0$i\" selected>0$i</option>\n";
                    }
                    else
                    {
                        $html .="<option value=\"0$i\">0$i</option>\n";
                    }
                }
                else
                {
                    if($_POST['au_horarioingreso']=="$i")
                    {
                        $html .="<option value=\"$i\" selected>$i</option>\n";
                    }
                    else
                    {
                        $html .="<option value=\"$i\">$i</option>\n";
                    }
                }
            }
            $html .= "      </select>\n";
            $html .= " : ";
            $html .= "      <select name=\"au_minuteroingreso\" class=\"select\">\n";
            $html .= "      <option value=\"-1\">--</option>\n";
            for($i=0;$i<60;$i++)
            {
                if($i<10)
                {
                    if($_POST['au_minuteroingreso']=="0$i")
                    {
                        $html .="<option value=\"0$i\" selected>0$i</option>\n";
                    }
                    else
                    {
                        $html .="<option value=\"0$i\">0$i</option>\n";
                    }
                }
                else
                {
                    if($_POST['au_minuteroingreso']=="$i")
                    {
                        $html .="<option value=\"$i\" selected>$i</option>\n";
                    }
                    else
                    {
                        $html .="<option value=\"$i\">$i</option>\n";
                    }
                }
            }
            $html .= "      </select>\n";
            //fin pide hora:minutos
            $html .= "          </td>\n";
            $html .= "      </tr>\n";
            $html .= "      <tr>\n";
/*          $html .= "          <td width=\"25%\">NUMERO DE AUTORIZACION</td>\n";
            $html .= "          <td width=\"25%\"><input type=\"text\" name=\"autorizacion\" value=\"1\" size=\"10\" class=\"input-text\"></td>\n";*/
            $html .= "          <td width=\"25%\">CAUSA EXTERNA:</td>\n";
            $html .= "          <td width=\"25%\"><select name=\"au_causaexterna\" class=\"select\">";
            $html .= "          <option value=\"\">-------SELECCIONE-------</option>";
            foreach($tiposcausaexterna as $tiposC => $tipos)
            {
                $html .="     <option value=\"".$tipos['causa_externa_id']."\" selected>".substr($tipos['descripcion'],0,50)."</option>";
            }
            $html .= "          </select></td>\n";
            $html .= "      </tr>\n";
            $html .= "      <tr>\n";
            $html .= "          <td width=\"25%\">DIAGNOSTICO SALIDA:</td>\n";
            $html .= "          <td width=\"20%\"  colspan=\"1\">";
            $html .= "              <table width=\"100%\" align=\"center\" class=\"label\" >\n";
            $html .= "                  <tr>\n";
            $html .= "                      <td width=\"20%\"><input type=\"text\" name=\"au_DiagnosticoSalida\" value=\"\" size=\"10\" class=\"input-text\" readonly></td>\n";
            $html .= "                      <td width=\"75%\"><input type=\"text\" name=\"au_DiagnosticoSalida_descripcion\" value=\"\" size=\"30\" class=\"input-text\" readonly></td>\n";
            $html .= "                      <td width=\"5%\"><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"BUSCAR\" onclick=abrirVentana('au_DiagnosticoSalida','au_DiagnosticoSalida_descripcion')></td>\n";
            $html .= "                  </tr>\n";
            $html .= "              </table>";
            $html .= "          </td>";
            $html .= "          <td width=\"25%\">DESTINO SALIDA:</td>\n";
            $html .= "          <td width=\"25%\"><select name=\"au_destinosalida\" class=\"select\">";
            $html .= "          <option value=\"\">-------SELECCIONE-------</option>";
    //      foreach($tiposdestinosalida as $tiposD => $tipos)
    //      {
    //          $html .="     <option value=\"".$tipos['']."\" selected>".substr($tipos[''],0,50)."</option>";
    //      }
            $html .= "</select></td>\n";
            $html .= "      </tr>\n";
            $html .= "      <tr>\n";
            $html .= "          <td width=\"25%\">ESTADO SALIDA:</td>\n";
            $html .= "          <td width=\"25%\"><select name=\"au_estadosalida\" class=\"select\">";
            $html .= "              <option value=\"\">-------SELECCIONE-------</option>";
            $html .= "              <option value=\"1\">VIVO(A)</option>";
            $html .= "              <option value=\"2\">MUERTO(A)</option>";
            $html .= "          </select></td>\n";
            $html .= "          <td width=\"25%\">FECHA SALIDA:</td>\n";
            $html .= "          <td width=\"25%\"><input type=\"text\" name=\"au_fechasalida\" value=\"\" size=\"10\" class=\"input-text\" readonly>\n";
            $html .= "          ".ReturnOpenCalendario('datosadicionalesrips','au_fechasalida','/')."</td>\n";
            $html .= "      </tr>\n";
            $html .= "      <tr>\n";
            $html .= "          <td width=\"25%\">HORA SALIDA:</td>\n";
            $html .= "          <td width=\"25%\">\n";
            //pide hora:minutos
            $html .= "      <select name=\"au_horariosalida\" class=\"select\">";
            $html .= "      <option value=\"-1\">--</option>";
            for($i=0;$i<24;$i++)
            {
                if($i<10)
                {
                    if($_POST['au_horariosalida']=="0$i")
                    {
                        $html .="<option value=\"0$i\" selected>0$i</option>";
                    }
                    else
                    {
                        $html .="<option value=\"0$i\">0$i</option>";
                    }
                }
                else
                {
                    if($_POST['au_horariosalida']=="$i")
                    {
                        $html .="<option value=\"$i\" selected>$i</option>";
                    }
                    else
                    {
                        $html .="<option value=\"$i\">$i</option>";
                    }
                }
            }
            $html .= "      </select>";
            $html .= " : ";
            $html .= "      <select name=\"au_minuterosalida\" class=\"select\">";
            $html .= "      <option value=\"-1\">--</option>";
            for($i=0;$i<60;$i++)
            {
                if($i<10)
                {
                    if($_POST['au_minuterosalida']=="0$i")
                    {
                        $html .="<option value=\"0$i\" selected>0$i</option>";
                    }
                    else
                    {
                        $html .="<option value=\"0$i\">0$i</option>";
                    }
                }
                else
                {
                    if($_POST['au_minuterosalida']=="$i")
                    {
                        $html .="<option value=\"$i\" selected>$i</option>";
                    }
                    else
                    {
                        $html .="<option value=\"$i\">$i</option>";
                    }
                }
            }
            $html .= "      </select>";
        //fin pide hora:minutos
            $html .= "</td>\n";
            $html .= "      </tr>\n";
            $html .= "  </table>\n";
            $html .= "  </fieldset>";
        }//fin au
        $html .= "  <table align=\"center\">\n";
        $html .= "      <tr>\n";
        $html .= "          <td>\n";
        $html .= "                  <input type=\"submit\" class=\"input-bottom\" value=\"GUARDAR\">\n";
        $html .= "          </td>\n";
        $html .= "</form>\n";
/*      $accionCancela = ModuloGetUrl("app","Facturacion","user","PideDatosAdicionalesRips");
        $html .= "          <form name=\"frmCancelar\" action=\"$accionCancela\" method = \"post\">\n";
        $html .= "          <input type=\"hidden\" name=\"datos\" value = 'cancela'>";
        $html .= "              <td>\n";
        $html .= "                  <input type=\"submit\" class=\"input-bottom\" value=\"CANCELAR\">\n";
        $html .= "              </td>\n";
        $html .= "          </form>\n";*/
        $html .= "      <tr>\n";
        $html .= "  </table>";
        $html .= ThemeCerrarTabla();
        return $html;
    }
    /**
    *
    */
    function FormaPideDatosAdicionalesRipsAP($EmpresaId,$cu,$cargos_cups,$Cuenta)
    {
        $html = ThemeAbrirTabla("ADICION INFORMACION NECESARIA PARA RIPS CON EL CARGO(AP): ".$cargos_cups);
        $html .= $this->EncabezadoEmpresa($EmpresaId,$cu);
        $html .= "<table align=\"center\">";
        $html .= $this->SetStyle("MensajeError");
        $html .= "</table>";
        $accion = ModuloGeturl("app","Cuentas","user","LlamaPideDatosAdicionalesRips",array('EmpresaId'=>$EmpresaId,'cargos_cups'=>$cargos_cups,'CU'=>$cu,'Cuenta'=>$Cuenta));
        $html .= "<form name=\"datosadicionalesrips\" action=\"$accion\" method=\"post\">\n";
        $html .= "  <input type=\"hidden\" name=\"datos\" value = 'adiciona'>";
        $html .= "  <table width=\"50%\" align=\"center\" class=\"label\" >\n";
        $html .= "      <tr>\n";
        $html .= "          <td width=\"25%\">FECHA DE CONSULTA:</td>\n";
        $html .= "          <td width=\"25%\"><input type=\"text\" name=\"ap_fechaprocedimiento\" value=\"\" size=\"10\" class=\"input-text\">\n";
        $html .= "          ".ReturnOpenCalendario('datosadicionalesrips','ap_fechaprocedimiento','/')."</td>\n";
        $html .= "          <td width=\"25%\">CODIGO DE PROCEDIMIENTO:</td>\n";
        $html .= "          <td width=\"25%\">".$cargos_cups."</td>\n";
        $html .= "      </tr>\n";
        $html .= "      <tr>\n";
        $html .= "          <td width=\"25%\">AMBITO DEL PROCEDIMIENTO:</td>\n";
        $html .= "          <td width=\"25%\"><select name=\"ap_ambitoprocedimiento\" class=\"select\">";
        $html .= "              <option value=\"\">-------SELECCIONE-------</option>";
        $html .= "              <option value=\"1\">AMBULATORIO</option>";
        $html .= "              <option value=\"2\">HOSPITALARIO</option>";
        $html .= "              <option value=\"3\">EN URGENCIAS</option>";
        $html .= "          </select></td>\n";
        $html .= "          <td width=\"25%\">FINALIDAD DEL PROCEDIMIENTO:</td>\n";
        $html .= "          <td width=\"25%\"><select name=\"ap_finalidadprocedimiento\" class=\"select\">";
        $html .= "              <option value=\"\">-------SELECCIONE-------</option>";
        $html .= "              <option value=\"1\">DIAGNOSTICO</option>";
        $html .= "              <option value=\"2\">TERAPEUTICO</option>";
        $html .= "              <option value=\"3\">PROTECCION ESPECIFICA</option>";
        $html .= "              <option value=\"4\">DETECCION TEMPRANA DE ENFERMEDAD GENERAL</option>";
        $html .= "          </select></td>\n";
        $html .= "      </tr>\n";
        $html .= "      <tr>\n";
        $html .= "          <td width=\"25%\">NUMERO DE AUTORIZACION</td>\n";
        $html .= "          <td width=\"25%\"><input type=\"text\" name=\"autorizacion\" value=\"1\" size=\"10\" class=\"input-text\"></td>\n";
        $html .= "      </tr>\n";
        $html .= "  </table>\n";
        $html .= "  <table align=\"center\">\n";
        $html .= "      <tr>\n";
        $html .= "          <td>\n";
        $html .= "                  <input type=\"submit\" class=\"input-bottom\" value=\"ACEPTAR\">\n";
        $html .= "          </td>\n";
        $html .= "</form>\n";
/*      $accionCancela = ModuloGetUrl("app","Facturacion","user","PideDatosAdicionalesRips");
        $html .= "          <form name=\"frmCancelar\" action=\"$accionCancela\" method = \"post\">\n";
        $html .= "          <input type=\"hidden\" name=\"datos\" value = 'cancela'>";
        $html .= "              <td>\n";
        $html .= "                  <input type=\"submit\" class=\"input-bottom\" value=\"CANCELAR\">\n";
        $html .= "              </td>\n";
        $html .= "          </form>\n";*/
        $html .= "      <tr>\n";
        $html .= "  </table>";
        $html .= ThemeCerrarTabla();
        return $html;
    }
    /**
    *
    */
    function FormaPideDatosAdicionalesRipsAT($EmpresaId,$cu,$cargos_cups,$Cuenta,$datos_cups)
    {
        $html = ThemeAbrirTabla("ADICION INFORMACION NECESARIA PARA RIPS CON EL CARGO(AT): ".$cargos_cups);
        $html .=$this->EncabezadoEmpresa($EmpresaId,$cu);
        $html .= "<table align=\"center\">";
        $html .= $this->SetStyle("MensajeError");
        $html .= "</table>";
        $accion = ModuloGeturl("app","Cuentas","user","LlamaPideDatosAdicionalesRips",array('EmpresaId'=>$EmpresaId,'CU'=>$cu,'cargos_cups'=>$cargos_cups,'Cuenta'=>$Cuenta));
        $html .= "<form name=\"datosadicionalesrips\" action=\"$accion\" method=\"post\">\n";
        $html .= "  <input type=\"hidden\" name=\"datos\" value = 'adiciona'>";
        $html .= "  <table width=\"50%\" align=\"center\" class=\"label\" >\n";
        $html .= "      <tr>\n";
        $html .= "          <td width=\"25%\">NUMERO DE AUTORIZACION</td>\n";
        $html .= "          <td width=\"25%\"><input type=\"text\" name=\"autorizacion\" value=\"1\" size=\"10\" class=\"input-text\"></td>\n";
        $html .= "      </tr>\n";
        $html .= "      <tr>\n";
        $html .= "          <td width=\"25%\">TIPO DE SERVICIO:</td>\n";
        $html .= "          <td width=\"25%\"><input type=\"text\" name=\"at_tiposervicio\" value=\"".$datos_cups['tipo_servicio']."\" size=\"10\" class=\"input-text\">".$datos_cups[tipo_servicio_descripcion]."</td>\n";
        $html .= "      </tr>\n";
        $html .= "  </table>\n";
        $html .= "  <table align=\"center\">\n";
        $html .= "      <tr>\n";
        $html .= "          <td>\n";
        $html .= "                  <input type=\"submit\" class=\"input-bottom\" value=\"ACEPTAR\">\n";
        $html .= "          </td>\n";
        $html .= "</form>\n";
/*      $accionCancela = ModuloGetUrl("app","Facturacion","user","PideDatosAdicionalesRips");
        $html .= "          <form name=\"frmCancelar\" action=\"$accionCancela\" method = \"post\">\n";
        $html .= "          <input type=\"hidden\" name=\"datos\" value = 'cancela'>";
        $html .= "              <td>\n";
        $html .= "                  <input type=\"submit\" class=\"input-bottom\" value=\"CANCELAR\">\n";
        $html .= "              </td>\n";
        $html .= "          </form>\n";*/
        $html .= "      <tr>\n";
        $html .= "  </table>";
        $html .= ThemeCerrarTabla();
        return $html;
    }


    function FormaPideDatosAdicionalesRipsAU($EmpresaId,$cu,$cargos_cups,$Cuenta)
    {
        $titulocampos = "&titulo[0]=CODIGO&titulo[1]=DESCRIPCION";
        $nombrecampossql = "&campossql[0]=diagnostico_id&campossql[1]=diagnostico_nombre";

        $action7  = "classes/BuscadorConsulta/BuscadorHtml.class.php?buscador=diagnosticos&forma=datosadicionalesrips";
        $action7 .= $nombrecampos.$nombrecampossql.$titulocampos;
		$tiposcausaexterna = $this->ConsultaCausaExterna();
        $this->salida .= ThemeAbrirTabla("ADICION INFORMACION NECESARIA PARA RIPS PARA EL CARGO(AU): ".$cargos_cups);
        $this->EncabezadoEmpresa($EmpresaId,$cu);
        $this->salida .= "<table align=\"center\">";
        $this->salida .= $this->SetStyle("MensajeError");
        $this->salida .= "</table>";
        $accion = ModuloGeturl("app","Cuentas","user","LlamaPideDatosAdicionalesRips",array('EmpresaId'=>$EmpresaId,'CU'=>$cu,'cargos_cups'=>$cargos_cups,'Cuenta'=>$Cuenta));
        $this->salida .= "<form name=\"datosadicionalesrips\" action=\"$accion\" method=\"post\">\n";
            $this->salida .= "\n<script>\n";
            $this->salida .= "var rem=\"\";\n";
            $this->salida .= "  function abrirVentana(campo1,campo2){\n";
            $this->salida .= "      var nombre='';\n";
            $this->salida .= "      var url2='';\n";
            $this->salida .= "      var str='';\n";
            $this->salida .= "      var ALTO=screen.height;\n";
            $this->salida .= "      var ANCHO=screen.width;\n";
            $this->salida .= "      nombre=\"buscador_General\";\n";
            $this->salida .= "      str =\"ANCHO,ALTO,resizable=no,status=no,scrollbars=yes\";\n";
            $this->salida .= "      url2 = '$action7&campos[0]='+campo1+'&campos[1]='+campo2;\n";
            $this->salida .= "      rem = window.open(url2, nombre, str);\n";
            $this->salida .= "  }\n";
            $this->salida .= "</script>\n";
        $this->salida .= "  <input type=\"hidden\" name=\"datos\" value = 'adiciona'>";
        $this->salida .= "  <table width=\"50%\" align=\"center\" class=\"label\" >\n";
        $this->salida .= "      <tr>\n";
        $this->salida .= "          <td width=\"25%\">FECHA DE INGRESO:</td>\n";
        $this->salida .= "          <td width=\"25%\"><input type=\"text\" name=\"au_fechaingreso\" value=\"\" size=\"10\" class=\"input-text\" readonly>\n";
        $this->salida .= "          ".ReturnOpenCalendario('datosadicionalesrips','au_fechaingreso','/')."</td>\n";
        $this->salida .= "          <td width=\"25%\">HORA DE INGRESO:</td>\n";
        $this->salida .= "          <td width=\"25%\">\n";
        //pide hora:minutos
        $this->salida .= "      <select name=\"au_horarioingreso\" class=\"select\">\n";
        $this->salida .= "      <option value=\"-1\">--</option>\n";
        for($i=0;$i<24;$i++)
        {
            if($i<10)
            {
                if($_POST['au_horarioingreso']=="0$i")
                {
                    $this->salida .="<option value=\"0$i\" selected>0$i</option>\n";
                }
                else
                {
                    $this->salida .="<option value=\"0$i\">0$i</option>\n";
                }
            }
            else
            {
                if($_POST['au_horarioingreso']=="$i")
                {
                    $this->salida .="<option value=\"$i\" selected>$i</option>\n";
                }
                else
                {
                    $this->salida .="<option value=\"$i\">$i</option>\n";
                }
            }
        }
        $this->salida .= "      </select>\n";
        $this->salida .= " : ";
        $this->salida .= "      <select name=\"au_minuteroingreso\" class=\"select\">\n";
        $this->salida .= "      <option value=\"-1\">--</option>\n";
        for($i=0;$i<60;$i++)
        {
            if($i<10)
            {
                if($_POST['au_minuteroingreso']=="0$i")
                {
                    $this->salida .="<option value=\"0$i\" selected>0$i</option>\n";
                }
                else
                {
                    $this->salida .="<option value=\"0$i\">0$i</option>\n";
                }
            }
            else
            {
                if($_POST['au_minuteroingreso']=="$i")
                {
                    $this->salida .="<option value=\"$i\" selected>$i</option>\n";
                }
                else
                {
                    $this->salida .="<option value=\"$i\">$i</option>\n";
                }
            }
        }
        $this->salida .= "      </select>\n";
        //fin pide hora:minutos
        $this->salida .= "          </td>\n";
        $this->salida .= "      </tr>\n";
        $this->salida .= "      <tr>\n";
        $this->salida .= "          <td width=\"25%\">NUMERO DE AUTORIZACION</td>\n";
        $this->salida .= "          <td width=\"25%\"><input type=\"text\" name=\"autorizacion\" value=\"1\" size=\"10\" class=\"input-text\"></td>\n";
        $this->salida .= "          <td width=\"25%\">CAUSA EXTERNA:</td>\n";
        $this->salida .= "          <td width=\"25%\"><select name=\"au_causaexterna\" class=\"select\">";
    $this->salida .= "          <option value=\"\">-------SELECCIONE-------</option>";
        foreach($tiposcausaexterna as $tiposC => $tipos)
        {
            $this->salida .="     <option value=\"".$tipos['causa_externa_id']."\" selected>".substr($tipos['descripcion'],0,50)."</option>";
        }
    $this->salida .= "          </select></td>\n";
        $this->salida .= "      </tr>\n";
        $this->salida .= "      <tr>\n";
        $this->salida .= "          <td width=\"25%\">DIAGNOSTICO SALIDA:</td>\n";
            $this->salida .= "          <td width=\"20%\"  colspan=\"1\">";
            $this->salida .= "              <table width=\"100%\" align=\"center\" class=\"label\" >\n";
            $this->salida .= "                  <tr>\n";
            $this->salida .= "                      <td width=\"20%\"><input type=\"text\" name=\"au_DiagnosticoSalida\" value=\"\" size=\"10\" class=\"input-text\" readonly></td>\n";
            $this->salida .= "                      <td width=\"75%\"><input type=\"text\" name=\"au_DiagnosticoSalida_descripcion\" value=\"\" size=\"30\" class=\"input-text\" readonly></td>\n";
            $this->salida .= "                      <td width=\"5%\"><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"BUSCAR\" onclick=abrirVentana('au_DiagnosticoSalida','au_DiagnosticoSalida_descripcion')></td>\n";
            $this->salida .= "                  </tr>\n";
            $this->salida .= "              </table>";
            $this->salida .= "          </td>";
        $this->salida .= "          <td width=\"25%\">DESTINO SALIDA:</td>\n";
        $this->salida .= "          <td width=\"25%\"><select name=\"au_destinosalida\" class=\"select\">";
    $this->salida .= "          <option value=\"\">-------SELECCIONE-------</option>";
//      foreach($tiposdestinosalida as $tiposD => $tipos)
//      {
//          $this->salida .="     <option value=\"".$tipos['']."\" selected>".substr($tipos[''],0,50)."</option>";
//      }
    $this->salida .= "</select></td>\n";
        $this->salida .= "      </tr>\n";
        $this->salida .= "      <tr>\n";
        $this->salida .= "          <td width=\"25%\">ESTADO SALIDA:</td>\n";
        $this->salida .= "          <td width=\"25%\"><select name=\"au_estadosalida\" class=\"select\">";
    $this->salida .= "              <option value=\"\">-------SELECCIONE-------</option>";
        $this->salida .= "              <option value=\"1\">VIVO(A)</option>";
        $this->salida .= "              <option value=\"2\">MUERTO(A)</option>";
        $this->salida .= "          </select></td>\n";
        $this->salida .= "          <td width=\"25%\">FECHA SALIDA:</td>\n";
        $this->salida .= "          <td width=\"25%\"><input type=\"text\" name=\"au_fechasalida\" value=\"\" size=\"10\" class=\"input-text\" readonly>\n";
        $this->salida .= "          ".ReturnOpenCalendario('datosadicionalesrips','au_fechasalida','/')."</td>\n";
        $this->salida .= "      </tr>\n";
        $this->salida .= "      <tr>\n";
        $this->salida .= "          <td width=\"25%\">HORA SALIDA:</td>\n";
        $this->salida .= "          <td width=\"25%\">\n";
        //pide hora:minutos
        $this->salida .= "      <select name=\"au_horariosalida\" class=\"select\">";
        $this->salida .= "      <option value=\"-1\">--</option>";
        for($i=0;$i<24;$i++)
        {
            if($i<10)
            {
                if($_POST['au_horariosalida']=="0$i")
                {
                    $this->salida .="<option value=\"0$i\" selected>0$i</option>";
                }
                else
                {
                    $this->salida .="<option value=\"0$i\">0$i</option>";
                }
            }
            else
            {
                if($_POST['au_horariosalida']=="$i")
                {
                    $this->salida .="<option value=\"$i\" selected>$i</option>";
                }
                else
                {
                    $this->salida .="<option value=\"$i\">$i</option>";
                }
            }
        }
        $this->salida .= "      </select>";
        $this->salida .= " : ";
        $this->salida .= "      <select name=\"au_minuterosalida\" class=\"select\">";
        $this->salida .= "      <option value=\"-1\">--</option>";
        for($i=0;$i<60;$i++)
        {
            if($i<10)
            {
                if($_POST['au_minuterosalida']=="0$i")
                {
                    $this->salida .="<option value=\"0$i\" selected>0$i</option>";
                }
                else
                {
                    $this->salida .="<option value=\"0$i\">0$i</option>";
                }
            }
            else
            {
                if($_POST['au_minuterosalida']=="$i")
                {
                    $this->salida .="<option value=\"$i\" selected>$i</option>";
                }
                else
                {
                    $this->salida .="<option value=\"$i\">$i</option>";
                }
            }
        }
        $this->salida .= "      </select>";
    //fin pide hora:minutos
        $this->salida .= "</td>\n";
        $this->salida .= "      </tr>\n";
        $this->salida .= "  </table>\n";
        $this->salida .= "  <table align=\"center\">\n";
        $this->salida .= "      <tr>\n";
        $this->salida .= "          <td>\n";
        $this->salida .= "                  <input type=\"submit\" class=\"input-bottom\" value=\"ACEPTAR\">\n";
        $this->salida .= "          </td>\n";
        $this->salida .= "</form>\n";
/*      $accionCancela = ModuloGetUrl("app","Facturacion","user","PideDatosAdicionalesRips");
        $this->salida .= "          <form name=\"frmCancelar\" action=\"$accionCancela\" method = \"post\">\n";
        $this->salida .= "          <input type=\"hidden\" name=\"datos\" value = 'cancela'>";
        $this->salida .= "              <td>\n";
        $this->salida .= "                  <input type=\"submit\" class=\"input-bottom\" value=\"CANCELAR\">\n";
        $this->salida .= "              </td>\n";
        $this->salida .= "          </form>\n";*/
        $this->salida .= "      <tr>\n";
        $this->salida .= "  </table>";
        $this->salida .= ThemeCerrarTabla();
        return true;
    }

    /**
    *FormaPideDatosAdicionalesRipsAH
    *   @var cargos_cups cargo base del sistema
    *   @var datos_cups
    *   @var viasingreso
    */
    //FormaPideDatosAdicionalesRipsAH
    function FormaPideDatosAdicionalesRipsAH($EmpresaId,$cargos_cups,$datos_cups,$viasingreso)
    {
        $titulocampos = "&titulo[0]=CODIGO&titulo[1]=DESCRIPCION";
        $nombrecampossql = "&campossql[0]=diagnostico_id&campossql[1]=diagnostico_nombre";

        $action7  = "classes/BuscadorConsulta/BuscadorHtml.class.php?buscador=diagnosticos&forma=datosadicionalesrips";
        $action7 .= $nombrecampos.$nombrecampossql.$titulocampos;

        $tiposcausaexterna = $this->LlamaConsultaCausaExterna();

        $html = ThemeAbrirTabla("ADICION INFORMACION NECESARIA PARA RIPS PARA EL CARGO(AH): ".$cargos_cups);
        $html .= $this->EncabezadoEmpresa($EmpresaId,$cu);
        $html .= "<table align=\"center\">";
        $html .= $this->SetStyle("MensajeError");
        $html .= "</table>";
        $accion = ModuloGeturl("app","Cuentas","user","LlamaPideDatosAdicionalesRips");
        $html .= "<form name=\"datosadicionalesrips\" action=\"$accion\" method=\"post\">\n";
            $html .= "\n<script>\n";
            $html .= "var rem=\"\";\n";
            $html .= "  function abrirVentana(ncodigo,ndescripcion){\n";
            $html .= "      var nombre='';\n";
            $html .= "      var url2='';\n";
            $html .= "      var str='';\n";
            $html .= "      var ALTO=screen.height;\n";
            $html .= "      var ANCHO=screen.width;\n";
            $html .= "      nombre=\"buscador_General\";\n";
            $html .= "      str =\"ANCHO,ALTO,resizable=no,status=no,scrollbars=yes\";\n";
            //$html .= "      url2 ='".$_ROOT."classes/classbuscador/buscador.php?tipo=diagnostico&forma=datosadicionalesrips';\n";
						$html .= "      url2 ='$action7&campos[0]='+ncodigo+'&campos[1]='+ndescripcion;\n";
            $html .= "      rem = window.open(url2, nombre, str);\n";
            $html .= "  }\n";
            $html .= "</script>\n";
        $html .= "  <input type=\"hidden\" name=\"datos\" value = 'adiciona'>";
        $html .= "  <table width=\"50%\" align=\"center\" class=\"label\" >\n";
        $html .= "      <tr>\n";
        $html .= "          <td width=\"25%\">VIAS DE INGRESO:</td>\n";
        $html .= "          <td width=\"25%\"><select name=\"ah_ViaIngreso\" class=\"select\">";
    $html .= "          <option value=\"\">-------SELECCIONE-------</option>";
        foreach($viasingreso as $viasI => $vias)
        {
            $html .="     <option value=\"".$vias['via_ingreso_id']."\" selected>".substr($vias['via_ingreso_nombre'],0,50)."</option>";
        }
    $html .= "          </select></td>\n";
        $html .= "      </tr>\n";
        $html .= "      <tr>\n";
        $html .= "          <td width=\"25%\">FECHA DE INGRESO:</td>\n";
        $html .= "          <td width=\"25%\"><input type=\"text\" name=\"ah_fechaingreso\" value=\"\" size=\"10\" class=\"input-text\" readonly>\n";
        $html .= "          ".ReturnOpenCalendario('datosadicionalesrips','ah_fechaingreso','/')."</td>\n";
        $html .= "          <td width=\"25%\">HORA DE INGRESO:</td>\n";
        $html .= "          <td width=\"25%\">\n";
        //pide hora:minutos
        $html .= "      <select name=\"ah_horarioingreso\" class=\"select\">\n";
        $html .= "      <option value=\"-1\">--</option>\n";
        for($i=0;$i<24;$i++)
        {
            if($i<10)
            {
                if($_POST['ah_horarioingreso']=="0$i")
                {
                    $html .="<option value=\"0$i\" selected>0$i</option>\n";
                }
                else
                {
                    $html .="<option value=\"0$i\">0$i</option>\n";
                }
            }
            else
            {
                if($_POST['ah_horarioingreso']=="$i")
                {
                    $html .="<option value=\"$i\" selected>$i</option>\n";
                }
                else
                {
                    $html .="<option value=\"$i\">$i</option>\n";
                }
            }
        }
        $html .= "      </select>\n";
        $html .= " : ";
        $html .= "      <select name=\"ah_minuteroingreso\" class=\"select\">\n";
        $html .= "      <option value=\"-1\">--</option>\n";
        for($i=0;$i<60;$i++)
        {
            if($i<10)
            {
                if($_POST['ah_minuteroingreso']=="0$i")
                {
                    $html .="<option value=\"0$i\" selected>0$i</option>\n";
                }
                else
                {
                    $html .="<option value=\"0$i\">0$i</option>\n";
                }
            }
            else
            {
                if($_POST['ah_minuteroingreso']=="$i")
                {
                    $html .="<option value=\"$i\" selected>$i</option>\n";
                }
                else
                {
                    $html .="<option value=\"$i\">$i</option>\n";
                }
            }
        }
        $html .= "      </select>\n";
        //fin pide hora:minutos
        $html .= "          </td>\n";
        $html .= "      </tr>\n";
        $html .= "      <tr>\n";
        $html .= "          <td width=\"25%\">NUMERO DE AUTORIZACION</td>\n";
        $html .= "          <td width=\"25%\"><input type=\"text\" name=\"autorizacion\" value=\"1\" size=\"10\" class=\"input-text\"></td>\n";
        $html .= "          <td width=\"25%\">CAUSA EXTERNA:</td>\n";
        $html .= "          <td width=\"25%\"><select name=\"ah_causaexterna\" class=\"select\">";
    $html .= "          <option value=\"\">-------SELECCIONE-------</option>";
        foreach($tiposcausaexterna as $tiposC => $tipos)
        {
            $html .="     <option value=\"".$tipos['causa_externa_id']."\" selected>".substr($tipos['descripcion'],0,50)."</option>";
        }
    $html .= "          </select></td>\n";
        $html .= "      </tr>\n";
        //DIAGNOSTICO INGRESO ah_diagnosticoingreso
            $html .= "          <td width=\"25%\">DIAGNOSTICO INGRESO:</td>\n";
            $html .= "          <td width=\"20%\"  colspan=\"1\">";
            $html .= "              <table width=\"100%\" align=\"center\" class=\"label\" >\n";
            $html .= "                  <tr>\n";
            $html .= "                      <td width=\"20%\"><input type=\"text\" name=\"ah_diagnosticoingreso\" value=\"\" size=\"10\" class=\"input-text\" readonly></td>\n";
            $html .= "                      <td width=\"75%\"><input type=\"text\" name=\"ah_diagnosticoingreso_descripcion\" value=\"\" size=\"30\" class=\"input-text\" readonly></td>\n";
            $html .= "                      <td width=\"5%\"><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"BUSCAR\" onclick=abrirVentana('ah_diagnosticoingreso','ah_diagnosticoingreso_descripcion')></td>\n";
            $html .= "                  </tr>\n";
            $html .= "              </table>";
            $html .= "          </td>";
            $html .= "      </tr>\n";
            //FIN DIAGNOSTICO INGRESO
            //DIAGNOSTICO SALIDA ah_diagnosticosalida
            $html .= "      <tr>\n";
            $html .= "          <td width=\"25%\">DIAGNOSTICO SALIDA:</td>\n";
            $html .= "          <td width=\"20%\"  colspan=\"1\">";
            $html .= "              <table width=\"100%\" align=\"center\" class=\"label\" >\n";
            $html .= "                  <tr>\n";
            $html .= "                      <td width=\"20%\"><input type=\"text\" name=\"ah_diagnosticosalida\" value=\"\" size=\"10\" class=\"input-text\" readonly></td>\n";
            $html .= "                      <td width=\"75%\"><input type=\"text\" name=\"ah_diagnosticosalida_descripcion\" value=\"\" size=\"30\" class=\"input-text\" readonly></td>\n";
            $html .= "                      <td width=\"5%\"><input class=\"input-submit\" type=\"button\" name=\"Consultar\" value=\"BUSCAR\" onclick=abrirVentana('ah_diagnosticosalida','ah_diagnosticosalida_descripcion')></td>\n";
            $html .= "                  </tr>\n";
            $html .= "              </table>";
            $html .= "          </td>";
            //FIN DIAGNOSTICO SALIDA
        $html .= "          <td width=\"25%\">ESTADO SALIDA:</td>\n";
        $html .= "          <td width=\"25%\"><select name=\"ah_estadosalida\" class=\"select\">";
    $html .= "              <option value=\"\">-------SELECCIONE-------</option>";
        $html .= "              <option value=\"1\">VIVO(A)</option>";
        $html .= "              <option value=\"2\">MUERTO(A)</option>";
        $html .= "          </select></td>\n";
        $html .= "      </tr>\n";
        $html .= "      <tr>\n";
        $html .= "          <td width=\"25%\">FECHA SALIDA:</td>\n";
        $html .= "          <td width=\"25%\"><input type=\"text\" name=\"ah_fechasalida\" value=\"\" size=\"10\" class=\"input-text\" readonly>\n";
        $html .= "          ".ReturnOpenCalendario('datosadicionalesrips','ah_fechasalida','/')."</td>\n";
        $html .= "          <td width=\"25%\">HORA SALIDA:</td>\n";
        $html .= "          <td width=\"25%\">\n";
        //pide hora:minutos
        $html .= "      <select name=\"ah_horariosalida\" class=\"select\">";
        $html .= "      <option value=\"-1\">--</option>";
        for($i=0;$i<24;$i++)
        {
            if($i<10)
            {
                if($_POST['ah_horariosalida']=="0$i")
                {
                    $html .="<option value=\"0$i\" selected>0$i</option>";
                }
                else
                {
                    $html .="<option value=\"0$i\">0$i</option>";
                }
            }
            else
            {
                if($_POST['ah_horariosalida']=="$i")
                {
                    $html .="<option value=\"$i\" selected>$i</option>";
                }
                else
                {
                    $html .="<option value=\"$i\">$i</option>";
                }
            }
        }
        $html .= "      </select>";
        $html .= " : ";
        $html .= "      <select name=\"ah_minuterosalida\" class=\"select\">";
        $html .= "      <option value=\"-1\">--</option>";
        for($i=0;$i<60;$i++)
        {
            if($i<10)
            {
                if($_POST['ah_minuterosalida']=="0$i")
                {
                    $html .="<option value=\"0$i\" selected>0$i</option>";
                }
                else
                {
                    $html .="<option value=\"0$i\">0$i</option>";
                }
            }
            else
            {
                if($_POST['ah_minuterosalida']=="$i")
                {
                    $html .="<option value=\"$i\" selected>$i</option>";
                }
                else
                {
                    $html .="<option value=\"$i\">$i</option>";
                }
            }
        }
        $html .= "      </select>";
    //fin pide hora:minutos
        $html .= "</td>\n";
        $html .= "      </tr>\n";
        $html .= "  </table>\n";
        $html .= "  <table align=\"center\">\n";
        $html .= "      <tr>\n";
        $html .= "          <td>\n";
        $html .= "                  <input type=\"submit\" class=\"input-bottom\" value=\"ACEPTAR\">\n";
        $html .= "          </td>\n";
        $html .= "</form>\n";
/*      $accionCancela = ModuloGetUrl("app","Facturacion","user","PideDatosAdicionalesRips");
        $html .= "          <form name=\"frmCancelar\" action=\"$accionCancela\" method = \"post\">\n";
        $html .= "          <input type=\"hidden\" name=\"datos\" value = 'cancela'>";
        $html .= "              <td>\n";
        $html .= "                  <input type=\"submit\" class=\"input-bottom\" value=\"CANCELAR\">\n";
        $html .= "              </td>\n";
        $html .= "          </form>\n";*/
        $html .= "      <tr>\n";
        $html .= "  </table>";
        $html .= ThemeCerrarTabla();
        return $html;
    }
    //FIN FormaPideDatosAdicionalesRipsAN

    /**
    *
    */
    function FormaPideDatosAdicionalesRipsAM($EmpresaId,$cargos_cups,$datos_cups)
    {
        $html = ThemeAbrirTabla("ADICION INFORMACION NECESARIA PARA RIPS PARA EL CARGO(AM): ".$cargos_cups);
        $html .= $this->EncabezadoEmpresa($EmpresaId,$cu);
        $html .= "<table align=\"center\">";
        $html .= $this->SetStyle("MensajeError");
        $html .= "</table>";
        $accion = ModuloGeturl("app","Cuentas","user","LlamaPideDatosAdicionalesRips");
        $html .= "<form name=\"datosadicionalesrips\" action=\"$accion\" method=\"post\">\n";
        $html .= "  <input type=\"hidden\" name=\"datos\" value = 'adiciona'>";
        $html .= "  <table width=\"50%\" align=\"center\" class=\"label\" >\n";
        $html .= "      <tr>\n";
        $html .= "          <td>\n";
        $html .= "          </td>\n";
        $html .= "      </tr>\n";
        $html .= "  </table>\n";
        $html .= "  <table align=\"center\">\n";
        $html .= "      <tr>\n";
        $html .= "          <td>\n";
        $html .= "                  <input type=\"submit\" class=\"input-bottom\" value=\"ACEPTAR\">\n";
        $html .= "          </td>\n";
        $html .= "</form>\n";
        $accionCancela = ModuloGetUrl("app","Facturacion","user","PideDatosAdicionalesRips");
        $html .= "          <form name=\"frmCancelar\" action=\"$accionCancela\" method = \"post\">\n";
        $html .= "          <input type=\"hidden\" name=\"datos\" value = 'cancela'>";
        $html .= "              <td>\n";
        $html .= "                  <input type=\"submit\" class=\"input-bottom\" value=\"CANCELAR\">\n";
        $html .= "              </td>\n";
        $html .= "          </form>\n";
        $html .= "      <tr>\n";
        $html .= "  </table>";
        $html .= ThemeCerrarTabla();
        return $html;
    }

  /**
  *ENCABEZADO EMPRESA
  */
    function EncabezadoEmpresa($empresa,$cu)
    {
        $datos = $this->LlamaDatosEncabezadoEmpresa($empresa,$cu);

        if(!empty($datos))
        {
            if(!$Caja)
                $var='DEPARTAMENTO';
            else
                $var='CAJA';

            $html .= "<table  border=\"0\" class=\"modulo_table_list\" width=\"80%\" align=\"center\" >\n";
            $html .= "  <tr class=\"modulo_table_list_title\">\n";
            $html .= "      <td>EMPRESA</td>\n";
            $html .= "      <td>CENTRO UTILIDAD</td>\n";
						if($datos[descripcion1])
						{
							if(empty($_SESSION['CUENTAS']['RETORNO']))
                $html .= "      <td>$var</td>\n";
						}

            $html .= "  </tr>";
            $html .= "  <tr align=\"center\">";
            if(!empty($_SESSION['CUENTAS']['RETORNO']))
            {
                $html .= "      <td class=\"normal_10AN\">".$_SESSION['CUENTAS']['RETORNO']['empresa']."</td>\n";
                $html .= "      <td class=\"normal_10AN\">".$_SESSION['CUENTAS']['RETORNO']['centro']."</td>\n";
            }
            else
            {
                $html .= "      <td class=\"normal_10AN\" >".$datos[razon_social]."</td>\n";
                $html .= "      <td class=\"normal_10AN\">".$datos[descripcion]."</td>\n";
                if($datos[descripcion1]) $html .= "      <td class=\"normal_10AN\" >".$datos[descripcion1]."</td>\n";
            }
            $html .= "  </tr>\n";
            $html .= "</table>\n";
        }
        return $html;
  }

	/**
		* Forma para los mansajes
		* @access private
		* @return void
		*/
		function FormaMensaje($mensaje,$titulo,$accion,$boton)
		{
			$html = ThemeAbrirTabla($titulo);
			$html .= "            <table width=\"60%\" align=\"center\" >";
			$html .= "            <form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
			$html .= "               <tr><td colspan=\"2\" class=\"label\" align=\"center\">$mensaje<br><br></td></tr><br><br>";
			if($boton){
				$html .= "               <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"$boton\"></td></tr>";
			}
			else{
				$html .= "               <tr><td colspan=\"2\" align=\"center\"><input class=\"input-submit\" type=\"submit\" name=\"Aceptar\" value=\"Aceptar\"></td></tr>";
			}
			$html .= "           </form>";
			$html .= "           </table>";
			$html .= ThemeCerrarTabla();
			return $html;
		}
		/**
		**/
		function LlamaDatosEncabezadoEmpresa($empresa,$cu)
		{
			$dat = new AgregarCargos();
			$encabezado = $dat->DatosEncabezadoEmpresa($empresa,$cu);
			return $encabezado;
		}

		/**
		**/
		function LlamaConsultaTiposFinalidad()
		{
			$dat = new AgregarCargos();
			$TiposFinalidad = $dat->ConsultaTiposFinalidad();
			return $TiposFinalidad;
		}

		/**
		**/
		function LlamaConsultaCausaExterna()
		{
			$dat = new AgregarCargos();
			$CausaExterna = $dat->ConsultaCausaExterna();
			return $CausaExterna;
		}

		/**
		**/
		function LlamaConsultaDiagnostico()
		{
			$dat = new AgregarCargos();
			$Diagnostico = $dat->ConsultaDiagnostico();
			return $Diagnostico;
		}

	}
?>
