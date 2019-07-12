<?php
	/**************************************************************************************
	* $Id: EditarMedicamento.class.php,v 1.5 2011/02/17 13:21:27 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* @author Hugo F. Manrique
	**************************************************************************************/	
	
	class EditarMedicamento
	{
		var $fm;
		var $capa = "";
		var $tema = "";
		var $salida = "";
		var $inicio = "0";
		var $indice = "0";
		var $datos =array(); 
		
		function EditarMedicamento()
		{
			$this->datos['codigo_producto'] = $_REQUEST['codigo'];
			$this->datos['ingreso'] = $_REQUEST['ingreso'];
			$this->tema = $_REQUEST['tema'];
			$this->capa = $_REQUEST['capa'];
			
			if ($_REQUEST['indice']) $this->indice = $_REQUEST['indice'];
			if ($_REQUEST['inicio']) $this->inicio = $_REQUEST['inicio'];
			$this->Consulta_Solicitud_Medicamentos();
		}		
		/*****************************************************************************************************
		*
		******************************************************************************************************/
		function Iniciar()
		{
			$estilo .= "border-top:	3px solid #FFFFFF;";
			$estilo .= "border-right: 3px solid	#000000;";
			$estilo .= "border-bottom: 3px solid #000000;";
			$estilo .= "border-left: 3px solid #FFFFFF;";
			
			$scripts .= "\n";
			$scripts .= "<script lenguage=\"javascript\" src=\"../../javascripts/cross-browser/x/x_core.js\"></script>\n";
			$scripts .= "<script lenguage=\"javascript\" src=\"../../javascripts/jsrsClient.js\"></script>\n";
			$scripts .= "<script lenguage=\"javascript\" src=\"../../javascripts/VisibilidadMenuHc.js\"></script>\n";
			$scripts .= "<script lenguage=\"javascript\" src=\"../../javascripts/cross-browser/x/x_drag.js\"></script>\n";
			$scripts .= "<script lenguage=\"javascript\" src=\"../../javascripts/cross-browser/x/x_event.js\"></script>\n";
			
			$scripts .= "<script>\n";
			$scripts .= "		var formulacion = new Array();\n";
			$scripts .= "		function acceptNum(evt)\n";
			$scripts .= "		{\n";
			$scripts .= "			var nav4 = window.Event ? true : false;\n";
			$scripts .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$scripts .= "			return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$scripts .= "		}\n";
			
      $scripts .= "  function ProtocolosFormulacion(codigo_medicamento)\n";
      $scripts .= "  {\n";
      $scripts .= "    var url = \"ProtocolosFormulacion.class.php?codigo_medicamento=\"+codigo_medicamento+\"&ingreso=".$this->datos['ingreso']."\";\n";
      $scripts .= "    var str =\"width=900 ,height=300,resizable=no,location=yes,toolbar=1,status=no,scrollbars=yes\";\n";
      $scripts .= "    window.open(url,'PROTOCOLOS DE FORMULACION',str).focus();\n";
      $scripts .= "  }\n";
      
      $conversiones = $this->ObtenerFactorConversion($this->datos['codigo_medicamento'], $this->datos['ummi']);
			$scripts .= "		function EvaluaEdit()\n";
			$scripts .= "		{\n";
      $scripts .= "     var conversion = new Array(";
      $fl = "";
      foreach($conversiones as $k1 => $dtl1)
      {
        $scripts .= $fl." new Array('".$dtl1['unidad_dosificacion']."','".$dtl1['factor_conversion']."' )";
        $fl = ",";
      }
      $scripts .= ")\n";
      $prtl = $this->ObtenereProtocoloMedicamento($this->datos['codigo_producto']);
      
			$scripts .= "			mensaje = \"\";\n";
			$scripts .= "			formulacion[0] = '".$this->datos['codigo_producto']."'; \n";
			$scripts .= "			formulacion[1] = document.formulacion.dosis.value; \n";
			$scripts .= "			formulacion[2] = CalcularCantidad('1',conversion)+\"\";\n";
			$scripts .= "			formulacion[3] = document.formulacion.dosiscant.value; \n";
			$scripts .= "			formulacion[4] = document.formulacion.viasadmin.value;\n";
			$scripts .= "			formulacion[5] = document.formulacion.medicamento.value;\n";
			$scripts .= "			formulacion[6] = document.formulacion.frecuenciadosis01.value;\n";
			$scripts .= "			formulacion[7] = '".$this->indice."';\n";
			$scripts .= "			formulacion[8] = '".$this->tema."';\n ";
			$scripts .= "			formulacion[9] = '".$this->inicio."';\n";
			$scripts .= "			formulacion[10] = '".$this->datos['usuario_id']."';\n";
      $scripts .= "	    formulacion[11] = document.formulacion.tratamiento1.value;\n";
      $scripts .= "     formulacion[12] = document.formulacion.frecuencia_Numero1.value;\n";
      $scripts .= "     formulacion[13] = document.formulacion.frecuencia_Intensidad1.value;\n";

			$scripts .= "			if(formulacion[4] == \"\" || formulacion[4] == \"0\")\n";
			$scripts .= "				mensaje = 'SE DEBE SELECCIONAR LA VIA DE ADMINISTRACIÓN DEL MEDICAMENTO';\n";
			$scripts .= "			  else if(formulacion[6] == '')\n";
			$scripts .= "				  mensaje = 'FAVOR INGRESAR LA FRECUENCIA DEL MEDICAMENTO'; \n";
			$scripts .= "			    else if(!IsNumeric(formulacion[3]))\n";
			$scripts .= "				    mensaje = 'LA CANTIDAD, INGRESADA EN LA DOSIS NO ES VALIDA'; \n";
			$scripts .= "				    else if(formulacion[1] == \"0\")\n";
			$scripts .= "					    mensaje = 'SE DEBE SELECCIONAR LA DOSIS DEL MEDICAMENTO'; \n";
			$scripts .= "					    else if(!IsNumeric(formulacion[2]))\n";
			$scripts .= "						    mensaje = 'LA CANTIDAD INGRESADA NO ES VALIDA';\n";
			//$scripts .= "					      else if(!IsNumeric(formulacion[11]))\n";
			//$scripts .= "						      mensaje = 'EL VALOR INGRESADO PARA LOS DIAS DE TRATAMIENTO NO ES VALIDO';\n";
			$scripts .= "																														\n";
			$scripts .= "			document.getElementById('ErrorFormulacion').innerHTML = '<center><b class=\"label_error\">'+mensaje+'</b></center><br>';\n";
			$scripts .= "			paso = false;\n";
			$scripts .= "			if(mensaje == \"\")\n";
			$scripts .= "			{\n";
			$scripts .= "				ActualizarFormulacionMedicamento(formulacion);\n";
			$scripts .= "				paso = true;\n";
			$scripts .= "			}\n";
			$scripts .= "		}\n";
			$scripts .= "		function ActualizarDatosForma(html)\n";
			$scripts .= "		{\n";
			$scripts .= "			if(html)\n";
			$scripts .= "			{\n";
			$scripts .= "				window.opener.document.getElementById('".$this->capa."').innerHTML = html;\n";
			$scripts .= "				window.close();\n";
			$scripts .= "			}\n";
			$scripts .= "		}\n";
			
			$datose = "&tema=".GetThemePath();
			$action2 = "FrecuenciaMedicamentos.class.php?".$datose."";
			
			$scripts .= "		function Adicionarfrecuencia(codigo)\n";
			$scripts .= "		{\n";
			$scripts .= "			var url=\"".$action2."&codigo=\"+codigo+\"\";\n";
			$scripts .= "			window.open(url,'Formulación','width=500,height=150,x=200,Y=200,resizable=no,status=yes,scrollbars=yes,location=no');\n";
			$scripts .= "		}\n";			
			$scripts .= "</script>\n";
			
			$this->salida .= ReturnHeader('EDITAR MEDICAMENTOS',$scripts);
      $this->salida .= ReturnBody()."<br>\n";
			$this->salida .= "<div name=\"ErrorFormulacion\" id=\"ErrorFormulacion\"><br></div>\n";
			$this->salida .= "<form name=\"formulacion\" action=\"".$action."\" method=\"post\">\n";
			$this->salida .= "	<table align=\"center\" border=\"0\" width=\"98%\" class=\"modulo_table_list\">\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "  		<td align=\"center\" colspan=\"7\" height=\"16\">FORMULACIÓN DE MEDICAMENTOS</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\" >\n";
			$this->salida .= "			<td colspan=\"2\"  align=\"center\" >PRODUCTO</td>\n";
			$this->salida .= "			<td align=\"center\" >PRINCIPIO ACTIVO</td>\n";
			$this->salida .= "			<td width=\"12%\" style=\"text-indent:0pt;\" align=\"center\" >CONCENTRACIÓN</td>\n";
			$this->salida .= "			<td align=\"center\" >FORMA</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"hc_table_submodulo_list_title\" >\n";
			$this->salida .= "			<td align=\"center\" width=\"6%\">".$this->datos['item']."</td>\n";
			$this->salida .= "			<td align=\"left\"  >".$this->datos['producto']."</td>\n";
			$this->salida .= "			<td align=\"left\"  >".$this->datos['principio_activo']."</td>\n";
			$this->salida .= "			<td align=\"right\" >".$this->datos['cff']."</td>\n";
			$this->salida .= "			<td align=\"left\"  >".$this->datos['forma']."</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"hc_table_submodulo_list_title\" >\n";
			$this->salida .= "			<td align=\"left\" colspan=\"6\">\n";
			$this->salida .= "				<table class=\"label\" border=\"0\" widtah=\"100%\">\n";
			$this->salida .= "					<tr >\n";
			$this->salida .= "						<td >VIA DE ADMINISTRACIÓN: </td>\n";
			$this->salida .= "						<td colspan=\"2\"><b class=\"label_mark\">".$this->ObtenerVias($this->datos['codigo_producto'],$this->datos['via_administracion_id'])."</b></td>\n";
      $this->salida .= "						<td colspan=\"2\">\n";
     
      if(!empty($prtl))
      {
        $this->salida .= "						  <a href=\"javascript:ProtocolosFormulacion('".$this->datos['codigo_producto']."')\" title=\"Protocolos Formulacion\" >\n";
        $this->salida .= "        	      <img src=\"".GetThemepath()."/images/informacion.png\" border=\"0\">PROTOCOLO DE FORMULACION\n";
        $this->salida .= "              <a>\n";
      }
      $this->salida .= "            </td>\n";
      $this->salida .= "					</tr>\n";
			$this->salida .= "					<tr >\n";
			$this->salida .= "						<td valign=\"top\">DOSIS</td>\n";
			$this->salida .= "						<td valign=\"top\">\n";
			$this->salida .= "							<input type=\"text\" class='input-text' size=\"10\" id=\"dosiscant1\" name=\"dosiscant\" onkeypress=\"return acceptNum(event);\" value=\"".$this->datos['dosis']."\">\n";
			$this->salida .= "							<input type=\"hidden\" id=\"frecuenciadosis0\" name=\"frecuenciadosis0\" value=\"\" >\n";
			$this->salida .= "						</td>\n";
			$this->salida .= "						<td valign=\"top\">\n";
			$this->salida .= "".$this->ObtenerCombo()."\n";
			$this->salida .= "						</td>\n";
			
			$this->salida .= "						<td valign=\"top\">\n";
			$this->salida .= "							<a href=\"javascript:Adicionarfrecuencia('1')\" title=\"Frecuencia Medicamento\">\n";
			$this->salida .= "								<img src=\"".GetThemePath()."/images/modificar.png\" border=\"0\">\n";
			$this->salida .= "							</a>\n";
			$this->salida .= "						</td>\n";
			
      $aux = explode(" ",$this->datos['frecuencia']);
      if($aux[0] != "Cada")
      {
        $aux[2] = "Dia(s)";
        $aux[1] = "1";
      }
      
			$this->salida .= "						<td width=\"40%\">\n";
      $this->salida .= "							<textarea rows=\"2\" class=\"textarea\" style=\"width:100%\" id=\"Tfrecuenciadosis01\" name=\"Tfrecuenciadosis01\" readonly>".$this->datos['frecuencia']."</textarea>";
      $this->salida .= "							<input type=\"hidden\" id=\"frecuenciadosis01\" name=\"frecuenciadosis01\" value=\"".$this->datos['frecuencia']."\">\n";
      $this->salida .= "							<input type=\"hidden\" id=\"frecuencia_Numero1\" name=\"frecuencia_Numero1\" value=\"".$aux[1]."\">\n";
      $this->salida .= "							<input type=\"hidden\" id=\"frecuencia_Intensidad1\" name=\"frecuencia_Intensidad1\" value=\"".$aux[2]."\">\n";
      $this->salida .= "						</td>\n";
			$this->salida .= "					</tr>\n";				
      $this->salida .= "					<tr >\n";
			$this->salida .= "						<td >DIAS TRATAMIENTO:</td>\n";
			$this->salida .= "						<td colspan=\"4\">\n";
			$this->salida .= "							<input type=\"text\" class='input-text' size=\"5\" id=\"tratamiento1\" name=\"tratamiento1\" value=\"".$this->datos['dias_tratamiento']."\" onkeypress=\"return acceptNum(event);\">\n";
			$this->salida .= "						</td>\n";
			$this->salida .= "					</tr>\n";
			$this->salida .= "				</table>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"modulo_table_list_title\" >\n";
			$this->salida .= "			<td align=\"center\" colspan=\"6\">OBSERVACIONES E INDICACIONES DE SUMINISTRO</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "		<tr class=\"hc_table_submodulo_list_title\" >\n";
			$this->salida .= "			<td class=\"modulo_list_oscuro\" style=\"$estilo\">\n";
			$this->salida .= "				<a href=\"javascript:EvaluaEdit();\">\n";
			$this->salida .= "					<img name =\"ImgHistoriaActual\" src=\"".GetThemePath()."/images/pcopiar.png\" border=\"0\" >GUARDAR\n";
			$this->salida .= "				</a>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "			<td align=\"center\" colspan=\"5\">\n";
			$this->salida .= "				<textarea rows=\"2\" class=\"textarea\" style=\"width:100%\" id=\"medicamento\" name=\"medicamento\">".$this->datos['observacion']."</textarea>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "</form>\n";
			
			$this->salida .= "<div id='Frecuencias' class='d2Container' style=\"display:none\">\n";
			$this->salida .= "	<div id='tituloFrecuencia' class='draggable' style=\"	text-transform: uppercase;\">ADICIONAR FRECUENCIA</div>\n";
			$this->salida .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:MostrarSpan('Frecuencias')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$this->salida .= "	<div id='Contenedor' class=\"d2Content\">\n";
		
			$this->salida .= "	</div>\n";
			$this->salida .= "</div>\n";
      if(!empty($prtl))
      {
        $this->salida .= "<script>\n";
        $this->salida .= "  ProtocolosFormulacion('".$this->datos['codigo_producto']."');\n";
        $this->salida .= "</script>\n";
      }
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ObtenerCombo()
		{
			$sql .= "SELECT unidad_dosificacion ";
			$sql .= "FROM 	hc_unidades_dosificacion ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$combo = array();
			while (!$rst->EOF)
			{
				$combo[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			$dosis .= "							<select class=\"select\" id=\"dosis1\" name=\"dosis\">\n";
			$dosis .= "								<option value=\"0\">-----SELECCIONAR-----</option>\n";
			for($i=0; $i<sizeof($combo); $i++)
			{
				$sel = "";
				if($this->datos['unidad_dosificacion'] == $combo[$i]['unidad_dosificacion'] ) $sel = "selected";
				$dosis .= "								<option value=\"".$combo[$i]['unidad_dosificacion']."\" $sel>".$combo[$i]['unidad_dosificacion']."</option>\n";
			}
			$dosis .= "							</select>\n";
			return $dosis;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ObtenerVias($codigo,$via)
		{
			$sql .= "SELECT * ";
			$sql .= "FROM 	inv_medicamentos_vias_administracion ";
			$sql .= "WHERE 	codigo_medicamento = '".$codigo."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			if(!$rst->EOF)
			{
				$sql  = "SELECT HA.via_administracion_id,  ";
				$sql .= "				HA.nombre ";
				$sql .= "FROM 	inv_medicamentos_vias_administracion IA, ";
				$sql .= "				hc_vias_administracion HA ";
				$sql .= "WHERE	HA.via_administracion_id = IA.via_administracion_id ";
				$sql .= "AND 		IA.codigo_medicamento = '".$codigo."' ";
			}
			else
			{
				$sql  = "SELECT HA.via_administracion_id,  ";
				$sql .= "				HA.nombre ";
				$sql .= "FROM 	hc_vias_administracion HA ";
			}
			
			if(!$rstm = $this->ConexionBaseDatos($sql)) return false;
			
			$datos = array();
			$i=0;
			while (!$rstm->EOF)
			{
				$datos[$i] = $rstm->GetRowAssoc($ToUpper = false);
				$rstm->MoveNext();
				$i++;
			}
			
			$rstm->Close();
			
			$vias = "";
			
			if(sizeof($datos) == 1)
			{
				$vias .= "		<input type=\"hidden\" id=\"viasadmin\" name=\"viasadmin\" value=\"".$datos[0]['via_administracion_id']."\">\n";
				$vias .= "		<b>".$datos[0]['nombre']."</b>\n";
			}
			else
			{
				$vias .= "		<select class=\"select\" id=\"viasadmin\" name=\"viasadmin\">\n";
				$vias .= "			<option value=\"0\">-----SELECCIONAR-----</option>\n";
				for($i = 0; $i< sizeof($datos); $i++ )
				{
					$sel = "";
					if($via == $datos[$i]['via_administracion_id']) $sel = "selected";
					$vias .= "			<option value=\"".$datos[$i]['via_administracion_id']."\" $sel>".$datos[$i]['nombre']."</option>\n";
				}
				$vias .= "		</select>\n";
			}
			return $vias;
		}
		/********************************************************************
		*
		*********************************************************************/  
    function Consulta_Solicitud_Medicamentos()
    {
	    $sql  = "SELECT ID.descripcion AS producto, ";
			$sql .= "				IA.descripcion AS principio_activo, ";
			$sql .= "				IM.descripcion AS umm, ";
			$sql .= "				HA.nombre, ";
			$sql .= "				FM.dosis, ";
			$sql .= "				FM.unidad_dosificacion, ";
			$sql .= "				FM.cantidad, ";
			$sql .= "				FM.ingreso, ";
			$sql .= "				FM.observacion, ";
			$sql .= "				FM.sw_estado, ";
			$sql .= "				FM.codigo_producto, ";
			$sql .= "				FM.via_administracion_id, ";
			$sql .= "				FM.frecuencia, ";
			$sql .= "				IF.descripcion AS forma, ";
      $sql .= "				ME.codigo_medicamento, ";
			$sql .= "				ME.concentracion_forma_farmacologica AS cff, ";
      $sql .= "				ME.unidad_medida_medicamento_id AS ummi,";
			$sql .= "				CASE WHEN ME.sw_pos = 1 THEN 'POS' ";
			$sql .= "						 ELSE 'NO POS' END AS item, ";
			$sql .= "       FM.dias_tratamiento, ";
			$sql .= "				FH.usuario_id ";
			$sql .= "FROM 	inv_med_cod_principios_activos AS IA, ";
			$sql .= "				hc_formulacion_medicamentos FM,";
			$sql .= "				hc_formulacion_medicamentos_eventos FH,";
			$sql .= "				inventarios_productos ID, ";
			$sql .= "				inv_med_cod_forma_farmacologica AS IF, ";
			$sql .= "				medicamentos ME LEFT JOIN inv_unidades_medida_medicamentos IM ";
			$sql .= "				ON(ME.unidad_medida_medicamento_id = IM.unidad_medida_medicamento_id), ";
			$sql .= "				hc_vias_administracion HA ";
			$sql .= "WHERE	ID.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND 		ME.cod_principio_activo = IA.cod_principio_activo ";
			$sql .= "AND 		FM.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND		FM.ingreso = ".$this->datos['ingreso']." ";
			$sql .= "AND		FM.num_reg_formulacion = FH.num_reg ";
			$sql .= "AND		FM.codigo_producto = '".$this->datos['codigo_producto']."' ";
			$sql .= "AND		HA.via_administracion_id = FM.via_administracion_id ";
			$sql .= "AND 		IF.cod_forma_farmacologica = ME.cod_forma_farmacologica ";
			$sql .= "ORDER BY FM.sw_estado ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			while (!$rst->EOF)
			{
				$this->datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
      
			return true;
		}
    /**
    *
    */
    function ObtenerFactorConversion($medicamento, $umm_id)
    {
      $sql  = "SELECT unidad_dosificacion,";
      $sql .= " 	    factor_conversion ";
      $sql .= "FROM   hc_formulacion_factor_conversion ";
      $sql .= "WHERE  codigo_producto = '".$medicamento."' ";
      $sql .= "AND  	unidad_id = '".$umm_id."' ";
      
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      while (!$rst->EOF)
			{
				$datos[] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
      
      return $datos;
    }
    /**
    * Funcion donde se obtiene el protocolo de formulacion para el medicamente
    * solicitado
    *
    * @param string $codigo Codigo del medicamento
    *
    * @return mixed
    */
    function ObtenereProtocoloMedicamento($codigo)
    {
      $sql  = "SELECT protocolo, ";
      $sql .= "       suministro_texto ";
      $sql .= "FROM   medicamentos_protocolos ";
      $sql .= "WHERE  codigo_medicamento = '".$codigo."' ";
      
      $datos = array();
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      if (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
      
			return $datos;
    }
		/**
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la 
		* consulta sql 
		* 
		* @param string sentencia sql a ejecutar 
		* @return rst 
		*/
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			//$dbconn->debug = true;
			$rst = $dbconn->Execute($sql);
				
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				return false;
			}
			return $rst;
		}
	}
	$VISTA='HTML';
	$_ROOT='../../';
	include $_ROOT.'includes/enviroment.inc.php';
	
	$fileName = "themes/$VISTA/" . GetTheme() . "/module_theme.php";
	IncludeFile($fileName);
	
	$edit = new EditarMedicamento();
	$edit->Iniciar();
	echo $edit->salida; 
?>