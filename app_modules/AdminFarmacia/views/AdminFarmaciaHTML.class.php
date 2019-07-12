<?php
	/**
	* @package IPSOFT-SIIS
	* @version $Id: AdminFarmaciaHTML.class.php,v 1.0 
	* @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres
	*/

	IncludeClass("ClaseHTML");
  IncludeClass("ClaseUtil");

	class AdminFarmaciaHTML
	{
	/**
		* Constructor de la clase
	*/

	function  AdminFarmaciaHTML()
	{}
	/*
		* Funcion donde se crea la forma para el menu principal
          * @param array $action vector que contiene los link de la aplicacion
          * @return string $html retorna la cadena con el codigo html de la pagina
        
	*/
		function FormaMenu($action)
		{
			$html  = ThemeAbrirTabla('ADMINISTRACIÒN DE FARMACIAS');
			$ctl = AutoCarga::factory("ClaseUtil");
			$html .= $ctl->RollOverFilas();
			$html .= "<center>\n";
			$html .= "<table width=\"50%\" class=\"modulo_table_list\" border=\"0\" align=\"center\" >\n";
			$html .= "  <tr class=\"formulacion_table_list\" >\n";
			$html .= "     <td align=\"center\">MENU\n";
			$html .= "     </td>\n";
			$html .= "  </tr>\n";
			$html .= "  <tr   class=\"LABEL\"  onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
			$html .= "      <td  align=\"center\">\n";
			$html .= "        <a href=\"".$action['documentos']."\"><b>DOCUMENTOS DE LA FARMACIA</b></a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "  <tr  class=\"LABEL\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
			$html .= "      <td   align=\"center\">\n";
			$html .= "        <a href=\"".$action['productos']."\"><b> PRODUCTOS EN OTRAS FARMACIAS</b></a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "	</table><BR>";
			$html .= "<table align=\"center\" width=\"50%\">\n";
			$html .= "  <tr>\n";
			$html .= "    <td align=\"center\" class=\"label_error\">\n";
			$html .= "      <a href=\"".$action['volver']."\">\n";
			$html .= "        VOLVER\n";
			$html .= "      </a>\n";
			$html .= "    </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table><br>\n";
			$html .= "</center>\n";
			$html .= ThemeCerrarTabla();
			return $html;
		}
	/*
		* Funcion donde se crea la forma para el menu  de los documentos
		* @param array $action vector que contiene los link de la aplicacion
        * @return string $html retorna la cadena con el codigo html de la pagina
        
	*/
		function FormaMenuDocumentos($action)
		{
			$html  ="  <script>\n";
			$html .= "	  function validarEmpresaDestino(frms)\n";
			$html .= "	  {\n";
			$html .= "    if(frms.empresas.selectedIndex==0)\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE SELECCIONAR UNA EMPRESA PARA REALIZAR LA DEVOLUCION';\n";
			$html .= "      return;\n";
			$html .= "    } \n";
			$html .= "    if(frms.empresas.selectedIndex!=-1)\n";
			$html .= "    {\n";
			$html .= " 	xajax_TransEmpresaDestino(frms.empresas.value);";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    frms.submit();\n";
			$html .= "    }\n";   
			$html .="  </script>\n";
			$html  .= ThemeAbrirTabla('MENU DOCUMENOS DE FARMACIA');
			$ctl = AutoCarga::factory("ClaseUtil");
			$html .= $ctl->RollOverFilas();
			$html .= "<center>\n";
			$html .= "<table width=\"50%\" class=\"modulo_table_list\" border=\"0\" align=\"center\" >\n";
			$html .= "  <tr class=\"formulacion_table_list\" >\n";
			$html .= "     <td align=\"center\">MENU\n";
			$html .= "     </td>\n";
			$html .= "  </tr>\n";/*
			$html .= "  <tr   class=\"LABEL\"  onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
			$html .= "      <td  align=\"center\">\n";
			$html .= "        <a href=\"".$action['ingreso']."\"><b>DOCUMENTOS DE INGRESO</b></a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";*/
			$html .= "  <tr  class=\"LABEL\" onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
			$html .= "      <td   align=\"center\">\n";
			$html .= "         <a href=\"#\" onclick=\"xajax_EmpresaDestino()\"  class=\"label_error\">DOCUMENTO DE DEVOLUCIÒN POR FECHA VENCIMIENTO</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "	</table><br>\n";
			$html .= "</center>\n";
      $html .= "<table align=\"center\" width=\"50%\">\n";
      $html .= "  <tr>\n";
      $html .= "    <td align=\"center\" class=\"label_error\">\n";
      $html .= "      <a href=\"".$action['volver']."\">\n";
      $html .= "        VOLVER\n";
      $html .= "      </a>\n";
      $html .= "    </td>\n";
      $html .= "  </tr>\n";
      $html .= "</table><br>\n";
      $html .= ThemeCerrarTabla();
			$html .= $this->CrearVentana(560,"EMPRESA DESTINO");
		  return $html;
		}
	/* DOCUMENTO DE INGRESO APARTIR DE UN DOCUMENTO DE DESPACHO */
	/*
	*	Funcion que contiene la Forma De buscar y seleccionar La empresa que contiene el Documento 
	* 	Busca por Prefijo y/o numero 		
	* 	@param array $action vector que contiene los link de la aplicacion
	* 	@return string $html retorna la cadena con el codigo html de la pagina
	*/
		function FormaBuscarEmpresas2($action,$request,$datos,$conteo,$pagina,$datosemp,$excon,$bodega,$doc_id_E,$numeracionE,$IdIngreso,$empres,$farmacia)                             
		{
			$html = ThemeAbrirTabla('BUSCAR DOCUMENTOS DE DESPACHO');
     
			$html .= "		<form name=\"formita\" id=\"formita\" action=\"".$action['buscador']."\" method=\"post\"     >";
			$html .= "			<table  class=\"modulo_table_list\"  width=\"30%\" align=\"center\" border=\"0\"   >";
			$html .= "         <tr align=\"left\" class=\"formulacion_table_list\">\n";
			$html .= "		          	<td align=\"left\"  class=\"modulo_table_list_title\" ><b>EMPRESA:</b></td>\n";
			$html .= "			            <td  align=\"left\" class=\"modulo_list_claro\" >\n";
			$html .= "					            <select name=\"empresas\" class=\"select\" onchange=\"xajax_MostrarPrefijos(xajax.getFormValues('formita'))\">\n";
			$html .= "                        	<option value = '-1'>--  SELECCIONE --</option>\n";
			$csk = "";
			foreach($datosemp as $indice => $valor)
  			{
  				if($valor['empresa_id']==$request['empresa_id'])
  				$sel = "selected";
  				else   $sel = "";
  				$html .= "  <option value=\"".$valor['empresa_id']."\" ".$sel.">".$valor['razon_social']."</option>\n";
  			}
  			$html .= "                </select>\n";
  			$html .= "					  	  </td>\n";
  			$html .= "	 </tr>\n";
  			$html .= "  <tr class=\"modulo_table_list_title\">\n";
  			$html .= "			         	<td  class=\"modulo_table_list_title\" width=\"40%\"  >PREFIJO:</td>\n";
  			$html .= "			        	<td class=\"modulo_list_claro\" align=\"left\">\n";
  			$html .= "			         		<select name=\"prefijo\"  class=\"select\"  >\n";
  			$html .= "					       	<option value=\"-1\">-SELECCIONAR-</option>\n";
  			$html .= "				        	</select>\n";			
  			$html .= "			          	</td>\n";
			$html .= "	 </tr>\n";
			$html .= "  <tr class=\"modulo_table_list_title\">\n";
  			$html .= "		           	<td class=\"modulo_table_list_title\">NUMERO:</td>\n";
  			$html .= "		           <td  align=\"left\" colspan=\"4\" class=\"modulo_list_claro\" ><input type=\"text\" class=\"input-text\" name=\"buscador[numero]\" maxlength=\"32\" value=".$request['numero']."></td>\n";
  			$html .= "		</tr>\n";
  			$html .= "</table><br>\n";
  			$html .= "			<table   width=\"30%\" align=\"center\" border=\"0\"   >";
  			$html .= "		<tr>\n";
  			$html .= "	             	<td  colspan=\"10\"  align='center'>\n";
  			$html .= "			         <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\"  >\n";
  			$html .= "		          	</td>\n";
  			$html .= "		</tr>\n";
  			$html .= "</table><br>\n";
  			if(!empty($datos))
  			{
  				$pghtml = AutoCarga::factory('ClaseHTML');
  				$html .= "  <table width=\"75%\" class=\"modulo_table_list\" border=\"0\"  align=\"center\">";
  				$html .= "	  <tr align=\" class=\"formulacion_table_list\" >\n";
  				$html .= "      <td class=\"modulo_table_list_title\" width=\"5%\">PREFIJO</td>\n";
  				$html .= "      <td class=\"modulo_table_list_title\" width=\"5%\">NUMERO</td>\n";
  				$html .= "      <td class=\"modulo_table_list_title\" width=\"25%\">OBSERVACIÒN</td>\n";
  				$html .= "      <td class=\"modulo_table_list_title\" width=\"5%\">CANT.PROD</td>\n";
  				$html .= "      <td class=\"modulo_table_list_title\" width=\"3%\">VERI.</td>\n";
  				$html .= "      <td class=\"modulo_table_list_title\" width=\"10%\">PENDIENTE.</td>\n";
  				$html .= "  </tr>\n";
  				$est = "modulo_list_claro"; $back = "#DDDDDD";
  				$i=0;
  				foreach($datos as $key => $dtl)
  				{
  					$html .= "  <tr class=\"modulo_list_claro\">\n";
  					$html .= "      <td align=\"center\"><b>".$dtl['prefijo']."</b> </td>\n";
  					$html .= "      <td align=\"left\"><b>".$dtl['numero']."</b></td>\n";
  					$html .= "      <td align=\"left\"><b>".$dtl['observacion']."</b></td>\n";
  					$html .= "      <td align=\"center\"><b>".$dtl['cantidad']." PRODUCTOS.<b></td>\n";
  					$mdl = AutoCarga::factory("AdminFarmaciaSQL", "classes", "app", "AdminFarmacia");
					$excon=$mdl->ConsultarExistencias($empres,$dtl['prefijo'],$dtl['numero'],$farmacia);

					if(empty($excon))
  					{
  						$html .= "      <td align=\"center\">\n";
  						$html .= "      <a href=\"".$action['confir'].URLRequest(array( "prefijo"=>$dtl['prefijo'],"numero"=>$dtl['numero'],"empresa_envia"=>$dtl['empresa_id'],"cantida"=>$dtl['cantidad'],"doc_idE"=>$doc_id_E,"numeracion"=>$numeracionE))."\">\n";
  						$html .= "        <img src=\"".GetThemePath()."/images/si.png\" border=\"0\">\n";
  						$html .= "    </a>\n";
  						$html .= "      </td>\n";
  						$html .= "      <td align=\"center\"><b>\n";
  						$html .= "           No hay Pendientes</b>";
  						$html .= "      </td>\n";
  					}
  					if(!empty($excon))
  					{
  						$html .= "      <td align=\"center\">\n";
  						$html .= "        <img src=\"".GetThemePath()."/images/no.png\" border=\"0\">\n";
  						$html .= "      </td>\n";
  						$html .= "      <td align=\"center\">\n";
  						$html .= "      <a href=\"".$action['pendie'].URLRequest(array( "prefijo"=>$dtl['prefijo'],"numero"=>$dtl['numero'],"empresa"=>$dtl['empresa_id'],"cantida"=>$dtl['cantidad']))."\">\n";
  						$html .= "       <b> Pendientes</b>\n";
  						$html .= "    </a>\n";
  						$html .= "      </td>\n";
  					}
  				$html .= "  </tr>\n";
  			    }
  				$html .= "	</table><br>\n";
  				$html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
  				$html .= "	<br>\n";
  			} 
			
			$html .= "<table align=\"center\" width=\"50%\">\n";
			$html .= "  <tr>\n";
			$html .= "    <td align=\"center\" class=\"label_error\">\n";
			$html .= "      <a href=\"".$action['volver']."\">\n";
			$html .= "        VOLVER\n";
			$html .= "      </a>\n";
			$html .= "    </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table><br>\n";
			$html .= "		   </form>\n";
			$html .= ThemeCerrarTabla();
			return $html;
		}  
			/*
		*Funcion que contiene la Forma de listar Los productos asociados a un documento consultado
		* @param array $action vector que contiene los link de la aplicacion
		* @param array $Datos vector que contiene la informacion del documento de despacho.
		* @param String $prefijo  variable que contiene el prefijo del documento de despacho.
		* @param String $numero variable  que contiene el numero del documento de despacho.
		* @param String $empresa variable que contiene el id de la empresa que genera del documento de despacho.
		* @param String $far  variable que contiene el id de la empresa que va a generar el documento de ingreso.
		* @param String $bodega  variable que contiene el id de la bodega de la farmacia que va a generar el documento de ingreso.
		* @param String $contador  variable que contiene la cantidad de registros arrojados de la consulta de los productos asociados al documento de despacho.
		* @param String $cen  variable que contiene el centro de utilidad de la empresa ò farmacia que va a generar el documento de ingreso.
        * @return string $html retorna la cadena con el codigo html de la pagina
	*/
		function FormaListaProductBodMov($action,$Datos,$request,$conteo,$pagina,$prefijo,$numero,$empresa,$far,$bodega,$contador,$cen,$abreviatura_estado)
		{
			$html  = ThemeAbrirTabla("PRODUCTOS DEL DOCUMENTO DE DESPACHO PENDIENTES POR VERIFICAR ");
			$html  .="  <script>\n";
			$html  .=" function  ValidarDtos(frms){ ";
			$html .= "    if(frms.observar.value==\"\")\n";
			$html .= "    {\n";
			$html .= "     alert(' DEBE  INGRESAR  UNA  OBSERVACION  PARA  GENERAR  EL  DOCUMENTO  DE  INGRESO ');\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    if(frms.observar.value!=\"\")\n";
			$html .= "    {\n";
			$html .= " 	xajax_TransVariables(frms.observar.value,'".$empresa."','".$bodega."','".$cen."','".$abreviatura_estado."','".$prefijo."','".$numero."');";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    frms.submit();\n";
			$html .= "    }\n";   
			$html .= "	function Todoselec(frm,x){";
			$html .= "	  if(x==true ){";
			$html .= "	    for(i=0;i<frm.elements.length;i++){";
			$html .= "	      if(frm.elements[i].type=='checkbox'){";
			$html .= "              if(frm.elements[i].name=='checkseleccionar' ){";
			$html .= "	                  frm.elements[i].checked=true;";
			$html .= "	      }";
			$html .= "	      }";
			$html .= "	 }";
			$html .= "	 }";
			$html .= "	  else{";
			$html .= "	    for(i=0;i<frm.elements.length;i++){";
			$html .= "	         if(frm.elements[i].type=='checkbox'){";
			$html .= "              if(frm.elements[i].name=='checkseleccionar'){";
			$html .= "	              frm.elements[i].checked=false;";
			$html .= "	      }";
			$html .= "	      }";
			$html .= "	      }";
			$html .= "	}";
			$html .= "	}";
			$html .= " function contarMarcados(){";
			$html .= " var checks=document.getElementsByTagName('input');";
			$html .= " var totalChecks=checks.length; ";
			$html .= "  var totalNoMarcados=0; ";
			$html .= "  var totalMarcados=0; ";
			$html .= "  var cantidadchec=0; ";
			$html .= "  var cadenaMarc=[]; " ;
			$html .= "  var descMarc=[]; " ;
			$html .= "  var cadenitaNoMar=[]; " ;
			$html .= "  var descNoM=[]; " ;
			$html .= "  var cantidadM=[]; " ;
			$html .= "  var cantidadNM=[]; " ;
			$html .= "  var porcentaje_gravamenM=[]; " ;
			$html .= "  var porcentaje_gravamenNM=[]; " ;
			$html .= "  var total_costoM=[]; " ;
			$html .= "  var total_costoNM=[]; " ;
			$html .= "  var existencia_bodegaM=[]; " ;
			$html .= "  var existencia_bodegaNM=[]; " ;
			$html .= "  var existencia_inventarioM=[]; " ;
			$html .= "  var existencia_inventarioNM=[]; " ;
			$html .= "  var costo_inventarioM=[]; " ;
			$html .= "  var costo_inventarioNM=[]; " ;
			$html .= "  var fecha_vencimientoM=[]; " ;
			$html .= "  var lote=[]; " ;
      $html .= "  var fecha_vencimientoNM=[]; " ;
			$html .= "  var loteNM=[]; " ;
      
			$html .= "for(var pos=0;pos<totalChecks;pos++){" ;
			$html .= " if(checks[pos].type=='checkbox' && checks[pos].name=='checkseleccionar'){ "; 
			$html .= " if(checks[pos].checked==false){ ";
			$html .= "     totalNoMarcados++;   ";
			$html .= "}";
			$html .= "else{";
			$html .= " if(checks[pos].checked==true){ ";
			$html .= "     totalMarcados++;   ";
			$html .= " }";
			$html .= "}";
			$html .= "}";
			$html .= " }";
			
			for($i=0;$i<$contador;$i++)
			{
				$html .= "   if(document.getElementById('checkseleccionar".$i."').checked==true){";
				$html .= "      cadenaMarc.push(document.formita.codigo_producto".$i.".value); ";
				$html .= "      descMarc.push(document.formita.descripcion".$i.".value); ";
				$html .= "      cantidadM.push(document.formita.cantidad".$i.".value); ";
				$html .= "      porcentaje_gravamenM.push(document.formita.porcentaje_gravamen".$i.".value); ";
				$html .= "      total_costoM.push(document.formita.total_costo".$i.".value); ";
				$html .= "      existencia_bodegaM.push(document.formita.existencia_bodega".$i.".value); ";
				$html .= "      existencia_inventarioM.push(document.formita.existencia_inventario".$i.".value); ";
				$html .= "      costo_inventarioM.push(document.formita.costo_inventario".$i.".value); ";
				$html .= "      fecha_vencimientoM.push(document.formita.fecha_vencimiento".$i.".value); ";
				$html .= "      lote.push(document.formita.lote".$i.".value); ";
				
				$html .= "  } else { ";
				$html .= "   if(document.getElementById('checkseleccionar".$i."').checked==false){";
				$html .= "      cadenitaNoMar.push(document.formita.codigo_producto".$i.".value); ";
				$html .= "      descNoM.push(document.formita.descripcion".$i.".value); ";
				$html .= "      cantidadNM.push(document.formita.cantidad".$i.".value); ";
				$html .= "      porcentaje_gravamenNM.push(document.formita.porcentaje_gravamen".$i.".value); ";
				$html .= "      total_costoNM.push(document.formita.total_costo".$i.".value); ";
				$html .= "      existencia_bodegaNM.push(document.formita.existencia_bodega".$i.".value); ";
				$html .= "      existencia_inventarioNM.push(document.formita.existencia_inventario".$i.".value); ";
				$html .= "      costo_inventarioNM.push(document.formita.costo_inventario".$i.".value); ";
        $html .= "      fecha_vencimientoNM.push(document.formita.fecha_vencimiento".$i.".value); ";
				$html .= "      loteNM.push(document.formita.lote".$i.".value); ";
				
				$html .= "  }  ";
				$html .= "  }  ";
			}
			$html .= " 	xajax_OrganizarInfor(totalNoMarcados,totalMarcados,cadenaMarc,descMarc,cantidadM,porcentaje_gravamenM,total_costoM,existencia_bodegaM,existencia_inventarioM,costo_inventarioM,cadenitaNoMar,descNoM,fecha_vencimientoM,lote,'".$prefijo."','".$numero."','".$empresa."','".$far."','".$bodega."','".$cen."',fecha_vencimientoNM,loteNM);";
			$html .= "   }";
			$html .="  </script>\n";
			if(!empty($Datos))
			{
        $html .= "        <center>";
				$html .= "	      <fieldset style=\"width:40%\" class=\"fieldset\" align=\"center\">\n";
        $html .= "          <legend class=\"normal_10AN\">NOMENCLATURA</legend>\n";
        //Convenciones
        $colores['PV'] = ModuloGetVar('app','ReportesInventariosGral','color_proximo_vencer');
        $colores['VN'] = ModuloGetVar('app','ReportesInventariosGral','color_vencido');
        $fech_vencmodulo = ModuloGetVar('app','AdminFarmacia','dias_vencimiento_product_bodega_farmacia_'.$far);
        
        $html .= "                <table class=\"modulo_table_list\" width=\"100%\" align=\"center\">";
        $html .= "                 <td style=\"background:".$colores['PV']."\" width=\"50%\" align=\"center\">";
        $html .= "                  PROD. PROXIMO A VENCER";
        $html .= "                  </td>";
        $html .= "                 <td style=\"background:".$colores['VN']."\" width=\"50%\" align=\"center\">";
        $html .= "                  PROD. VENCIDO";
        $html .= "                  </td>";
        $html .= "                  <tr class=\"modulo_list_claro\">";
        $html .= "                  <td colspan=\"2\" align=\"center\"><input type=\"checkbox\" disabled> <b>PRODUCTO NO EXISTENTE EN LA BODEGA</b></td>";
        $html .= "                  </tr>";
        $html .= "                 </table>";
        $html .= "       </fieldset>";
        $html .= "        </center>";
        $pghtml= AutoCarga::factory('ClaseHTML');
        $mdl = AutoCarga::factory("AdminFarmaciaSQL", "classes", "app", "AdminFarmacia");
				$html .= "		<form name=\"formita\" id=\"formita\" action=\"".$action['buscador']."\" method=\"post\"     >";
				$html .= "        <input type=\"hidden\" name=\"cadenaMarc\" id=\"cadenaMarc\" value=\"\">";
				$html .= "  <table width=\"90%\" class=\"modulo_table_list\" border=\"0\"  align=\"center\">";
				$html .= "	  <tr align=\"center\" class=\"modulo_table_list_title\" >\n";
				$html .= "      <td class=\"formulacion_table_list\"  width=\"2%\"><b>No</td>\n";
				$html .= "      <td  class=\"formulacion_table_list\" width=\"10%\"><b>CODIGO</b></td>\n";
				$html .= "      <td class=\"formulacion_table_list\" width=\"45%\"><b>PRODUCTO</b></td>\n";
				$html .= "      <td class=\"formulacion_table_list\" width=\"6%\"><b>CANTIDAD.</b></td>\n";
				$html .= "      <td class=\"formulacion_table_list\" width=\"15%\"><b>FECHA.VENC.</b></td>\n";
				$html .= "      <td class=\"formulacion_table_list\" width=\"15%\"><b>LOTE.</b></td>\n";
				$html .= "	      <td align=\"center\" width=\"5%\" class=\"formulacion_table_list\" >";
				$html .= "	     <input type=\"checkbox\" name=\"Todo\"  onClick=\"Todoselec(this.form,this.checked)\">";
				$html .= "	      </td>";
				$html .= "  </tr>\n";
				$est = "modulo_list_claro"; $back = "#DDDDDD";
				$n=1;
				$i=0;
				
        $html .= "     <input type=\"hidden\" name=\"empresa_id\" id=\"empresa_id\" value=\"".$dtl['empresa_id']."\">\n";
				foreach($Datos as $key => $dtl)
				{
					$registros=$mdl->ConsultarExistenciasBodegas($far,$cen,$bodega,$dtl['codigo_producto']);
          
          $fecha =$dtl['fecha_vencimiento'];  //esta es la que viene de la DB
          list($ano,$mes,$dia) = split( '[/.-]', $fecha );
          $fecha = $mes."/".$dia."/".$ano;
          
          $fecha_actual=date("m/d/Y");
          $fecha_compara_actual=date("Y-m-d");
          //Mes/Dia/Año  "02/02/2010
          $int_nodias = floor(abs(strtotime($fecha) - strtotime($fecha_actual))/86400);
          
          $fecha_uno_act= mktime(0,0,0,date('m'),date('d'),date('Y'));
          $fecha_dos= mktime(0,0,0,$mes,$dia,$ano);
          $color ="";
          if($int_nodias<$fech_vencmodulo)
          {
            $color = "style=\"background:".$colores['PV']."\"";
          }
          
              if($fecha_dos<=$fecha_uno_act)
                {
                $color = "style=\"background:".$colores['VN']."\"";
                }
          
          $html .= "  <tr class=\"modulo_list_claro\">\n";
					$html .= "      <td align=\"center\"><b>".$n."</b></td>\n";
					$html .= "     <input type=\"hidden\" name=\"empresa_id\" id=\"empresa_id\" value=\"".$dtl['empresa_id']."\">\n";
					$html .= "     <input type=\"hidden\" name=\"prefijo\" id=\"prefijo\" value=\"".$dtl['prefijo']."\">\n";
					$html .= "     <input type=\"hidden\" name=\"numero\" id=\"numero\" value=\"".$dtl['numero']."\">\n";
					$html .= "     <input type=\"hidden\" name=\"farmacia_id\" id=\"farmacia_id\" value=\"".$dtl['farmacia_id']."\">\n";
					$html .= "      <td align=\"left\"><b>".$dtl['codigo_producto']."</b></td>\n";
					$html .= "        <input type=\"hidden\" name=\"codigo_producto".$i."\" id=\"codigo_producto".$i."\" value=\"".$dtl['codigo_producto']."\">";
					$html .= "        <input type=\"hidden\" name=\"cantidad".$i."\" id=\"cantidad".$i."\" value=\"".$dtl['cantidad']."\">";
					$html .= "        <input type=\"hidden\" name=\"porcentaje_gravamen".$i."\" id=\"porcentaje_gravamen".$i."\" value=\"".$dtl['porcentaje_gravamen']."\">";
					$html .= "        <input type=\"hidden\" name=\"total_costo".$i."\" id=\"total_costo".$i."\" value=\"".$dtl['total_costo']."\">";
					$html .= "        <input type=\"hidden\" name=\"existencia_bodega".$i."\" id=\"existencia_bodega".$i."\" value=\"".$dtl['existencia_bodega']."\">";
					$html .= "        <input type=\"hidden\" name=\"existencia_inventario".$i."\" id=\"existencia_inventario".$i."\" value=\"".$dtl['existencia_inventario']."\">";
					$html .= "        <input type=\"hidden\" name=\"costo_inventario".$i."\" id=\"costo_inventario".$i."\" value=\"".$dtl['costo_inventario']."\">";
					$html .= "      <td align=\"center\"><b>".$dtl['descripcion']." ".$dtl['unidad']." ".$dtl['contenido_unidad_venta']." -".$dtl['laboratorio']."</b></td>\n";
					$html .= "        <input type=\"hidden\" name=\"descripcion".$i."\" id=\"descripcion".$i."\" value=\"".$dtl['descripcion']."\">";
					$html .= "      <td align=\"center\"><b>".round($dtl['cantidad'])."</b> </td>\n";
					$html .= "      <td align=\"center\" ".$color."><b>".$dtl['fecha_vencimiento']."</b> </td>\n";
					$html .= "        <input type=\"hidden\" name=\"fecha_vencimiento".$i."\" id=\"fecha_vencimiento".$i."\" value=\"".$dtl['fecha_vencimiento']."\">";
					$html .= "      <td align=\"center\"><b>".$dtl['lote']." </b></td>\n";
				    $html .= "        <input type=\"hidden\" name=\"lote".$i."\" id=\"lote".$i."\" value=\"".$dtl['lote']."\">";
					$html .= "        <input type=\"hidden\" name=\"abreviatura_estado".$i."\" id=\"abreviatura_estado".$i."\" value=\"".$dtl['abreviatura_estado']."\">";
					
          if(empty($registros))
            $disabled = " disabled ";
            else
            $disabled = " ";
          
          $html .=" <td width=\"5%\"> <input ".$disabled." type=\"checkbox\" name=\"checkseleccionar\" id=\"checkseleccionar".$i."\" value=\"".$i."\"> ";       
					$html .= " </td>\n";
					$html .= "  </tr>\n";
					$i=$i+1; 
					$n=$n+1;
				}
				$html .= "	</table><br>\n";
				$html .= "  <table width=\"70%\"  border=\"0\"  align=\"center\">";
				$html .= "		<tr>\n";
				$html .= "	             	<td  colspan=\"10\"  align='center'>\n";
				$html .= "			         <input class=\"input-submit\" type=\"button\" name=\"Buscar\" value=\"Validar\" onClick=\"contarMarcados();\"  >\n";
				$html .= "		          	</td>\n";
				$html .= "		<tr>\n";
				$html .= "	</table><br>\n";
				$html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
				$html .= "	<br>\n";
			}     
			else
			{
			if($request)
				$html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
			}
			$html .= "		   </form>\n";
			$html .= "<table align=\"center\">\n";
			$html .= "<br>";
			$html .= "  <tr>\n";
			$html .= "      <td align=\"center\" class=\"label_error\">\n";
			$html .= "        <a href=\"".$action['volver']."\">VOLVER</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= $this->CrearVentana(800,"GENERAR DOCUMENTO DE INGRESO");
			$html .= ThemeCerrarTabla();
			return $html;
		}
	/*
		* Funcion que Contiene la Forma de los productos que han quedado pendientes despues de  generar  el documento de ingreso.
		* @param array $action vector que contiene los link de la aplicacion
		* @param array $Datos vector que contiene la informacion del documento de despacho.
		* @param String $prefijo  variable que contiene el prefijo del documento de despacho.
		* @param String $numero variable  que contiene el numero del documento de despacho.
		* @param String $empresa variable que contiene el id de la empresa que genera del documento de despacho.
		* @param String $far  variable que contiene el id de la empresa que va a generar el documento de ingreso.
		* @param String $bodega  variable que contiene el id de la bodega de la farmacia que va a generar el documento de ingreso.
		* @param String $contador  variable que contiene la cantidad de registros arrojados de la consulta de los productos asociados al documento de despacho.
		* @param String $cen  variable que contiene el centro de utilidad de la empresa ò farmacia que va a generar el documento de ingreso.
        * @return string $html retorna la cadena con el codigo html de la pagina.
	*/
		function FormaPendientesProductos($action,$Datos,$request,$conteo,$pagina,$prefijo,$numero,$empresa,$far,$bodega,$contador,$cen,$abreviatura_estado)
		{
		 
			$html  = ThemeAbrirTabla("PRODUCTOS DEL DOCUMENTO DE DESPACHO PENDIENTES POR VERIFICAR");
			$html  .="  <script>\n";
			$html  .=" function  ValidarDtosP(frms){ ";
			$html .= "    if(frms.observar.value==\"\")\n";
			$html .= "    {\n";
			$html .= "     alert(' DEBE  INGRESAR  UNA  OBSERVACION  PARA  GENERAR  EL  DOCUMENTO  DE  INGRESO ');\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    if(frms.observar.value!=\"\")\n";
			$html .= "    {\n";
			$html .= " 	xajax_TransVariablesP(frms.observar.value,'".$empresa."','".$bodega."','".$cen."','".$prefijo."',".$numero.",'".$far."','".$abreviatura_estado."','".$prefijo."','".$numero."');";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    frms.submit();\n";
			$html .= "    }\n";  
			$html  .=" function  ValidarDtosPen(frms){ ";
			$html .= "    if(frms.observar.value==\"\")\n";
			$html .= "    {\n";
			$html .= "     alert(' DEBE  INGRESAR  UNA  OBSERVACION  PARA  GENERAR  EL  DOCUMENTO  DE  INGRESO ');\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    if(frms.observar.value!=\"\")\n";
			$html .= "    {\n";
			$html .= " 	xajax_TransVariables(frms.observar.value,'".$empresa."','".$bodega."','".$cen."','".$abreviatura_estado."','".$prefijo."',".$numero.",'".$far."','".$abreviatura_estado."');";
			                       
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    frms.submit();\n";
			$html .= "    }\n";			
			$html .= "	function Todoselec(frm,x){";
			$html .= "	  if(x==true ){";
			$html .= "	    for(i=0;i<frm.elements.length;i++){";
			$html .= "	      if(frm.elements[i].type=='checkbox'){";
			$html .= "              if(frm.elements[i].name=='checkseleccionar' ){";
			$html .= "	                  frm.elements[i].checked=true;";
			$html .= "	      }";
			$html .= "	      }";
			$html .= "	 }";
			$html .= "	 }";
			$html .= "	  else{";
			$html .= "	    for(i=0;i<frm.elements.length;i++){";
			$html .= "	         if(frm.elements[i].type=='checkbox'){";
			$html .= "              if(frm.elements[i].name=='checkseleccionar'){";
			$html .= "	              frm.elements[i].checked=false;";
			$html .= "	      }";
			$html .= "	      }";
			$html .= "	      }";
			$html .= "	}";
			$html .= "	}";
			$html .= " function contarMarcados(){";
			$html .= " var checks=document.getElementsByTagName('input');";
			$html .= " var totalChecks=checks.length; ";
			$html .= "  var totalNoMarcados=0; ";
			$html .= "  var totalMarcados=0; ";
			$html .= "  var cantidadchec=0; ";
			$html .= "  var cadenaMarc=[]; " ;
			$html .= "  var descMarc=[]; " ;
			$html .= "  var cadenitaNoMar=[]; " ;
			$html .= "  var descNoM=[]; " ;
			$html .= "  var cantidadM=[]; " ;
			$html .= "  var cantidadNM=[]; " ;
			$html .= "  var porcentaje_gravamenM=[]; " ;
			$html .= "  var porcentaje_gravamenNM=[]; " ;
			$html .= "  var total_costoM=[]; " ;
			$html .= "  var total_costoNM=[]; " ;
			$html .= "  var existencia_bodegaM=[]; " ;
			$html .= "  var existencia_bodegaNM=[]; " ;
			$html .= "  var existencia_inventarioM=[]; " ;
			$html .= "  var existencia_inventarioNM=[]; " ;
			$html .= "  var costo_inventarioM=[]; " ;
			$html .= "  var costo_inventarioNM=[]; " ;
			$html .= "  var fecha_vencimientoM=[]; " ;
			$html .= "  var lote=[]; " ;
      $html .= "  var fecha_vencimientoNM=[]; " ;
			$html .= "  var loteNM=[]; " ;
			$html .= "for(var pos=0;pos<totalChecks;pos++){" ;
			$html .= " if(checks[pos].type=='checkbox' && checks[pos].name=='checkseleccionar'){ "; 
			$html .= " if(checks[pos].checked==false){ ";
			$html .= "     totalNoMarcados++;   ";
			$html .= "}";
			$html .= "else{";
			$html .= " if(checks[pos].checked==true){ ";
			$html .= "     totalMarcados++;   ";
			$html .= "}";
			$html .= "}";
			$html .= "}";
			$html .= "}";
			for($i=0;$i<$contador;$i++)
			{
				$html .= "   if(document.getElementById('checkseleccionar".$i."').checked==true){";
				$html .= "      cadenaMarc.push(document.formita.codigo_producto".$i.".value); ";
				$html .= "      descMarc.push(document.formita.descripcion".$i.".value); ";
				$html .= "      cantidadM.push(document.formita.cantidad".$i.".value); ";
				$html .= "      porcentaje_gravamenM.push(document.formita.porcentaje_gravamen".$i.".value); ";
				$html .= "      total_costoM.push(document.formita.total_costo".$i.".value); ";
				$html .= "      existencia_bodegaM.push(document.formita.existencia_bodega".$i.".value); ";
				$html .= "      existencia_inventarioM.push(document.formita.existencia_inventario".$i.".value); ";
				$html .= "      costo_inventarioM.push(document.formita.costo_inventario".$i.".value); ";
				$html .= "      fecha_vencimientoM.push(document.formita.fecha_vencimiento".$i.".value); ";
				$html .= "      lote.push(document.formita.lote".$i.".value); ";
				$html .= "  } else { ";
				$html .= "   if(document.getElementById('checkseleccionar".$i."').checked==false){";
				$html .= "      cadenitaNoMar.push(document.formita.codigo_producto".$i.".value); ";
				$html .= "      descNoM.push(document.formita.descripcion".$i.".value); ";
				$html .= "      cantidadNM.push(document.formita.cantidad".$i.".value); ";
				$html .= "      porcentaje_gravamenNM.push(document.formita.porcentaje_gravamen".$i.".value); ";
				$html .= "      total_costoNM.push(document.formita.total_costo".$i.".value); ";
				$html .= "      existencia_bodegaNM.push(document.formita.existencia_bodega".$i.".value); ";
				$html .= "      existencia_inventarioNM.push(document.formita.existencia_inventario".$i.".value); ";
				$html .= "      costo_inventarioNM.push(document.formita.costo_inventario".$i.".value); ";
        $html .= "      fecha_vencimientoNM.push(document.formita.fecha_vencimiento".$i.".value); ";
				$html .= "      loteNM.push(document.formita.lote".$i.".value); ";
				$html .= "  }  ";
				$html .= "  }  ";
			}
			$html .= " 	xajax_OrganizarInforPend(totalNoMarcados,totalMarcados,cadenaMarc,descMarc,cantidadM,porcentaje_gravamenM,total_costoM,existencia_bodegaM,existencia_inventarioM,costo_inventarioM,cadenitaNoMar,descNoM,fecha_vencimientoM,lote,'".$prefijo."','".$numero."','".$empresa."','".$far."','".$bodega."','".$cen."','".$numeracion."',fecha_vencimientoNM,loteNM);";
			$html .= "   }";
			$html .="  </script>\n";
			if(!empty($Datos))
			{
				$pghtml = AutoCarga::factory('ClaseHTML');
				$html .= "		<form name=\"formita\" id=\"formita\" action=\"".$action['buscador']."\" method=\"post\"     >";
				$html .= "        <input type=\"hidden\" name=\"cadenaMarc\" id=\"cadenaMarc\" value=\"\">";
				$html .= "  <table width=\"90%\" class=\"modulo_table_list\" border=\"0\"  align=\"center\">";
     		$html .= "	  <tr align=\"center\" class=\"modulo_table_list_title\" >\n";
				$html .= "      <td class=\"formulacion_table_list\"  width=\"2%\"><b>No</td>\n";
				$html .= "      <td  class=\"formulacion_table_list\" width=\"10%\"><b>CODIGO</b></td>\n";
				$html .= "      <td class=\"formulacion_table_list\" width=\"45%\"><b>PRODUCTO</b></td>\n";
				$html .= "      <td class=\"formulacion_table_list\" width=\"6%\"><b>CANTIDAD.</b></td>\n";
				$html .= "      <td class=\"formulacion_table_list\" width=\"15%\"><b>FECHA.VENC.</b></td>\n";
				$html .= "      <td class=\"formulacion_table_list\" width=\"15%\"><b>LOTE.</b></td>\n";
				$html .= "	      <td align=\"center\" width=\"5%\" class=\"formulacion_table_list\" >";
				$html .= "	     <input type=\"checkbox\" name=\"Todo\"  onClick=\"Todoselec(this.form,this.checked)\">";
				$html .= "	      </td>";
				$html .= "  </tr>\n";
      
				$est = "modulo_list_claro"; $back = "#DDDDDD";
				$n=1;
				$i=0;
				$html .= "     <input type=\"hidden\" name=\"empresa_id\" id=\"empresa_id\" value=\"".$dtl['empresa_id']."\">\n";
				foreach($Datos as $key => $dtl)
				{
					$html .= "  <tr class=\"modulo_list_claro\">\n";
					$html .= "      <td align=\"center\"><b>".$n."</b></td>\n";
				
					$html .= "     <input type=\"hidden\" name=\"empresa_id\" id=\"empresa_id\" value=\"".$dtl['empresa_id']."\">\n";
					$html .= "     <input type=\"hidden\" name=\"prefijo\" id=\"prefijo\" value=\"".$dtl['prefijo']."\">\n";
					$html .= "     <input type=\"hidden\" name=\"numero\" id=\"numero\" value=\"".$dtl['numero']."\">\n";
					$html .= "     <input type=\"hidden\" name=\"farmacia_id\" id=\"farmacia_id\" value=\"".$dtl['farmacia_id']."\">\n";
					$html .= "      <td align=\"left\"><b>".$dtl['codigo_producto']."</b></td>\n";
					$html .= "        <input type=\"hidden\" name=\"codigo_producto".$i."\" id=\"codigo_producto".$i."\" value=\"".$dtl['codigo_producto']."\">";
					$html .= "        <input type=\"hidden\" name=\"cantidad".$i."\" id=\"cantidad".$i."\" value=\"".$dtl['cantidad']."\">";
					$html .= "        <input type=\"hidden\" name=\"porcentaje_gravamen".$i."\" id=\"porcentaje_gravamen".$i."\" value=\"".$dtl['porcentaje_gravamen']."\">";
					$html .= "        <input type=\"hidden\" name=\"total_costo".$i."\" id=\"total_costo".$i."\" value=\"".$dtl['total_costo']."\">";
					$html .= "        <input type=\"hidden\" name=\"existencia_bodega".$i."\" id=\"existencia_bodega".$i."\" value=\"".$dtl['existencia_bodega']."\">";
					$html .= "        <input type=\"hidden\" name=\"existencia_inventario".$i."\" id=\"existencia_inventario".$i."\" value=\"".$dtl['existencia_inventario']."\">";
					$html .= "        <input type=\"hidden\" name=\"costo_inventario".$i."\" id=\"costo_inventario".$i."\" value=\"".$dtl['costo_inventario']."\">";
					$html .= "      <td align=\"center\"><b>".$dtl['descripcion']." ".$dtl['contenido_unidad_venta']."  ".$dtl['unidad']."  -".$dtl['laboratorio']."</b> </td>\n";
					$html .= "        <input type=\"hidden\" name=\"descripcion".$i."\" id=\"descripcion".$i."\" value=\"".$dtl['descripcion']."\">";
					$html .= "      <td align=\"center\"><b>".round($dtl['cantidad'])."</b> </td>\n";
					$html .= "      <td align=\"center\"><b>".$dtl['fecha_vencimiento']."</b> </td>\n";
					$html .= "        <input type=\"hidden\" name=\"fecha_vencimiento".$i."\" id=\"fecha_vencimiento".$i."\" value=\"".$dtl['fecha_vencimiento']."\">";
					$html .= "      <td align=\"center\"><b>".$dtl['lote']." </b></td>\n";
				    $html .= "        <input type=\"hidden\" name=\"lote".$i."\" id=\"lote".$i."\" value=\"".$dtl['lote']."\">";
					$html .=" <td width=\"5%\"> <input type=\"checkbox\" name=\"checkseleccionar\" id=\"checkseleccionar".$i."\" value=\"".$i."\">";       
					$html .= " </td>\n";
					$html .= "  </tr>\n";
					$i=$i+1; 
					$n=$n+1;
				}
				$html .= "	</table><br>\n";
				$html .= "  <table width=\"70%\"  border=\"0\"  align=\"center\">";
				$html .= "		<tr>\n";
				$html .= "	             	<td  colspan=\"10\"  align='center'>\n";
				$html .= "			         <input class=\"input-submit\" type=\"button\" name=\"Buscar\" value=\"Validar\" onClick=\"contarMarcados();\"  >\n";
				$html .= "		          	</td>\n";
				$html .= "		<tr>\n";
				$html .= "	</table><br>\n";
				$html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
				$html .= "	<br>\n";
			}else
			{
				if($request)
				$html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
			}
			$html .= "		   </form>\n";
			$html .= "<table align=\"center\">\n";
			$html .= "<br>";
			$html .= "  <tr>\n";
			$html .= "      <td align=\"center\" class=\"label_error\">\n";
			$html .= "        <a href=\"".$action['volver']."\">VOLVER</a>\n";
			$html .= "      </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= $this->CrearVentana(800,"GENERAR DOCUMENTO DE INGRESO");
			$html .= ThemeCerrarTabla();
			return $html;
		}
	/*
		* Funcion que Contiene la Forma de los productos que han quedado pendientes despues de  generar  el documento de ingreso.
		* @param array $action vector que contiene los link de la aplicacion
		* @param String $numeroGenerado variable  que contiene el numero del documento de Ingreso que se ha generado.
        * @return string $html retorna la cadena con el codigo html de la pagina.
	*/	
			function FormaMensajeGnerarDocumento($action,$numeroGenerado,$preIng,$farmacia)
			{
			$rpt  = new GetReports();
			$html .= "<script>";
      $html .= "
                function Imprimir(direccion,empresa_id,prefijo,numero)
                {
                var url=direccion+\"?empresa_id=\"+empresa_id+\"&prefijo=\"+prefijo+\"&numero=\"+numero;
                window.open(url,'','width=700,height=450,X=300,Y=800,resizable=no,status=no,scrollbars=yes,location=no,menubar=yes,toolbar=yes');
                }
                ";
			$html .= "</script>";
      $html .= "<center>";
      $html .= "	<table width=\"98%\" align=\"center\">\n";
			$html .= "		<tr>\n";
			$html .= "			<td>\n";
			$html .= "				<table width=\"90%\" align=\"center\">\n";
			$html .= "					<tr>\n";
			$html .= "						<td>\n";
			$html .= "							<div class=\"tab-pane\" id=\"creacion_asociacion_estadosdocumentos\">\n";
			$html .= "								<script>	tabPane = new WebFXTabPane( document.getElementById( \"creacion_asociacion_estadosdocumentos\" )); </script>\n";
      
      //PRIMER TAB
			$html .= "								<div class=\"tab-page\" id=\"crear_estadosdocumentos\">\n";
			$html .= "									<h2 class=\"tab\">MENSAJE</h2>\n";
      $html .= "									<script>	tabPane.addTabPage( document.getElementById(\"crear_estadosdocumentos\")); </script>\n";
        $html .= ThemeAbrirTabla("MENSAJE");
				$html .= "<table border=\"1\" width=\"50%\" align=\"center\" >\n";
				$html .= "	<tr>\n";
				$html .= "		<td>\n";
				$html .= "		  <table width=\"100%\" class=\"modulo_table_list\">\n";
				$html .= "		    <tr class=\"normal_10AN\">\n";
				$html .= "		      <td align=\"center\">SE GENERO EL DOCUMENTO DE INGRESO DE PRODUCTOS( ".$preIng." ) NUMERO:\n".$numeroGenerado."";
				$html .= "";
				$datos['prefijo']=$preIng;
				$datos['numero']=$numeroGenerado;
				$datos['empresa_id']=$farmacia;
				$html .= $rpt->GetJavaReport('app','AdminFarmacia','DocumentoIngreso',$datos,
  																	array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
				$fnc  = $rpt->GetJavaFunction();

				$html .= "			  <a title=\"IMPRIMIR\" class=\"label_error\" href=\"javascript:".$fnc."\">\n";
				$html .= "			    <image src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\">\n";
				$html .= "			  </a>\n";
				$html .= "";
				$html .= "          </td>\n";
				$html .= "		    </tr>\n";
        
        $html .= "		    <tr class=\"normal_10AN\">\n";
				$html .= "		      <td align=\"center\">IMPRIMIR ACTAS TECNICAS";
				$html .= "";
				$html .= "        <a title='ACTAS TECNICAS' onclick=\"xajax_ActasTecnicas('".$farmacia."','".$preIng."','".$numeroGenerado."');\">\n";
				$html .= "			    <image src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\">\n";
				$html .= "			  </a>\n";
				$html .= "";
				$html .= "          </td>\n";
				$html .= "		    </tr>\n";
        
        $html .= "		    <tr class=\"normal_10AN\">\n";
				$html .= "		      <td align=\"center\">CREAR ACTA TECNICA DE RECEPCION DE PRODUCTOS";
				$html .= "";
				$html .= "			  <a title=\"IMPRIMIR\" class=\"label_error\" onclick=\"xajax_VerDocumentoCreado('".$farmacia."','".$preIng."','".$numeroGenerado."');\">\n";
				$html .= "			    <image src=\"".GetThemePath()."/images/folder_vacio.png\" border=\"0\">\n";
				$html .= "			  </a>\n";
				$html .= "";
				$html .= "          </td>\n";
				$html .= "		    </tr>\n";
				$html .= "		  </table>\n";
				$html .= "		</td>\n";
				$html .= "	</tr>\n";
				$html .= "</table>";
				$html .= "<br>";
				$html .= ThemeCerrarTabla();
        $html .= "								</div>\n"; //CIERRA PRIMER TAB
      $html .= "								<div class=\"tab-page\" id=\"asociar_estadosdocumentos\">\n";
      $html .= "									<h2 class=\"tab\">PRODUCTOS DEL DOCUMENTO</h2>\n";
      $html .= "									<script>	tabPane.addTabPage( document.getElementById(\"asociar_estadosdocumentos\")); </script>\n";
      $html .= ThemeAbrirTabla("CREAR ACTA TECNICA DE RECEPCION DE PRODUCTOS");
      $html .= " <div id=\"Documento\"></div>";
      $html .= ThemeCerrarTabla();
      $html .= "								</div>\n"; //CIERRO SEGUNDO TAB
      $html .= "							</div>\n";
			$html .= "						</td>\n";
			$html .= "					</tr>\n";
			$html .= "				</table>\n";
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "  </table>\n"; //CIERRO TODOS LOS TABS
        
        $html .= "<table align=\"center\" width=\"50%\">\n";
				$html .= "  <tr>\n";
				$html .= "    <td align=\"center\">\n";
				$html .= "      <a href=\"".$action['volver']."\"  class=\"label_error\">\n";
				$html .= "        VOLVER\n";
				$html .= "      </a>\n";
				$html .= "    </td>\n";
				$html .= "  </tr>\n";
				$html .= "</table>\n";
				$html .= $this->CrearVentana(800,"ACTA TECNICA DE RECEPCION DE PRODUCTOS");
				$html .= " <script> ";
				$html .= "   tabPane.setSelectedIndex(0);";
				$html .= " </script> ";
        return $html;
			} 		
      /*
      * Funcion que Contiene la Forma del mensaje en el caso de que no exista una variable parametrizada 
      * @param array $action vector que contiene los link de la aplicacion
      */	
			function FormaMensajeError($action,$farmacia)
			{
				$rpt  = new GetReports();
				$html  = ThemeAbrirTabla("MENSAJE");
				$html .= "<table border=\"1\" width=\"50%\" align=\"center\" >\n";
				$html .= "	<tr>\n";
				$html .= "		<td>\n";
				$html .= "		  <table width=\"100%\" class=\"modulo_table_list\">\n";
				$html .= "		    <tr class=\"normal_10AN\">\n";
				$html .= "		      <td align=\"center\">NO EXISTE UNA VARIABLE PARAMETRIZADA PARA CONTINUAR CON EL PROCESO.";
				$html .= "		    </tr>\n";
      	$html .= "		  </table>\n";
				$html .= "		</td>\n";
				$html .= "	</tr>\n";
				$html .= "</table>";
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
      
         
		/* EMPIEZA LO DE DEVOLUCION X FECHA DE VENCIMIENTO*/
		/** Funcion que Contiene la Forma del tipo de documento de egreso 
		* @param array $action vector que contiene los link de la aplicacion
		  * @return string $html retorna la cadena con el codigo html de la pagina.
		*/
		function FormaTipoDocumento($action,$datos,$farmacia,$Centrid,$bod)
		{
			$html  .= ThemeAbrirTabla('TIPO DOCUMENTO');
			$html .= "            <form name=\"tipodocumento\"  method=\"post\">\n";
			$html .= "                 <table width=\"75%\" align=\"center\" class=\"modulo_table_list\">\n";
			$html .= "                    <tr class=\"modulo_list_claro\">\n";
			$html .= "                       <td width=\"45%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
			$html .= "                          TIPO CLASE - DOCUMENTO";
			$html .= "                       </td>";
			$html .= "                       <td width=\"7%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
			$html .= "                          PREFIJO";
			$html .= "                       </td>";
			$html .= "                       <td width=\"45%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
			$html .= "                          DESCRIPCION ";
			$html .= "                       </td>";
			$html .= "                       <td width=\"15%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
			$html .= "                          ACCION ";
			$html .= "                       </td>";
			$html .= "                       <td width=\"15%\" colspan=\"1\" align=\"left\" class=\"modulo_table_list_title\">\n";
			$html .= "                          C ";
			$html .= "                       </td>";
			$html .= "                    </tr>";
			foreach($datos as $key => $dtl)
				{
                                  $mdl = AutoCarga::factory("AdminFarmaciaSQL", "classes", "app", "AdminFarmacia");
                                  $docPermitidos=$mdl->DocumentosParaFarmacia();
                                  $valor=explode(",",$docPermitidos['valor']);
                                  
                                     if(in_array($dtl['documento_id'],$valor)){
					$html .= "  <tr class=\"modulo_list_claro\">\n";
					$html .= "      <td class=\"label_error\" align=\"center\"><b>".$dtl['tipo_clase_documento']."</b></td>\n";
					$html .= "      <td align=\"left\"><b>".$dtl['prefijo']."</b></td>\n";
					$html .= "      <td align=\"left\"><b>".$dtl['descripcion']."</b></td>\n";
					$html .= "      <td align=\"center\">\n";
					$html .= "         <a href=\"#\" onclick=\"xajax_TransDocid('".$dtl['documento_id']."','".$_REQUEST['empresa_destino']."')\" class=\"label_error\"><img src=\"".GetThemePath()."/images/modificar.png\" border=\"0\" title=\"tipo documento\"></a>\n";
					$html .= "      </td>\n";
					$html .= "      <td align=\"center\">\n";
					$html .= "        <a href=\"#\" onclick=\"xajax_DocumentosEstadosVerificar('".$farmacia."','".$Centrid."','".$bod."','".$dtl['documento_id']."','".$dtl['tipo_doc_bodega_id']."')\" class=\"label_error\">VER</a>\n";
					$html .= "      </td>\n";
					$html .= "  </tr>\n";
                                        }
				}
			$html .= "                 </table>";
			$html .= "<br> ";
			$html .= "<table  width=\"75%\"  class=\"modulo_list_claro\"   align=\"center\">\n";
			$html .= "  <tr class=\"modulo_list_oscuro\">\n";
	    	$html .= "      <td colspan=\"25\"><div id=\"DocumentosTmp\"></div></td>\n";
			$html .= "  </tr>\n";
			$html .= "</table><br>\n";
			$html .= "<table align=\"center\" width=\"50%\">\n";
			$html .= "  <tr>\n";
			$html .= "    <td align=\"center\">\n";
			$html .= "      <a href=\"".$action['volver']."\"  class=\"label_error\">\n";
			$html .= "        VOLVER\n";
			$html .= "      </a>\n";
			$html .= "    </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
				
			$html .= "              </form>";
			$html .= ThemeCerrarTabla();
		    return $html;
		
		}
	/** Funcion que Contiene la Forma del del tipo de documentos generados de Egreso 
		* @param array $action vector que contiene los link de la aplicacion
		  * @return string $html retorna la cadena con el codigo html de la pagina.
		*/
		function PintarTabla($action,$farnom,$desCentr,$bodegades,$doc_tmp_id,$preEgre,$Descripcion,$bodegas_doc_id,$Centrid,$bod,$tipo_doc_general_id,$ConEstados,$empresa_destino,$farmacia)
    {
	    $html  = ThemeAbrirTabla('TIPO DE DOCUMENTO EGRESO');
			$html .="  <script>\n";
			$html .= "	  function GrabarDocumentoTmp(frms)\n";
			$html .= "	  {\n";
			$html .= "    if(frms.estados.selectedIndex==0)\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE SELECCIONAR EL ESTADO DEL DOCUMENTO';\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    if(frms.estados.selectedIndex!=-1)\n";
			$html .= "    {\n";
			$html .= " 	xajax_TransferirDatosTmp(frms.empresa_destino.value,frms.observar.value,'".$bodegas_doc_id."','".$doc_tmp_id."',frms.estados.value,'".$tipo_doc_general_id."','".$farmacia."');";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    frm.submit();\n";
			$html .= "    }\n";
			$html .="  </script>\n";
			$html .= "<form name=\"FormaDocumentoDev\" id=\"FormaDocumentoDev\"  method=\"post\" >\n";
	    $html .= "                 <table width=\"60%\" align=\"center\"  border=\"0\" class=\"modulo_table_list\">\n";
			$html .= "                   <tr class=\"modulo_table_list_title\">\n";
			$html .= "                      <td align=\"center\" >\n";
			$html .= "                        <a title='DOCUMENTO TEMPORAL ID'>TMP-ID</a>";
			$html .= "                                  <input type=\"hidden\" name=\"empresa_destino\" id=\"empresa_destino\" value=\"".$empresa_destino."\"> ";
      $html .= "                      </td>\n";
			$html .= "                     <td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "                        ".$doc_tmp_id;
			$html .= "                      </td>\n";
			$html .= "                       <td align=\"center\">\n";
			$html .= "                         <a title='ID DOCUMENTO DE LA BODEGA'>TIPO DOCUMENTO<a> ";
			$html .= "                      </td>\n";
			$html .= "                       <td align=\"left\" class=\"modulo_list_claro\" class=\"label_mark\">\n";
			$html .= "                        ".$tipo_doc_general_id;
			$html .= "                       </td>\n";
			$html .= "                      <td align=\"center\">\n";
			$html .= "                         FARMACIA ";
			$html .= "                       </td>\n";
			$html .= "                      <td align=\"left\" class=\"modulo_list_claro\">\n";
			$html .= "                        ".$farnom. " (".$desCentr.")";
			$html .= "                       </td>\n";
			$html .= "                    </tr>\n";
			$html .= "                   <tr class=\"modulo_table_list_title\">\n";
			$html .= "                     <td align=\"center\">\n";
			$html .= "                        <a title='PREFIJO DEL DOCUMENTO'>PREFIJO<a>";
			$html .= "                      </td>\n";
			$html .= "                       <td align=\"left\" class=\"modulo_list_claro\" class=\"label_mark\">\n";
			$html .= "                          ".$preEgre;
			$html .= "                       </td>\n";
			$html .= "                      <td align=\"center\" >\n";
			$html .= "                        <a title='DOCUMENTO DESCRIPCION'>DESCRIPCION<a>";
			$html .= "                      </td>\n";
			$html .= "                       <td COLSPAN='7' align=\"left\" class=\"modulo_list_claro\" class=\"label_mark\">\n";
			$html .= "                         ".$Descripcion;
			$html .= "                     </td>\n";
			$html .= "                   <tr class=\"modulo_table_list_title\">\n";
			$html .= "  <td  width='25%' align=\"center\"  >\n";
			$html .= "   ESTADOS";
			$html .= " </td>\n";
			$html .= " <td COLSPAN='6' align=\"left\" class=\"modulo_list_claro\">";
			$html .= "   <select COLSPAN='4' class=\"select\"  name=\"estados\" id=\"estados\" >";
			$html .= "  <option value=\"-1\">-- Seleccionar --</option>\n";
			$selected ="";
			foreach ($ConEstados as $indice=>$valor)
			{ 
							$html .= "  <option value=\"".$valor['abreviatura']."\" ".$selected.">".$valor['descripcion']."</option>";
			}
			$html .= " </td>";
            $html .= "     </tr>\n";
			$html .= "                        <td rowspan='1' colspan='10' align=\"center\" class=\"modulo_list_claro\"> \n";
			$html .= "                          <fieldset>";
			$html .= "                           <legend>OBSERVACIONES</legend>";
			$html .= "                              <TEXTAREA id='observar' ROWS='2' COLS=55 ></TEXTAREA>\n";
			$html .= "                        </td>\n";
			$html .= "                     </tr>\n";
			$html .= "                          </fieldset>";
			$html .= "</table><br>\n";
			$html .= "			<table   width=\"30%\" align=\"center\" border=\"0\"   >";
			$html .= "  <tr>\n";
			$html .= "	             	<td  colspan=\"10\"  align='center'>\n";
			$html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\"   value=\"GRABAR DOCUMENTO\" onclick=\"GrabarDocumentoTmp(document.FormaDocumentoDev);\" >\n";
            $html .= " </td>\n";
			$html .="<div align=\"center\" class=\"label_error\" id='error'></div> ";
			$html .= "		</tr>\n";
			$html .= "</table><br>\n";
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
	/* Funcion que Contiene la Forma de buscar productos
		* @param array $action vector que contiene los link de la aplicacion
		* @param array $datos vector que contiene la informacion del productos que se ha buscado.
		* @param String $farnom variable que contiene el nombre de la farmacia seleccionada.
		* @param String $desCentr  variable que contiene el nombre del centro de utilidad seleccionado.
		* @param String $bodegades variable  que contiene el nombre de la bodega seleccionada.
		* @param String $dias_vencimiento variable que contiene los dias que se debe devolver el producto antes de la fecha de vencimiento.
		* @param String $far  variable que contiene el id de la empresa que va a generar el documento de devolucion.
		* @param String $Centrid  variable que contiene el id del centro de utilidad  de la empresa.
		* @param String $bod  variable que contiene el id de la bodega de la farmacia.
		* @param array $cdev vector que contiene la informacion de los  productos que se han seleccionados.
		* @param String $tipo_doc_general_id  variable que contiene el id del tipo del documento general.
		* @param array $InfDocP vector que contiene la informacion de los estados del documento.
		* @param String $bodegas_doc_id  variable que contiene el id de bodegas_documentos.
		* @param array $Inftmp_d vector que contiene la informacion de inv_bodegas_movimiento_tmp_d.
        * @return string $html retorna la cadena con el codigo html de la pagina.
	*/	
		
	function FormaBuscarProductos($action,$farnom,$desCentr,$bodegades,$datos,$conteo,$pagina,$request,$dias_vencimiento,$far,$Centrid,$bod,$cdev,$tipo_doc_general_id,$InfDocP,$bodegas_doc_id,$Inftmp_d,$estadosEmpresa,$doc_tmp_id,$ConEstados,$sw_verificono,$documentos2,$num)
		{  
			$ctl = AutoCarga::factory("ClaseUtil"); 
			$html .= $ctl->LimpiarCampos();
			$html  .="  <script>\n";
			$html .= "	function acceptNum(evt)\n";
			$html .= "	{\n";
			$html .= "		var nav4 = window.Event ? true : false;\n";
			$html .= "		var key = nav4 ? evt.which : evt.keyCode;\n";
			$html .= "		return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$html .= "	}\n";		
			$html .="  </script>\n";
			$html .= ThemeAbrirTabla("BUSCAR PRODUCTO ");
			$html .= "<form name=\"Forma12\" id=\"Forma12\" method=\"post\"  action=\"".$action['buscador']."\">\n";
			$html .= "<table   width=\"30%\" align=\"center\" border=\"0\"   >";
			$html .= "   <tr class=\"modulo_table_list_title\">\n";
			$html .= "		          	<td  width=\"40%\" class=\"modulo_table_list_title\">CODIGO:</td>\n";
			$html .= "	                <td class=\"modulo_list_claro\" colspan=\"5\" align=\"left\">\n";
			$html .= "                   <input type=\"text\" class=\"input-text\" name=\"buscador[codigo_producto]\" maxlength=\"32\" size=\"25\" value=".$request['codigo_producto']."></td>\n";
			$html .= "	 </tr>\n";
			$html .= "  <tr class=\"modulo_table_list_title\">\n";
			$html .= "			         <td width=\"40%\" class=\"modulo_table_list_title\">CODIGO ALTERNO:</td>\n";
			$html .= "		           	<td  colspan=\"5\" class=\"modulo_list_claro\" align=\"left\"><input type=\"text\"  size=\"25\"  class=\"input-text\" name=\"buscador[codigo_alterno]\" maxlength=\"32\" value=".$request['codigo_alterno']."></td>\n";
			$html .= "	</tr>\n";
			$html .= "  <tr class=\"modulo_table_list_title\">\n";
			$html .= "			          <td  width=\"40%\" class=\"modulo_table_list_title\">CODIGO DE BARRAS:</td>\n";
			$html .= "		              <td  colspan=\"5\" class=\"modulo_list_claro\" align=\"left\"><input type=\"text\" size=\"25\" class=\"input-text\" name=\"buscador[codigo_barras]\" maxlength=\"32\" value=".$request['codigo_barras']."></td>\n";
			$html .= "	</tr>\n";
			$html .= "		           	<td width=\"40%\" class=\"modulo_table_list_title\">DESCRIPCION:</td>\n";
			$html .= "		            <td  colspan=\"5\" class=\"modulo_list_claro\" align=\"left\"><input type=\"text\" size=\"25\"  class=\"input-text\" name=\"buscador[descripcion]\" maxlength=\"40\" value=".$request['descripcion']."></td>\n";
			$html .= "	</tr>\n";
			$html .= "</table> <br>\n";
			$html .= "	<table   width=\"30%\" align=\"center\" border=\"0\"   >";
			$html .= "  <tr>\n";
			$html .= "	             	<td  colspan=\"10\"  align='center'>\n";
			$html .= "			         <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\"  >\n";
			$html .= "		          	</td>\n";
			$html .= "					<td  colspan=\"10\" align='center' >\n";
			$html .= "					<input class=\"input-submit\" type=\"button\" name=\"limpiar\" onclick=\"LimpiarCampos(document.Forma12)\" value=\"Limpiar Campos\">\n";
			$html .= "	  				</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table><br>\n";
			$html .= "<table align=\"center\" width=\10%\">\n";
			$html .= "  <tr  class=\"modulo_table_list_title\" align=\"center\" >\n";
			$html .= "      <td  colspan=\"10\" style=\"background:#FF0000\" >  Prox.Fecha_vencimiento    </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
      $html .= "<br> ";
      $html .= "<table  width=\"95%\"   align=\"center\">\n";
      $html .= "  <tr>\n";
      $html .= "      <td colspan=\"15\"><div id=\"productos\"></div></td>\n";
      $html .= "  </tr>\n";
      $html .= "</table>\n";
      $html .= "<br> ";
      $html .= "      <script>\n";
      $html .= "        xajax_ProductosSeleccionados('".$far."','".$Centrid."','".$bod."');\n";
      $html .= "      </script>\n";

      
      
		  $html .="<div align=\"center\" class=\"label_error\" id='error'></div> ";
			if(!empty($datos))
			{
					$pghtml = AutoCarga::factory('ClaseHTML');
					$html .= "  <table width=\"98%\" class=\"modulo_table_list_title\" align=\"center\">";
					$html .= "	  <tr align=\" class=\"modulo_table_list_title\" >\n";
					$html .= "      <td width=\"25%\">MOLECULA</td>\n";
					$html .= "      <td width=\"13%\">CODIGO</td>\n";
					$html .= "      <td width=\"55%\">PRODUCTO</td>\n";
					$html .= "      <td width=\"15%\">EXIS</td>\n";
					$html .= "      <td width=\"10%\">CANTIDAD</td>\n";
					$html .= "      <td width=\"10%\">FECHA VEN</td>\n";
					$html .= "      <td  width=\"15%\" >LOTE</td>\n";
					$html .= "      <td  width=\"5%\" >OP</td>\n";
					$html .= "  </tr>\n";
					$est = "modulo_list_claro"; $back = "#DDDDDD";
					$num=count($cdev);
					$i=0;
					foreach($datos as $key => $dtl)
						{
					        $html .= "  <tr class=\"modulo_list_claro\">\n";
							$fdatos=explode("-", $dtl['fecha_vencimiento']);
							$fedatos= $fdatos[1]."-".$fdatos[2]."-".$fdatos[0];
							$tiempo = mktime(0, 0, 0, $fdatos[1],$fdatos[2], $fdatos[0], 1) - time();
							$dias = floor($tiempo/86400);
						    $checked = "";
							
							if(!empty($cdev[$dtl['codigo_producto']]))
							$checked = "checked";
						
					
							$html .= "      <td   align=\"center\">".$dtl['molecula']."</td>\n";
    						$html .= "      <td   align=\"center\">".$dtl['codigo_producto']."</td>\n";
							$html .= "      <td  align=\"left\">".$dtl['descripcion']." ".$dtl['unidad']." ".$dtl['contenido_unidad_venta']."- ".$dtl['laboratorio']."</td>\n";
							$html .= "      <td  align=\"left\">".$dtl['actual']."</td>\n";
								$cadena=$dtl['codigo_producto']." ".$dtl['fecha_vencimiento']." ".$dtl['lote'];
               
							if($dias<=$dias_vencimiento)
							{
								$html .=" <td> <input type=\"text\" name=\"cantidad".$cadena."\" id=\"cantidad".$cadena."\" SIZE=\"5\"  value=\"\" onkeypress=\"return acceptNum(event)\" maxlength=\"12\" >  ";       
								$html .= "      <td style=\"background:#FF0000\" align=\"left\">".$dtl['fecha_vencimiento']."</td>\n";
								$html .= "      <td  align=\"left\">".$dtl['lote']."</td>\n";
								$html .=" <td> <input type=\"checkbox\" name=\"Enviar\" id=\"Enviar".$cadena."\" value=\"".$dtl['codigo_producto']."\" ".$checked." onClick=\"xajax_ValidarDatosProducto(this.value,'".$dtl['codigo_producto']."','".$dtl['fecha_vencimiento']."','".$dtl['lote']."','".$far."','".$Centrid."','".$bod."','".$dtl['costo_ultima_compra']."','".$dtl['actual']."');\" > ";       
							
               
							
							} else 
							{
								if($dias>$dias_vencimiento)
								{
									//$html .=" <td> <input type=\"input-text\" SIZE=\"5\" name=\"cantidad2\" id=\"cantidad2\" value=\"\" disabled >  ";       
                  $html .=" <td> <input type=\"text\" name=\"cantidad".$cadena."\" id=\"cantidad".$cadena."\" SIZE=\"5\"  value=\"\" onkeypress=\"return acceptNum(event)\" maxlength=\"12\" >  ";       
                  $html .= "      <td align=\"left\">".$dtl['fecha_vencimiento']."</td>\n";
									$html .= "      <td  align=\"left\">".$dtl['lote']."</td>\n";
								  $html .=" <td> <input type=\"checkbox\" name=\"Enviar\" id=\"Enviar".$cadena."\" value=\"".$dtl['codigo_producto']."\" ".$checked." onClick=\"xajax_ValidarDatosProducto(this.value,'".$dtl['codigo_producto']."','".$dtl['fecha_vencimiento']."','".$dtl['lote']."','".$far."','".$Centrid."','".$bod."','".$dtl['costo_ultima_compra']."','".$dtl['actual']."');\" > ";       
									//$html .=" <td> <input type=\"checkbox\" name=\"Enviar\" id=\"Enviar\" value=\"".$i."\" disabled )\">  ";       
								}
							}
						
						$html .= "  </tr>\n";
						$i=$i+1;
						}
					$html .= "	</table><br>\n";
					$html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
					$html .= "  <table width=\"70%\"  border=\"0\"  align=\"center\">";
					$html .= "		<tr>\n";
					$html .= "	             	<td  colspan=\"10\"  align='center'>\n";
					$html .= "			         <input class=\"input-submit\" type=\"button\" name=\"Buscar\" value=\"O.K\"  onClick=\"xajax_OrganizarInfo('".$far."','".$Centrid."','".$bod."','".$tipo_doc_general_id."');\" >\n";
					$html .= "		          	</td>\n";
					$html .= "		<tr>\n";
				    $html .= "	</table><br>\n";
			}
			else
			{
			if($request)
				$html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
			}
			$html .= "<table align=\"center\" width=\"50%\">\n";
			$html .= "  <tr>\n";
			$html .= "    <td align=\"center\">\n";
			$html .= "      <a href=\"".$action['volver']." \" class=\"label_error\">\n";
			$html .= "        Volver\n";
			$html .= "      </a>\n";
			$html .= "    </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= "  </form>\n";
			$html .= $this->CrearVentana(800,"GENERAR DOCUMENTO DE EGRESO(DEVOLUCIÒN)");
			$html .= ThemeCerrarTabla();
			return $html;
		} 
	/*
		* Funcion que Contiene la Forma de visualizar un mensaje cuando se genero un documento temporal.
		* @param array $action vector que contiene los link de la aplicacion
		* @param String  $numeroGenerado variable que contiene la numeracion del documento generado.
        * @return string $html retorna la cadena con el codigo html de la pagina.
	*/
		function FormaMensajeGnerarDocumentoTmp($action,$numeroGenerado)
		{
				$html  = ThemeAbrirTabla("MENSAJE");
				$html .= "<table border=\"1\" width=\"50%\" align=\"center\" >\n";
				$html .= "	<tr>\n";
				$html .= "		<td>\n";
				$html .= "		  <table width=\"100%\" class=\"modulo_table_list\">\n";
				$html .= "		    <tr class=\"normal_10AN\">\n";
				$html .= "		      <td align=\"center\">SE GENERARON LOS ITEMS DEL DOCUMENTO  </td>\n";
				$html .= "		    </tr>\n";
				$html .= "		  </table>\n";
				$html .= "		</td>\n";
				$html .= "	</tr>\n";
				$html .= "</table>";
				$html .= "<br>";
				$html .= "<table align=\"center\" width=\"50%\">\n";
				$html .= "  <tr>\n";
				$html .= "    <td align=\"center\">\n";
				$html .= "      <a href=\"".$action['volver']."\"  class=\"label_error\">\n";
				$html .= "       VOLVER\n";
				$html .= "      </a>\n";
				$html .= "    </td>\n";
				$html .= "  </tr>\n";
				$html .= "</table>\n";
				$html .= ThemeCerrarTabla();
				return $html;
		}
		/** Funcion que Contiene la Forma de listar los productos asociados a un documento temporal 
		* @param array $action vector que contiene los link de la aplicacion
		  * @return string $html retorna la cadena con el codigo html de la pagina.
		*/
		
		function formaListarDocumentoTemp_d($action,$datos,$ConEstados,$tipo_doc_general_id,$doc_tmp_id)
		{
			$num=count($datos);
			$html  = ThemeAbrirTabla('DOCUMENTO DEVOLUCION TMP ');
			$html .="  <script>\n";
			$html .= "	  function ContinuarVerificaTmp(frms)\n";
			$html .= "	  {\n";
			$html .= "    if(frms.estados.selectedIndex==0)\n";
			$html .= "    {\n";
			$html .= "      document.getElementById('error').innerHTML = 'DEBE SELECCIONAR EL ESTADO A VERIFICAR DEL DOCUMENTO';\n";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    if(frms.estados.selectedIndex!=-1)\n";
			$html .= "    {\n";
			$html .= " 	xajax_TrasnpoVerifEstados('".$doc_tmp_id."',frms.estados.value,'".$tipo_doc_general_id."');";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    frm.submit();\n";
			$html .= "    }\n";
			$html .= "   function GenerarDocumentoRealE(frms)\n";  
			$html .= "    {\n";
			$html .= "    if('".$num."'>0)\n";
			$html .= "   { ";
			$html .= " 	xajax_TrasnpoDocumGenerarE('".$doc_tmp_id."');";
			$html .= "      return;\n";
			$html .= "    }\n";
			$html .= "    frm.submit();\n";
			$html .= "    }\n";				
			$html .="  </script>\n";
	       			$html .= "<form name=\"FormaVe\" id=\"FormaVe\" method=\"post\" >\n";
					if(!empty($datos))
			       {
					   $html .="<div align=\"center\" class=\"label_error\" id='error'></div> ";
					   $html .= "<br>";
						$html .= "  <table width=\"25%\" class=\"modulo_table_list_title\" align=\"center\">";
						$html .= "  <tr class=\"modulo_table_list_title\">\n";
						$html .= "  	<td  width='25%' align=\"center\"  >\n";
						$html .= "   ESTADOS";
						$html .= "	 </td>\n";
						$html .= " <td COLSPAN='2' align=\"center\" class=\"modulo_list_claro\">";
						$html .= "   <select COLSPAN='4' class=\"select\"  name=\"estados\" id=\"estados\" >";
						$html .= "  <option value=\"-1\">-- Seleccionar --</option>\n";
						$selected ="";
						foreach ($ConEstados as $indice=>$valor)
						{ 
							$html .= "  <option value=\"".$valor['abreviatura']."\" ".$selected.">".$valor['descripcion']."</option>";
						}
					$html .= " </td>";
					$html .= "     </tr>\n";
					$html .= "  </table>";
					$html .= "  <br>";
					$html .= "  <table width=\"90%\" class=\"modulo_table_list_title\" align=\"center\">";

					$html .= "	  <tr align=\" class=\"modulo_table_list_title\" >\n";
					$html .= "      <td width=\"15%\">MOLECULA</td>\n";
					$html .= "      <td width=\"10%\">CODIGO</td>\n";
					$html .= "      <td width=\"60%\">PRODUCTO</td>\n";
					$html .= "      <td width=\"5%\">CANT.</td>\n";
					$html .= "      <td width=\"15%\">FECHA VEN</td>\n";
					$html .= "      <td  width=\"20%\" >LOTE</td>\n";
					$html .= "  </tr>\n";
					$est = "modulo_list_claro"; $back = "#DDDDDD";
					foreach($datos as $key => $dtl)
					{
					        $html .= "  <tr class=\"modulo_list_claro\">\n";
							$html .= "      <td   align=\"center\">".$dtl['molecula']."</td>\n";
    						$html .= "      <td   align=\"center\">".$dtl['codigo_producto']."</td>\n";
							$html .= "      <td  align=\"left\">".$dtl['descripcion']." ".$dtl['unidad']."".$dtl['contenido_unidad_venta']."- ".$dtl['laboratorio']."</td>\n";
							$html .= "      <td align=\"left\">".round($dtl['cantidad'])."</td>\n";
							$html .= "      <td align=\"left\">".$dtl['fecha_vencimiento']."</td>\n";
							$html .= "      <td  align=\"left\">".$dtl['lote']."</td>\n";
							/*$html .= "      <td  align=\"center\">\n";
							$html .= "                        <a href=\"#\" onclick=\"xajax_EliminadocTmp_d('".$dtl['doc_tmp_id']."','".$dtl['codigo_producto']."','".$tipo_doc_general_id."');\">";
							$html .= "                          <img src=\"".GetThemePath()."/images/delete2.gif\" border=\"0\" width=\"15\" height=\"17\"></sub>\n";
							$html .= "                         </a>\n";
							$html .= "       </td>\n";	*/					
							$html .= "  </tr>\n";
						$i=$i+1;
					}
					$html .= "	</table><br>\n";
					
					$html .= "  <table width=\"70%\"  border=\"0\"  align=\"center\">";
					$html .= "		<tr>\n";
				
					if(empty($ConEstados))
					{
					$html .= "	             	<td  colspan=\"10\"  align='center'>\n";
			        $html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\"   value=\"CONTINUAR\"  disabled onclick=\"ContinuarVerificaTmp(document.FormaVe);\" >\n";
					$html .= "		          	</td>\n";
					
					$html .= "	             	<td  colspan=\"10\"  align='center'>\n";
			        $html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\"   value=\"GENERAR DOCUMENTO\"   onclick=\"GenerarDocumentoRealE(document.FormaVe);\" >\n";
					$html .= "		          	</td>\n";
					}
					else {
					$html .= "	             	<td  colspan=\"10\"  align='center'>\n";
			        $html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\"   value=\"CONTINUAR\" onclick=\"ContinuarVerificaTmp(document.FormaVe);\" >\n";
					$html .= "		          	</td>\n";
					
					$html .= "	             	<td  colspan=\"10\"  align='center'>\n";
			        $html .= "        <input class=\"input-submit\" type=\"button\" class=\"input-text\" name=\"btnCrear\"   value=\"GENERAR DOCUMENTO\"  disabled  onclick=\"GenerarDocumentoRealE(document.FormaVe);\" >\n";
					$html .= "		          	</td>\n";
					
					}
					$html .= "		<tr>\n";
				    $html .= "	</table><br>\n";
			}
			
			$html .= "<table align=\"center\" width=\"50%\">\n";
			$html .= "  <tr>\n";
			$html .= "    <td align=\"center\">\n";
			$html .= "      <a href=\"".$action['volver']." \" class=\"label_error\">\n";
			$html .= "        VOLVER\n";
			$html .= "      </a>\n";
			$html .= "    </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= "  </form>\n";
			$html .= ThemeCerrarTabla();
			return $html;
		}
		
		/*
		* Funcion que Contiene la Forma para visualizar un mensaje sobre la creacion del documento real.
		* @param array $action vector que contiene los link de la aplicacion
		* @param String  $numeroGenerado variable que contiene la numeracion del documento generado.
		* @return string $html retorna la cadena con el codigo html de la pagina.
	*/
		function FormaMensajeGenerarDocReal($action,$numeroGenerado,$prefijo,$farmacia)
		{
		$rpt  = new GetReports();	
      $html = ThemeAbrirTabla("MENSAJE ");
			$html .= "<table border=\"1\" width=\"65%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "		  <table width=\"100%\" class=\"modulo_table_list\">\n";
			$html .= "		    <tr class=\"normal_10AN\">\n";
			$html .= "		      <td align=\"center\">SE GENERO EL DOCUMENTO DE DEVOLUCION DE PRODUCTOS ( ".$prefijo." ) NUMERO:\n".$numeroGenerado."\n";
			$datos['prefijo']=$prefijo;
			$datos['numero']=$numeroGenerado;
			$datos['empresa_id']=$farmacia;
			$html .= $rpt->GetJavaReport('app','AdminFarmacia','DocumentoIngreso',$datos,
  																	array('rpt_name'=>'','rpt_dir'=>'cache','rpt_rewrite'=>TRUE));
				$fnc  = $rpt->GetJavaFunction();

				$html .= "			  <a title=\"IMPRIMIR\" class=\"label_error\" href=\"javascript:".$fnc."\">\n";
				$html .= "			    <image src=\"".GetThemePath()."/images/imprimir.png\" border=\"0\">\n";
				$html .= "			  </a>\n";
				$html .= "";
				$html .= "          </td>\n";
			$html .= "		    </tr>\n";
     
			$html .= "		  </table>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>";
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
	/* CONSULTAR QUE OTRAS FARMACIAS TIENEN UN DETERMINADO PRODUCTO*/
		
		/*
		* Funcion que Contiene la Forma para buscar productos en otras farmacias o bodegas.
		* @param array $action vector que contiene los link de la aplicacion.
		* @param array $datos vector que contiene la informacion del producto.
		* @param String $conteo variable que contiene el numero de registros.
		* @param String  $pagina variable que contiene el numero de paginas de acuerdo a los registros generados.
		* @return string $html retorna la cadena con el codigo html de la pagina.
	*/
			
		function FormaBuscarProductosFarmacia($action,$datos,$conteo,$pagina,$request)
		{
			$ctl   = AutoCarga::factory("ClaseUtil"); 
			$html .= $ctl->LimpiarCampos();
			$html  = ThemeAbrirTabla("BUSCAR PRODUCTO");
			$html .= $ctl->RollOverFilas();
			$html .= "<form name=\"Forma21\" id=\"Forma21\" method=\"post\"  action=\"".$action['buscador']."\">\n";
			$html .= "			<table     class=\"modulo_table_list\"  width=\"45%\" align=\"center\" border=\"0\"   >";
			$html .= "         <tr class=\"formulacion_table_list\" >\n";
			$html .= "		          	<td  width=\"40%\" >CODIGO:</td>\n";
			$html .= "	                <td class=\"modulo_list_claro\" colspan=\"5\" align=\"left\">\n";
			$html .= "                        <input type=\"text\" class=\"input-text\" name=\"buscador[codigo_producto]\" maxlength=\"32\" size=\"45\" value=".$request['codigo_producto']."></td>\n";
			$html .= "	 </tr>\n";
			$html .= "  <tr class=\"formulacion_table_list\" >\n";
			$html .= "			          <td width=\"40%\" >CODIGO ALTERNO:</td>\n";
			$html .= "		           	<td  colspan=\"5\" class=\"modulo_list_claro\" align=\"left\"><input type=\"text\"  size=\"45\"  class=\"input-text\" name=\"buscador[codigo_alterno]\" maxlength=\"32\" value=".$request['codigo_alterno']."></td>\n";
			$html .= "		</tr>\n";
			$html .= "  <tr class=\"formulacion_table_list\" >\n";
			$html .= "			          <td  width=\"40%\">CODIGO DE BARRAS:</td>\n";
			$html .= "		              <td  colspan=\"5\" class=\"modulo_list_claro\" align=\"left\"><input type=\"text\" size=\"45\" class=\"input-text\" name=\"buscador[codigo_barras]\" maxlength=\"32\" value=".$request['codigo_barras']."></td>\n";
			$html .= "		</tr>\n";
      	$html .= "  <tr class=\"formulacion_table_list\" >\n";
			$html .= "		           	<td width=\"40%\" >DESCRIPCION:</td>\n";
			$html .= "		           <td  colspan=\"5\" class=\"modulo_list_claro\" align=\"left\"><input type=\"text\" size=\"45\"  class=\"input-text\" name=\"buscador[descripcion]\" maxlength=\"40\" value=".$request['descripcion']."></td>\n";
			$html .= "		</tr>\n";
			$html .= "</table> <br>\n";
			$html .= "			<table   width=\"30%\" align=\"center\" border=\"0\"   >";
			$html .= "  <tr>\n";
			$html .= "	             	<td  colspan=\"10\"  align='center'>\n";
			$html .= "			         <input class=\"input-submit\" type=\"submit\" name=\"Buscar\" value=\"Buscar\"  >\n";
			$html .= "		          	</td>\n";
			$html .= "			<td  colspan=\"10\" align='center' >\n";
			$html .= "			<input class=\"input-submit\" type=\"button\" name=\"limpiar\" onclick=\"LimpiarCampos(document.Forma21)\" value=\"Limpiar Campos\">\n";
			$html .= "	  	</td>\n";
			$html .= "		</tr>\n";
			$html .= "</table><br>\n";
			if(!empty($datos))
			{
					$pghtml = AutoCarga::factory('ClaseHTML');
					$html .= "  <table width=\"100%\"  class=\"modulo_table_list\" border=\"0\"  align=\"center\">";
					$html .= "	  <tr align=\"CENTER\"    class=\"formulacion_table_list\"  >\n";
					$html .= "      <td width=\"35%\">FARMACIA</td>\n";
          $html .= "      <td width=\"20%\">BODEGA</td>\n";
        	$html .= "      <td width=\"15%\">CODIGO</td>\n";
					$html .= "      <td width=\"40%\">PRODUCTO</td>\n";
					$html .= "      <td  width=\"30%\" >EXISTENCIA</td>\n";
					$html .= "  </tr>\n";
					
					$est = "modulo_list_oscuro"; $back = "#DDDDDD";
					foreach($datos as $key => $dtl)
						{
			//				$html .= "	 <tr  align=\"CENTER\"    class=\"modulo_list_claro\"  >\n";  					
							$html .= "  <tr   class=\"LABEL\"  onmouseout=mOut(this,\"".$back."\"); onmouseover=\"mOvr(this,'#FFFFFF');\">\n";
			
							$html .= "      <td   align=\"center\"><B>".$dtl['razon_social']."- ".$dtl['centro']."</td>\n";
							$html .= "      <td   align=\"center\">".$dtl['bodega']."</td>\n";
              $html .= "      <td align=\"left\"><B>".$dtl['codigo_producto']."</td>\n";
							$html .= "      <td  align=\"left\"><B>".$dtl['producto']." ".$dtl['contenido_unidad_venta']." ".$dtl['unidad']." -".$dtl['laboratorio']."</td>\n";
							$html .= "      <td  align=\"left\"><B>".round($dtl['existencia'])."</B></td>\n";
							$html .= "  </tr></B>\n";
						}
					$html .= "	</table>\n";
					$html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action['paginador']);
			}
			else
			{
			if($request)
				$html .= "<br><center class=\"label_error\">NO SE ENCONTRARON REGISTROS PARA LA BUSQUEDA REALIZADA</center><br>\n";
			}
			$html .= "<table align=\"center\" width=\"50%\">\n";
			$html .= "  <tr>\n";
			$html .= "    <td align=\"center\">\n";
			$html .= "      <a href=\"".$action['volver']." \" class=\"label_error\">\n";
			$html .= "        Volver\n";
			$html .= "      </a>\n";
			$html .= "    </td>\n";
			$html .= "  </tr>\n";
			$html .= "</table>\n";
			$html .= "  </form>\n";
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

    /**
      * Funcion donde se crea la Forma del Mensaje  sobre el documento de Ingreso
      * @param array $action vector que contiene los link de la aplicacion.
      * @param string $msg1 Cadena con el texto del mensaje a mostrar  en pantalla.
      * @return string $html retorna la cadena con el codigo html de la pagina.
    */ 
		function FormaMensajeDocumento($action, $msg1=null)
		{
			$html  = ThemeAbrirTabla("MENSAJE");
			$html .= " <form name=\"form8\" method=\"post\" enctype=\"multipart/form-data\"  >";
			$html .= "<table border=\"0\" width=\"50%\" class=\"modulo_table_list\" align=\"center\" >\n";
	  	$html .= "		    <tr class=\"normal_10AN\">\n";
			$html .= "		      <td align=\"center\">\n".$msg1."</td>\n";
			$html .= "		    </tr>\n";
			$html .= "		  </table>\n";
			$html .= " <table width=\"50\" border=\"0\" align=\"center\" class=\"modulo_list_title\" >";
			$html .= "		<tr>\n";
      $html .= "		<td>\n";
    	$html .= "     <a href=\"".$action['volver']."\"  class=\"label_error\">\n";
    	$html .= "       VOLVER\n";
			$html .= "      </a>\n";
      $html .= "	  	</td>\n";
			$html .= "		</tr>\n";  
			$html .= "</table>\n";
			$html .= "</form>";
			$html .= ThemeCerrarTabla();
			return $html;
		}        
	
	}
?>