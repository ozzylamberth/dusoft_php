<?php
  /******************************************************************************
  * $Id: BuscarCargoIYMHTML.class.php,v 1.8 2010/11/29 14:05:53 hugo Exp $
  * @copyright (C) 2007 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.8 $ 
	* 
	* @autor
  ********************************************************************************/
  IncludeClass('BuscarCargoIYM','','app','Cuentas');
  
	class BuscarCargoIYMHTML
	{
		function BuscarCargoIYMHTML(){}
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
		function FormaBusquedaCargoIyM($EmpresaId,$CU,$UsuarioId,$Cuenta,$PlanId,$Ingreso,$TipoId,$PacienteId,$Nombres,$Apellidos)
		{
					if($EmpresaId AND $CU)
					{
						SessionSetVar('EmpresaIyM',$EmpresaId);
						SessionSetVar('CutilidadIyM',$CU);
						SessionSetVar('CuentaIyM',$Cuenta);
						SessionSetVar('PlanIyM',$PlanId);
						SessionSetVar('TipoIdIyM',$TipoId);
						SessionSetVar('PacienteIdIyM',$PacienteId);
						SessionSetVar('NivelIyM',$Nivel);
						SessionSetVar('IngresoIyM',$Ingreso);
						SessionSetVar('FechaIyM',$Fecha);
					}
					else
					{
						$EmpresaId = SessionGetVar('EmpresaIyM');
						$CU = SessionGetVar('CutilidadIyM');
						$Cuenta = SessionGetVar('CuentaIyM');
						$PlanId = SessionGetVar('PlanIyM');
						$TipoId = SessionGetVar('TipoIdIyM');
						$PacienteId = SessionGetVar('PacienteIdIyM');
						$Nivel = SessionGetVar('NivelIyM');
						$Ingreso = SessionGetVar('IngresoIyM');
						$Fecha = SessionGetVar('FechaIyM');
					}

					UNSET($_SESSION['CUENTAS']['ADD_IYM']);
					UNSET($_SESSION['TMP_DATOS']['Cuenta']);

					$VISTA='HTML';
          $html = "<SCRIPT>";
					$html .= "    function PasarValor(forma)\n";
					$html .= "    {\n";
					$html .= "        var v;\n";
					$html .= "        var vect;\n";     
					$html .= "        v=forma.tipocama.value;\n";
					$html .= "        a=v.split('||');\n";
					$html .= "        forma.excedenteN.value = a[0];\n"; 
					$html .= "        if(a[1] > 0){\n";
					$html .= "          forma.precioN.value = a[1]; \n"; 
					$html .= "        }\n"; 
					$html .= "        else{\n";
					$html .= "          forma.precioN.value = (parseInt(a[2]) + (a[2]*a[3]/100)); \n";            
					$html .= "        }\n";       
					$html .= "    }\n";  
					$html .= "    function ContarIyM()\n";
					$html .= "    {\n";
					$html .= "       cont++;\n";
					$html .= "      // alert(document.getElementById('cantidad').value);\n";
					$html .= "    }\n";  
					$html .= "    function VerificarDatosIyM()\n";
					$html .= "    {\n";
					$html .= "			cont--;";
					$html .= "        if(cont == 0){\n";
					$html .= "         	ELE = document.getElementById('SeleccionadosIyM'); \n";
					$html .= "          ELE.style.display=\"none\"; \n";
					$html .= "         	tmp = document.getElementById('guardarIyM'); \n";
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
					$html .= "		function AsignarValorIyM(v,descripcion,precio_venta,existencia,sw_descripcion)\n";
					$html .= "		{\n";
					$html .= "			document.getElementById('codigoIyM').value = v;\n";
					$html .= "			document.getElementById('descripcionIyM').value = descripcion;\n";
					$html .= "			document.getElementById('precio_venta').value = precio_venta;\n";
					$html .= "			//document.getElementById('existencia').value = existencia;\n";
					$html .= "			//if(sw_descripcion == '1')\n";
					$html .= "			//{\n";
					$html .= "			//document.getElementById('lista_descripcionIyM').style.display = '';\n";
					$html .= "			//}else{\n";
					$html .= "			//document.getElementById('listaIyM').style.display = '';\n";
					$html .= "			//}\n";
					$html .= "		}\n";
					$html .= "		function mOvr(src,clrOver)\n";
					$html .= "		{\n";
					$html .= "			src.style.background = clrOver;\n";
					$html .= "		}\n";
					$html .= "		function mOut(src,clrIn)\n";
					$html .= "		{\n";
					$html .= "			src.style.background = clrIn;\n";
					$html .= "		}\n";
          $html .= "</SCRIPT>";

          $html .="<table  align=\"center\" border=\"0\"  width=\"80%\">";
          $html .= $this->SetStyle("MensajeError");
          $html .="</table>";

					//FORMA DATOS SELECCIONADOS(SELECCIONADOS) A CARGOS IyM
					$html .="<form name=\"FormaSeleccionadosIyM\" method=\"post\">";
					$html .="<div id='SeleccionadosIyM' style=\"display:none\">";
					/*$html .="<br>\n";
          $html .= "<table id=\"tablacargosIyMSeleccionados\" align=\"center\" border=\"0\" width=\"90%\" class=\"modulo_table_list\">";
					$html .= "<tr class=\"modulo_table_list_title\">";
					$html .= "  <td align=\"center\" colspan=\"8\">IyM SELECCIONADOS</td>";
					$html .= "</tr>";
					$html .= "<tr class='modulo_list_claro'>";
					$html .= "  <td align=\"center\" width=\"15%\">Dpto</td>";
					$html .= "  <td align=\"center\" width=\"15%\">Bodega</td>";
					$html .= "  <td align=\"center\" width=\"15%\">Codigo</td>";
					$html .= "  <td align=\"center\" width=\"33%\">Descripción</td>";
					$html .= "  <td align=\"center\" width=\"7%\">Precio</td>";
					$html .= "  <td align=\"center\" width=\"5\">Cant</td>";
					$html .= "  <td align=\"center\" width=\"5\">Total</td>";
					//$html .="  <td align=\"center\" width=\"5%\">Exist.</td>";
					$html .= "  <td align=\"center\" width=\"5%\">Elim</td>";
					$html .= "</tr>";
					$html .= "</table>";*/
					$html .="</div>";
					$html .="</form>";

          $acc = ModuloGetURL('app','Cuentas','user','LlamaInsertarCargosIyM',array('obj'=>&$obj,'EmpresaId'=>$EmpresaId,'CU'=>$CU,'PlanId'=>$PlanId,'Cuenta'=>$Cuenta));
          $html .= "<div id='guardarIyM' style=\"display:none\">";
          $html .= "<form name=\"formainsert\" action=\"$acc\" method=\"post\">";
          $html .= '<br><br><table align="center" width="40%" border="0">';
          $html .= '<tr>';
          $html .= '<td align="center">';
          $html .= '<input type="submit" name="GUARDAR" value="GUARDAR" class="input-submit">';
          $html .= '</td>';
          $html .= '</tr>';
          $html .= '</table>';
          $html .= '</form>';
          $html .= "</div>";
					//FIN FORMA DATOS AGREGADOS(SELECCIONADOS) A CARGOS IyM   
          
          $html .= "<form name=\"FomabuscarIyM\" action=\"$accion\" method=\"post\">";
          $html .= "<br>\n";
          $html .= "  <table  align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list_title\">\n";
          $html .= "    <tr class=\"modulo_table_list_title\">";
          $html .= "      <td align=\"center\" colspan=\"5\">BUSCADOR DE INSUMOS Y MEDICAMENTOS</td>";
          $html .= "    </tr>";
          $html .= "    <tr class=\"hc_table_submodulo_list_title\">";
          $html .= "      <td width=\"25%\">BODEGA:&nbsp&nbsp&nbsp;";
                    
          $bodegas = $this->LlamaBuscarBodegasPorUsuarioId($EmpresaId,$CU,$UsuarioId);
          $html .= "        <select name=bodega class='select'>";
          for($i=0;$i<sizeof($bodegas);$i++)
            $html .= "          <option value=".$bodegas[$i][bodega].">".$bodegas[$i][descripcion]."</option>";	
          $html .= "        </select>\n";
          $html .= "      </td>\n";
					$html .= "      <td width=\"45%\">DEPARTAMENTO:&nbsp&nbsp&nbsp;";
					$Departamentos = $this->LlamaDepartamentos($EmpresaId,$CU);
					
          $html .= "        <select name=departamento class='select'>";
					for($i=0;$i<sizeof($Departamentos);$i++)
						$html .= "          <option value=".$Departamentos[$i][departamento].">".$Departamentos[$i][descripcion]."</option>";
					
          $html .= "       </select>";
					$html .= "      </td>";
					$html .= "      <td width=\"30%\" class=\"".$this->SetStyle("FechaCargoIyM")."\">\n";
          $html .= "        FECHA CARGO: <input type=\"text\" name=\"FechaCargoIyM\" id=\"FechaCargoIyM\" value=\"".date("d/m/Y")."\" size=\"10\" class=\"input-text\" onFocus=\"this.select();\"  onKeyUp=\"setDate(this,'DD/MM/YYYY','es')\">&nbsp;".ReturnOpenCalendario('FomabuscarIyM','FechaCargoIyM','/')."";
					$html .= "      </td>\n";
					$html .= "    </tr>\n";
          $html .= "    <tr class=\"hc_table_submodulo_list_title\">";
          $html .= "      <td align='center'>Código";
					$html .= "        <div>\n";
          $html .= "          <input type='text' class='input-text' name = 'codigoIyM' id=\"codigoIyM\" size=\"20\" maxlength=\"30\">";
					$html .= "          <div style=\"position: relative;width: 100px;\"></div>\n";
					$html .= "        </div>\n";
          $html .= "      </td>";
          $html .= "      <td align='center'>Descripción";
					$html .= "        <div>\n";
					$html .= "          <input type='text' class='input-text' name = 'descripcionIyM' id=\"descripcionIyM\" size=\"40\" maxlength=\"60\">";
					$html .= "          <div style=\"position: relative; width:100%;\"></div>\n";
					$html .= "        </div>\n";
					$html .= "        <iput type=\"hidden\" name=\"precio_venta\" id=\"precio_venta\" value=\"\">\n";
					$html .= "      </td>\n";
          $html .= "      <td  align=\"center\">\n";
          $html .= "        <input name='adicionar' class=\"input-submit\" type=\"button\" value=\"BUSCAR\" onClick=\"xajax_reqBuscarDatosIyM(document.getElementById('codigoIyM').value,document.getElementById('descripcionIyM').value,'$EmpresaId','$CU','$Cuenta','$PlanId','$Ingreso',document.FomabuscarIyM.bodega.value,document.FomabuscarIyM.departamento.value,document.FomabuscarIyM.bodega[document.FomabuscarIyM.bodega.selectedIndex].text,document.FomabuscarIyM.departamento[document.FomabuscarIyM.departamento.selectedIndex].text,document.getElementById('FechaCargoIyM').value,document.getElementById('precio_venta').value,'$TipoId','$PacienteId')\">\n";
          $html .= "      </td>\n";
          $html .= "    </tr>\n";
          $html .= "    <tr class=\"modulo_table_list_title\">";
          if($_REQUEST['busqueda'])
          {
               $cadena="El Buscador Avanzado: realizó la  busqueda &nbsp;'".$_REQUEST['busqueda']."'&nbsp;";
          }
          else
          {
               $cadena="Buscador Avanzado: Busqueda de todos los insumos";
          }
          $html .= "      <td align=\"left\" colspan=\"3\">$cadena</td>";
          $html .= "    </tr>";
          $html .= "  </table>";
          $html .= "</form>\n";

					//RESULTADO DE LA BUSQUEDA DATOS IYM 
					$html .= "<form name=\"FormaBusquedaIyM\" id=\"FormaResultadosIyM\" method=\"post\">";
					$html .= "<div id='BusquedaIyM' style=\"display:none\">";
					$html .= "  <div id=\"tablacargosIyMBusqueda\"></div>";
					$html .= "</div>";
					$html .="</form>";               
					return $html;
		}
    
		/**
			* Forma para los mansajes
			* @access private
			* @return void
		**/
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
		**
		**/
		function LlamaDepartamentos($EmpresaId,$CU)
		{
			IncludeClass('BuscarCargoIYM','','app','Cuentas');
			$fact = new BuscarCargoIYM();
			$dat = $fact->Departamentos($EmpresaId,$CU);
			return $dat;
			
		}

		/**
		**
		**/
		function LlamaBuscarBodegasPorUsuarioId($EmpresaId,$CU,$UsuarioId)
		{
			IncludeClass('BuscarCargoIYM','','app','Cuentas');
			$fact = new BuscarCargoIYM();
			$dat = $fact->BuscarBodegasPorUsuarioId($EmpresaId,$CU,$UsuarioId);
			return $dat;
		}

	}
?>