<?php

/**
 * $Id: app_Os_Entrega_Apoyod_userclasses_HTML.php,v 1.10 2006/02/20 14:39:01 ehudes Exp $
 * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
 * @package IPSOFT-SIIS
 *
 * Modulo para el manejo de listas de trabajo
 */

class app_Os_Entrega_Apoyod_userclasses_HTML extends app_Os_Entrega_Apoyod_user
{
    //Constructor de la clase app_Os_Entrega_Apoyod_userclasses_HTML
		function app_Os_Entrega_Apoyod_userclasses_HTML()
		{
				$this->salida='';
				$this->app_Os_Entrega_Apoyod_user();
				return true;
		}

  //aoltu
		function SetStyle($campo)
		{
				if ($this->frmError[$campo] || $campo=="MensajeError")
				{
						if ($campo=="MensajeError")
						{
								$arreglo=array('numero'=>$numero,'prefijo'=>$prefijo);
								return ("<tr><td class='label_error' colspan='3' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
						}
						return ("label_error");
				}
				return ("label");
		}


	/*
	* aoltu
	* Funcion donde se visualiza el encabezado de la empresa.
	* @return boolean
	*/
	function Encabezado()
	{
				$this->salida .= "<br><table  border=\"0\" class=\"modulo_table_list\" width=\"80%\" align=\"center\" >";
				$this->salida .= " <tr class=\"modulo_table_title\">";
				$this->salida .= " <td>EMPRESA</td>";
				$this->salida .= " <td>CENTRO UTILIDAD</td>";
				$this->salida .= " <td>DEPARTAMENTO</td>";
				$this->salida .= " </tr>";
				$this->salida .= " <tr align=\"center\">";
				$this->salida .= " <td class=\"modulo_list_claro\" >".$_SESSION['ENTREGA']['NOM_EMP']."</td>";
				$this->salida .= " <td class=\"modulo_list_claro\">".$_SESSION['ENTREGA']['NOM_CENTRO']."</td>";
				$this->salida .= " <td class=\"modulo_list_claro\" >".$_SESSION['ENTREGA']['NOM_DPTO']."</td>";
				$this->salida .= " </tr>";
				$this->salida .= " </table>";
				return true;
	}


  //aoltu
  /**
		* Se utilizada listar en el combo los diferentes tipo de identificacion de los pacientes
		* @access private
		* @return void
	*/
	function BuscarIdPaciente($tipo_id,$TipoId='')
	{
			foreach($tipo_id as $value=>$titulo)
			{
					if($value==$TipoId)
					{
							$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
					}
					else
					{
							$this->salida .=" <option value=\"$value\">$titulo</option>";
					}
			}
	}


/*
* Esta funcion realiza la busqueda de examen medicos para su entrega
* según filtros como tipo, documento, nombre, etc
* @return boolean
*/
function FormaMetodoBuscar($arr)
{

		$this->salida.= ThemeAbrirTabla('ATENCION ENTREGA DE RESULTADOS');
		$accion=ModuloGetURL('app','Os_Entrega_Apoyod','user','BuscarOrden');
		$this->salida .= "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
		$this->Encabezado();
		$this->salida .= "<br><table class=\"modulo_table_list\" border=\"0\" width=\"80%\" align=\"center\">";
		$this->salida .= "<tr class=\"modulo_table_title\">";
		$this->salida .= "<td align = center colspan = 2 >RESULTADOS DE APOYOS</td>";
		$this->salida .= "</tr>";
		$this->salida .= "<tr class=\"modulo_table_list_title\">";
		$this->salida .= "<td align = left >CRITERIOS DE BUSQUEDA:</td>";
		//$this->salida .= "<td align = left >SELECCIONE LA FECHA:</td>";
		$this->salida .= "</tr>";
		$this->salida .= "<tr class=\"modulo_list_claro\" >";
		$this->salida .= "<td width=\"40%\" >";
		$this->salida .= "<table border=\"0\" width=\"80%\" align=\"center\">";
		$this->salida .= "<tr><td>";
		$this->salida .= "<table width=\"80%\" align=\"center\" border=\"0\">";
		$this->salida .= "<SCRIPT>";
		$this->salida .= "function Revisar(frm,x){";
		$this->salida .= "  if(x==true){";
		$this->salida .= "frm.Fecha.value='TODAS LAS FECHAS'";
		$this->salida .= "  }";
		$this->salida .= "else{";
		$this->salida .= "frm.Fecha.value=''";
		$this->salida .= "}";
		$this->salida .= "}";
		$this->salida .= "</SCRIPT>";

		$this->salida .= "<tr><td class=\"label\">TIPO DOCUMENTO: </td><td><select name=\"TipoDocumento\" class=\"select\">";
		$tipo_id=$this->tipo_id_paciente();
		$this->salida .=" <option value= -1 selected>Todos</option>";
		//$this->BuscarIdPaciente($tipo_id,'');
		$this->BuscarIdPaciente($tipo_id,$_REQUEST['TipoDocumento']);
		$this->salida .= "</select></td></tr>";

		$this->salida .= "<tr><td class=\"label\">DOCUMENTO: </td><td><input type=\"text\" class=\"input-text\" name=\"Documento\" maxlength=\"32\" value = ".$_REQUEST['Documento']."></td></tr>";

		$this->salida .= "<tr><td class=\"label\">NOMBRES</td><td><input type=\"text\" class=\"input-text\" name=\"Nombres\" maxlength=\"64\" value = ".$_REQUEST['Nombres']."></td></tr>";

		//cambio dar: q siempre apareciera chequeado todas las fechas por default
		$c='';
		if(empty($_REQUEST['Fecha']))		
		{ 
				$_REQUEST['Fecha']="TODAS LAS FECHAS"; 
				$c='checked';		
		}
		//fin cambio dar
				
    //buscar por orden
		$this->salida .= "<tr><td class=\"label\">ITEM DE LA ORDEN</td><td><input type=\"text\" class=\"input-text\" name=\"Numero_Orden\" value = ".$_REQUEST['Numero_Orden']."></td></tr>";
		//if(empty($_REQUEST['DiaEspe'])){$_REQUEST['DiaEspe']=$_REQUEST['Fecha'];}
		$this->salida .= "<tr><td class=\"label\">FECHA</td><td><input type=\"text\" readonly class=\"input-text\" name=\"Fecha\" value = \"".$_REQUEST['Fecha']."\"><sub>".ReturnOpenCalendario("formabuscar","Fecha","-")."</sub></td></tr>";
		$this->salida .= "<tr class=\"label\">";
		$this->salida .= "<td align = left >TODAS LAS FECHAS</td>";
		//cambio dar: en esta linea agregue la variable $c q me chequea
		$this->salida.="  <td align=\"left\"><input type = checkbox name= 'allfecha' $v onclick=Revisar(this.form,this.checked) $c></td>";
		$this->salida .= "</tr>";

		//filtro de entregas
    $this->salida .= "<tr><td class=\"label\">EXAMENES: </td><td><select name=\"opcion_entregados\" class=\"select\">";

		$this->salida .=" <option value= 1 selected>Examenes sin Entregar</option>";
		if ($_REQUEST['opcion_entregados']==2)
		{
			$this->salida .=" <option value= 2 selected>Examenes en Proceso</option>";
		}
		else
		{
			$this->salida .=" <option value= 2 >Examenes en Proceso</option>";
		}
		if ($_REQUEST['opcion_entregados']==3)
		{
			$this->salida .=" <option value= 3 selected>Examenes Entregados</option>";
		}
		else
		{
			$this->salida .=" <option value= 3 >Examenes Entregados</option>";
		}
		if ($_REQUEST['opcion_entregados']==4)
		{
			$this->salida .=" <option value= 4 selected>Todos los Examenes</option>";
		}
		else
		{
			$this->salida .=" <option value= 4 >Todos los Examenes</option>";
		}

    $this->salida .= "</select></td></tr>";
    //fin de filtros
    /*LORENA*/
		if($this->VerifyDeptoPatologia()==1){
		  if($_REQUEST['infoCadaver']){
        $v='checked';
			}
			$this->salida .= "<tr><td colspan=\"2\" class=\"label\">INFORMES CADAVERES&nbsp&nbsp&nbsp;<input type=\"checkbox\" name=\"infoCadaver\" $v></td></tr>";
		}
		/*finlorena*/

    $this->salida .= "<tr><td colspan = 2 align=\"center\" ><table>";
		$this->salida .= "<tr><td align=\"right\" ><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar_Orden_Cargar_Session\" value=\"BUSCAR\"></td>";
		$this->salida .= "</form>";

		$actionM=ModuloGetURL('app','Os_Entrega_Apoyod','user','main');
		$this->salida .= "<form name=\"formaVolver\" action=\"$actionM\" method=\"post\">";
		$this->salida .= "<td align=\"center\"><br><input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"MENU\"><br></td></form>";
		$this->salida .= "</tr>";

		$this->salida .= "</table></td></tr>";

		$this->salida .= "</td></tr></table>";

		$this->salida .= "</table>";
		$this->salida .= "</td>";
/*
		$this->salida .= "<td>";

		$this->salida .= "<BR><table border=\"0\" width=\"80%\" align=\"center\">";
    //aqui inserte lo de lorena
		$this->salida .= "<tr><td>";

		//$_REQUEST['DiaEspe'];

		$this->salida.="\n".'<script>'."\n";
		$this->salida.='function year1(t)'."\n";
		$this->salida.='{'."\n";
		$this->salida.='window.location.href="Contenido.php?year="+t.elements[0].value+"&meses="+t.elements[1].value+"';
		foreach($_REQUEST as $v=>$v1)
		{
				if($v!='year' and $v!='meses' and $v!='DiaEspe')
				{
						if (is_array($v1))
						{
							foreach($v1 as $k2=>$v2)
							{
									if (is_array($v2))
									{
											foreach($v2 as $k3=>$v3)
											{
													if (is_array($v3))
													{
															foreach($v3 as $k4=>$v4)
															{
																	$this->salida .= "&$v" . "[$k2][$k3][$k4]=$v4";
															}
													}
													else
													{
													  	$this->salida .= "&$v" . "[$k2][$k3]=$v3";
													}
											}
									}
									else
									{
											$this->salida .= "&$v" . "[$k2]=$v2";
									}
							}
				    }
				    else
				    {
    				  	$this->salida .= "&$v=$v1";
				    }
		    }
			}
			$this->salida.='";'."\n";
			$this->salida.='}'."\n";
			$this->salida.='</script>';

			$this->salida .='<form name="cosa">';
			$this->salida .="<table border=\"0\" width=\"80%\" align=\"center\">";
			$this->salida .='<tr align="center">';
			$this->salida .="<td class=\"label\">AÑO</td><td><select name=\"year\" onchange=\"year1(this.form)\" class=\"select\">";
			if(empty($_REQUEST['year']))
			{
					$_REQUEST['year']=date("Y");
					$this->AnosAgenda(True,$_REQUEST['year']);
			}
			else
			{
					$this->AnosAgenda(true,$_REQUEST['year']);
					$year=$_REQUEST['year'];
			}
			$this->salida .= "</select></td>";
			$this->salida .="<td class=\"label\">MES</td><td><select name=\"mes\" onchange=\"year1(this.form)\" class=\"select\">";
			if(empty($_REQUEST['meses']))
			{
					$mes=$_REQUEST['meses']=date("m");
					$this->MesesAgenda(True,$year,$mes);
			}
			else
			{
					$this->MesesAgenda(True,$year,$_REQUEST['meses']);
					$mes=$_REQUEST['meses'];
			}
			$this->salida .= "</select>";
			$this->salida .= "</td>";
			$this->salida .= "</tr>";
			$this->salida .= "</table>";
			$this->salida .='</form>';
			$this->ReturnMetodoExterno('app', 'Agenda', 'user', 'CalendarioEstandard');
			$this->salida .= "   </td></tr>";
			$this->salida .= "<tr class=\"modulo_table_list_title\">";
			$this->salida .= "<td>";

	    $this->salida .= "</td>";
			$this->salida .= "</tr>";
/**************************************/
   /*   $this->salida .= "</table>";

			$this->salida .= "</td>";*/
			$this->salida .= "</tr>";
			$this->salida .= "</table>";

			if(!empty($arr))
			{
					$this->salida.="<table border=\"0\" align=\"center\"  width=\"80%\">";
					$this->salida .= $this->SetStyle("MensajeError");
					$this->salida.="</table>";
					$this->salida .= "        <table width=\"80%\" border=\"0\" cellspacing=\"3\" cellpadding=\"3\" align=\"center\" >";

	        //codigo para pintar en el resultado de la busqueda el filtro utilizado.
					$texto = '';
					if ($_REQUEST['opcion_entregados'] == 1)
					{
						$texto = 'EXAMENES SIN ENTREGAR';
					}
					if ($_REQUEST['opcion_entregados'] == 2)
					{
						$texto = 'EXAMENES EN PROCESO';
					}
					if ($_REQUEST['opcion_entregados'] == 3)
					{
						$texto = 'EXAMENES ENTREGADOS';
					}
					if ($_REQUEST['opcion_entregados'] == 4)
					{
						$texto = 'TODOS LOS EXAMENES';
					}
					if ($texto != '')
					{
						$this->salida .= "<tr class=\"modulo_table_title\">";
						$this->salida.="<td colspan=3 align=\"center\">FILTRO DE BUSQUEDA: ".$texto."</td>";
						$this->salida.="</tr><br>";
					}
					//fin del pintado del filtro


					$this->salida .= "<tr align=\"center\" class=\"modulo_table_list_title\">";
					$this->salida .= "<td width=\"15%\">IDENTIFICACION</td>";
					$this->salida .= "<td width=\"55%\">NOMBRE DEL PACIENTE</td>";
					$this->salida .= "<td width=\"10%\">OPCION</td>";
					$this->salida .= "</tr>";
					for($i=0;$i<sizeof($arr);$i++)
					{
							if( $i % 2){ $estilo='modulo_list_claro';}
							else {$estilo='modulo_list_oscuro';}

							//Edad
							$edad_paciente = CalcularEdad($arr[$i][fecha_nacimiento],date("Y-m-d"));
							$this->salida.="<tr class='$estilo' align='center'>";
							$this->salida.="  <td >".$arr[$i][tipo_id_paciente]." - ".$arr[$i][paciente_id]."</td>";
							$this->salida.="  <td >".$arr[$i][nombre]."</td>";
							/*LORENA*/
							if($this->VerifyDeptoPatologia()==1){
							  if($_SESSION['PATOLOGIA']['SW_CADAVERES']==1){
                  $this->salida .= "<td width=\"10%\"><a href=".ModuloGetURL('app','Os_Entrega_Apoyod','user','LlamaEntregaResultadoPatologiaCad',array('tipo_id_paciente'=>$arr[$i][tipo_id_paciente],'paciente_id'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre], 'edad_paciente'=>$edad_paciente[edad_aprox]))."><img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;&nbsp;VER</a></td>";
								}else{
                  $this->salida .= "<td width=\"10%\"><a href=".ModuloGetURL('app','Os_Entrega_Apoyod','user','LlamaEntregaResultadoPatologia',array('tipo_id_paciente'=>$arr[$i][tipo_id_paciente],'paciente_id'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre], 'edad_paciente'=>$edad_paciente[edad_aprox]))."><img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;&nbsp;VER</a></td>";
								}
							}else{
							/*FIN LORENA*/
							$this->salida .= "<td width=\"10%\"><a href=".ModuloGetURL('app','Os_Entrega_Apoyod','user','GetForma',array('departamento'=>$arr[$i][departamento], 'tipo_id_paciente'=>$arr[$i][tipo_id_paciente],'paciente_id'=>$arr[$i][paciente_id],'nombre'=>$arr[$i][nombre], 'edad_paciente'=>$edad_paciente[edad_aprox]))."><img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'>&nbsp;&nbsp;VER</a></td>";
							}
							$this->salida.="</tr>";
					}
					$this->salida.="</table>";
					$this->salida .=$this->RetornarBarra();
			}
			else
			{
				$this->salida.="<table border=\"0\" align=\"center\"  width=\"80%\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida.="</table><br>";
			}
			if($this->VerifyDeptoPatologia()==1){
			  if(empty($_REQUEST['DiaEspe'])||$_REQUEST['DiaEspe']=='TODAS LAS FECHAS'){
					$fechaSolici=date("Y-m-d");
				}else{
					$fechaSolici=$_REQUEST['DiaEspe'];
				}
				if($_SESSION['PATOLOGIA']['SW_CADAVERES']==1){
				$reporte= new GetReports();
				$this->salida.="<table border=\"0\" align=\"center\"  width=\"80%\">";
				$this->salida.="<tr><td align=\"right\">";
				$mostrar=$reporte->GetJavaReport('app','Procedimientos_Cadaveres','reporteEntregas_html',array("dia"=>$fechaSolici,"usuarioImprime"=>UserGetUID()),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
				$nombre_funcion=$reporte->GetJavaFunction();
				$this->salida .=$mostrar;
				$this->salida .= "	  <a class=\"Link\" href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> Solicitudes Patologicas <BR>".$fechaSolici."</a>";
				$this->salida.="</td></tr>";
				$this->salida.="</table><br>";
				}else{
				$reporte= new GetReports();
				$this->salida.="<table border=\"0\" align=\"center\"  width=\"80%\">";
				$this->salida.="<tr><td align=\"right\">";
				$mostrar=$reporte->GetJavaReport('app','Patologia','reporteEntregas_html',array("dia"=>$fechaSolici,"usuarioImprime"=>UserGetUID()),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
				$nombre_funcion=$reporte->GetJavaFunction();
				$this->salida .=$mostrar;
				$this->salida .= "	  <a class=\"Link\" href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> Solicitudes Patologicas <BR>".$fechaSolici."</a>";
				$this->salida.="</td></tr>";
				$this->salida.="</table><br>";
				}
			}
			$this->salida .= ThemeCerrarTabla();
		  return true;
}
    /**
    *
    */
    function Consultar_Examenes_Paciente()
    {
  		$request = $_REQUEST;
     
      $datR['nombre'] = $request['nombre'];
      $datR['paciente_id'] = $request['paciente_id'];
      $datR['departamento'] = $request['departamento'];
      $datR['edad_paciente'] = $request['edad_paciente'];
      $datR['tipo_id_paciente'] = $request['tipo_id_paciente']; 
      $datR['imprime_varios'] = 1; 
      
      $accion = ModuloGetURL('app','Os_Entrega_Apoyod','user','ControlGrabacion');// InsertarRegistroEntrega
      $accion1 = ModuloGetURL('app','Os_Entrega_Apoyod','user','GetForma',$datR);// InsertarRegistroEntrega
  		$vector = $this->ConsultaExamenesPaciente();

  		$this->salida  = ThemeAbrirTablaSubModulo('CONSULTA DE EXAMENES DEL PACIENTE ');
  		$this->salida .= "<script>\n";
  		$this->salida .= "  var resultados = ".(($request['imprime_varios'] == '1')? "0":sizeof($request['resultados'])).";\n";
  		$this->salida .= "  function SeleccionarExamenes(seleccion)\n";
  		$this->salida .= "  {\n";
  		$this->salida .= "    if(seleccion)\n";
  		$this->salida .= "      resultados++;\n";
  		$this->salida .= "    else\n";
  		$this->salida .= "      resultados--;\n";
  		$this->salida .= "    if(resultados > 0)\n";
  		$this->salida .= "      document.getElementById('imprimir_varios').style.display = 'block';\n";
  		$this->salida .= "    else\n";
  		$this->salida .= "      document.getElementById('imprimir_varios').style.display = 'none';\n";
  		$this->salida .= "  }\n";
  		$this->salida .= "  function ImprimirResultados()\n";
  		$this->salida .= "  {\n";
  		$this->salida .= "    cadena = \"".$accion1 ."\";\n";
  		$this->salida .= "    valores = document.getElementsByName('imprime');\n";
  		$this->salida .= "    for(i=0; i< valores.length; i++)\n";
  		$this->salida .= "    {\n";
      $this->salida .= "      if(valores[i].checked)\n";
  		$this->salida .= "        cadena += \"&resultados[\"+i+\"]=\"+valores[i].value;\n";
  		$this->salida .= "    }\n";
      $this->salida .= "	  location.href = cadena; \n";
  		$this->salida .= "  }\n";
  		$this->salida .= "</script>\n";
      $this->salida .= "<table align=\"center\" class=\"modulo_table_list\" width=\"80%\">\n";
  		$this->salida .= "  <tr class=\"formulacion_table_list\">\n";
  		$this->salida .= "    <td >ID DEL PACIENTE</td>\n";
  		$this->salida .= "    <td >NOMBRE DEL PACIENTE</td>\n";
  		$this->salida .= "    <td >EDAD</td>\n";
  		$this->salida .= "  </tr>\n";
  		$this->salida .= "  <tr class=\"label\" align=\"center\">\n";
  		$this->salida .= "    <td >".$_SESSION[Paciente_Entrega]['tipo_id_paciente']." - ".$_SESSION[Paciente_Entrega]['paciente_id']."</td>\n";
  		$this->salida .= "    <td >".$_SESSION[Paciente_Entrega]['nombre']."</td>\n";
  		$this->salida .= "    <td >".$_SESSION[Paciente_Entrega]['edad_paciente']."</td>\n";
  		$this->salida .= "  </tr>\n";
  		$this->salida .= "</table><br>\n";
  	  $this->salida .= "<form name=\"forma\" action=\"".$accion."\" method=\"post\">\n";
		
      $funcionA = "";
      if ($vector)
      {
        $display = "none";
				$items= 0;
				$reporte= new GetReports();
        $chk = array();
        if($request['imprime_varios'] == '1')
        {
          $display = "block";
          $this->salida .= $reporte->GetJavaReport('app','ImpresionHC','examenesresultados',array('resultados'=>$request['resultados'],'sw_firma'=>true),array('rpt_name'=>'examenesresultado','rpt_dir'=>'','rpt_rewrite'=>TRUE));
          $funcionA = $reporte->GetJavaFunction();
          
          foreach($request['resultados'] as $k => $dtl)
            $chk[$dtl] = "checked";
        }
        $this->salida .= "  <table align=\"center\" class=\"modulo_table_list\" width=\"98%\">\n";
				$this->salida .= "    <tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "      <td colspan=\"7\">EXAMENES PENDIENTES POR ENTREGAR</td>\n";
				$this->salida .= "    </tr>\n";

				for($i=0;$i<sizeof($vector);$i++)
				{
					if( $i % 2){ $estilo='modulo_list_claro';}
					else {$estilo='modulo_list_oscuro';}
          
					if (($vector[$i][fecha_cumplimiento] != $vector[$i-1][fecha_cumplimiento]) OR
					($vector[$i][numero_cumplimiento] != $vector[$i-1][numero_cumplimiento]))
					{
            $this->salida .= "    <tr class=\"modulo_table_title\">\n";
            $this->salida .= "      <td align=\"center\" colspan=\"7\">\n";
            $this->salida .= "        <table width=\"100%\">\n";
            $this->salida .= "          <tr class=\"modulo_table_title\">\n";
            $this->salida .= "            <td width=\"50%\">FECHA DE CUMPLIMIENTO: ".$vector[$i][fecha_cumplimiento]."</td>\n";
            $this->salida .= "            <td width=\"50%\">NUMERO DE CUMPLIMIENTO: ".$vector[$i][numero_cumplimiento]."</td>\n";
            $this->salida .= "          </tr>\n";
            $this->salida .= "        </table>\n";
            $this->salida .= "      </td>\n";
            $this->salida .= "    </tr>\n";
            $this->salida .= "    <tr class=\"hc_table_submodulo_list_title\">\n";
            $this->salida .= "      <td align=\"center\" width=\"5%\">ORDEN</td>\n";
            $this->salida .= "      <td align=\"center\" width=\"12%\">No. RESULTADO</td>\n";
            $this->salida .= "      <td align=\"center\" width=\"10%\">CARGO</td>\n";
            $this->salida .= "      <td align=\"center\" width=\"51%\">DESCRIPCION</td>\n";
            $this->salida .= "      <td align=\"center\" width=\"11%\">ENTREGAR</td>\n";
            $this->salida .= "      <td align=\"center\" width=\"5%\">IMPRIMIR</td>\n";
            $this->salida .= "      <td align=\"center\" width=\"4%\">OP</td>\n";
            $this->salida .= "    </tr>\n";
					}

          $this->salida.="<tr class=\"$estilo\">";
          if ($_SESSION['BUSQUEDA_ORDEN']['numero_orden'] == $vector[$i][numero_orden_id])
          {
            $this->salida .= "      <td class = \"label_error\" align=\"center\" >".$vector[$i][numero_orden_id]."</td>\n";
          }
          else
          {
            $this->salida .= "      <td align=\"center\" >".$vector[$i][numero_orden_id]."</td>\n";
          }
          $this->salida .= "      <td align=\"center\" >".$vector[$i][resultado_id]."</td>\n";
          $this->salida .= "      <td align=\"center\" >".$vector[$i][cargo]."</td>\n";
          $this->salida .= "      <td >".$vector[$i][descripcion]."</td>\n";

          if ($vector[$i][maestro]!=4)
          {
            if(!empty($vector[$i][resultado]))
            {
                $this->salida.="  <td align=\"center\" >SIN FIRMAR</td>";
            }
            else
            {
              if($vector[$i][sw_estado]== '1')
              {
                $estado_examen=$this->ConsultaExamenSinResultado($vector[$i][numero_orden_id]);
                if($estado_examen=='a')
                {
                  $this->salida .= "  <td align=\"center\" >EXAMEN ENTREGADO SIN RESPUESTA</td>";
                  $this->salida .= "<td align=\"center\" ><a href=".ModuloGetURL('app','Os_Entrega_Apoyod','user','ActivarEntregaSinResultado',array('numero_orden_id'=>$vector[$i][numero_orden_id]))."><img src=\"". GetThemePath() ."/images/flecha_der.gif\" width='10' height='10'></br>ACTIVAR EXAMEN</a></td>";
                }
                else
                {
                  //$mostrar=$reporte->GetJavaReport('app','Os_Listas_Trabajo_Apoyod_Agrupado','examenes_html',array('resultado_id'=>$vector[$i][resultado_id]),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
                  $mostrar = $reporte->GetJavaReport('app','ImpresionHC','examenesresultados',array('resultado_id'=>$vector[$i][resultado_id],'sw_firma'=>true),array('rpt_name'=>'examenesresultado','rpt_dir'=>'','rpt_rewrite'=>TRUE));
                  $nombre_funcion=$reporte->GetJavaFunction();
                  
                  $this->salida .= "      <td align=\"center\" >\n";
                  $this->salida .= "        <input type = checkbox name= 'sin_respuesta[$i]' value = ".$vector[$i][numero_orden_id]."></br>SIN RESULTADO\n";
                  $this->salida .= "      </td>\n";
                  $this->salida .=$mostrar;
                  $this->salida .= "      <td align=\"center\" valign=\"center\"><a href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'></a></td>";
                  $items++;
                }
              }
              else
              {
                $this->salida .= "  <td align=\"center\" >SIN TOMAR</td>";
              }
            }
            if($vector[$i][sw_estado]!= '1')
            {
              $this->salida .= "<td align=\"center\" valign=\"center\"></td>";
            }
					}
					else
					{
            if($_SESSION['ENTREGA']['ACCESO']!='1')
            {
              $this->salida .= "  <td align=\"center\" ><input type = checkbox name= 'op[$i]' value = ".$vector[$i][resultado_id]."></td>";
            }
            else
            {
              $this->salida .= "  <td align=\"center\" ></td>";
            }

            //$mostrar=$reporte->GetJavaReport('app','Os_Listas_Trabajo_Apoyod_Agrupado','examenes_html',array('resultado_id'=>$vector[$i][resultado_id]),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
            $mostrar = $reporte->GetJavaReport('app','ImpresionHC','examenesresultados',array('resultado_id'=>$vector[$i][resultado_id],'sw_firma'=>true),array('rpt_name'=>'examenesresultado','rpt_dir'=>'','rpt_rewrite'=>TRUE));

            $nombre_funcion=$reporte->GetJavaFunction();
            $this->salida .=$mostrar;
            $this->salida .= "<td  align=\"center\" valign=\"center\"><a href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'></a></td>";

            $items++;
					}
          $this->salida .= "      <td align=\"center\" >\n";
          if(!empty($vector[$i]['resultado_id']))
          {
            $this->salida .= "        <input type=\"checkbox\" name= \"imprime\" value = ".$vector[$i]['resultado_id']." ".$chk[$vector[$i]['resultado_id']]." onclick=\"SeleccionarExamenes(this.checked)\">\n";
          }
          $this->salida .= "      </td>\n";
					$this->salida .= "    </tr>\n";
				}
				$this->salida .= "  </table>\n";
        $this->salida .= "  <br>\n";
        $this->salida .= "  <center>\n";
        $this->salida .= "    <div id=\"imprimir_varios\" style=\"display:".$display."\">\n";
        $this->salida .= "      <input type=\"button\" class=\"input-submit\" name=\"imprimir\" value=\"Imprimir Varios Resultados\" onclick=\"ImprimirResultados()\">\n";
        $this->salida .= "    </div>\n";
        $this->salida .= "  </center>\n";
      }
      //INICIO DE REGISTRO DE ENTREGA
      //validando el acceso a la forma de entrega de resultados
      if($_SESSION['ENTREGA']['ACCESO'] !='1')
      {
      	if ($items>0)
      	{
          $this->salida.="<script>";
          $this->salida.="function desabilitar(frm,valor){";
          $this->salida.="  if(valor==1){";
          $this->salida.="    frm.parentesco.disabled=true;";
          $this->salida.="    frm.tipo_id_funcionario.disabled=true;";
          $this->salida.="    frm.funcionario_id.disabled=true;";
          $this->salida.="    frm.nombre_recibe.disabled=true;";
          $this->salida.="    frm.telefono.disabled=true;";
          $this->salida.="    frm.observacion.disabled=true;";
          $this->salida.="  }else{";
          $this->salida.="  if(valor==2){";
          $this->salida.="    frm.parentesco.disabled=false;";
          $this->salida.="    frm.tipo_id_funcionario.disabled=true;";
          $this->salida.="    frm.funcionario_id.disabled=true;";
          $this->salida.="    frm.nombre_recibe.disabled=false;";
          $this->salida.="    frm.telefono.disabled=false;";
          $this->salida.="    frm.observacion.disabled=false;";
          $this->salida.="  }else{";
          $this->salida.="    frm.parentesco.disabled=true;";
          $this->salida.="    frm.tipo_id_funcionario.disabled=false;";
          $this->salida.="    frm.funcionario_id.disabled=false;";
          $this->salida.="    frm.nombre_recibe.disabled=false;";
          $this->salida.="    frm.telefono.disabled=true;";
          $this->salida.="    frm.observacion.disabled=true;";
          $this->salida.="  }";
          $this->salida.="  }";
          $this->salida.="}";
          $this->salida.="</script>";

          $this->salida.="<table  align=\"center\" border=\"0\" width=\"98%\" class=\"modulo_table_list\">";
          $this->salida.="<tr class=\"modulo_table_title\">";
          $this->salida.="<td align=\"center\">REGISTRO DE ENTREGA DE RESULTADOS</td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"modulo_list_oscuro\">";
          $this->salida.="<td>";
          $this->salida.="<BR><table width=\"80%\" border=\"0\" align=\"center\">";

          $this->salida.="<tr class=\"modulo_table_title\">";
          $this->salida.="<td class=\"".$this->SetStyle("responsable")."\" class=\"label\" colspan=\"2\" width=\"80%\" valign=\"top\">PERSONA QUE RECLAMA RESULTADOS DE APOYOS:</td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"modulo_list_claro\">";
          $this->salida.="<td class=\"label\" valign=\"top\">PACIENTE</td>";

          if(!$responsable)
          {
            $var='checked';
            $var1='disabled';
          }

          $this->salida.="<td valign=\"top\"><input type=\"radio\" name=\"responsable\" value=\"1\" onclick=\"desabilitar(this.form,this.value)\" $var></td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"modulo_list_claro\">";
          $this->salida.="<td class=\"label\" valign=\"top\">OTRO RESPONSABLE</td>";
          $this->salida.="<td valign=\"top\"><input type=\"radio\" name=\"responsable\" value=\"2\" onclick=\"desabilitar(this.form,this.value)\"></td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"modulo_list_claro\">";
          $this->salida.="<td class=\"label\" valign=\"top\">FUNCIONARIO DE LA INSTITUCION</td>";
          $this->salida.="<td valign=\"top\"><input type=\"radio\" name=\"responsable\" value=\"3\" onclick=\"desabilitar(this.form,this.value)\"></td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"modulo_list_claro\">";
          $this->salida.="<td colspan=\"2\">";
          $this->salida.="<BR><table width=\"80%\" border=\"0\" align=\"center\">";
          $this->salida.="<tr class=\"modulo_table_list_title\">";
          $this->salida.="<td colspan=\"3\">DATOS PERSONA </td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"modulo_list_oscuro\">";
          $this->salida.="<td colspan=\"1\" width=\"25%\" class=\"".$this->SetStyle("parentesco")."\">PARENTESCO</td>";
          $this->salida.="<td colspan=\"2\" width=\"55%\"><select name=\"parentesco\" class=\"select\" $var1>";
          $parentescos=$this->tiposParentescosPaciente();
          $this->MostrasSelect($parentescos,'False',$parentescoResponsable);
          $this->salida.="</select></td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"modulo_list_oscuro\">";
          $this->salida.="<td colspan=\"1\" class=\"".$this->SetStyle("identificacion")."\">No. IDENTIFICACION</td>";
          $this->salida .= "<td colspan=\"1\" ><select name=\"tipo_id_funcionario\" class=\"select\" $var1>";
          $tipo_id=$this->tipo_id_paciente();
          $this->BuscarIdPaciente($tipo_id,$_REQUEST['tipo_id_funcionario']);
          $this->salida .= "</select></td>";

          $this->salida.="<td colspan=\"1\"><input type=\"text\" class=\"input-text\" name=\"funcionario_id\" size =\"20\" maxlength=\"20\" value=\"$funcionario_id\" $var1></td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"modulo_list_oscuro\">";
          $this->salida.="<td colspan=\"1\" class=\"".$this->SetStyle("nombre_recibe")."\">NOMBRE</td>";
          $this->salida.="<td colspan=\"2\" ><input type=\"text\" class=\"input-text\" name=\"nombre_recibe\" size =\"32\" maxlength=\"32\" value=\"$nombre_recibe\" $var1></td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"modulo_list_oscuro\">";
          $this->salida.="<td colspan=\"1\" class=\"".$this->SetStyle("telefono")."\">TELEFONO</td>";
          $this->salida.="<td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"telefono\" size =\"20\" maxlength=\"20\" value=\"$telefono\" $var1></td>";
          $this->salida.="</tr>";

          $this->salida.="<tr class=\"modulo_list_oscuro\">";
          $this->salida.="<td colspan=\"1\" class=\"".$this->SetStyle("observacion")."\">OBSERVACION</td>";
          $this->salida.="<td colspan=\"2\" width=\"60%\" align='left' ><textarea style = \"width:80%\" class='textarea' name=\"observacion\" cols = 60 rows = 3 $var1>".$_REQUEST['observacion']."</textarea></td>" ;
          //$this->salida.="<td><input type=\"text\" class=\"input-text\" name=\"observacion\" maxlength=\"32\" value=\"$observacion\" $var1></td>";
          $this->salida.="</tr>";

          $this->salida.="</table><BR>";
          $this->salida.="</td>";
          $this->salida.="</tr>";
          $this->salida.="<tr class=\"modulo_list_oscuro\"><td colspan=\"7\" align=\"center\">";
          $this->salida.="<input type=\"submit\" class=\"input-submit\" value=\"GUARDAR\" name=\"Guardar\"></td></tr>";
          $this->salida.="</table><BR>";
          $this->salida.="</td></tr>";
          $this->salida.="</table>";
      	}
      	$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
      	$this->salida .= $this->SetStyle("MensajeError");
      	$this->salida.="</table>";
      }
      //FIN DE LA ENTREGA
      $this->salida.="</form>";
      //examenes entregados
      $entregados = $this->ConsultaExamenesEntregados($_SESSION[Paciente_Entrega]['departamento'], $_SESSION[Paciente_Entrega]['tipo_id_paciente'], $_SESSION[Paciente_Entrega]['paciente_id']);
  		if($entregados)
  		{
        $this->salida .= "<table  align=\"center\" border=\"0\"  width=\"80%\">";
        $this->salida .= "<tr class=\"modulo_table_list_title\">";
        $this->salida .= "  <br><td colspan=\"4\" align=\"center\" width=\"80%\">REGISTRO DE EXAMENES ENTREGADOS</td>";
        $this->salida .= "</tr>";
        $this->salida .= "<tr class=\"hc_table_submodulo_list_title\">";
        $this->salida .= "  <td  align=\"center\" width=\"15%\">FECHA DE RECIBIDO</td>";
        $this->salida .= "  <td  align=\"center\" width=\"15%\">HORA DE RECIBIDO</td>";
        $this->salida .= "  <td  align=\"center\" width=\"40%\">RECIBIO</td>";
        $this->salida .= "  <td  align=\"center\" width=\"10%\">DETALLE</td>";
        $this->salida .= "</tr>";

        $pintar = 0;
				for($i=0;$i<sizeof($entregados);$i++)
				{
          if( $i % 2){ $estilo='modulo_list_claro';}
          else {$estilo='modulo_list_oscuro';}

          if ($entregados[$i][apoyod_entrega_id] != $entregados[$i-1][apoyod_entrega_id])
          {
            $this->salida.="<tr class=\"$estilo\">";
            $this->salida.="  <td  align=\"center\" width=\"15%\">".$this->FechaStamp($entregados[$i][fecha_entrega])."</td>";
            $this->salida.="  <td  align=\"center\" width=\"15%\">".$this->HoraStamp($entregados[$i][fecha_entrega])."</td>";
            if ($entregados[$i][sw_tipo_persona]=='1')
            {
              $this->salida.="  <td align=\"center\" width=\"40%\">PERSONALMENTE</td>";
            }
            elseif($entregados[$i][sw_tipo_persona]=='2' OR $entregados[$i][sw_tipo_persona]=='3')
            {
              $this->salida.="  <td align=\"center\" width=\"40%\">".STRTOUPPER($entregados[$i][nombre])."</td>";
            }
            $pintar =1;
          }
          if ($pintar==1 and $_SESSION['BUSQUEDA_ORDEN']['numero_orden'] == $entregados[$i][numero_orden_id])
          {
            $this->salida .= "<td align=\"center\" width=\"10%\"><a href=".ModuloGetURL('app','Os_Entrega_Apoyod','user','VerDetalleEntrega',array('apoyod_entrega_id'=>$entregados[$i][apoyod_entrega_id]))."><img src=\"". GetThemePath() ."/images/auditoria_selec.png\" width='15' height='15'></a></td>";
            $this->salida.="</tr>";
            $pintar = 0;
          }
          if($pintar== 1 and $entregados[$i][apoyod_entrega_id] != $entregados[$i+1][apoyod_entrega_id])
          {
            $this->salida .= "<td align=\"center\" width=\"10%\"><a href=".ModuloGetURL('app','Os_Entrega_Apoyod','user','VerDetalleEntrega',array('apoyod_entrega_id'=>$entregados[$i][apoyod_entrega_id]))."><img src=\"". GetThemePath() ."/images/auditoria.png\" width='15' height='15'></a></td>";
            $this->salida.="</tr>";
            $pintar = 0;
          }
				}
				$this->salida.="</table>";
      }
      //fin de entrega
  		$accionV=ModuloGetURL('app','Os_Entrega_Apoyod','user','BuscarOrden');
  		$this->salida .= "<form name=\"forma\" action=\"$accionV\" method=\"post\">\n";
  		$this->salida .= "  <table  align=\"center\" border=\"0\"  width=\"80%\">\n";
  		$this->salida .= "    <tr>\n";
  		$this->salida .= "      <td align=\"center\">\n";
      $this->salida .= "        <input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"VOLVER\">\n";
      $this->salida .= "      </td>\n";
  		$this->salida .= "    </tr>\n";
  		$this->salida .= "  </table>\n";
      $this->salida .= "</form>\n";
  		$this->salida .= ThemeCerrarTabla();
      if($funcionA != "")
      {
        $this->salida .= "<script>\n";
        $this->salida .= "  ".$funcionA."\n";
        $this->salida .= "</script>\n";
      }
  		return true;
    }
    /**
    *
    */
    function VerDetalleEntrega()
    {
      $request = $_REQUEST;
  
      $datR['nombre'] = $request['nombre'];
      $datR['paciente_id'] = $request['paciente_id'];
      $datR['departamento'] = $request['departamento'];
      $datR['edad_paciente'] = $request['edad_paciente'];
      $datR['tipo_id_paciente'] = $request['tipo_id_paciente']; 
      $datR['apoyod_entrega_id'] = $request['apoyod_entrega_id']; 
      $datR['imprime_varios'] = 1; 
      
      $accion1 = ModuloGetURL('app','Os_Entrega_Apoyod','user','VerDetalleEntrega',$datR);// InsertarRegistroEntrega

      $this->salida .= "<script>\n";
  		$this->salida .= "  var resultados = ".(($request['imprime_varios'] == '1')? "0":sizeof($request['resultados'])).";\n";
  		$this->salida .= "  function SeleccionarExamenes(seleccion)\n";
  		$this->salida .= "  {\n";
  		$this->salida .= "    if(seleccion)\n";
  		$this->salida .= "      resultados++;\n";
  		$this->salida .= "    else\n";
  		$this->salida .= "      resultados--;\n";
  		$this->salida .= "    if(resultados > 0)\n";
  		$this->salida .= "      document.getElementById('imprimir_varios').style.display = 'block';\n";
  		$this->salida .= "    else\n";
  		$this->salida .= "      document.getElementById('imprimir_varios').style.display = 'none';\n";
  		$this->salida .= "  }\n";
  		$this->salida .= "  function ImprimirResultados()\n";
  		$this->salida .= "  {\n";
  		$this->salida .= "    cadena = \"".$accion1 ."\";\n";
  		$this->salida .= "    valores = document.getElementsByName('imprime');\n";
  		$this->salida .= "    for(i=0; i< valores.length; i++)\n";
  		$this->salida .= "    {\n";
  		$this->salida .= "      if(valores[i].checked)\n";
  		$this->salida .= "        cadena += \"&resultados[\"+i+\"]=\"+valores[i].value;\n";
  		$this->salida .= "    }\n";
      $this->salida .= "	  location.href = cadena; \n";
  		$this->salida .= "  }\n";
  		$this->salida .= "</script>\n";
  		$this->salida .= ThemeAbrirTablaSubModulo('VER DETALLE DE ENTREGA ');
      $this->salida .= "<table align=\"center\" class=\"modulo_table_list\" width=\"80%\">\n";
  		$this->salida .= "  <tr class=\"formulacion_table_list\">\n";
  		$this->salida .= "    <td >ID DEL PACIENTE</td>\n";
  		$this->salida .= "    <td >NOMBRE DEL PACIENTE</td>\n";
  		$this->salida .= "    <td >EDAD</td>\n";
  		$this->salida .= "  </tr>\n";
  		$this->salida .= "  <tr class=\"label\" align=\"center\">\n";
  		$this->salida .= "    <td >".$_SESSION[Paciente_Entrega]['tipo_id_paciente']." - ".$_SESSION[Paciente_Entrega]['paciente_id']."</td>\n";
  		$this->salida .= "    <td >".$_SESSION[Paciente_Entrega]['nombre']."</td>\n";
  		$this->salida .= "    <td >".$_SESSION[Paciente_Entrega]['edad_paciente']."</td>\n";
  		$this->salida .= "  </tr>\n";
  		$this->salida .= "</table><br>\n";
      $this->salida .= "<form name=\"forma\" action=\"$accion\" method=\"post\">";

      $entregados = $this->ConsultaDetalleExamenesEntregados($_SESSION[Paciente_Entrega]['departamento'], $_SESSION[Paciente_Entrega]['tipo_id_paciente'], $_SESSION[Paciente_Entrega]['paciente_id'], $_REQUEST[apoyod_entrega_id]);
  		if($entregados)
  		{
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida.="<tr class=\"modulo_table_list_title\">";
				$this->salida.="  <br><td colspan=\"7\" align=\"center\" width=\"80%\">EXAMENES ENTREGADOS</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="  <td align=\"center\" colspan=\"7\">";
				$this->salida.="<table>";
				$reporte= new GetReports();
        
        $display = "none";
        $chk = array();
        if($request['imprime_varios'] == '1')
        {
          $display = "block";
          $this->salida .= $reporte->GetJavaReport('app','ImpresionHC','examenesresultados',array('resultados'=>$request['resultados']),array('rpt_name'=>'examenesresultado','rpt_dir'=>'','rpt_rewrite'=>TRUE));
          $funcionA = $reporte->GetJavaFunction();
          
          foreach($request['resultados'] as $k => $dtl)
            $chk[$dtl] = "checked";
        }
        
				for($i=0;$i<sizeof($entregados);$i++)
				{
						if( $i % 2){ $estilo='modulo_list_claro';}
						else {$estilo='modulo_list_oscuro';}
							if ($i==0)
							{
									$this->salida.="<tr class=\"modulo_table_title\">";
									$this->salida.="  <td colspan=\"3\" align=\"left\" width=\"40%\">FECHA DE CUMPLIMIENTO: ".$entregados[$i][fecha_cumplimiento]."</td>";
									$this->salida.="  <td colspan=\"4\" align=\"left\" width=\"40%\">NUMERO DE CUMPLIMIENTO: ".$entregados[$i][numero_cumplimiento]."</td>";
									$this->salida.="</tr>";
									$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
									$this->salida.="  <td  align=\"center\" width=\"5%\">FECHA DE ENTREGA</td>";
									$this->salida.="  <td  align=\"center\" width=\"5%\">HORA DE ENTREGA</td>";
									$this->salida.="  <td  align=\"center\" width=\"30%\">RECIBIO</td>";
									$this->salida.="  <td colspan = 4 align=\"center\" width=\"40%\">OBSERVACION</td>";
									$this->salida.="</tr>";

									$this->salida.="<tr class=\"$estilo\">";
									$this->salida.="  <td  align=\"center\" width=\"5%\">".$this->FechaStamp($entregados[$i][fecha_entrega])."</td>";
									$this->salida.="  <td  align=\"center\" width=\"5%\">".$this->HoraStamp($entregados[$i][fecha_entrega])."</td>";
									if ($entregados[$i][sw_tipo_persona]=='1')
									{
											$this->salida.="  <td  align=\"center\" width=\"30%\">PERSONALMENTE</td>";
									}
									elseif($entregados[$i][sw_tipo_persona]=='2')
									{
											$this->salida.="  <td  align=\"center\" width=\"30%\">PARENTESCO:  ".$entregados[$i][parentesco]."<br>NOMBRE:  ".STRTOUPPER($entregados[$i][nombre])."<br>TEL.:  ".$entregados[$i][telefono]."</td>";
									}
									elseif($entregados[$i][sw_tipo_persona]=='3')
									{
											$this->salida.="  <td  align=\"center\" width=\"30%\">FUNCIONARIO:  ".STRTOUPPER($entregados[$i][nombre])."<br>IDENTIFICACION:  ".$entregados[$i][tipo_id_funcionario]." ".$entregados[$i][funcionario_id]."</td>";
									}
									$this->salida .= "    <td colspan = 4 align=\"left\" width=\"40%\">".$entregados[$i][observacion]."</td>";
									$this->salida .= "  </tr>\n";
									$this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
									$this->salida .= "    <td align=\"center\" width=\"5%\">ITEM DE LA ORDEN</td>\n";
									$this->salida .= "    <td align=\"center\" width=\"5%\">No. RESULTADO</td>\n";
									$this->salida .= "    <td align=\"center\" width=\"30%\">CARGO</td>\n";
									$this->salida .= "    <td align=\"center\" width=\"30%\">DESCRIPCION</td>\n";
									$this->salida .= "    <td align=\"center\" width=\"5%\">IMPRESION</td>\n";
									$this->salida .= "    <td align=\"center\" width=\"5%\">REENTREGA</td>\n";
									$this->salida .= "    <td align=\"center\" width=\"5%\">OP</td>\n";
									$this->salida .= "  </tr>\n";
							}
							$this->salida.="<tr class=\"$estilo\">";
							if ($_SESSION['BUSQUEDA_ORDEN']['numero_orden'] == $entregados[$i][numero_orden_id])
							{
								$this->salida.="  <td  class = \"label_error\" align=\"center\" width=\"5%\">".$entregados[$i][numero_orden_id]."</td>";
							}
							else
							{
	              $this->salida.="  <td  align=\"center\" width=\"5%\">".$entregados[$i][numero_orden_id]."</td>";
							}
							$this->salida.="  <td  align=\"center\" width=\"5%\">".$entregados[$i][resultado_id]."</td>";
							$this->salida.="  <td  align=\"center\" width=\"30%\">".$entregados[$i][cargo]."</td>";
							$this->salida.="  <td  align=\"left\" width=\"30%\">".$entregados[$i][descripcion]."</td>";

							//$mostrar = $reporte->GetJavaReport('app','Os_Listas_Trabajo_Apoyod_Agrupado','examenes_html',array('resultado_id'=>$entregados[$i][resultado_id]),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
              $mostrar = $reporte->GetJavaReport('app','ImpresionHC','examenesresultados',array('resultado_id'=>$entregados[$i][resultado_id]),array('rpt_name'=>'examenesresultado','rpt_dir'=>'','rpt_rewrite'=>TRUE));

              $nombre_funcion=$reporte->GetJavaFunction();
							$this->salida .=$mostrar;
							$this->salida .= "<td  align=\"center\" width=\"10%\" valign=\"center\"><a href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR</a></td>";

						  //CONTOLANDO EL ACCESO A LA REENTREGA DE EXAMENES
							if ($_SESSION['ENTREGA']['ACCESO']!= '1')
							{
							  $this->salida .= "<td  align=\"center\" width=\"5%\"><a href=".ModuloGetURL('app','Os_Entrega_Apoyod','user','VerDetalleEntrega',array('apoyod_entrega_id'=>$entregados[$i][apoyod_entrega_id],'resultado_id'=>$entregados[$i][resultado_id], 'descripcion'=>$entregados[$i][descripcion], 'reentrega'=>'1'))."><img src=\"". GetThemePath() ."/images/resultado.png\" ></a></td>";
							}
							else
							{
                $this->salida .= "<td  align=\"center\" width=\"5%\"><img src=\"". GetThemePath() ."/images/resultado.png\" ></td>";
							}
							//FIN DEL CONTROL          
              $this->salida .= "      <td align=\"center\" >\n";
              $this->salida .= "        <input type=\"checkbox\" name= \"imprime\" value = ".$entregados[$i]['resultado_id']." ".$chk[$entregados[$i]['resultado_id']]." onclick=\"SeleccionarExamenes(this.checked)\">\n";
              $this->salida .= "      </td>\n";
							$this->salida .= "    </tr>\n";
				}
			  $this->salida .= "        </table>\n";
				$this->salida .= "      </td>\n";
				$this->salida .= "    </tr>\n";
				$this->salida .= "  </table>\n";
        $this->salida .= "  <br>\n";
        $this->salida .= "  <center>\n";
        $this->salida .= "    <div id=\"imprimir_varios\" style=\"display:".$display."\">\n";
        $this->salida .= "      <input type=\"button\" class=\"input-submit\" name=\"imprimir\" value=\"Imprimir Varios Resultados\" onclick=\"ImprimirResultados()\">\n";
        $this->salida .= "    </div>\n";
        $this->salida .= "  </center>\n";
      }
			$this->salida.="</form>";
      //fin de entrega
      //examenes reentregados
      $reentregas = $this->ConsultaExamenesReEntregados($_REQUEST[apoyod_entrega_id]);
  		if($reentregas)
  		{
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="  <br><td colspan=\"5\" align=\"center\" width=\"80%\">REGISTRO DE EXAMENES REENTREGADOS</td>";
				$this->salida.="</tr>";
				for($i=0;$i<sizeof($reentregas);$i++)
				{
						if( $i % 2){ $estilo='modulo_list_claro';}
						else {$estilo='modulo_list_oscuro';}
							if ($i==0)
							{
									$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
									$this->salida.="  <td  align=\"center\" width=\"10%\">ITEM - RESULTADO</td>";
									$this->salida.="  <td  align=\"center\" width=\"10%\">FECHA - HORA ENTREGA</td>";
									$this->salida.="  <td  align=\"center\" width=\"10%\">CARGO</td>";
									$this->salida.="  <td  align=\"center\" width=\"25%\">DESCRIPCION</td>";
									$this->salida.="  <td  align=\"center\" width=\"25%\">OBSERVACION</td>";
									$this->salida.="</tr>";
							}
							$this->salida.="<tr class=\"$estilo\">";
							if ($_SESSION['BUSQUEDA_ORDEN']['numero_orden'] == $reentregas[$i][numero_orden_id])
							{
								$this->salida.="  <td  align=\"center\" class = \"label_error\" width=\"10%\">".$reentregas[$i][numero_orden_id]."-".$reentregas[$i][resultado_id]."</td>";
							}
							else
							{
    						$this->salida.="  <td  align=\"center\" width=\"10%\">".$reentregas[$i][numero_orden_id]."-".$reentregas[$i][resultado_id]."</td>";
							}
							$this->salida.="  <td  align=\"center\" width=\"10%\">".$this->FechaStamp($reentregas[$i][fecha_entrega])."<BR>".$this->HoraStamp($reentregas[$i][fecha_entrega])."</td>";
							$this->salida.="  <td  align=\"center\" width=\"10%\">".$reentregas[$i][cargo]."</td>";
							$this->salida.="  <td  align=\"left\" width=\"25%\">".$reentregas[$i][descripcion]."</td>";
							$this->salida.="  <td  align=\"left\" width=\"25%\">".$reentregas[$i][observacion]."</td>";
							$this->salida.="</tr>";
				}
				$this->salida.="</table>";
      }
      //fin de entrega

      //registro de reentrega
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida.="</table>";
      if ($_REQUEST[reentrega] == '1')
			{
				$accion=ModuloGetURL('app','Os_Entrega_Apoyod','user','InsertarRegistroReEntrega',array('apoyod_entrega_id'=>$_REQUEST[apoyod_entrega_id],'resultado_id'=>$_REQUEST[resultado_id], 'descripcion'=>$_REQUEST[descripcion], 'reentrega'=>'1'));
	      $this->salida.="<form name=\"forma\" action=\"$accion\" method=\"post\">";
        $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="  <br><td colspan=\"3\" align=\"center\" width=\"80%\">REENTREGA DE RESULTADOS</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="  <td colspan=\"1\" align=\"center\" width=\"20%\">No. RESULTADO: ".$_REQUEST[resultado_id]."</td>";
				$this->salida.="  <td colspan=\"2\" align=\"center\" width=\"50%\">".$_REQUEST[descripcion]."</td>";
				$this->salida.="</tr>";
        $this->salida.="<tr class=\"$estilo\">";
				$this->salida.="<td class=\"".$this->SetStyle("observacion_reentrega")."\" align=\"center\" width=\"20%\" >OBSERVACION</td>";
				$this->salida.="<td  width=\"50%\" align=\"center\" ><textarea style = \"width:80%\" class='textarea' name = 'observacion_reentrega$pfj' cols = 60 rows = 3>".$_REQUEST[observacion_reentrega]."</textarea></td>" ;
				$this->salida.="<td align=\"center\" width=\"10%\" ><input type=\"submit\" class=\"input-submit\" value=\"GUARDAR\" name=\"Guardar\"></td>";
				$this->salida.="</tr>";
				$this->salida.="</table>";
				$this->salida.="</form>";
			}
      //registro de reentrega
      $this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
      $this->salida.="<tr>";
      //BOTON DE VOLVER
      $accionV=ModuloGetURL('app','Os_Entrega_Apoyod','user','Consultar_Examenes_Paciente');
      $this->salida .= "<form name=\"forma\" action=\"$accionV\" method=\"post\">";
      $this->salida .= "<td  colspan = 2 align=\"center\"><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"VOLVER\"></form></td>";
      $this->salida.="</tr>";
      $this->salida.="</table>";
      $this->salida .= ThemeCerrarTabla();
      if($funcionA != "")
      {
        $this->salida .= "<script>\n";
        $this->salida .= "  ".$funcionA."\n";
        $this->salida .= "</script>\n";
      }
      return true;
    }

	/*
	* Esta funcion calcula el numero de pasos que saldran en la barra de navegación.
	* @return boolean
	*/
	function CalcularNumeroPasos($conteo)
	{
			$numpaso=ceil($conteo/$this->limit);
			return $numpaso;
	}

	/*
	* Esta funcion calcula la barra de navegación.
	* @return boolean
	*/
	function CalcularBarra($paso)
	{
			$barra=floor($paso/10)*10;
			if(($paso%10)==0)
			{
					$barra=$barra-10;
			}
			return $barra;
	}

	/*
	* Esta funcion calcula los segmentos en que se desplaza el apuntador de los registros
	* de la base de datos.
	* @return boolean
	*/
	function CalcularOffset($paso)
	{
			$offset=($paso*$this->limit)-$this->limit;
			return $offset;
	}


/*
* Esta funcion integra (CalcularNumeroPasos,CalcularOffset,CalcularBarra), para asi
* crear una barra de navegacion, para los registros.
* @return boolean
*/
function RetornarBarra()
{
		//$this->conteo;
		//$this->limit;

		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso'];
		if(is_null($paso))
		{
		   $paso=1;
		}
    $vec='';
		foreach($_REQUEST as $v=>$v1)
		{
				if($v!='modulo' and $v!='metodo' and $v!='SIIS_SID' and  $v!='Of')
				{
				  $vec[$v]=$v1;
				}
    }
		$accion=ModuloGetURL('app','Os_Entrega_Apoyod','user','BuscarOrden',$vec);
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;

		$this->salida .= "<br><table border='1' align='center'  cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
				$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset(1)."&paso=1'>&lt;</a></td>";
				$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso-1)."&paso=".($paso-1)."'>&lt;&lt;</a></td>";
				$colspan+=1;
		}
		else
		{
      // $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
	    //$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
    }
		$barra ++;
		if(($barra+10)<=$numpasos)
		{
				for($i=($barra);$i<($barra+10);$i++)
				{
						if($paso==$i)
						{
							$this->salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
						}
						else
						{
							$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
						}
						$colspan++;
				}
				$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
				$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
	      $colspan+=2;
		}
		else
		{
				$diferencia=$numpasos-9;
				if($diferencia<=0){$diferencia=1;}//cambiar en todas las barra
				for($i=($diferencia);$i<=$numpasos;$i++)
				{
    				if($paso==$i)
						{
								$this->salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
						}
						else
						{
								$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($i)."&paso=$i' >$i</a></td>";
						}
						$colspan++;
				}
				if($paso!=$numpasos)
				{
					$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($paso+1)."&paso=".($paso+1)."' >&gt;&gt;</a></td>";
						$this->salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of=".$this->CalcularOffset($numpasos)."&paso=$numpasos'>&gt;</a></td>";
						$colspan++;
				}
				else
				{
	// $this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
		//$this->salida .= "<td bgcolor=\"#DDDDDD\">&nbsp;</td>";
	      }
        }
				if(($_REQUEST['Of'])==0 OR ($paso==$numpasos))
				{
						if($numpasos>10)
						{
							$valor=10+3;
						}
						else
						{
									$valor=$numpasos+3;
						}
							$this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
						}
						else
						{
								if($numpasos>10)
								{
										$valor=10+5;
								}
								else
								{
                            $valor=$numpasos+5;
                        }
                    $this->salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Página $paso de $numpasos</td><tr></table>";
                    }

    }


//FUNCIONES QUE ACOMPAÑAN AL CALENDARIO
/**
* Funcion que Saca los años para el calendario a partir del año actual
* @return array
*/
 function AnosAgenda($Seleccionado='False',$ano)
	{
			$anoActual=date("Y");
			//$ano = $anoActual;
			$anoActual1=$anoActual-10;
	    for($i=0;$i<=20;$i++)
			{
		      $vars[$i]=$anoActual1;
					$anoActual1=$anoActual1+1;
			}
			switch($Seleccionado)
			{
					case 'False':
					{
							foreach($vars as $value=>$titulo)
							{
								if($titulo==$ano)
								{
										$this->salida .=" <option value=\"$titulo\" selected>$titulo</option>";
								}
								else
								{
									$this->salida .=" <option value=\"$titulo\">$titulo</option>";
								}
							}
							break;
					}
					case 'True':
					{
						foreach($vars as $value=>$titulo)
						{
							if($titulo==$ano)
							{
									$this->salida .=" <option value=\"$titulo\" selected>$titulo</option>";
						  }
							else
							{
									$this->salida .=" <option value=\"$titulo\">$titulo</option>";
							}
						}
						break;
          }
      }
    }

	function MesesAgenda($Seleccionado='False',$Año,$Defecto)
	{
			$anoActual=date("Y");
			$vars[1]='ENERO';
	    $vars[2]='FEBRERO';
			$vars[3]='MARZO';
			$vars[4]='ABRIL';
			$vars[5]='MAYO';
			$vars[6]='JUNIO';
			$vars[7]='JULIO';
			$vars[8]='AGOSTO';
			$vars[9]='SEPTIEMBRE';
			$vars[10]='OCTUBRE';
			$vars[11]='NOVIEMBRE';
			$vars[12]='DICIEMBRE';
			//$mesActual=date("m");
			switch($Seleccionado)
			{
				case 'False':
				{
					if($anoActual==$Año)
						{
						foreach($vars as $value=>$titulo)
						{
						    if($value>=$mesActual)
								{
									if($value==$Defecto)
										{
												$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
										}else{
												$this->salida .=" <option value=\"$value\">$titulo</option>";
										}
								}
						}
						}
						else
						{
							foreach($vars as $value=>$titulo)
							{
								if($value==$Defecto)
								{
										$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
								}
								else
								{
										$this->salida .=" <option value=\"$value\">$titulo</option>";
								}
							}
						}
						break;
        }
				case 'True':
				{
					if($anoActual==$Año)
						{
							foreach($vars as $value=>$titulo)
							{
								if($value>=$mesActual)
								{
									if($value==$Defecto)
									{
											$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
									}
									else
									{
											$this->salida .=" <option value=\"$value\">$titulo</option>";
									}
								}
							}
						}
						else
						{
							foreach($vars as $value=>$titulo)
							{
									if($value==$Defecto)
									{
											$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
									}else
									{
											$this->salida .=" <option value=\"$value\">$titulo</option>";
									}
							}
						}
						break;
					}
			}
	}


/**
* Funcion que se encarga de listar los nombres de los tipos de origen de una cirugia
* @return array
* @param array codigos y valores de los tipos de origen de la base de datos
* @param boolean indicador de selecion de un elemento en el objeto donde se imprimen los tipos de origen
* @param string elemento seleccionado en el objeto donde se imprimen los tipo de origen
*/
	function MostrasSelect($arreglo,$Seleccionado='False',$valor=''){
		switch($Seleccionado){
			case 'False':{
        $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
				foreach($arreglo as $value=>$titulo){
					if($value==$valor){
					$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				  }else{
            $this->salida .=" <option value=\"$value\">$titulo</option>";
				  }
			  }
			  break;
		  }case 'True':{
			  foreach($arreglo as $value=>$titulo){
				  if($value==$valor){
				    $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				  }
				  $this->salida .=" <option value=\"$value\">$titulo</option>";
			  }
			  break;
		  }
	  }
	}

	/*********************************CODIGO LORENA*********************/


  function EntregaResultadoPatologia($tipo_id_paciente,$paciente_id,$nombre,$edad_paciente){

		$this->salida= ThemeAbrirTablaSubModulo('CONSULTA DE EXAMENES DEL PACIENTE');
		$accion=ModuloGetURL('app','Os_Entrega_Apoyod','user','InsertarRegistroEntregaPatologia',array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"nombre"=>$nombre,"edad_paciente"=>$edad_paciente));
	  $this->salida.="<form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"1\">ID DEL PACIENTE</td>";
		$this->salida.="  <td align=\"center\" colspan=\"2\">NOMBRE DEL PACIENTE</td>";
		$this->salida.="  <td align=\"center\" colspan=\"1\">EDAD</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"1\">".$tipo_id_paciente." - ".$paciente_id."</td>";
		$this->salida.="  <td align=\"center\" colspan=\"2\">".$nombre."</td>";
		$this->salida.="  <td align=\"center\" colspan=\"1\">".$edad_paciente."</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";

		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";

		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr class=\"modulo_table_list_title\">";
		$this->salida.="  <br><td colspan=\"4\" align=\"center\" width=\"40%\">EXAMENES PENDIENTES POR ENTREGAR</td>";
		$this->salida.="</tr>";
		$reporte= new GetReports();
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="  <td  align=\"center\" width=\"5%\">No. RESULTADO</td>";
		$this->salida.="  <td  align=\"center\" width=\"50%\">DESCRIPCION</td>";
		$this->salida.="  <td  align=\"center\" width=\"5%\">ENTREGAR</td>";
		$this->salida.="  <td  align=\"center\" width=\"5%\">IMPRIMIR</td>";
		$this->salida.="</tr>";
		$vector = $this->ConsultaExamenesPatologia($tipo_id_paciente,$paciente_id);
    for($i=0;$i<sizeof($vector);$i++){
      if( $i % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
			$this->salida.="<tr class=\"$estilo\">";
			$this->salida.="  <td  align=\"center\" width=\"5%\">".$vector[$i][prefijo]." ".$vector[$i][resultado_informe_id]."</td>";
			$this->salida.="  <td  align=\"left\" width=\"50%\">".$vector[$i][descripcion]."</td>";
			if($vector[$i][examen_firmado]!=1){
			  $this->salida.="  <td align=\"center\" width=\"10%\">SIN FIRMAR</td>";
				$this->salida.="  <td>&nbsp;</td>";
			}else{
				$this->salida.="  <td align=\"center\" width=\"5%\"><input type = checkbox name=\"op[]\" value = ".$vector[$i][prefijo]."||//".$vector[$i][resultado_informe_id]."></td>";
				//lo de alex
				$mostrar=$reporte->GetJavaReport('app','Patologia','examenes_html',array("prefijo"=>$vector[$i][prefijo],"resultado_id"=>$vector[$i][resultado_informe_id]),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
				$nombre_funcion=$reporte->GetJavaFunction();
				$this->salida .=$mostrar;
				$this->salida.="<td  align=\"center\" width=\"5%\" valign=\"center\"><a href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'></a></td>";
				//fin de alex
			}
			$this->salida.="</tr>";
		}
		$this->salida.="</table>";
    //INICIO DE REGISTRO DE ENTREGA
    //validando el acceso a la forma de entrega de resultados
	  if(sizeof($vector)>0){
			$this->salida.="<script>";
			$this->salida.="function desabilitar(frm,valor){";
			$this->salida.="  if(valor==1){";
			$this->salida.="    frm.parentesco.disabled=true;";
			$this->salida.="    frm.tipo_id_funcionario.disabled=true;";
			$this->salida.="    frm.funcionario_id.disabled=true;";
			$this->salida.="    frm.nombre_recibe.disabled=true;";
			$this->salida.="    frm.telefono.disabled=true;";
			$this->salida.="    frm.observacion.disabled=true;";
			$this->salida.="  }else{";
      $this->salida.="  if(valor==2){";
      $this->salida.="    frm.parentesco.disabled=false;";
			$this->salida.="    frm.tipo_id_funcionario.disabled=true;";
			$this->salida.="    frm.funcionario_id.disabled=true;";
			$this->salida.="    frm.nombre_recibe.disabled=false;";
      $this->salida.="    frm.telefono.disabled=false;";
      $this->salida.="    frm.observacion.disabled=false;";
			$this->salida.="  }else{";
      $this->salida.="    frm.parentesco.disabled=true;";
			$this->salida.="    frm.tipo_id_funcionario.disabled=false;";
			$this->salida.="    frm.funcionario_id.disabled=false;";
			$this->salida.="    frm.nombre_recibe.disabled=false;";
      $this->salida.="    frm.telefono.disabled=true;";
      $this->salida.="    frm.observacion.disabled=true;";
			$this->salida.="  }";
			$this->salida.="  }";
			$this->salida.="}";
			$this->salida.="</script>";
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="<td align=\"center\">REGISTRO DE ENTREGA DE RESULTADOS</td>";
			$this->salida.="</tr>";
 			$this->salida.="<tr class=\"modulo_list_oscuro\">";
			$this->salida.="<td>";
      $this->salida.="<BR><table width=\"80%\" border=\"0\" align=\"center\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="<td class=\"".$this->SetStyle("responsable")."\" class=\"label\" colspan=\"2\" width=\"80%\" valign=\"top\">PERSONA QUE RECLAMA RESULTADOS DE APOYOS:</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"modulo_list_claro\">";
			$this->salida.="<td class=\"label\" valign=\"top\">PACIENTE</td>";
			if(!$responsable){
   	    $var='checked';
				$var1='disabled';
			}
			$this->salida.="<td valign=\"top\"><input type=\"radio\" name=\"responsable\" value=\"1\" onclick=\"desabilitar(this.form,this.value)\" $var></td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"modulo_list_claro\">";
			$this->salida.="<td class=\"label\" valign=\"top\">OTRO RESPONSABLE</td>";
			$this->salida.="<td valign=\"top\"><input type=\"radio\" name=\"responsable\" value=\"2\" onclick=\"desabilitar(this.form,this.value)\"></td>";
      $this->salida.="</tr>";
			$this->salida.="<tr class=\"modulo_list_claro\">";
			$this->salida.="<td class=\"label\" valign=\"top\">FUNCIONARIO DE LA INSTITUCION</td>";
			$this->salida.="<td valign=\"top\"><input type=\"radio\" name=\"responsable\" value=\"3\" onclick=\"desabilitar(this.form,this.value)\"></td>";
      $this->salida.="</tr>";
			$this->salida.="<tr class=\"modulo_list_claro\">";
			$this->salida.="<td colspan=\"2\">";
			$this->salida.="<BR><table width=\"80%\" border=\"0\" align=\"center\">";
			$this->salida.="<tr class=\"modulo_table_list_title\">";
			$this->salida.="<td colspan=\"3\">DATOS PERSONA </td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"modulo_list_oscuro\">";
			$this->salida.="<td colspan=\"1\" width=\"25%\" class=\"".$this->SetStyle("parentesco")."\">PARENTESCO</td>";
			$this->salida.="<td colspan=\"2\" width=\"55%\"><select name=\"parentesco\" class=\"select\" $var1>";
			$parentescos=$this->tiposParentescosPaciente();
			$this->MostrasSelect($parentescos,'False',$parentescoResponsable);
			$this->salida.="</select></td>";
			$this->salida.="</tr>";
      $this->salida.="<tr class=\"modulo_list_oscuro\">";
			$this->salida.="<td colspan=\"1\" class=\"".$this->SetStyle("identificacion")."\">No. IDENTIFICACION</td>";
			$this->salida .= "<td colspan=\"1\" ><select name=\"tipo_id_funcionario\" class=\"select\" $var1>";
			$tipo_id=$this->tipo_id_paciente();
			$this->BuscarIdPaciente($tipo_id,$_REQUEST['tipo_id_funcionario']);
			$this->salida .= "</select></td>";
			$this->salida.="<td colspan=\"1\"><input type=\"text\" class=\"input-text\" name=\"funcionario_id\" size =\"20\" maxlength=\"20\" value=\"$funcionario_id\" $var1></td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"modulo_list_oscuro\">";
			$this->salida.="<td colspan=\"1\" class=\"".$this->SetStyle("nombre_recibe")."\">NOMBRE</td>";
			$this->salida.="<td colspan=\"2\" ><input type=\"text\" class=\"input-text\" name=\"nombre_recibe\" size =\"32\" maxlength=\"32\" value=\"$nombre_recibe\" $var1></td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"modulo_list_oscuro\">";
			$this->salida.="<td colspan=\"1\" class=\"".$this->SetStyle("telefono")."\">TELEFONO</td>";
			$this->salida.="<td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"telefono\" size =\"20\" maxlength=\"20\" value=\"$telefono\" $var1></td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"modulo_list_oscuro\">";
      $this->salida.="<td colspan=\"1\" class=\"".$this->SetStyle("observacion")."\">OBSERVACION</td>";
		  $this->salida.="<td colspan=\"2\" width=\"60%\" align='left' ><textarea style = \"width:80%\" class='textarea' name=\"observacion\" cols = 60 rows = 3 $var1>".$_REQUEST['observacion']."</textarea></td>" ;
			//$this->salida.="<td><input type=\"text\" class=\"input-text\" name=\"observacion\" maxlength=\"32\" value=\"$observacion\" $var1></td>";
			$this->salida.="</tr>";
			$this->salida.="</table><BR>";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"modulo_list_oscuro\"><td colspan=\"7\" align=\"center\">";
			$this->salida.="<input type=\"submit\" class=\"input-submit\" value=\"GUARDAR\" name=\"Guardar\"></td></tr>";
			$this->salida.="</table><BR>";
			$this->salida.="</td></tr>";
			$this->salida.="</table>";
	  }
    //FIN DE LA ENTREGA
    $this->salida.="</form>";
    //examenes entregados
    $entregados = $this->ConsultaExamenesEntregadosPatologia($tipo_id_paciente,$paciente_id);
		if($entregados){
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida.="<tr class=\"modulo_table_list_title\">";
			$this->salida.="  <br><td colspan=\"4\" align=\"center\" width=\"80%\">REGISTRO DE EXAMENES ENTREGADOS</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td  align=\"center\" width=\"15%\">FECHA DE RECIBIDO</td>";
			$this->salida.="  <td  align=\"center\" width=\"15%\">HORA DE RECIBIDO</td>";
			$this->salida.="  <td  align=\"center\" width=\"40%\">RECIBIO</td>";
			$this->salida.="  <td  align=\"center\" width=\"10%\">DETALLE</td>";
			$this->salida.="</tr>";
			for($i=0;$i<sizeof($entregados);$i++){
			  if( $i % 2){ $estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="  <td  align=\"center\" width=\"15%\">".$this->FechaStamp($entregados[$i][fecha_entrega])."</td>";
				$this->salida.="  <td  align=\"center\" width=\"15%\">".$this->HoraStamp($entregados[$i][fecha_entrega])."</td>";
				if($entregados[$i][sw_tipo_persona]=='1'){
					$this->salida.="  <td align=\"center\" width=\"40%\">PERSONALMENTE</td>";
				}elseif($entregados[$i][sw_tipo_persona]=='2' OR $entregados[$i][sw_tipo_persona]=='3'){
					$this->salida.="  <td align=\"center\" width=\"40%\">".STRTOUPPER($entregados[$i][nombre])."</td>";
				}
				$this->salida .= "<td align=\"center\" width=\"10%\"><a href=".ModuloGetURL('app','Os_Entrega_Apoyod','user','VerDetalleEntregaPatologia',array('apoyod_entrega_id'=>$entregados[$i][apoyod_entrega_id],"tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"nombre"=>$nombre,"edad_paciente"=>$edad_paciente))."><img src=\"". GetThemePath() ."/images/auditoria.png\" width='15' height='15'></a></td>";
				$this->salida.="</tr>";
			}
			$this->salida.="</table>";
    }
    //fin de entrega
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr>";
		//BOTON DE VOLVER
		$accionV=ModuloGetURL('app','Os_Entrega_Apoyod','user','BuscarOrden');
		$this->salida .= "<form name=\"forma\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "<td  colspan = 2 align=\"center\"><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"VOLVER\"></form></td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function FormaDatelleEntregaPatologia($apoyod_entrega_id,$tipo_id_paciente,$paciente_id,$nombre,$edad_paciente,
	$resultado_idInfo,$prefijoInfo,$descripcionInfo,$reentrega){

		$this->salida= ThemeAbrirTablaSubModulo('VER DETALLE DE ENTREGA');
    $this->salida.="<form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"1\">ID DEL PACIENTE</td>";
		$this->salida.="  <td align=\"center\" colspan=\"2\">NOMBRE DEL PACIENTE</td>";
		$this->salida.="  <td align=\"center\" colspan=\"1\">EDAD</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"1\">".$tipo_id_paciente." - ".$paciente_id."</td>";
		$this->salida.="  <td align=\"center\" colspan=\"2\">".$nombre."</td>";
		$this->salida.="  <td align=\"center\" colspan=\"1\">".$edad_paciente."</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
    //examenes entregados
    $entregados = $this->ConsultaDetalleExamenesEntregadosPat($tipo_id_paciente,$paciente_id,$apoyod_entrega_id);
		if($entregados){
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida.="<tr class=\"modulo_table_list_title\">";
			$this->salida.="  <br><td colspan=\"6\" align=\"center\" width=\"80%\">EXAMENES ENTREGADOS</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"6\">";
			$this->salida.="<table border=\"0\">";
			$reporte= new GetReports();
			for($i=0;$i<sizeof($entregados);$i++){
				if( $i % 2){ $estilo='modulo_list_claro';}else {$estilo='modulo_list_oscuro';}
				if($i==0){
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="  <td align=\"center\" width=\"5%\">FECHA DE ENTREGA</td>";
					$this->salida.="  <td align=\"center\" width=\"5%\">HORA DE ENTREGA</td>";
					$this->salida.="  <td align=\"center\" width=\"30%\">RECIBIO</td>";
					$this->salida.="  <td colspan=\"2\" align=\"center\" width=\"40%\">OBSERVACION</td>";
					$this->salida.="</tr>";

					$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="  <td align=\"center\" width=\"5%\">".$this->FechaStamp($entregados[$i][fecha_entrega])."</td>";
					$this->salida.="  <td align=\"center\" width=\"5%\">".$this->HoraStamp($entregados[$i][fecha_entrega])."</td>";
					if($entregados[$i][sw_tipo_persona]=='1'){
						$this->salida.="  <td align=\"center\" width=\"30%\">PERSONALMENTE</td>";
					}elseif($entregados[$i][sw_tipo_persona]=='2'){
						$this->salida.="  <td align=\"center\" width=\"30%\">PARENTESCO:  ".$entregados[$i][parentesco]."<br>NOMBRE:  ".STRTOUPPER($entregados[$i][nombre])."<br>TEL.:  ".$entregados[$i][telefono]."</td>";
					}elseif($entregados[$i][sw_tipo_persona]=='3'){
						$this->salida.="  <td align=\"center\" width=\"30%\">FUNCIONARIO:  ".STRTOUPPER($entregados[$i][nombre])."<br>IDENTIFICACION:  ".$entregados[$i][tipo_id_funcionario]." ".$entregados[$i][funcionario_id]."</td>";
					}
					$this->salida.="<td colspan=\"2\" align=\"left\" width=\"40%\">".$entregados[$i][observacion]."</td>";
					$this->salida.="</tr>";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="  <td align=\"center\" width=\"5%\">No. RESULTADO</td>";
					$this->salida.="  <td align=\"center\" width=\"30%\" colspan=\"2\">DESCRIPCION</td>";
					$this->salida.="  <td align=\"center\" width=\"5%\">IMPRESION</td>";
					$this->salida.="  <td align=\"center\" width=\"5%\">REENTREGA</td>";
					$this->salida.="</tr>";
				}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="  <td align=\"center\" width=\"5%\">".$entregados[$i][prefijo]." ".$entregados[$i][resultado_informe_id]."</td>";
				$this->salida.="  <td align=\"left\" width=\"30%\" colspan=\"2\">".$entregados[$i][descripcion]."</td>";

				$mostrar=$reporte->GetJavaReport('app','Patologia','examenes_html',array('resultado_id'=>$entregados[$i][resultado_informe_id],"prefijo"=>$entregados[$i][prefijo]),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
				$nombre_funcion=$reporte->GetJavaFunction();
				$this->salida .=$mostrar;
				$this->salida.="<td  align=\"center\" width=\"10%\" valign=\"center\"><a href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR</a></td>";

				$this->salida .= "<td  align=\"center\" width=\"5%\"><a href=".ModuloGetURL('app','Os_Entrega_Apoyod','user','VerDetalleEntregaPatologia',array('resultado_id'=>$entregados[$i][resultado_informe_id],"prefijo"=>$entregados[$i][prefijo],'descripcion'=>$entregados[$i][descripcion],'apoyod_entrega_id'=>$apoyod_entrega_id,'tipo_id_paciente'=>$tipo_id_paciente,'paciente_id'=>$paciente_id,'nombre'=>$nombre,'edad_paciente'=>$edad_paciente,'reentrega'=>'1'))."><img src=\"". GetThemePath() ."/images/resultado.png\" ></a></td>";

				$this->salida.="</tr>";
			}
			$this->salida.="</table>";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="</table>";
		}
		$this->salida.="</form>";

		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";

		$reentregas = $this->ConsultaExamenesReEntregadosPat($apoyod_entrega_id);
		if($reentregas){
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <br><td colspan=\"4\" align=\"center\" width=\"80%\">REGISTRO DE EXAMENES REENTREGADOS</td>";
			$this->salida.="</tr>";
			for($i=0;$i<sizeof($reentregas);$i++){
				if( $i % 2){ $estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
				if($i==0){
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="  <td  align=\"center\" width=\"10%\">No. RESULTADO</td>";
					$this->salida.="  <td  align=\"center\" width=\"10%\">FECHA - HORA ENTREGA</td>";
					$this->salida.="  <td  align=\"center\" width=\"25%\">DESCRIPCION</td>";
					$this->salida.="  <td  align=\"center\" width=\"25%\">OBSERVACION</td>";
					$this->salida.="</tr>";
				}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="  <td  align=\"center\" class = \"label_error\" width=\"10%\">".$reentregas[$i][prefijo]." ".$reentregas[$i][resultado_informe_id]."</td>";
				$this->salida.="  <td  align=\"center\" width=\"10%\">".$this->FechaStamp($reentregas[$i][fecha_entrega])."<BR>".$this->HoraStamp($reentregas[$i][fecha_entrega])."</td>";
				$this->salida.="  <td  align=\"left\" width=\"25%\">".$reentregas[$i][descripcion]."</td>";
				$this->salida.="  <td  align=\"left\" width=\"25%\">".$reentregas[$i][observacion]."</td>";
				$this->salida.="</tr>";
			}
			$this->salida.="</table>";
		}

		if($reentrega== '1'){
			$accion=ModuloGetURL('app','Os_Entrega_Apoyod','user','InsertarRegistroReEntregaPatologia',array('apoyod_entrega_id'=>$apoyod_entrega_id,'resultado_id'=>$resultado_idInfo,'prefijo'=>$prefijoInfo, 'descripcion'=>$descripcionInfo, 'reentrega'=>'1',
			"tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"nombre"=>$nombre,"edad_paciente"=>$edad_paciente));
			$this->salida.="<form name=\"forma\" action=\"$accion\" method=\"post\">";
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <br><td colspan=\"3\" align=\"center\" width=\"80%\">REENTREGA DE RESULTADOS</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td colspan=\"1\" align=\"center\" width=\"20%\">No. RESULTADO: ".$prefijoInfo." ".$resultado_idInfo."</td>";
			$this->salida.="  <td colspan=\"2\" align=\"center\" width=\"50%\">".$descripcionInfo."</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"$estilo\">";
			$this->salida.="<td class=\"".$this->SetStyle("observacion_reentrega")."\" align=\"center\" width=\"20%\" >OBSERVACION</td>";
			$this->salida.="<td  width=\"50%\" align=\"center\" ><textarea style = \"width:80%\" class='textarea' name = 'observacion_reentrega' cols = 60 rows = 3></textarea></td>" ;
			$this->salida.="<td align=\"center\" width=\"10%\" ><input type=\"submit\" class=\"input-submit\" value=\"GUARDAR\" name=\"Guardar\"></td>";
			$this->salida.="</tr>";
			$this->salida.="</table>";
			$this->salida.="</form>";
		}
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr>";
		//BOTON DE VOLVER
		$accionV=ModuloGetURL('app','Os_Entrega_Apoyod','user','LlamaEntregaResultadoPatologia',array('tipo_id_paciente'=>$tipo_id_paciente,'paciente_id'=>$paciente_id,'nombre'=>$nombre,'edad_paciente'=>$edad_paciente));
		$this->salida .= "<form name=\"forma\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "<td  colspan = 2 align=\"center\"><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"VOLVER\"></form></td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	function EntregaResultadoPatologiaCad($tipo_id_paciente,$paciente_id,$nombre,$edad_paciente){

		$this->salida= ThemeAbrirTablaSubModulo('CONSULTA DE EXAMENES DEL PACIENTE ');
		$accion=ModuloGetURL('app','Os_Entrega_Apoyod','user','InsertarRegistroEntregaPatologiaCad',array("tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"nombre"=>$nombre,"edad_paciente"=>$edad_paciente));
	  $this->salida.="<form name=\"forma\" action=\"$accion\" method=\"post\">";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"1\">ID DEL PACIENTE</td>";
		$this->salida.="  <td align=\"center\" colspan=\"2\">NOMBRE DEL PACIENTE</td>";
		$this->salida.="  <td align=\"center\" colspan=\"1\">EDAD</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"1\">".$tipo_id_paciente." - ".$paciente_id."</td>";
		$this->salida.="  <td align=\"center\" colspan=\"2\">".$nombre."</td>";
		$this->salida.="  <td align=\"center\" colspan=\"1\">".$edad_paciente."</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";
		$vector = $this->ConsultaExamenesPatologiaCad($tipo_id_paciente,$paciente_id);
		if($vector[entregado]==0){
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr class=\"modulo_table_list_title\">";
		$this->salida.="  <br><td colspan=\"4\" align=\"center\" width=\"40%\">EXAMEN PENDIENTE POR ENTREGAR</td>";
		$this->salida.="</tr>";
		$reporte= new GetReports();
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="  <td  align=\"center\" width=\"5%\">No. RESULTADO</td>";
		$this->salida.="  <td  align=\"center\" width=\"50%\">DESCRIPCION</td>";
		$this->salida.="  <td  align=\"center\" width=\"5%\">ENTREGAR</td>";
		$this->salida.="  <td  align=\"center\" width=\"5%\">IMPRIMIR</td>";
		$this->salida.="</tr>";
    if( $i % 2){$estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
		$this->salida.="<tr class=\"$estilo\">";
		$this->salida.="  <td  align=\"center\" width=\"5%\">".$vector[prefijo]." ".$vector[resultado_informe_id]."</td>";
		$this->salida.="  <td  align=\"left\" width=\"50%\">".$vector[descripcion]."</td>";
		if($vector[examen_firmado]!=1){
			$this->salida.="  <td align=\"center\" width=\"10%\">SIN FIRMAR</td>";
			$this->salida.="  <td>&nbsp;</td>";
		}else{
			$this->salida.="  <td align=\"center\" width=\"5%\"><input type = checkbox name=\"op[]\" value = ".$vector[prefijo]."||//".$vector[resultado_informe_id]."></td>";
			//lo de alex
			$mostrar=$reporte->GetJavaReport('app','Procedimientos_Cadaveres','examenes_html',array('cadaverId'=>$vector[cadaver_id],'informe_id'=>$vector[resultado_informe_id],"prefijo"=>$vector[prefijo]),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
			$nombre_funcion=$reporte->GetJavaFunction();
			$this->salida .=$mostrar;
			$this->salida.="<td  align=\"center\" width=\"5%\" valign=\"center\"><a href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'></a></td>";
			//fin de alex
		}
		$this->salida.="</tr>";
		$this->salida.="</table>";
		}else{
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr class=\"modulo_table_list_title\">";
		$this->salida.="  <br><td colspan=\"6\" align=\"center\" width=\"80%\">EXAMEN ENTREGADO</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"6\">";
		$this->salida.="<table border=\"0\">";
		$reporte= new GetReports();
		if( $i % 2){ $estilo='modulo_list_claro';}else {$estilo='modulo_list_oscuro';}
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="  <td align=\"center\" width=\"5%\">FECHA DE ENTREGA</td>";
		$this->salida.="  <td align=\"center\" width=\"5%\">HORA DE ENTREGA</td>";
		$this->salida.="  <td align=\"center\" width=\"30%\">RECIBIO</td>";
		$this->salida.="  <td align=\"center\" width=\"40%\">OBSERVACION</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"$estilo\">";
		$this->salida.="  <td align=\"center\" width=\"5%\">".$this->FechaStamp($vector[fecha_entrega])."</td>";
		$this->salida.="  <td align=\"center\" width=\"5%\">".$this->HoraStamp($vector[fecha_entrega])."</td>";
		if($vector[sw_tipo_persona]=='1'){
			$this->salida.="  <td align=\"center\" width=\"30%\">PERSONALMENTE</td>";
		}elseif($vector[sw_tipo_persona]=='2'){
			$this->salida.="  <td align=\"center\" width=\"30%\">PARENTESCO:  ".$vector[parentesco]."<br>NOMBRE:  ".STRTOUPPER($vector[nombre])."<br>TEL.:  ".$vector[telefono]."</td>";
		}elseif($vector[sw_tipo_persona]=='3'){
			$this->salida.="  <td align=\"center\" width=\"30%\">FUNCIONARIO:  ".STRTOUPPER($vector[nombre])."<br>IDENTIFICACION:  ".$vector[tipo_id_funcionario]." ".$vector[funcionario_id]."</td>";
		}
		$this->salida.="<td align=\"left\" width=\"40%\">".$vector[observacion]."</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="  <td align=\"center\" width=\"5%\">No. RESULTADO</td>";
		$this->salida.="  <td align=\"center\" width=\"30%\" colspan=\"2\">DESCRIPCION</td>";
		$this->salida.="  <td align=\"center\" width=\"5%\">IMPRESION</td>";
		//$this->salida.="  <td align=\"center\" width=\"5%\">REENTREGA</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"$estilo\">";
		$this->salida.="  <td align=\"center\" width=\"5%\">".$vector[prefijo]." ".$vector[resultado_informe_id]."</td>";
		$this->salida.="  <td align=\"left\" width=\"30%\" colspan=\"2\">".$vector[descripcion]."</td>";
    $mostrar=$reporte->GetJavaReport('app','Procedimientos_Cadaveres','examenes_html',array('cadaverId'=>$vector[cadaver_id],'informe_id'=>$vector[resultado_informe_id],"prefijo"=>$vector[prefijo]),array('rpt_name'=>'','rpt_dir'=>'','rpt_rewrite'=>TRUE));
		$nombre_funcion=$reporte->GetJavaFunction();
		$this->salida .=$mostrar;
		$this->salida.="<td  align=\"center\" width=\"10%\" valign=\"center\"><a href=\"javascript:$nombre_funcion\"><img src=\"".GetThemePath()."/images/imprimir.png\" border='0'> IMPRIMIR</a></td>";
		//$this->salida .= "<td  align=\"center\" width=\"5%\"><a href=".ModuloGetURL('app','Os_Entrega_Apoyod','user','VerDetalleEntregaPatologia',array('resultado_id'=>$vector[resultado_informe_id],"prefijo"=>$vector[prefijo],'descripcion'=>$vector[descripcion],'apoyod_entrega_id'=>$apoyod_entrega_id,'tipo_id_paciente'=>$tipo_id_paciente,'paciente_id'=>$paciente_id,'nombre'=>$nombre,'edad_paciente'=>$edad_paciente,'reentrega'=>'1'))."><img src=\"". GetThemePath() ."/images/resultado.png\" ></a></td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		}
    //INICIO DE REGISTRO DE ENTREGA
    //validando el acceso a la forma de entrega de resultados
	  if($vector[entregado]==0){
			$this->salida.="<script>";
			$this->salida.="function desabilitar(frm,valor){";
			$this->salida.="  if(valor==1){";
			$this->salida.="    frm.parentesco.disabled=true;";
			$this->salida.="    frm.tipo_id_funcionario.disabled=true;";
			$this->salida.="    frm.funcionario_id.disabled=true;";
			$this->salida.="    frm.nombre_recibe.disabled=true;";
			$this->salida.="    frm.telefono.disabled=true;";
			$this->salida.="    frm.observacion.disabled=true;";
			$this->salida.="  }else{";
      $this->salida.="  if(valor==2){";
      $this->salida.="    frm.parentesco.disabled=false;";
			$this->salida.="    frm.tipo_id_funcionario.disabled=true;";
			$this->salida.="    frm.funcionario_id.disabled=true;";
			$this->salida.="    frm.nombre_recibe.disabled=false;";
      $this->salida.="    frm.telefono.disabled=false;";
      $this->salida.="    frm.observacion.disabled=false;";
			$this->salida.="  }else{";
      $this->salida.="    frm.parentesco.disabled=true;";
			$this->salida.="    frm.tipo_id_funcionario.disabled=false;";
			$this->salida.="    frm.funcionario_id.disabled=false;";
			$this->salida.="    frm.nombre_recibe.disabled=false;";
      $this->salida.="    frm.telefono.disabled=true;";
      $this->salida.="    frm.observacion.disabled=true;";
			$this->salida.="  }";
			$this->salida.="  }";
			$this->salida.="}";
			$this->salida.="</script>";
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="<td align=\"center\">REGISTRO DE ENTREGA DE RESULTADOS</td>";
			$this->salida.="</tr>";
 			$this->salida.="<tr class=\"modulo_list_oscuro\">";
			$this->salida.="<td>";
      $this->salida.="<BR><table width=\"80%\" border=\"0\" align=\"center\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="<td class=\"".$this->SetStyle("responsable")."\" class=\"label\" colspan=\"2\" width=\"80%\" valign=\"top\">PERSONA QUE RECLAMA RESULTADOS DE APOYOS:</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"modulo_list_claro\">";
			$this->salida.="<td class=\"label\" valign=\"top\">PACIENTE</td>";
			if(!$responsable){
   	    $var='checked';
				$var1='disabled';
			}
			$this->salida.="<td valign=\"top\"><input type=\"radio\" name=\"responsable\" value=\"1\" onclick=\"desabilitar(this.form,this.value)\" $var></td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"modulo_list_claro\">";
			$this->salida.="<td class=\"label\" valign=\"top\">OTRO RESPONSABLE</td>";
			$this->salida.="<td valign=\"top\"><input type=\"radio\" name=\"responsable\" value=\"2\" onclick=\"desabilitar(this.form,this.value)\"></td>";
      $this->salida.="</tr>";
			$this->salida.="<tr class=\"modulo_list_claro\">";
			$this->salida.="<td class=\"label\" valign=\"top\">FUNCIONARIO DE LA INSTITUCION</td>";
			$this->salida.="<td valign=\"top\"><input type=\"radio\" name=\"responsable\" value=\"3\" onclick=\"desabilitar(this.form,this.value)\"></td>";
      $this->salida.="</tr>";
			$this->salida.="<tr class=\"modulo_list_claro\">";
			$this->salida.="<td colspan=\"2\">";
			$this->salida.="<BR><table width=\"80%\" border=\"0\" align=\"center\">";
			$this->salida.="<tr class=\"modulo_table_list_title\">";
			$this->salida.="<td colspan=\"3\">DATOS PERSONA </td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"modulo_list_oscuro\">";
			$this->salida.="<td colspan=\"1\" width=\"25%\" class=\"".$this->SetStyle("parentesco")."\">PARENTESCO</td>";
			$this->salida.="<td colspan=\"2\" width=\"55%\"><select name=\"parentesco\" class=\"select\" $var1>";
			$parentescos=$this->tiposParentescosPaciente();
			$this->MostrasSelect($parentescos,'False',$parentescoResponsable);
			$this->salida.="</select></td>";
			$this->salida.="</tr>";
      $this->salida.="<tr class=\"modulo_list_oscuro\">";
			$this->salida.="<td colspan=\"1\" class=\"".$this->SetStyle("identificacion")."\">No. IDENTIFICACION</td>";
			$this->salida .= "<td colspan=\"1\" ><select name=\"tipo_id_funcionario\" class=\"select\" $var1>";
			$tipo_id=$this->tipo_id_paciente();
			$this->BuscarIdPaciente($tipo_id,$_REQUEST['tipo_id_funcionario']);
			$this->salida .= "</select></td>";
			$this->salida.="<td colspan=\"1\"><input type=\"text\" class=\"input-text\" name=\"funcionario_id\" size =\"20\" maxlength=\"20\" value=\"$funcionario_id\" $var1></td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"modulo_list_oscuro\">";
			$this->salida.="<td colspan=\"1\" class=\"".$this->SetStyle("nombre_recibe")."\">NOMBRE</td>";
			$this->salida.="<td colspan=\"2\" ><input type=\"text\" class=\"input-text\" name=\"nombre_recibe\" size =\"32\" maxlength=\"32\" value=\"$nombre_recibe\" $var1></td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"modulo_list_oscuro\">";
			$this->salida.="<td colspan=\"1\" class=\"".$this->SetStyle("telefono")."\">TELEFONO</td>";
			$this->salida.="<td colspan=\"2\"><input type=\"text\" class=\"input-text\" name=\"telefono\" size =\"20\" maxlength=\"20\" value=\"$telefono\" $var1></td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"modulo_list_oscuro\">";
      $this->salida.="<td colspan=\"1\" class=\"".$this->SetStyle("observacion")."\">OBSERVACION</td>";
		  $this->salida.="<td colspan=\"2\" width=\"60%\" align='left' ><textarea style = \"width:80%\" class='textarea' name=\"observacion\" cols = 60 rows = 3 $var1>".$_REQUEST['observacion']."</textarea></td>" ;
			//$this->salida.="<td><input type=\"text\" class=\"input-text\" name=\"observacion\" maxlength=\"32\" value=\"$observacion\" $var1></td>";
			$this->salida.="</tr>";
			$this->salida.="</table><BR>";
			$this->salida.="</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"modulo_list_oscuro\"><td colspan=\"7\" align=\"center\">";
			$this->salida.="<input type=\"submit\" class=\"input-submit\" value=\"GUARDAR\" name=\"Guardar\"></td></tr>";
			$this->salida.="</table><BR>";
			$this->salida.="</td></tr>";
			$this->salida.="</table>";
			$this->salida.="</form>";
		}else{
		  $this->salida.="</form>";
			$accion1=ModuloGetURL('app','Os_Entrega_Apoyod','user','InsertarRegistroReEntregaPatologiaCad',array('apoyod_entrega_id'=>$vector[apoyod_entrega_id],'resultado_id'=>$vector[resultado_informe_id],'prefijo'=>$vector[prefijo], 'descripcion'=>$descripcionInfo, 'reentrega'=>'1',
			"tipo_id_paciente"=>$tipo_id_paciente,"paciente_id"=>$paciente_id,"nombre"=>$nombre,"edad_paciente"=>$edad_paciente));
			$this->salida.="<form name=\"formaun\" action=\"$accion1\" method=\"post\">";
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <br><td colspan=\"3\" align=\"center\" width=\"80%\">REENTREGA DE RESULTADOS</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td colspan=\"1\" align=\"center\" width=\"20%\">No. RESULTADO: ".$vector[prefijo]." ".$vector[resultado_informe_id]."</td>";
			$this->salida.="  <td colspan=\"2\" align=\"center\" width=\"50%\">".$vector[descripcion]."</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"$estilo\">";
			$this->salida.="<td class=\"".$this->SetStyle("observacion_reentrega")."\" align=\"center\" width=\"20%\" >OBSERVACION</td>";
			$this->salida.="<td  width=\"50%\" align=\"center\" ><textarea style = \"width:80%\" class='textarea' name = 'observacion_reentrega' cols = 60 rows = 3></textarea></td>" ;
			$this->salida.="<td align=\"center\" width=\"10%\" ><input type=\"submit\" class=\"input-submit\" value=\"GUARDAR\" name=\"Guardar\"></td>";
			$this->salida.="</tr>";
			$this->salida.="</table>";
			$this->salida.="</form>";
			$reentregas = $this->ConsultaExamenesReEntregadosPatCad($vector[apoyod_entrega_id]);
			if($reentregas){
				$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
				$this->salida.="<tr class=\"modulo_table_title\">";
				$this->salida.="  <br><td colspan=\"4\" align=\"center\" width=\"80%\">REGISTRO DE EXAMENES REENTREGADOS</td>";
				$this->salida.="</tr>";
				for($i=0;$i<sizeof($reentregas);$i++){
					if( $i % 2){ $estilo='modulo_list_claro';}else{$estilo='modulo_list_oscuro';}
					if($i==0){
						$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
						$this->salida.="  <td  align=\"center\" width=\"10%\">No. RESULTADO</td>";
						$this->salida.="  <td  align=\"center\" width=\"10%\">FECHA - HORA ENTREGA</td>";
						$this->salida.="  <td  align=\"center\" width=\"25%\">DESCRIPCION</td>";
						$this->salida.="  <td  align=\"center\" width=\"25%\">OBSERVACION</td>";
						$this->salida.="</tr>";
					}
					$this->salida.="<tr class=\"$estilo\">";
					$this->salida.="  <td  align=\"center\" class = \"label_error\" width=\"10%\">".$reentregas[$i][prefijo]." ".$reentregas[$i][resultado_informe_id]."</td>";
					$this->salida.="  <td  align=\"center\" width=\"10%\">".$this->FechaStamp($reentregas[$i][fecha_entrega])."<BR>".$this->HoraStamp($reentregas[$i][fecha_entrega])."</td>";
					$this->salida.="  <td  align=\"left\" width=\"25%\">".$reentregas[$i][descripcion]."</td>";
					$this->salida.="  <td  align=\"left\" width=\"25%\">".$reentregas[$i][observacion]."</td>";
					$this->salida.="</tr>";
				}
				$this->salida.="</table>";
			}
		}
    //FIN DE LA ENTREGA
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr>";
		//BOTON DE VOLVER
		$accionV=ModuloGetURL('app','Os_Entrega_Apoyod','user','BuscarOrden');
		$this->salida .= "<form name=\"forma\" action=\"$accionV\" method=\"post\">";
		$this->salida .= "<td  colspan = 2 align=\"center\"><input class=\"input-submit\" name=\"volver\" type=\"submit\" value=\"VOLVER\"></form></td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
		$this->salida .= ThemeCerrarTabla();
		return true;
	}

	/*********************************FIN CODIGO LORENA*********************/

}//fin clase

?>
