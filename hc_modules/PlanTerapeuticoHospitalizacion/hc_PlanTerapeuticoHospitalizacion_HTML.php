<?php
	/**************************************************************************************
	* $Id: hc_PlanTerapeuticoHospitalizacion_HTML.php,v 1.26 2009/06/26 13:55:26 hugo Exp $
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* $Revision: 1.26 $ 	
	* @author Hugo F. Manrique Arango
	*
	* Codigo tomado del submodulo de PlanTerapeutico.
	***************************************************************************************/
	IncludeClass("ClaseHTML");
	class PlanTerapeuticoHospitalizacion_HTML extends PlanTerapeuticoHospitalizacion
	{
		function PlanTerapeuticoHospitalizacion_HTML()
		{
			$this->PlanTerapeuticoHospitalizacion();//constructor del padre
			$this->cantMostrar=2;
			return true;
		}//End function

    var $cantMostrar = 0;
		/**
		* Cambia el estilo del label de los campos pasados por parametro, para indicar el error cambiando a
		* color rojo el label del campo "obligatorio" sin llenar.
		*
		* @param string $campo
		* @param string $campo2
		* @param string $colum Define el tamaño del colspan de la tabla donde se llama
    *                      retorna la etiqueta donde está el error
    *
		* @return string
		*/
		function SetStyle($campo,$campo2,$colum)
		{
			if ($this->frmError[$campo] || $this->frmError[$campo2] || $campo=="MensajeError")
			{
			  if ($campo=="MensajeError") {
					return ("<tr><td colspan='".$colum."' class='label_error' align='center'>".$this->frmError["MensajeError"]."</td></tr>");
				}
				return ("label_error");
			}
			return ("label");
		}
    /**
		* Esta función retorna los datos de concernientes a la version 
    * del submodulo
		* 
    * @return array
		*/
		function GetVersion()
		{
			$informacion=array(
			'version'=>'1',
			'subversion'=>'0',
			'revision'=>'0',
			'fecha'=>'01/27/2005',
			'autor'=>'HUGO F. MANRIQUE',
			'descripcion_cambio' => '',
			'requiere_sql' => false,
			'requerimientos_adicionales' => '',
			'version_kernel' => '1.0'
			);
      SessionSetVar("GetVersion",$informacion);
			return $informacion;
		}
		/**
		* Metodo donde dependiendo del valor de $action se ejecutan diferentes acciones (adicionar, insertar, entre otras)
		* retornando otra accion ó un boolean
		* Por defecto la accion esta vacia y muestra solo la consulta del plan terapeutico de mezclas y medicamentos
		* Estas acciones son cada uno de los enlaces del submodulo.
		*
		* @access private
		* @param string $action
    * 
		* @return boolean
		*/
		function FrmForma($action)
		{
			$pfj=$this->frmPrefijo;
			switch ($action)
			{
        //INICIO CASOS DE CLAUDIA DE SOLICTUD DE MEDICAMENTOS
				case "Busqueda_Avanzada_Medicamentos":
				 	$vectorA = $this->Busqueda_Avanzada_Medicamentos();
					$this->frmForma_Seleccion_Medicamentos($vectorA);
				break;
				case "frmForma_Add":
					$this->frmForma_Add();
				break;
				default :
          $this->frmForma_Add();
        break;
			}
			return true;
		}
		/***********************************************************************
		*
		************************************************************************/
		function frmForma_Add()
		{
			$pfj = $this->frmPrefijo;
			SessionDelVar("CodigosSeleccionados");
			SessionDelVar("MedicamentosFormulados");
			SessionDelVar("SolucionesFormuladas");
			SessionDelVar("EmpresaHc");
			SessionSetVar("EmpresaHc",$this->empresa_id);
			
			$clases1 = array(	"formulacion_table_list","modulo_list_claro","formulacion_table_list_oscuro",
												"formulacion_table_list_suspendido","formulacion_table_list_claro",
												"hc_table_submodulo_list_title","modulo_table_list_title","label","label2","SUSPENDER");
			$clases2 = array(	"formulacion_table_list_suspendido","modulo_list_claro",
												"formulacion_table_list_claro","formulacion_table_list","formulacion_table_list_oscuro",
												"hc_table_submodulo_list_title","formulacion_table_list_suspendido","label2","label","ACTIVAR");
				
			$imagenes1 = array ("historia_actual_osc.gif","pactivo.png");
			$imagenes2 = array ("historia_actual_osc.gif","pinactivo.png");
			
			
			$formulados = $this->Consulta_Solicitud_Medicamentos();
			$solucionesF = $this->FormulacionSoluciones();
			
			if(empty($this->titulo)) $this->titulo = "SOLICITUD DE MEDICAMENTOS";
			
			$this->salida .= "<script lenguage=\"javascript\" src=\"javascripts/Formulacion.js\"></script>\n";
			$this->salida .= ThemeAbrirTablaSubModulo($this->titulo);
			$this->salida .= "<script>\n";
			$this->salida .= "	var ImgSuspender = new Image();\n";
      $this->salida .= "	var ImgActivar = new Image();\n";
			$this->salida .= "	var ImgHistorialOs = new Image();\n";
			$this->salida .= "	var ImgHistorialCl = new Image();\n";
			$this->salida .= "	ImgSuspender.src = \"".GetThemePath()."/images/pactivo.png\";\n";
      $this->salida .= "	ImgActivar.src = \"".GetThemePath()."/images/pinactivo.png\";\n";
			$this->salida .= "	ImgHistorialCl.src = \"".GetThemePath()."/images/HistoriaClinica1/historia_actual_cla.gif\";\n";
			$this->salida .= "	ImgHistorialOs.src = \"".GetThemePath()."/images/HistoriaClinica1/historia_actual_osc.gif\";\n";
      $this->salida .= "  function ProtocolosFormulacion(codigo_medicamento)\n";
      $this->salida .= "  {\n";
      $this->salida .= "    var url = \"classes/FrecuenciaMedicamentos/ProtocolosFormulacion.class.php?codigo_medicamento=\"+codigo_medicamento;\n";
      $this->salida .= "    var str =\"width=900 ,height=300,resizable=no,location=yes,toolbar=1,status=no,scrollbars=yes\";\n";
      $this->salida .= "    window.open(url,'PROTOCOLOS DE FORMULACION',str).focus();\n";
      $this->salida .= "  }\n";      
      $this->salida .= "  function ProtocolosFormulacionII(codigo_medicamento)\n";
      $this->salida .= "  {\n";
      $this->salida .= "    var url = \"../FrecuenciaMedicamentos/ProtocolosFormulacion.class.php?codigo_medicamento=\"+codigo_medicamento;\n";
      $this->salida .= "    var str =\"width=900 ,height=300,resizable=no,location=yes,toolbar=1,status=no,scrollbars=yes\";\n";
      $this->salida .= "    window.open(url,'PROTOCOLOS DE FORMULACION',str).focus();\n";
      $this->salida .= "  }\n";
      $this->salida .= "</script>\n";
			$medicamentos = $this->Medicamentos_Frecuentes_Diagnostico();
			$action1 = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,
								 array('accion'.$pfj=>'Busqueda_Avanzada_Medicamentos','Of'.$pfj=>$_REQUEST['Of'.$pfj],'paso1'=>$_REQUEST['paso1'.$pfj],
								 'criterio1'.$pfj=>$_REQUEST['criterio1'.$pfj],'producto'.$pfj=>$_REQUEST['producto'.$pfj],'principio_activo'.$pfj=>$_REQUEST['principio_activo'.$pfj]));
				
			$this->CrearBuscador($action1,$pfj,"0","creartabla",$medicamentos);
			if(SessionGetVar("tipoProfesionalhc") == '1')
			{
				$this->salida .= "<div name=\"ErrorSolicitud\" id=\"ErrorSolicitud\" style=\"text-align:center\"></div>\n";
				$this->salida .= "<form name=\"formulacion\" action=\"".$action."\" method=\"post\">\n";
				$this->salida .= "	<div name=\"Solicitud\" id=\"Solicitud\"></div>\n";
				$this->salida .= "</form>\n";
			}

			$planmedicam = array();
			$finalizados = array();
			$suspendidos = array();
			$noconfirmados = array();
			
			$Splanmedicam = array();
			$Sfinalizados = array();
			$Ssuspendidos = array();
			
			if(sizeof($formulados) > 0)
			{
				$finalizados = $formulados[0];
				$planmedicam = $formulados[1];
				$suspendidos = $formulados[2];
				$noconfirmados = $formulados[8];
			}
			
			if(sizeof($solucionesF) > 0)
			{
				$Sfinalizados = $solucionesF[0];
				$Splanmedicam = $solucionesF[1];
				$Ssuspendidos = $solucionesF[2];
			}
			
			$this->salida .= "	<div id=\"MedicamentosNuevos\">\n";
			if(sizeof($planmedicam) || sizeof($Splanmedicam)) 
				$this->salida .= $this->CrearTablaFormulados($planmedicam,"PLAN DE MEDICAMENTOS",$clases1,0,$imagenes1,1,$Splanmedicam);
			
			$this->salida .= "	</div>\n";
			$this->salida .= "	<div id=\"SolucionesNuevas\">\n";
			$this->salida .= "	</div>\n";
			
			$tam = sizeof($planmedicam)+sizeof($Splanmedicam);
			
			if(sizeof($suspendidos) || sizeof($Ssuspendidos))
				$this->salida .= $this->CrearTablaFormulados($suspendidos,"MEDICAMENTOS SUSPENDIDOS",$clases2,$tam,$imagenes2,2,$Ssuspendidos);      

			$tam += sizeof($suspendidos)+sizeof($Ssuspendidos);
			if(sizeof($noconfirmados))
				$this->salida .= $this->CrearTablaFormulados($noconfirmados,"MEDICAMENTOS FINALIZADOS SIN CONFIRMAR",$clases1,$tam,$imagenes1,4,array());      

				
			if($_REQUEST['medicaCod'])
			{
				$codigo1 = str_replace("\'","",$_REQUEST['medicaCod']);
				if($codigo1 == $_REQUEST['medicaCod'])
          $codigo1 = str_replace("'","",$_REQUEST['medicaCod']);
				
        $this->salida .= "	<script>\n";
				$this->salida .= "		creartabla('".$codigo1."','0','".GetThemePath()."');\n";
				$this->salida .= "	</script>\n";				
			}
			
			if(sizeof($formulados) == 0 && sizeof($solucionesF) == 0 )
			{
				if ($_SESSION['PROFESIONAL'.$pfj]!=1)
				{
          $this->salida .= "<table  align=\"center\" border=\"0\"  width=\"90%\">";
          $this->salida .= "	<tr>";
          $this->salida .= "  	<td align=\"center\" width=\"7%\" class=\"label_error\">EL PACIENTE NO TIENE MEDICAMENTOS FORMULADOS</td>";
          $this->salida .= "	</tr>";
          $this->salida .= "</table><br>";
				}
      }
			
			$display = "display:none ";
			if(sizeof($finalizados) > 0 || sizeof($Sfinalizados) > 0) $display = "display:block ";
			
			$datose = "ingreso=".$this->ingreso;
			$actionu1 = "./classes/FrecuenciaMedicamentos/FormulacionesFinalizadas.class.php?".$datose."";
			
			$this->salida .= "<script>\n";
			$this->salida .= "	function VerMedicamentosFinalizados()\n";
			$this->salida .= "	{\n";
			$this->salida .= "		var url=\"".$actionu1."\"\n";
			$this->salida .= "		window.open(url,'MEDICAMENTOS FINALIZADOS','width=750,height=500,X=200,Y=0,resizable=no,status=no,scrollbars=yes,location=no').focus();\n";
			$this->salida .= "	}\n";
			$this->salida .= "</script>\n";			
			$this->salida .= "<div id=\"MedicamentosFinalizados\" class=\"label\" style=\"$display\">\n";
			$this->salida .= "	<center>\n";
			$this->salida .= "		<a href=\"javascript:VerMedicamentosFinalizados()\"  title=\"Grupo de Medicamentos\">\n";
			$this->salida .= "			<img src=\"".GetThemePath()."/images/desmonitorizado.png\" border=\"0\" >MEDICAMENTOS FINALIZADOS\n";
			$this->salida .= "		</a>\n";
			$this->salida .= "	</center>\n";
			$this->salida .= "</div>\n";
			$this->salida .= ThemeCerrarTablaSubModulo();
			
			$this->salida .= "<div id=\"boton\" style=\"display:none\">\n";
			if($this->paso == "cerrar")
			{
				$this->salida .= "<table align='center'>\n";
				$this->salida .= "	<tr>\n";
				$this->salida .= "		<td>\n";
				$url=ModuloHCGetURL($this->datosEvolucion['evolucion_id'],'cerrar',0,$this->hc_modulo,$this->hc_modulo,array('DESMARCAR'=>1));
				$this->salida .= "			<form method='post' action='$url'>\n";
				$this->salida .= "				<input type='submit' value='Continuar Cierre de la Atención' name='cerrar' class='input-submit'>\n";
				$this->salida .= "			</form>\n";
				$this->salida .= "		</td>\n";
				$this->salida .= "	</tr>\n";
				$this->salida .= "</table>\n";
			}
			$this->salida .= "</div>\n";												 
			return true;
		}
		/******************************************************************************
		* Funcion donde se construye el buscador de medicamentos
		* 
		* @params string $action Url a donde ira el buscador
		* @params String $pfj nombre adiconal a la forma de medicamentos 
		* @params String $opcion Indica si el menu de mezclas aparecera o no
		* @params String $nombref Nombre de la funcion java que se invocara
		* @params Sarray $medicamentos 
		*******************************************************************************/
		function CrearBuscador($action,$pfj,$opcion,$nombref,$medicamentos)
		{
			$capas = "var secc1 = new Array(";//Arreglo javascript
			$capas1 = "var mezc = new Array(";//Arreglo javascript
			$datose = "datos[hc_modulo]=".$this->hc_modulo."&datos[bodega]=".$this->bodega."&ingreso=".$this->ingreso."&tema=".GetThemePath();
			if (SessionGetVar("tipoProfesionalhc") == '1')
			{
				SessionSetVar("IngresoHc",$this->ingreso);
				SessionSetVar("EvolucionHc",$this->datosEvolucion['evolucion_id']);
				SessionSetVar("RutaImagenes",GetThemePath());

				$this->salida .= "	<table align=\"center\" border=\"0\" width=\"98%\" class=\"modulo_table_list\">\n";
				$this->salida .= "		<tr class=\"modulo_table_title\">\n";
				$this->salida .= "  		<td align=\"center\" colspan=\"7\">ADICION DE MEDICAMENTOS - BUSQUEDA AVANZADA </td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "		<tr class=\"hc_table_submodulo_list_title\">\n";
				if(sizeof($medicamentos) > 0)
				{
					$this->salida .= "			<td width=\"%\" align = left >\n";
		      $this->salida .= "				<a href=\"javascript:OcultarSpan('Mezclas');MostrarSpan('Frecuentes');\"  title=\"Grupo de Medicamentos\">\n";
		      $this->salida .= "					<img name =\"ImgHistoriaActual\" src=\"".GetThemePath()."/images/pparamed.png\" border=\"0\" >MEDICAMENTOS\n";
		      $this->salida .= "				</a>\n";
					$this->salida .= "			</td>\n";
				}
				if($opcion == "0")
				{
					$this->salida .= "			<td width=\"%\" align = left >\n";
		      $this->salida .= "				<a href=\"javascript:OcultarSpan('Frecuentes');MostrarSpan('Mezclas')\"  title=\"Soluciones\">\n";
		      $this->salida .= "					<img name =\"ImgHistoriaActual\" src=\"".GetThemePath()."/images/producto.png\" border=\"0\" >SOLUCIONES\n";
		      $this->salida .= "				</a>\n";
					$this->salida .= "			</td>\n";
				}
				$this->salida .= "		<form name=\"formades$pfj\" action=\"".$action."\" method=\"post\">\n";
				$this->salida .= "			<td width=\"10%\">PRODUCTO:</td>\n";
				$this->salida .= "			<td width=\"20%\" align='center'>\n";
				$this->salida .= "				<input type='text' class='input-text'  size = 22 name = 'producto$pfj'  value =\"".$_REQUEST['producto'.$pfj]."\">\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "			<td width=\"20%\">PRINCIPIO ACTIVO:</td>";
				$this->salida .= "			<td width=\"20%\" align='center' >\n";
				$this->salida .= "				<input type='text' class='input-text' size = 22 name = 'principio_activo$pfj' value =\"".$_REQUEST['principio_activo'.$pfj]."\" >\n";
				$this->salida .= "			</td>\n" ;
				$this->salida .= "			<td width=\"%\" align=\"center\">\n";
				$this->salida .= "				<input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"Buscar\">\n";
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "		<tr class=\"modulo_table_title\">\n";
				
				$flagPrimerCapa = true;
				$datos = SessionGetVar("MedicamentosSeleccionados");
				if(sizeof($medicamentos) > 0)
				{
					$this->salida .= "			<td style=\"text-indent:0pt\">\n";
					$this->salida .= "				<div name=\"Frecuentes\" id=\"Frecuentes\" class=\"MenuMedicamentos\" style=\"display:none;position:absolute\">\n";
					foreach($medicamentos as $key => $subnivel)
					{
						$this->salida .= "					<p class=\"GrupoMedicamentos\" onclick=\"OcultarCapas('$key');\">\n";
						$this->salida .= "    				<a href=\"#\">$key</a>\n";
						$this->salida .= "						<div name=\"$key\" id=\"$key\" style=\"display:none;width:280px;\">\n";
						$this->salida .= "							<ul class=\"Lista1\">\n";
						
						foreach($subnivel as $key2 => $subnivel1)
						{							
							$datos[$subnivel1['codigo_medicamento']] = $subnivel1;
							
							$this->salida .= "							<li class=\"Medicamentos\">\n";
							$this->salida .= "								<a class=\"SubMenuHC\" href=\"javascript:OcultarCapas(''); OcultarSpan('Frecuentes');$nombref('".$subnivel1['codigo_medicamento']."','0','".GetThemePath()."')\"\">".$subnivel1['producto']."</a>\n";
							$this->salida .= "							</li>\n";
						}
						SessionSetVar("MedicamentosSeleccionados",$datos);
						
						$this->salida .= "							</ul>\n";
						$this->salida .= "						</div>\n";
						$this->salida .= "    			</p>\n";
						
						$flagPrimerCapa ?  $capas .= "\"$key\"" : $capas .= ",\"$key\"";
						$flagPrimerCapa = false;
					}
					$this->salida .= "				</div>\n";
					$this->salida .= "			</td>\n";
				}
					
				$this->salida .= "			<td colspan=\"6\" style=\"text-indent:0pt\">\n";
				
				if($opcion == "0")
				{
					$mezclas = $this->ObtenerMezclas();
					$this->salida .= "				<div name=\"Mezclas\" id=\"Mezclas\" class=\"MenuMedicamentos\" style=\"display:none;position:absolute\">\n";
					
					$k = 0;
					foreach($mezclas as $key => $subnivel)
					{
						$this->salida .= "					<p class=\"GrupoMedicamentos\" onclick=\"OcultarCapasNuevas('$key');\" >\n";
						$this->salida .= "    				<a href=\"#\" title=\"$title\">\n";
						$this->salida .= "							<img src=\"".GetThemePath()."/images/infor.png\" height=\"10\" border=\"0\">\n";
						$this->salida .= "								$key</a>\n";
						$this->salida .= "						<div name=\"$key\" id=\"$key\" style=\"display:none;width:280px;\">\n";
						$this->salida .= "							<ul class=\"Lista1\">\n";

						foreach($subnivel as $key2 => $subnivel1)
						{
							$this->salida .= "							<li class=\"Medicamentos\" onMouseOut=\"xHide('$key2');PosicionarCapa('$key2',-50,+13)\" onMouseOver=\"PosicionarCapa('$key2',50,-13);xShow('$key2');\">\n";
							$this->salida .= "								<a class=\"SubMenuM\" href=\"javascript:CrearMezcla('&mezcla=".$subnivel1[$k]['mezcla_id']."');OcultarSpan('Mezclas')\">$key2</a>\n";
							$this->salida .= "									<div class=\"GrupoMezclas\" name=\"$key2\" id=\"$key2\">\n";
							$this->salida .= "										COMPONENTES:\n";
							$this->salida .= "										<ul class=\"Lista1\">\n";
							foreach($subnivel1 as $key3 => $subnivel2)
							{
								$this->salida .= "											<li class=\"Mezclas\">".ucwords($subnivel2['producto'])."</li>\n";
								$k++;
							}				
							$this->salida .= "										</ul>\n";
							$this->salida .= "									</div>\n";
							$this->salida .= "							</li>\n";
						}
						$this->salida .= "							</ul>\n";
						$this->salida .= "						</div>\n";
						$this->salida .= "    			</p>\n";
					}
				
					$this->salida .= "					<p class=\"GrupoMedicamentos\">\n";
					$this->salida .= "    				<a href=\"javascript:CrearMezcla();OcultarSpan('Mezclas')\">\n";
					$this->salida .= "							<img name =\"ImgHistoriaActual\" src=\"".GetThemePath()."/images/pmodificar.png\" border=\"0\">\n";
					$this->salida .= "								NUEVA RECETA</a>\n";
					$this->salida .= "    			</p>\n";
					$this->salida .= "				</div>\n";
					
					$action1 = "./classes/BuscadorMedicamentos/BuscadorMedicamentosHtml.class.php?".$datose."";
					
					$this->salida .= "	<script>\n";
					$this->salida .= "		function CrearMezcla(datos)\n";
					$this->salida .= "		{\n";
					$this->salida .= "			var url=\"".$action1."\"+datos;\n";
					$this->salida .= "			window.open(url,'','width=950,height=600,X=200,Y=0,resizable=no,status=no,scrollbars=yes,location=no');\n";
					$this->salida .= "		}\n";
					$this->salida .= "	</script>\n";
					
					$flagPrimerCapa = true;
					$grupos = $this->ObtenerGrupos();
					for($i=0; $i<sizeof($grupos); $i++)
					{
						$flagPrimerCapa ?  $capas1 .= "\"".$grupos[$i]['descripcion']."\"" : $capas1 .= ",\"".$grupos[$i]['descripcion']."\"";
						$flagPrimerCapa = false;
					}
				}
				
				$this->salida .= "			</td>\n";
				$this->salida .= "		</tr>\n";
				$this->salida .= "	</table>\n";
			}
			$this->salida .= "	<script language=\"javascript\">\n";
			$this->salida .= "		var alternar = new Array();\n";
			$this->salida .= "		var alternarS = new Array();\n";
			$this->salida .= "		".$capas.");\n";
			$this->salida .= "		".$capas1.");\n";
			$this->salida .= "		function OcultarCapas(Seccion)\n";
			$this->salida .= "		{ \n";
			$this->salida .= "			for(i=0; i<secc1.length; i++)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				if(secc1[i] != Seccion)\n";
			$this->salida .= "				{\n";
			$this->salida .= "					e = xGetElementById(secc1[i]);\n";
			$this->salida .= "					e.style.display = \"none\";\n";
			$this->salida .= "				}\n";
			$this->salida .= "				else\n";
			$this->salida .= "				{\n";
			$this->salida .= "					if(Seccion != \"\") MostrarSpan(Seccion);\n";
			$this->salida .= "				}\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function OcultarCapasNuevas(Seccion)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			for(i=0; i<mezc.length; i++)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				if(mezc[i] != Seccion)\n";
			$this->salida .= "				{\n";
			$this->salida .= "					try\n";
			$this->salida .= "					{\n";
			$this->salida .= "						e = xGetElementById(mezc[i]);\n";
			$this->salida .= "						e.style.display = \"none\";\n";
			$this->salida .= "					}\n";
			$this->salida .= "					catch(error){}\n";
			$this->salida .= "				}\n";
			$this->salida .= "				else\n";
			$this->salida .= "				{\n";
			$this->salida .= "					if(Seccion != \"\") MostrarSpan(Seccion);\n";
			$this->salida .= "				}\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function OcultarSpan(Seccion)\n";
			$this->salida .= "		{ \n";
			$this->salida .= "			try\n";
			$this->salida .= "			{\n";
			$this->salida .= "				e = xGetElementById(Seccion);\n";
			$this->salida .= "				e.style.display = \"none\";\n";
			$this->salida .= "			}\n";
			$this->salida .= "			catch(error){}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function MostrarSpan(Seccion)\n";
			$this->salida .= "		{ \n";
			$this->salida .= "			e = xGetElementById(Seccion);\n";
			$this->salida .= "			if(e.style.display == \"none\")\n";
			$this->salida .= "			{\n";
			$this->salida .= "				e.style.display = \"\";\n";
			$this->salida .= "			}\n";
			$this->salida .= "			else \n";
			$this->salida .= "			{\n";
			$this->salida .= "				e.style.display = \"none\";\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function acceptNum(evt)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var nav4 = window.Event ? true : false;\n";
			$this->salida .= "			var key = nav4 ? evt.which : evt.keyCode;\n";
			$this->salida .= "			return (key <= 13 || (key >= 48 && key <= 57) || key == 46);\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function PosicionarCapa (capa,x,y)\n";
      $this->salida .= "		{\n";
      $this->salida .= "    	posx= xOffsetLeft('Mezclas') ;\n";
      $this->salida .= "    	posy= xOffsetTop (capa) ;\n";
			
			if(eregi("MSIE",$_SERVER["HTTP_USER_AGENT"])) $this->salida .= "		x = -50;\n";
			
      $this->salida .= "    	xMoveTo(capa,posx+x,posy+y);\n";
      $this->salida .= "		}\n";
			
			$action2 = "./classes/FrecuenciaMedicamentos/FrecuenciaMedicamentos.class.php?".$datose."";
			$this->salida .= "		function Adicionarfrecuencia(codigo)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var url=\"".$action2."&codigo=\"+codigo+\"\";\n";
			$this->salida .= "			window.open(url,'Formulación','width=400,height=150,x=200,Y=200,resizable=no,status=yes,scrollbars=yes,location=no').focus();\n";
			$this->salida .= "		}\n";
			
			$action3 = "./classes/FrecuenciaMedicamentos/EditarMedicamento.class.php?".$datose."&tema=".GetThemePath();
			$this->salida .= "		function EditarFormulacion(codigo,i,inicio)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			if(alternar[i] == undefined) alternar[i] = inicio;\n";
			$this->salida .= "			if(alternar[i] == 1)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				var url=\"".$action3."&codigo=\"+codigo+\"&capa=CapaFormula\"+i+\"&indice=\"+i+\"&inicio=\"+inicio+\"\";\n";
			$this->salida .= "				window.open(url,'EDITAR FORMULACION MEDICAMENTO','width=700,height=400,X=200,Y=0,resizable=no,status=no,scrollbars=yes,location=no').focus();\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";

			$action4 = "./classes/FrecuenciaMedicamentos/Justificacion.class.php?";
			$this->salida .= "		function Justificar(codigo,justifica,ruta)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var url=\"".$action4."&codigo=\"+codigo+\"&justifica=\"+justifica+\"&path=\"+ruta+\"\";\n";
			$this->salida .= "			//window.open(url,'Formulación','width=650,height=600,x=200,Y=200,resizable=no,status=yes,scrollbars=yes,location=no');\n";
			$this->salida .= "			window.open(url,'Formulación','scrollbars=yes,fullscreen=yes');\n";
			$this->salida .= "		}\n";
			$this->salida .= "    var reque = new Array();\n";
			$this->salida .= "    var final = new Array();\n";
			$this->salida .= "		function Finalizar(codigo,i,inicio,producto)\n";
			$this->salida .= "		{\n";
			if ($_SESSION['PROFESIONAL'.$pfj]== 1)
			{
        $this->salida .= "      reque[0] = codigo;\n";
        $this->salida .= "      reque[1] = producto;\n";
        $this->salida .= "      reque[2] = \"'\"+i+\"'\";\n";
        $this->salida .= "      reque[3] = \"'\"+inicio+\"'\";\n";        
        $this->salida .= "      final[0] = i;\n";
        $this->salida .= "      final[1] = inicio;\n";
        $this->salida .= "      jsrsExecute('classes/modules/procesos.php',ConfirmarFinalizacion,'EvaluarFinalizacion',reque);\n";
			}
			else
				$this->salida .= "				alert(\"SIN PRIVILEGIOS PARA FINALIZAR MEDICAMENTOS Y/O SOLUCIONES FORMULADAS\");\n";
			
			$this->salida .= "		}\n";
      
      $this->salida .= "    function ConfirmarFinalizacion(cadena)\n";
      $this->salida .= "		{	\n";
      $this->salida .= "		  resultado  = jsrsArrayFromString( cadena, \"*\" );\n";
      $this->salida .= "		  if(resultado[0] == 'S')\n";
      $this->salida .= "		  {	\n";
      $this->salida .= "			  var answer = confirm(\"ESTA SEGURO QUE DESEA FINALIZAR EL MEDICAMENTO: \\n \"+reque[1]+\"?\");\n";
      $this->salida .= "			  if (answer)\n";
      $this->salida .= "			  {\n";
      $this->salida .= "				  FinalizarMedicamento('0',reque[0],final[0]);\n";
      $this->salida .= "			  }\n";
      $this->salida .= "			}\n";
      $this->salida .= "			else\n";
      $this->salida .= "			{  alert(resultado[1]);}\n";
      $this->salida .= "    }\n";
      
			$this->salida .= "		function FinalizarS(num_mezcla,i)\n";
			$this->salida .= "		{\n";
			if ($_SESSION['PROFESIONAL'.$pfj]== 1)
			{
				$this->salida .= "			var answer = confirm(\"ESTA SEGURO QUE DESEA FINALIZAR LA SOLUCION?\");\n";
				$this->salida .= "			if (answer)\n";
				$this->salida .= "			{\n";
				$this->salida .= "				FinalizarSolucion('0',num_mezcla,i);\n";
				$this->salida .= "			}\n";
			}
			else
			
				$this->salida .= "				alert(\"SIN PRIVILEGIOS PARA FINALIZAR MEDICAMENTOS Y/O SOLUCIONES FORMULADAS\");\n";
			
			$this->salida .= "		}\n";
			$this->salida .= "		function CambiarImagen(i,clases,codigo,inicio)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			if(alternar[i] == undefined) alternar[i] = inicio;\n";
			$this->salida .= "			if(alternar[i] == 1)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				window.document['Suspender'+i].src = ImgActivar.src; \n";
			$this->salida .= "				window.document['Historial'+i].src = ImgHistorialCl.src; \n";
			$this->salida .= "				window.document['Suspender'+i].title = \"ACTIVAR MEDICAMENTO\";\n";
			$this->salida .= "				document.getElementById('Formulacion0x'+i).className = clases[0] ;\n";
			$this->salida .= "				document.getElementById('Formulacion1x'+i).className = clases[0] ;\n";
			$this->salida .= "				document.getElementById('Formulacion2x'+i).className = clases[0] ;\n";
			$this->salida .= "				document.getElementById('Formulacion3x1'+i).className = clases[5] ;\n";
			$this->salida .= "				document.getElementById('Formulacion3x2'+i).className = clases[5] ;\n";
			$this->salida .= "				document.getElementById('Bordex'+i).className = clases[3] ;\n";
			$this->salida .= "				try{\n";
			$this->salida .= "					document.getElementById('Formulacion5x'+i).className = clases[5] ;\n";
			$this->salida .= "				}catch(error){}\n";
			$this->salida .= "				alternar[i] = 2;\n";
			$this->salida .= "				ActualizarDatos('2',codigo);\n";
			$this->salida .= "			}\n";
			$this->salida .= "			else if(alternar[i] == 2)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				window.document['Suspender'+i].src = ImgSuspender.src; ";
			$this->salida .= "				window.document['Historial'+i].src = ImgHistorialOs.src;\n";
			$this->salida .= "				window.document['Suspender'+i].title = \"SUSPENDER MEDICAMENTO\";\n";
			$this->salida .= "				document.getElementById('Formulacion0x'+i).className = clases[1] ;\n";
			$this->salida .= "				document.getElementById('Formulacion1x'+i).className = clases[1] ;\n";
			$this->salida .= "				document.getElementById('Formulacion2x'+i).className = clases[1] ;\n";
			$this->salida .= "				document.getElementById('Formulacion3x1'+i).className = clases[4] ;\n";
			$this->salida .= "				document.getElementById('Formulacion3x2'+i).className = clases[4] ;\n";
			$this->salida .= "				try{\n";
			$this->salida .= "					document.getElementById('Formulacion5x'+i).className = clases[4] ;\n";
			$this->salida .= "				}catch(error){}\n";
			$this->salida .= "				document.getElementById('Bordex'+i).className = clases[2] ;\n";
			$this->salida .= "				alternar[i] = 1;\n";
			$this->salida .= "				ActualizarDatos('1',codigo);\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			
			$this->salida .= "		function CambiarImagenS(i,clases,codigo,inicio)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			if(alternarS[i] == undefined) alternarS[i] = inicio;\n";
			$this->salida .= "			if(alternarS[i] == 1)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				window.document['SuspenderS'+i].src = ImgActivar.src; \n";
			$this->salida .= "				window.document['HistorialS'+i].src = ImgHistorialCl.src; \n";
			$this->salida .= "				window.document['SuspenderS'+i].title = \"ACTIVAR SOLUCION\";\n";
			$this->salida .= "				document.getElementById('Solucion0'+i).className = clases[0] ;\n";
			$this->salida .= "				document.getElementById('Solucion1'+i).className = clases[0] ;\n";
			$this->salida .= "				document.getElementById('Solucion2'+i).className = clases[0] ;\n";
			$this->salida .= "				document.getElementById('Solucion3'+i).className = clases[5] ;\n";
			$this->salida .= "				document.getElementById('Solucion41'+i).className = clases[5] ;\n";
			$this->salida .= "				document.getElementById('Solucion42'+i).className = clases[5] ;\n";
			$this->salida .= "				try{\n";
			$this->salida .= "					document.getElementById('Solucion5'+i).className = clases[5] ;\n";
			$this->salida .= "				}catch(error){}\n";
			$this->salida .= "				alternarS[i] = 2;\n";
			$this->salida .= "				ActualizarSolucion('2',codigo);\n";
			$this->salida .= "			}\n";
			$this->salida .= "			else if(alternarS[i] == 2)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				window.document['SuspenderS'+i].src = ImgSuspender.src; ";
			$this->salida .= "				window.document['HistorialS'+i].src = ImgHistorialOs.src;\n";
			$this->salida .= "				window.document['SuspenderS'+i].title = \"SUSPENDER SOLUCION\";\n";
			$this->salida .= "				document.getElementById('Solucion0'+i).className = clases[1] ;\n";
			$this->salida .= "				document.getElementById('Solucion1'+i).className = clases[1] ;\n";
			$this->salida .= "				document.getElementById('Solucion2'+i).className = clases[1] ;\n";
			$this->salida .= "				document.getElementById('Solucion3'+i).className = clases[4] ;\n";
			$this->salida .= "				document.getElementById('Solucion41'+i).className = clases[4] ;\n";
			$this->salida .= "				document.getElementById('Solucion42'+i).className = clases[4] ;\n";

			$this->salida .= "				try{\n";
			$this->salida .= "					document.getElementById('Solucion5'+i).className = clases[4] ;\n";
			$this->salida .= "				}catch(error){}\n";
			$this->salida .= "				alternarS[i] = 1;\n";
			$this->salida .= "				ActualizarSolucion('1',codigo);\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			
			$this->salida .= "		function Iniciar(tit)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			if(alternar[contador] == undefined) alternar[contador] = inicio;\n";
			$this->salida .= "			if(alternar[contador] == 2 )\n";
			$this->salida .= "			{\n";
			$this->salida .= " 				EnviarInformacion();";
			$this->salida .= "			}\n";
			$this->salida .= "			else\n";
			$this->salida .= "			{\n";
			$this->salida .= "				IniciarVentana(tit);\n";
			$this->salida .= "				document.oculta.action = 'javascript:EvaluarDatosSuspension(document.oculta)';\n";
			$this->salida .= "				MostrarSpan('d2Container');\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			
			$this->salida .= "		function IniciarS(tit)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			if(alternarS[contador] == undefined) alternarS[contador] = inicio;\n";
			$this->salida .= "			if(alternarS[contador] == 2 )\n";
			$this->salida .= "			{\n";
			$this->salida .= " 				EnviarInformacionSolucion();";
			$this->salida .= "			}\n";
			$this->salida .= "			else\n";
			$this->salida .= "			{\n";
			$this->salida .= "				IniciarVentana(tit);\n";
			$this->salida .= "				document.oculta.action = 'javascript:EvaluarDatosSuspensionS(document.oculta)';\n";
			$this->salida .= "				MostrarSpan('d2Container');\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			
			$this->salida .= "		function IniciarVentana(tit)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			DatosObligatorias('titulo','d2Container','error');\n";
			$this->salida .= "			document.getElementById('titulo').innerHTML = '<center>'+tit+'</center>';\n";
			$this->salida .= "			document.getElementById('error').innerHTML = \"\";\n";
			$this->salida .= "			document.getElementById('observacion').value = '';\n";
			$this->salida .= "			ele = xGetElementById('d2Container');\n";
			$this->salida .= "	  	xMoveTo(ele, xClientWidth()/4, xScrollTop()+24);\n";
			$this->salida .= "			ele = xGetElementById('titulo');\n";
			$this->salida .= "	  	xResizeTo(ele,280, 25);\n";
			$this->salida .= "			xMoveTo(ele, 0, 0);\n";
			$this->salida .= "	  	xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$this->salida .= "			ele = xGetElementById('cerrar');\n";
			$this->salida .= "	  	xResizeTo(ele,20, 25);\n";
			$this->salida .= "			xMoveTo(ele, 280, 0);\n";
			$this->salida .= "		}\n";

			$this->salida .= "		function IniciarConfirmacion(producto,codigo,num_reg,capa)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			DatosObligatorias('tituloC','Confirmacion','errorC');\n";
			$this->salida .= "			document.getElementById('error').innerHTML = \"\";\n";
			$this->salida .= "			document.getElementById('producto_name').innerHTML = producto;\n";
			$this->salida .= "			document.confirma.observacion.value = '';\n";
			$this->salida .= "			document.confirma.action = 'javascript:EvaluarConfirmacion(\"'+codigo+'\",\"'+num_reg+'\",\"'+capa+'\")';\n";
			$this->salida .= "			ele = xGetElementById('Confirmacion');\n";
			$this->salida .= "	  	xMoveTo(ele, xClientWidth()/4, xScrollTop()+24);\n";
			$this->salida .= "			ele = xGetElementById('tituloC');\n";
			$this->salida .= "	  	xResizeTo(ele,280, 20);\n";
			$this->salida .= "			xMoveTo(ele, 0, 0);\n";
			$this->salida .= "	  	xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$this->salida .= "			ele = xGetElementById('cerrarC');\n";
			$this->salida .= "	  	xResizeTo(ele,20, 20);\n";
			$this->salida .= "			xMoveTo(ele, 280, 0);\n";
			$this->salida .= "		}\n";
			
			$this->salida .= "		function IniciarEdicion(tit)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			document.getElementById(titulo).innerHTML = '<center>'+tit+'</center>';\n";
 			$this->salida .= "			ele = xGetElementById('ContenedorSolucion');\n";
			$this->salida .= "	  	xResizeTo(ele,500, 150);\n";
			
			$this->salida .= "			ele = xGetElementById(contenedor);\n";
			$this->salida .= "	  	xResizeTo(ele,500, 'auto');\n";
			$this->salida .= "	  	xMoveTo(ele, xClientWidth()/5, xScrollTop()+24);\n";
			$this->salida .= "			ele = xGetElementById(titulo);\n";
			$this->salida .= "	  	xResizeTo(ele,480, 20);\n";
			$this->salida .= "			xMoveTo(ele, 0, 0);\n";
			$this->salida .= "	  	xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$this->salida .= "			ele = xGetElementById('cerrarS');\n";
			$this->salida .= "	  	xResizeTo(ele,20, 20);\n";
			$this->salida .= "			xMoveTo(ele, 480, 0);\n";
			$this->salida .= "		}\n";
			
			$this->salida .= "		function EditarSolucion(capa,num_reg,i,inicio)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			if(alternar[i] == undefined) alternar[i] = inicio;\n";
			$this->salida .= "			if(alternar[i] == 1)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				CrearEdicion(capa,num_reg);\n";
			$this->salida .= "				\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			
			$this->salida .= "	</script>\n\n";
			$this->salida .= "</form>\n";	

			$this->salida .= "<div id='d2Container' class='d2Container' style=\"display:none\">\n";
			$this->salida .= "	<div id='titulo' class='draggable' style=\"	text-transform: uppercase;text-align:center\"></div>\n";
			$this->salida .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:MostrarSpan('d2Container')\" title=\"Cerrar\" style=\"font-size:9px\">[X]</a></div><br><br>\n";
			$this->salida .= "	<div id='error' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
			$this->salida .= "	<div id='d2Contents'>\n";
			$this->salida .= "		<form name=\"oculta\" action=\"\" method=\"post\">\n";
			$this->salida .= "			<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "					<td >JUSTIFICACIÓN</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "					<td >\n";
			$this->salida .= "						<textarea class=\"textarea\" id=\"observacion\" name=\"observacion\" rows=\"3\" style=\"width:100%\"></textarea>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "					<td align=\"center\">\n";
			$this->salida .= "						<input type=\"button\" class=\"input-submit\"name=\"aceptar\" value=\"Aceptar\" onclick=\"submit();\">\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "			</table>\n";
			$this->salida .= "		</form>\n";
			$this->salida .= "	</div>\n";
			$this->salida .= "</div>\n";
			
			$this->salida .= "<div id='Soluciones' class='d2Container' style=\"display:none\">\n";
			$this->salida .= "	<div id='tituloS' class='draggable' style=\"	text-transform: uppercase;\"></div>\n";
			$this->salida .= "	<div id='cerrarS' class='draggable'><a class=\"hcPaciente\" href=\"javascript:MostrarSpan('Soluciones')\" title=\"Cerrar\" style=\"font-size:9px\">[X]</a></div><br><br>\n";
			$this->salida .= "	<div id='errorS' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
			$this->salida .= "	<div id='ContenedorSolucion' class=\"d2Content\">\n";
			$this->salida .= "	</div>\n";
			$this->salida .= "</div>\n";
			
			$this->salida .= "<div id='Confirmacion' class='d2Container' style=\"display:none\">\n";
			$this->salida .= "	<div id='tituloC' class='draggable' style=\"text-transform: uppercase;text-align:center\">confirmar formualcion medicamento</div>\n";
			$this->salida .= "	<div id='cerrarC' class='draggable'><a class=\"hcPaciente\" href=\"javascript:MostrarSpan('Confirmacion')\" title=\"Cerrar\" style=\"font-size:9px\">[X]</a></div><br><br>\n";
			$this->salida .= "	<div id='errorC' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
			$this->salida .= "	<div id='ConfirmarMedicamento'>\n";
			$this->salida .= "		<form name=\"confirma\" action=\"\" method=\"post\">\n";
			$this->salida .= "			<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "				<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "					<td class=\"normal_10AN\" align=\"center\" id=\"producto_name\"></td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "					<td >NOTA A LA CONFIRMACIÓN</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "					<td >\n";
			$this->salida .= "						<textarea class=\"textarea\" id=\"observacion\" name=\"observacion\" rows=\"3\" style=\"width:100%\"></textarea>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "					<td align=\"center\">\n";
			$this->salida .= "						<input type=\"button\" class=\"input-submit\"name=\"aceptar\" value=\"Aceptar\" onclick=\"submit();\">\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "			</table>\n";
			$this->salida .= "		</form>\n";
			$this->salida .= "	</div>\n";
			$this->salida .= "</div>\n";
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function CrearTablaFormulados($datos,$titulo,$clases,$cont=0,$imagenes,$inicio,$soluciones)
		{
			$html = "";
			if(sizeof($datos) > 0 || sizeof($soluciones) > 0)
			{
				$html .= "	<table align=\"center\" border=\"0\" width=\"98%\" class=\"modulo_table_list\">\n";
				$html .= "		<tr class=\"".$clases[6]."\">\n";
				$html .= "  		<td align=\"center\">".$titulo."</td>\n";
				$html .= "		</tr>\n";
				$html .= "		<tr>\n";
				$html .= "			<td><br>\n";
				
				$est0 = "style=\"text-indent:2pt;text-align:left;font-size:11px;\" ";
				$est1 = "style=\"text-indent:2pt;text-align:left;font-size:9px;\" ";
				
				$clasesjs = "new Array('".$clases[3]."','".$clases[0]."','".$clases[2]."','".$clases[4]."','".$clases[7]."','".$clases[8]."')";
				if($inicio == 2)	$clasesjs = "new Array('".$clases[0]."','".$clases[3]."','".$clases[4]."','".$clases[2]."','".$clases[8]."','".$clases[7]."')";
				
				$width = "92%"; $span = "3";
				if(SessionGetVar("tipoProfesionalhc") == '1')
				{
					$width = "84%"; $span = "5";
				}
				$usuariohc = UserGetUID();
				for($i=0; $i<sizeof($datos); $i++)
				{
					$html .= "<div id=\"CapaFormula".($i+$cont)."\">\n";
					$html .= "	<table id=\"Bordex".($i+$cont)."\" align=\"center\" border=\"0\" width=\"100%\" class=\"".$clases[2]."\">\n";
					$html .= "		<tr id=\"Formulacion0x".($i+$cont)."\" class=\"".$clases[0]."\">\n";
					$html .= "  		<td width=\"$width\">\n";
					$html .= "				<table widtah=\"100%\" id=\"Formulacion1x".($i+$cont)."\" class=\"".$clases[0]."\" >\n";
					$html .= "					<tr>\n";
					$html .= "						<td valign=\"bottom\" $est0 >".$datos[$i]['producto']."</td>\n";
					$html .= "						<td valign=\"bottom\" id=\"Formulacion2x".($i+$cont)."\" $est1> (".$datos[$i]['principio_activo'].")</td>\n";
					$html .= "					</tr>\n";
					$html .= "				</table>\n";
					$html .= "			</td>\n";
					if(SessionGetVar("tipoProfesionalhc") == '1' && $inicio != 4)
					{
						$html .= "			<td width=\"4%\" align=\"center\" >\n";
						$html .= "				<a href=\"javascript:EditarFormulacion('".$datos[$i]['codigo_producto']."',".($i+$cont).",$inicio)\"  title=\"EDITAR\">\n";
						$html .= "					<img name =\"Editar\" height=\"18\" src=\"".GetThemePath()."/images/edita.png\" border=\"0\" >\n";
						$html .= "				</a>\n";
						$html .= "			</td>\n";
					}
					$html .= "			<td width=\"4%\" align=\"center\">\n";
					$html .= "				<a href=\"javascript:VisualizarHistorial('".$datos[$i]['codigo_producto']."')\"  title=\"HISTORIAL\">\n";
					$html .= "					<img name =\"Historial".($i+$cont)."\" height=\"18\"  src=\"".GetThemePath()."/images/HistoriaClinica1/".$imagenes[0]."\" border=\"0\">\n";
					$html .= "				</a>\n";
					$html .= "			</td>\n";
					if($inicio != 4)
					{
						$html .= "			<td width=\"4%\" align=\"center\">\n";
						$html .= "				<a href=\"javascript:DatosActuales(".($i+$cont).",$clasesjs,'".$datos[$i]['codigo_producto']."',$inicio);Iniciar('".$datos[$i]['producto']."');\" >\n";
						$html .= "					<img width=\"16\" height=\"18\" title=\"".$clases[9]." MEDICAMENTO\" src=\"".GetThemePath()."/images/".$imagenes[1]."\" border=\"0\" name=\"Suspender".($i+$cont)."\" >\n";
						$html .= "				</a>\n";
						$html .= "			</td>\n";
					}
					
					if(SessionGetVar("tipoProfesionalhc") == '1' && $inicio != 4)
					{
						$html .= "			<td width=\"4%\" align=\"center\" >\n";
						$html .= "				<a href=\"javascript:Finalizar('".$datos[$i]['codigo_producto']."',".($i+$cont).",$inicio,'".$datos[$i]['producto']."')\"  title=\"FINALIZAR MEDICAMENTO\">\n";
						$html .= "					<img name =\"Finalizar\" height=\"18\" src=\"".GetThemePath()."/images/HistoriaClinica1/cerrar_claro.gif\" border=\"0\" >\n";
						$html .= "				</a>\n";
						$html .= "			</td>\n";
					}
					$html .= "		</tr>\n";
					$html .= "		<tr class=\"".$clases[1]."\">\n";
					$html .= "			<td colspan=\"$span\">\n";
					$estilos = "style=\"border-bottom-width:0px;border-left-width:1px;border-right-width:0px;border-top-width:0px;border-style: solid;\""; 
					
					$html .= "				<table width=\"100%\">\n";
					$html .= "					<tr>\n";
					$html .= "						<td width=\"60%\" valign=\"top\">\n";
					$html .= "							<table align=\"left\" id=\"Formulacion3x1".($i+$cont)."\" class=\"".$clases[7]."\">\n";
					$html .= "								<tr>\n";
					$html .= "									<td >VIA DE ADMINISTRACIÓN: </td>\n";
					$html .= "									<td colspan=\"3\">".$datos[$i]['nombre']."</td>\n";
					$html .= "								</tr>\n";
					$html .= "								<tr >\n";
					$html .= "									<td >DOSIS</td>\n";
					$html .= "									<td align=\"right\">".$datos[$i]['dosis']."</td>\n";
          $html .= "                  <td>".$datos[$i]['unidad_dosificacion']."</td>\n";
					$html .= "									<td align=\"left\">".$datos[$i]['frecuencia']."</td>\n";
					$html .= "								</tr>\n";				
					$html .= "								<tr >\n";
					$html .= "									<td >CANTIDAD</td>\n";
					$html .= "									<td align=\"right\">".$datos[$i]['cantidad']."</td>\n";
          $html .= "                  <td colspan=\"2\">".$datos[$i]['umm']."</td>\n";
					$html .= "								</tr>\n";
					$html .= "								<tr >\n";
          $html .= "									<td >DIAS TRATAMIENTO</td>\n";
					$html .= "									<td align=\"right\">".intval($datos[$i]['dias_tratamiento'])."</td>\n";
          $html .= "                  <td colspan=\"2\"></td>\n";
					$html .= "								</tr>\n";
					$html .= "							</table>\n";
					$html .= "						</td>\n";
					$html .= "						<td width=\"40%\" valign=\"top\" $estilos>\n";
					$html .= "							<table align=\"center\" id=\"Formulacion3x2".($i+$cont)."\" class=\"".$clases[7]."\" width=\"98%\">\n";
					$html .= "								<tr>\n";
					$html .= "									<td align=\"left\">FORMULÓ: <font style=\"font-weight:normal;\">".$datos[$i]['med_formula']."</font></td>\n";
					$html .= "								</tr>\n";
					if($datos[$i]['med_modifica'] != $datos[$i]['med_formula'])
					{
						$html .= "								<tr>\n";
						$html .= "									<td align=\"left\">MODIFICO: <font style=\"font-weight:normal;\">".$datos[$i]['med_modifica']."</font></td>\n";
						$html .= "								</tr>\n";
					}
					if($datos[$i]['sw_confirmacion_formulacion'] == '0' && $datos[$i]['usuario_id'] == $usuariohc)
					{
						$arr = "'".$datos[$i]['codigo_producto']."','".$datos[$i]['num_reg_formulacion']."'";
						if($inicio == 4) $arr .= ",'CapaFormula".($i+$cont)."'";
						
						$html .= "								<tr>\n";
						$html .= "									<td id=\"confirmacion".$datos[$i]['codigo_producto']."\" align=\"center\"><a href=\"javascript:IniciarConfirmacion('".$datos[$i]['producto']."',$arr);MostrarCapas('Confirmacion')\" class=\"normal_10AN\">CONFIRMAR</a></td>\n";
						$html .= "								</tr>\n";
					}
					if($datos[$i]['sw_requiere_autorizacion_no_pos'] == 'S' && !$datos[$i]['justificacion_no_pos_id'] && $inicio != 4)
					{
						$html .= "								<tr>\n";
						$html .= "									<td id=\"justificacion".$datos[$i]['codigo_producto']."\" align=\"center\"><a href=\"javascript:Justificar('".$datos[$i]['codigo_producto']."','".$datos[$i]['justificacion_no_pos_id']."')\" class=\"normal_10AN\">JUSTIFICAR</a></td>\n";
						$html .= "								</tr>\n";					
					}
					if($datos[$i]['sw_requiere_autorizacion_no_pos'] == 'S' && $datos[$i]['justificacion_no_pos_id'])
					{
						$html .= "								<tr>\n";
						$html .= "									<td id=\"justificacion".$datos[$i]['codigo_producto']."\" align=\"center\"><a href=\"javascript:Justificar('".$datos[$i]['codigo_producto']."','".$datos[$i]['justificacion_no_pos_id']."')\" class=\"normal_10AN\">VER JUSTIFICACIÓN</a></td>\n";
						$html .= "								</tr>\n";					
					}
					if($datos[$i]['sw_requiere_autorizacion_no_pos'] == 'P')
					{
						$html .= "								<tr>\n";
						$html .= "									<td id=\"justificacion".$datos[$i]['codigo_producto']."\" align=\"center\"><b class=\"normal_10AN\">MEDICAMENTO NO POS A PETICION DEL PACIENTE</b></td>\n";
						$html .= "								</tr>\n";					
					}
					if($datos[$i]['sw_requiere_autorizacion_no_pos'] == 'N')
					{
						$html .= "								<tr>\n";
						$html .= "									<td id=\"justificacion".$datos[$i]['codigo_producto']."\" align=\"center\"><b class=\"normal_10AN\">MEDICAMENTO POS</b></td>\n";
						$html .= "								</tr>\n";					
					}
					$html .= "							</table>\n";
					$html .= "						</td>\n";
					$html .= "					</tr>\n";					
					$html .= "				</table>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
					
					if($datos[$i]['observacion'] != "")
					{
						$html .= "		<tr class=\"".$clases[1]."\">\n";
						$html .= "			<td colspan=\"$span\">\n";
						$html .= "				<table width=\"100%\" id=\"Formulacion5x".($i+$cont)."\" class=\"".$clases[7]."\">\n";
						$html .= "					<tr>\n";
						$html .= "						<td valign=\"top\" width=\"30%\">\n";
						$html .= "							OBSERVACIONES E INDICACIONES DE SUMINISTRO</td>\n";
						$html .= "						</td>\n";
						$html .= "						<td valign=\"top\" width=\"70%\" align=\"justify\" colspan=\"4\" >\n";
						$html .= "							".$datos[$i]['observacion']."\n";
						$html .= "						</td>\n";
						$html .= "					</tr>\n";
						$html .= "				</table>\n";
						$html .= "			</td>\n";
						$html .= "		</tr>\n";
					}
					
					$html .= "	</table><br>";
					$html .= "</div>\n";
				}
				
				$j = 0;
				$est0 = "style=\"text-indent:2pt;font-size:11px;\" ";
				$est1 = "style=\"text-indent:2pt;font-size:9px;\" ";
				foreach($soluciones as $key=> $nivel1)
				{
					$html .= "<div id=\"CapaSolucion".($j+$cont)."\">\n";
					$html .= "	<table align=\"center\" border=\"0\" width=\"100%\" class=\"".$clases[2]."\">\n";
					$html .= "		<tr id=\"Solucion1".($j+$cont)."\" class=\"".$clases[0]."\">\n";
					$html .= "  		<td width=\"84%\">\n";
					$html .= "				<table id=\"Solucion2".($j+$cont)."\" class=\"".$clases[0]."\" >\n";
					$html .= "					<tr >\n";
					$html .= "						<td valign=\"bottom\" $est0 >SOLUCION</td>\n";
					$html .= "					</tr>\n";
					$html .= "				</table>\n";
					$html .= "			</td>\n";
					
					if(SessionGetVar("tipoProfesionalhc") == '1')
					{
						$html .= "			<td width=\"4%\" align=\"center\" >\n";
						$html .= "				<a href=\"javascript:CrearEdicion('CapaSolucion".($j+$cont)."','".$key."',".$inicio.")\"  title=\"EDITAR\">\n";
						$html .= "					<img name =\"Editar\" height=\"18\" src=\"".GetThemePath()."/images/edita.png\" border=\"0\" >\n";
						$html .= "				</a>\n";
						$html .= "			</td>\n";
					}
					
					$html .= "			<td width=\"4%\" align=\"center\">\n";
					$html .= "				<a href=\"javascript:VerHistorial(new Array('".$key."'))\"  title=\"HISTORIAL\">\n";
					$html .= "					<img name =\"HistorialS".($j+$cont)."\" height=\"18\"  src=\"".GetThemePath()."/images/HistoriaClinica1/".$imagenes[0]."\" border=\"0\">\n";
					$html .= "				</a>\n";
					$html .= "			</td>\n";
					$html .= "			<td width=\"4%\" align=\"center\">\n";
					$html .= "				<a href=\"javascript:DatosActuales(".($j+$cont).",$clasesjs,'".$key."',$inicio);IniciarS('SOLUCION');\" >\n";
					$html .= "					<img name =\"SuspenderS".($j+$cont)."\" width=\"16\" height=\"18\" title=\"SUSPENDER SOLUCION\" src=\"".GetThemePath()."/images/".$imagenes[1]."\" border=\"0\" name=\"Suspender".($i+$cont)."\" >\n";
					$html .= "				</a>\n";
					$html .= "			</td>\n";
					if(SessionGetVar("tipoProfesionalhc") == '1')
					{
						$html .= "			<td width=\"4%\" align=\"center\" >\n";
						$html .= "				<a href=\"javascript:FinalizarS('".$key."','".($j+$cont)."')\"  title=\"FINALIZAR MEDICAMENTO\">\n";
						$html .= "					<img name =\"Finalizar\" height=\"18\" src=\"".GetThemePath()."/images/HistoriaClinica1/cerrar_claro.gif\" border=\"0\" >\n";
						$html .= "				</a>\n";
						$html .= "			</td>\n";
					}
					$html .= "		</tr>\n";
					$html .= "		<tr >\n";
					$html .= "			<td colspan=\"5\">\n";
					$html .= "				<table id=\"Solucion0".($j+$cont)."\"  class=\"".$clases[0]."\" width=\"100%\">\n";
					foreach($nivel1 as $key0=> $nivel2)
					{
						if($nivel2['sw_solucion'] == '1') 
						{
							$html .= "					<tr>\n";
							$html .= "						<td valign=\"bottom\" align=\"left\"  $est0 width=\"75%\">".$nivel2['producto']." <font $est1>(".$nivel2['principio_activo'].")</font></td>\n";
							$html .= "						<td valign=\"bottom\" align=\"right\" $est1 width=\"10%\">".$nivel2['dosis']."</td>\n";
							$html .= "						<td valign=\"bottom\" align=\"left\"  $est1 width=\"15%\">".$nivel2['unidad_dosificacion']."</td>\n";
							$html .= "					</tr>\n";
						}
					}
					$html .= "				</table>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
					$html .= "		<tr>\n";
					$html .= "			<td colspan=\"5\" class=\"modulo_list_oscuro\">\n";
					$html .= "				<table id=\"Solucion3".($j+$cont)."\"  class=\"".$clases[7]."\" width=\"100%\">\n";
					foreach($nivel1 as $key1=> $nivel2)
					{
						if($nivel2['sw_solucion'] == '0') 
						{
							$html .= "					<tr>\n";
							$html .= "						<td valign=\"bottom\" align=\"left\"  $est0 width=\"75%\">".$nivel2['producto']." <font $est1>(".$nivel2['principio_activo'].")</font></td>\n";
							$html .= "						<td valign=\"bottom\" align=\"right\" $est1 width=\"10%\">".$nivel2['dosis']."</td>\n";
							$html .= "						<td valign=\"bottom\" align=\"left\"  $est1 width=\"15%\">".$nivel2['unidad_dosificacion']."</td>\n";
							$html .= "					</tr>\n";
						}
					}
					$html .= "				</table>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
 					$html .= "		<tr class=\"".$clases[1]."\">\n";
					$html .= "			<td colspan=\"5\">\n";
					
					$estilos = "style=\"border-bottom-width:0px;border-left-width:1px;border-right-width:0px;border-top-width:0px;border-style: solid;\""; 
					
					$html .= "				<table width=\"100%\">\n";
					$html .= "					<tr>\n";
					$html .= "						<td width=\"60%\" valign=\"top\">\n";
					
					$html .= "							<table id=\"Solucion41".($j+$cont)."\" class=\"".$clases[7]."\" >\n";
					$html .= "								<tr>\n";
					$html .= "									<td >CANTIDAD TOTAL </td>\n";
					$html .= "									<td align=\"right\">".$nivel1[$key1]['cantidad']."</td><td colspan=\"2\"><b>Unidad(es)</b></td>\n";
					$html .= "								</tr>\n";
					$html .= "								<tr >\n";
					$html .= "									<td >VOLUMEN DE INFUSIÓN</td>\n";
					$html .= "									<td align=\"right\">".$nivel1[$key1]['volumen_infusion']."</td><td colspan=\"2\">".$nivel1[$key1]['unidad_volumen']."</td>\n";
					$html .= "								</tr>\n";
					$html .= "							</table>\n";
					$html .= "						</td>\n";
					$html .= "						<td width=\"40%\" valign=\"top\" $estilos>\n";
					$html .= "							<table align=\"center\" id=\"Solucion42".($j+$cont)."\" class=\"".$clases[7]."\" >\n";
					$html .= "								<tr>\n";
					$html .= "									<td align=\"center\">FORMULÓ:</td>\n";
					$html .= "								</tr>\n";
					$html .= "								<tr>\n";
					$html .= "									<td style=\" font-weight:normal\">".$nivel1[$key1]['med_formula']."</td>\n";
					$html .= "								</tr>\n";
					$html .= "							</table>\n";
					$html .= "						</td>\n";
					$html .= "					</tr>\n";	
					
					$html .= "				</table>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n"; 
				
					if($nivel1[$key1]['observacion'] != "")
					{
						$html .= "		<tr class=\"".$clases[1]."\" >\n";
						$html .= "			<td colspan=\"5\" width=\"100%\" >\n";
						$html .= "				<table width=\"100%\" id=\"Solucion5".($j+$cont)."\" class=\"".$clases[7]."\">\n";
						$html .= "					<tr>\n";
						$html .= "						<td valign=\"top\" width=\"30%\">\n";
						$html .= "							OBSERVACIONES E INDICACIONES DE SUMINISTRO:</td>\n";
						$html .= "						</td>\n";
						$html .= "						<td valign=\"top\" width=\"70%\" align=\"justify\" colspan=\"4\" >\n";
						$html .= "							".$nivel1[$key1]['observacion']."\n";
						$html .= "						</td>\n";
						$html .= "					</tr>\n";
						$html .= "				</table>\n";
						$html .= "			</td>\n";
						$html .= "		</tr>\n";
					}
					
					$html .= "	</table><br>";
					$html .= "</div>\n";
					$j++;
				}
				
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$html .= "	</table><br>";
			}
			return $html;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function frmForma_Seleccion_Medicamentos($medicamentos)
    {
      $pfj = $this->frmPrefijo;
      $this->salida= ThemeAbrirTablaSubModulo('SOLICITUD DE MEDICAMENTOS - BUSQUEDA');
      $accion1 = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Busqueda_Avanzada_Medicamentos',
          'paso1'=>$_REQUEST['paso1'.$pfj], 'producto'.$pfj=>$_REQUEST['producto'.$pfj],
          'principio_activo'.$pfj=>$_REQUEST['principio_activo'.$pfj]));
			
			$action2 = ModuloHCGetURL($this->evolucion, $this->paso, 0, '', false,array('accion'.$pfj=>'frmForma_Add'));
			
			$accionV = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
			
			$this->salida .= "<script language=\"javascript\">\n";
			$this->salida .= "	function mOvr(src,clrOver)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		src.style.background = clrOver;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function mOut(src,clrIn)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		src.style.background = clrIn;\n";
			$this->salida .= "	}\n";
			$this->salida .= "</script>\n";
      $this->salida .= "<form name=\"formades$pfj\" action=\"$accion1\" method=\"post\">\n";
      $this->salida .= "	<table  align=\"center\" class=\"modulo_table_list\" width=\"80%\">\n";
      $this->salida .= "		<tr class=\"modulo_table_list_title\">\n";
      $this->salida .= "  		<td align=\"center\" colspan=\"7\">BUSQUEDA AVANZADA </td>\n";
      $this->salida .= "		</tr>\n";
      $this->salida .= "		<tr class=\"hc_table_submodulo_list_title\">\n";
      $this->salida .= "			<td width=\"%\" class=\"normal_10AN\">PRODUCTO:</td>\n";
      $this->salida .= "			<td width=\"%\" align='center'><input type='text' class='input-text'  size = 22 name = 'producto$pfj'  value =\"".$_REQUEST['producto'.$pfj]."\"    ></td>\n" ;
      $this->salida .= "			<td width=\"%\" class=\"normal_10AN\">PRINCIPIO ACTIVO:</td>\n";
      $this->salida .= "			<td width=\"%\" align='center'><input type='text' class='input-text' size = 22 name = 'principio_activo$pfj'   value =\"".$_REQUEST['principio_activo'.$pfj]."\"></td>\n" ;
      $this->salida .= "			<td width=\"%\" align=\"center\" class=\"normal_10AN\"><input class=\"input-submit\" name=\"buscar$pfj\" type=\"submit\" value=\"Buscar\"></td>\n";
      $this->salida .= "		</tr>\n";
      $this->salida .= "	</table><br>\n";
      $this->salida .= "	<table  align=\"center\" border=\"0\"  width=\"80%\">\n";
      $this->salida .= $this->SetStyle("MensajeError");
      $this->salida .= "	</table>\n";
      $this->salida .= "</form>\n";
               
      if ($medicamentos)
      {
				$est = "style=\"text-indent:0pt\" ";
				$this->salida .= "<table  align=\"center\" border=\"0\"  width=\"98%\" class=\"modulo_table_list\">\n";
				$this->salida .= "	<tr class=\"formulacion_table_list\">\n";
				$this->salida .= "  	<td align=\"center\" colspan=\"7\">RESULTADO DE LA BUSQUEDA</td>\n";
				$this->salida .= "	</tr>\n";
				$this->salida .= "	<tr class=\"formulacion_table_list\">\n";
				$this->salida .= "  	<td $est align=\"center\" width=\"5%\"></td>\n";
				$this->salida .= "  	<td $est align=\"center\" width=\"10%\" title=\"ORDENAR POR CODIGO\">\n";
				$this->salida .= "  		<a href=\"$accion1&orden=codigo_producto\" style=\"color:#FFFFFF;text-decoration: none;\">CODIGO</a>\n";
				$this->salida .= "  	</td>\n";
				$this->salida .= "  	<td $est align=\"center\" width=\"26%\" title=\"ORDENAR POR PRODUCTO\">\n";
				$this->salida .= "  		<a href=\"$accion1&orden=producto\" style=\"color:#FFFFFF;text-decoration: none;\">PRODUCTO</a>\n";
				$this->salida .= "  	</td>\n";
				$this->salida .= "  	<td $est align=\"center\" width=\"25%\" title=\"ORDENAR POR PRINCIPIO ACTIVO\">\n";
				$this->salida .= "  		<a href=\"$accion1&orden=principio_activo\" style=\"color:#FFFFFF;text-decoration: none;\">P. ACTIVO</a>\n";
				$this->salida .= "  	</td>\n";
				$this->salida .= "  	<td $est align=\"center\" width=\"24%\">FORMA</td>\n";
				$this->salida .= "  	<td $est align=\"center\" width=\"5%\">EXIST</td>\n";
				$this->salida .= "  	<td $est align=\"center\" width=\"5%\">OP.</td>\n";
				$this->salida .= "	</tr>\n";
				
				for($i=0;$i<sizeof($medicamentos);$i++)
				{
					if($i % 2 == 0)
					{
					  $estilo='modulo_list_oscuro'; $background = "#CCCCCC";
					}
					else
					{
					  $estilo='modulo_list_claro';  $background = "#DDDDDD";
					}
					
					$exis = $medicamentos[$i][existencia];
					if(!$medicamentos[$i][existencia]) $exis = "--";
					
					$actionL = $action2."&medicaCod='".$medicamentos[$i][codigo_producto]."'";
					
					$this->salida .= "	<tr class=\"".$estilo."\" height=\"21\" onmouseout=mOut(this,\"".$background."\"); onmouseover=mOvr(this,'#FFFFFF');>\n";
					$this->salida .= "  	<td align=\"center\">".$medicamentos[$i]['item']."</td>\n";
					$this->salida .= "  	<td align=\"center\">".$medicamentos[$i]['codigo_producto']."</td>\n";
					$this->salida .= "  	<td align=\"left\" class=\"normal_10A\">".$medicamentos[$i]['producto']."</td>\n";
					$this->salida .= "  	<td align=\"left\" >".$medicamentos[$i]['principio_activo']."</td>\n";
					$this->salida .= "  	<td align=\"left\" >".$medicamentos[$i]['forma']."</td>\n";
					$this->salida .= "  	<td align=\"right\">".$exis."</td>\n";
					$this->salida .= "		<td align=\"center\" title=\"FORMULAR MEDICAMENTO\" >\n";
					$this->salida .= "			<a href=\"$actionL\">\n";
					$this->salida .= "				<img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\" width=\"15\" height=\"15\">\n";				
					$this->salida .= "			<a>\n";
					$this->salida .= "		</td>\n";
					$this->salida .= "	</tr>\n";
				}
				$this->salida .= "</table><br>\n";
				
				$Paginador = new ClaseHTML();
				$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$accion1);
				$this->salida .= "		<br>\n";
      }
			else
			{
				$this->salida .= "<center><b class=\"label_error\">LA BUSQUEDA NO ARROJO NINGUN RESULTADO</b></center><br>\n";
			}
         
      $this->salida .= "<form name=\"forma$pfj\" action=\"$accionV\" method=\"post\">";
      $this->salida .= "<center><input class=\"input-submit\" name=\"volver$pfj\" type=\"submit\" value=\"Volver\"></center></form>\n";
      $this->salida .= ThemeCerrarTablaSubModulo();
      return true;
    }
		/********************************************************************************
		*
		*********************************************************************************/
		function frmHistoria()
		{
			$formulacion = $this->ConsultaMedicamentosFormulados();
			$soluciones = $this->ConsultaSolucionesFormuladas(1);
			
			$formulados = $formulacion['formulacion'];
			
			if(sizeof($formulados) > 0)
			{
				$modifica = $this->ConsultaAccionesMedicamentos();
				$datos = array();
				foreach($formulados as $key => $datos)
				{
					$html .= "				<table align=\"center\" width=\"100%\" border=\"1\" bordercolor=\"#000000\" cellpadding=\"0\" cellspacing=\"0\">\n";
					$html .= "					<tr>\n";
					$html .= "						<td align=\"center\" class=\"label\" width=\"10%\">CÓDIGO</td>\n";
					$html .= "						<td align=\"center\" class=\"label\" width=\"50%\">PRODUCTO</td>\n";
					$html .= "						<td colspan=\"2\" align=\"center\" class=\"label\" width=\"30%\">PRINCIPIO ACTIVO</td>\n";
					$html .= "					</tr>\n";
					$html .= "					<tr class=\"normal_10\" >\n";
					$html .= "						<td align=\"center\">".$datos['codigo_producto']." ".$datos['item']."</td>\n";
					$html .= "						<td class=\"normal_10N_menu\">".$datos['producto']."</td>\n";
					$html .= "						<td colspan=\"2\">".$datos['principio_activo']."</td>\n";
					$html .= "					</tr>\n";
					$html .= "					<tr>\n";
					$html .= "						<td align=\"center\" class=\"label\">FORMULÓ</td>\n";
					$html .= "						<td class=\"normal_10\">".$datos['usuario']."</td>\n";
					$html .= "						<td align=\"center\" class=\"label\">FECHA FORMULACIÓN: ".$datos['fecha']."</td>\n";
					$html .= "					</tr>\n";
					$html .= "					<tr class=\"normal_10\" >\n";
					$html .= "						<td colspan=\"4\">\n";
					$html .= "							<table class=\"normal_10\" >\n";
					$html .= "								<tr>\n";
					$html .= "									<td >VIA DE ADMINISTRACIÓN: </td>\n";
					$html .= "									<td colspan=\"3\">".$datos['nombre']."</td>\n";
					$html .= "								</tr>\n";
					$html .= "								<tr>\n";
					$html .= "									<td >DOSIS</td>\n";
					$html .= "									<td align=\"right\">".$datos['dosis']."</td><td>".$datos['unidad_dosificacion']."</td>\n";
					$html .= "									<td align=\"left\">".$datos['frecuencia']."</td>\n";
					$html .= "								</tr>\n";				
					$html .= "								<tr >\n";
					$html .= "									<td >CANTIDAD</td>\n";
					$html .= "									<td align=\"right\">".$datos['cantidad']."</td><td colspan=\"2\">".$datos['umm']."</td>\n";
					$html .= "								</tr>\n";
					$html .= "							</table>\n";
					$html .= "						</td>\n";
					$html .= "					</tr>\n";
						
					if($datos['observacion'] != "")
					{
						$html .= "					<tr class=\"normal_10\">\n";
						$html .= "						<td colspan=\"4\">\n";
						$html .= "							<table width=\"100%\" class=\"normal_10\">\n";
						$html .= "								<tr>\n";
						$html .= "									<td valign=\"top\" width=\"30%\">\n";
						$html .= "										OBSERVACIONES E INDICACIONES DE SUMINISTRO</td>\n";
						$html .= "									</td>\n";
						$html .= "									<td valign=\"top\" width=\"70%\" align=\"justify\" colspan=\"4\" >\n";
						$html .= "										".$datos['observacion']."\n";
						$html .= "									</td>\n";
						$html .= "								</tr>\n";
						$html .= "							</table>\n";
						$html .= "						</td>\n";
						$html .= "					</tr>\n";
					}
					
					if(sizeof($modifica[$key]) > 0)
					{
						$html .= "					<tr class=\"normal_10\">\n";
						$html .= "						<td colspan=\"4\">\n";
						$html .= "							<center class=\"label_mark\">HISTORICO FORMULACION</center>\n";
						$html .= "							<table width=\"99%\" class=\"normal_10\" border=\"1\" align=\"center\" bordercolor=\"#000000\" cellpadding=\"0\" cellspacing=\"0\">\n";
						$html .= "								<tr class=\"label\" >\n";
						$html .= "									<td align=\"center\" width=\"25%\">FECHA</td>\n";
						$html .= "									<td align=\"center\" width=\"40%\">FORMULÓ</td>\n";
						$html .= "									<td align=\"center\" width=\"35%\">ACCION</td>\n";
						$html .= "								</tr>\n";

						foreach($modifica[$key] as $key => $datos1)
						{
							$html .= "								<tr class=\"normal_10\" >\n";
							$html .= "									<td align=\"center\" >".$datos1['fecha']."</td>\n";
							$html .= "									<td align=\"center\" >".$datos1['usuario']."</td>\n";
							$estado = "";
							if($datos1['sw_estado'] == '1')
								$estado = "ACTIVACIÓN / MODIFACIÓN";
							else if($datos1['sw_estado'] == '2')
									$estado = "SUSPENSIÓN";
								else
									$estado = "FINALIZACIÓN";
								
							$html .= "									<td align=\"center\" class=\"label_mark\">".$estado."</td>\n";
							$html .= "								</tr>\n";
							
							if(	$datos1['sw_observacion'] == '1' || $datos1['sw_via_administracion_id'] == '1' || $datos1['sw_unidad_dosificacion'] == '1'
									|| $datos1['sw_dosis'] == '1' ||	$datos1['sw_frecuencia'] == '1' || $datos1['sw_cantidad'] == '1'
								)
							{
								$html .= "					<tr class=\"normal_10\" >\n";
								$html .= "						<td align=\"center\" class=\"label\">CAMBIOS</td>\n";
								$html .= "						<td colspan=\"2\">\n";
								$html .= "							<table class=\"normal_10\" >\n";
								if($datos1['sw_via_administracion_id'] == '1')
								{
									$html .= "								<tr>\n";
									$html .= "									<td >VIA DE ADMINISTRACIÓN: </td>\n";
									$html .= "									<td colspan=\"3\">".$datos1['nombre']."</td>\n";
									$html .= "								</tr>\n";
								}
								if($datos1['sw_dosis'] == '1' ||	$datos1['sw_frecuencia'] == '1' || $datos1['sw_unidad_dosificacion'] == '1')
								{
									$html .= "								<tr>\n";
									$html .= "									<td >DOSIS</td>\n";
									$html .= "									<td align=\"right\">".$datos1['dosis']."</td><td>".$datos1['unidad_dosificacion']."</td>\n";
									$html .= "									<td align=\"left\">".$datos1['frecuencia']."</td>\n";
									$html .= "								</tr>\n";
								}
								if($datos1['sw_cantidad'] == '1')
								{
									$html .= "								<tr >\n";
									$html .= "									<td >CANTIDAD</td>\n";
									$html .= "									<td align=\"right\">".$datos1['cantidad']."</td><td colspan=\"2\">".$datos['umm']."</td>\n";
									$html .= "								</tr>\n";
								}	
								if($datos1['observacion'] != "" && $datos1['sw_observacion'] == '1' )
								{
									$html .= "								<tr>\n";
									$html .= "									<td valign=\"top\" width=\"30%\">\n";
									$html .= "										OBSERVACIONES E INDICACIONES DE SUMINISTRO</td>\n";
									$html .= "									</td>\n";
									$html .= "									<td valign=\"top\" width=\"70%\" align=\"justify\" colspan=\"4\" >\n";
									$html .= "										".$datos1['observacion']."\n";
									$html .= "									</td>\n";
									$html .= "								</tr>\n";
								}
								$html .= "							</table>\n";
								$html .= "						</td>\n";
								$html .= "					</tr>\n";
							}
						}
						$html .= "							</table><br>\n";
						$html .= "						</td>\n";
						$html .= "					</tr>\n";
					}

					if($datos['usuario_suministro'])
					{
						$html .= "					<tr>\n";
						$html .= "						<td align=\"center\" colspan=\"4\">\n";
						
						$html .= "							<center class=\"label_mark\">REGISTRO DE ADMINISTRACION DE MEDICAMENTOS</center>\n";
						$html .= "							<table width=\"99%\" align=\"center\" class=\"normal_10\" border=\"1px\" bordercolor=\"#000000\" cellpadding=\"0\" cellspacing=\"0\">\n";
						$html .= "								<tr>\n";
						$html .= "									<td align=\"center\" class=\"label\" width=\"%\">Fecha</td>\n";
						$html .= "									<td align=\"center\" class=\"label\" width=\"%\">Usuario</td>\n";
						$html .= "									<td align=\"center\" class=\"label\" width=\"%\">Cantidad</td>\n";
						$html .= "									<td align=\"center\" class=\"label\" width=\"%\">Desechos</td>\n";
						$html .= "									<td align=\"center\" class=\"label\" width=\"%\">Entregas Al Paciente</td>\n";
						$html .= "									<td align=\"center\" class=\"label\" width=\"30%\">Observación</td>\n";
						$html .= "								</tr>\n";

						foreach($formulacion['suministro'][$datos['num_reg']] as $keyII => $suministro)
						{
							$html .= "								<tr>\n";
							$html .= "									<td valign=\"top\" width=\"%\" align=\"center\">\n";
							$html .= "										".$suministro['fecha_suministro']."\n";
							$html .= "									</td>\n";
							$html .= "									<td valign=\"top\" width=\"%\" align=\"justify\">\n";
							$html .= "										".$suministro['usuario_suministro']."\n";
							$html .= "									</td>\n";							
							$html .= "									<td valign=\"top\" width=\"%\" class=\"normal_10\" align=\"right\">".$this->SeleccionFactorConversion($datos['codigo_producto'], $datos['unidad_id'],$datos['unidad_dosificacion'],$suministro['cantidad_suministrada'])."</td>\n";
							$html .= "									<td valign=\"top\" width=\"%\" class=\"normal_10\" align=\"right\">".$this->SeleccionFactorConversion($datos['codigo_producto'], $datos['unidad_id'],$datos['unidad_dosificacion'],$suministro['cantidad_perdidas'])."</td>\n";
							$html .= "									<td valign=\"top\" width=\"%\" class=\"normal_10\" align=\"right\">\n";
							$html .= "										".$suministro['cantidad_aprovechada']."\n";
							$html .= "									</td>\n";
							$html .= "									<td valign=\"top\" width=\"%\" align=\"justify\" >\n";
							$html .= "										".$suministro['observacion_suministro']."&nbsp;\n";
							$html .= "									</td>\n";
							$html .= "								</tr>\n";
						}
						$html .= "							</table><br>\n";
						$html .= "						</td>\n";
						$html .= "					</tr>\n";
					}
					$html .= "				</table><br>\n";
				}
			}
			
			if($soluciones['1'])
			{
				$html .= "	<table align=\"center\" width=\"100%\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" rules=\"none\">\n";
				$html .= "		<tr>\n";
				$html .= "  		<td align=\"center\" class=\"normal_11N_menu\">SOLUCIONES ACTIVAS </td>\n";
				$html .= "		</tr>\n";
				if($soluciones['1'])
				{
					$html .= "		<tr>\n";
					$html .= "			<td>\n";
					$html .= "				<table align=\"center\" width=\"100%\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\">\n";
					$html .= "					<tr class=\"label\">\n";
					$html .= "						<td align=\"center\" colspan=\"4\">SOLUCIONES</td>\n";
					$html .= "					</tr>\n";
					$html .= "					<tr>\n";
					$html .= "						<td align=\"center\" class=\"label\" width=\"10%\">CÓDIGO</td>\n";
					$html .= "						<td align=\"center\" class=\"label\" width=\"40%\">PRODUCTO</td>\n";
					$html .= "						<td align=\"center\" class=\"label\" width=\"35%\">PRINCIPIO ACTIVO</td>\n";
					$html .= "						<td align=\"center\" class=\"label\" width=\"15%\">CANTIDAD</td>\n";
					$html .= "					</tr>\n";
					foreach($soluciones['1'] as $key=> $nivel1)
					{
						$html .= "					<tr class=\"normal_10\">\n";
						$html .= "						<td colspan=\"4\">\n";
						foreach($nivel1 as $key0=> $nivel2)
						{
							if($nivel2['sw_solucion'] == '1') 
							{
								$html .= "					<tr class=\"label\">\n";
								$html .= "						<td align=\"center\" >".$nivel2['codigo_producto']." ".$nivel2['item']."</td>\n";
								$html .= "						<td align=\"left\" >".$nivel2['producto']."</td>\n";
								$html .= "						<td align=\"left\" >".$nivel2['principio_activo']."</td>\n";
								$html .= "						<td align=\"right\">".$nivel2['dosis']." ".$nivel2['unidad_dosificacion']."</td>\n";
								$html .= "					</tr>\n";
							}
						}
						foreach($nivel1 as $key1=> $nivel2)
						{
							if($nivel2['sw_solucion'] == '0') 
							{
								$html .= "					<tr class=\"normal_10\">\n";
								$html .= "						<td align=\"center\" >".$nivel2['codigo_producto']." ".$nivel2['item']."</td>\n";
								$html .= "						<td align=\"left\" >".$nivel2['producto']."</td>\n";
								$html .= "						<td align=\"left\" >".$nivel2['principio_activo']."</td>\n";
								$html .= "						<td align=\"right\">".$nivel2['dosis']." ".$nivel2['unidad_dosificacion']."</td>\n";
								$html .= "					</tr>\n";
							}
						}
						$html .= "				<tr class=\"normal_10\">\n";
						$html .= "					<td colspan=\"4\">\n";
						$html .= "						<table class=\"normal_10\" >\n";
						$html .= "							<tr>\n";
						$html .= "								<td >CANTIDAD TOTAL </td>\n";
						$html .= "								<td >". $nivel2['cantidad']."</td><td colspan=\"2\"><b>Unidad(es)</b></td>\n";
						$html .= "							</tr>\n";
						$html .= "							<tr >\n";
						$html .= "								<td >VOLUMEN DE INFUSIÓN</td>\n";
						$html .= "								<td align=\"right\">". $nivel2['volumen_infusion']."</td><td colspan=\"2\">".$nivel1[$key1]['unidad_volumen']."</td>\n";
						$html .= "							</tr>\n";				
						$html .= "						</table>\n";
						$html .= "					</td>\n";
						$html .= "				</tr>\n"; 
					
						if( $nivel2['observacion'] != "")
						{
							$html .= "				<tr class=\"normal_10\">\n";
							$html .= "					<td colspan=\"4\" width=\"100%\" >\n";
							$html .= "						<table width=\"100%\" class=\"normal_10\">\n";
							$html .= "							<tr>\n";
							$html .= "								<td valign=\"top\" width=\"30%\">\n";
							$html .= "									OBSERVACIONES E INDICACIONES DE SUMINISTRO:</td>\n";
							$html .= "								</td>\n";
							$html .= "								<td valign=\"top\" width=\"70%\" align=\"justify\" colspan=\"4\" >\n";
							$html .= "									". $nivel2['observacion']."\n";
							$html .= "								</td>\n";
							$html .= "							</tr>\n";
							$html .= "						</table>\n";
							$html .= "					</td>\n";
							$html .= "				</tr>\n";
						}
						
						$html .= "				<tr>\n";
						$html .= "					<td align=\"center\" class=\"label\">FORMULÓ</td>\n";
						$html .= "					<td class=\"normal_10\">". $nivel2['nombre']."</td>\n";
						$html .= "					<td align=\"center\" class=\"label\">FECHA FORMULACIÓN6</td>\n";
						$html .= "					<td class=\"normal_10\">". $nivel2['fecha']."</td>\n";
						$html .= "				</tr>\n";
						
						if( $nivel2['usuario_suministro'])
						{
							$html .= "					<tr>\n";
							$html .= "						<td align=\"center\" colspan=\"4\">\n";
							$html .= "							<table width=\"100%\" class=\"normal_10\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\">\n";
							if( $nivel2['observacion_suministro'] != " ")
							{							
								$html .= "								<tr>\n";
								$html .= "									<td valign=\"top\" width=\"25%\" class=\"label\">\n";
								$html .= "										OBSERVACIONES DE SUMINISTRO\n";
								$html .= "									</td>\n";
								$html .= "									<td valign=\"top\" width=\"75%\" align=\"justify\" colspan=\"3\" >\n";
								$html .= "										". $nivel2['observacion_suministro']."&nbsp;\n";
								$html .= "									</td>\n";
								$html .= "								</tr>\n";
							}
							$html .= "								<tr>\n";
							$html .= "									<td valign=\"top\" width=\"25%\" class=\"label\">USUARIO SUMISTRO</td>\n";
							$html .= "									<td valign=\"top\" width=\"25%\" align=\"justify\">\n";
							$html .= "										". $nivel2['usuario_suministro']."\n";
							$html .= "									</td>\n";
							$html .= "									<td valign=\"top\" width=\"25%\" class=\"label\">FECHA SUMISTRO</td>\n";
							$html .= "									<td valign=\"top\" width=\"25%\" align=\"justify\">\n";
							$html .= "										". $nivel2['fecha_suministro']."\n";
							$html .= "									</td>\n";
							$html .= "								</tr>\n";
							$html .= "								<tr class=\"label\">\n";
							$html .= "									<td colspan=\"4\">\n";
							$html .= "										<table width=\"100%\" border=\"1\" rules=\"all\" cellspacing=\"0\" cellpading=\"0\">\n";
							$html .= "											<tr class=\"label\">\n";
							$html .= "												<td align=\"center\">PRODUCTO</td>\n";
							$html .= "												<td align=\"center\">CANTIADAD SUMINISTRADA</td>\n";
							$html .= "												<td align=\"center\">DESECHOS</td>\n";
							$html .= "												<td align=\"center\">ENTREGAS AL PACIENTE</td>\n";
							$html .= "											</tr>\n";
							foreach($nivel1 as $keyy => $nively)
							{
								$html .= "											<tr class=\"label\">\n";
								$html .= "												<td >".$nively['producto']."</td>\n";
								$html .= "												<td align=\"right\">".$this->SeleccionFactorConversion($nively['codigo_producto'], $nively['unidad_id'],$nively['unidad_dosificacion'],$nively['cantidad_suministrada'])."</td>\n";
								$html .= "												<td align=\"right\">".$this->SeleccionFactorConversion($nively['codigo_producto'], $nively['unidad_id'],$nively['unidad_dosificacion'],$nively['cantidad_perdidas'])."</td>\n";
								$html .= "												<td align=\"right\">".$nively['cantidad_aprovechada']."</td>\n";
								$html .= "											</tr>\n";
							}
							$html .= "										</table>\n";
							$html .= "									</td>\n";
							$html .= "								</tr>\n";
							$html .= "							</table>\n";
							$html .= "						</td>\n";
							$html .= "					</tr>\n";
						}
						$j++;
					}
					$html .= "				</table>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
				}
				$html .= "	</table><br>";
			}
			
			$soluciones = $this->ConsultaSolucionesFormuladas(2);
			
			if($soluciones['2'])
			{
				$html .= "	<table align=\"center\" width=\"100%\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" rules=\"none\">\n";
				$html .= "		<tr>\n";
				$html .= "  		<td align=\"center\" class=\"normal_11N_menu\">SOLUCIONES SUSPENDIDOS</td>\n";
				$html .= "		</tr>\n";
				
				if($soluciones['2'])
				{
					$html .= "		<tr>\n";
					$html .= "			<td>\n";
					$html .= "				<table align=\"center\" width=\"100%\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\">\n";
					$html .= "					<tr class=\"label\">\n";
					$html .= "						<td align=\"center\" colspan=\"4\">SOLUCIONES</td>\n";
					$html .= "					</tr>\n";
					$html .= "					<tr>\n";
					$html .= "						<td align=\"center\" class=\"label\" width=\"10%\">CÓDIGO</td>\n";
					$html .= "						<td align=\"center\" class=\"label\" width=\"40%\">PRODUCTO</td>\n";
					$html .= "						<td align=\"center\" class=\"label\" width=\"35%\">PRINCIPIO ACTIVO</td>\n";
					$html .= "						<td align=\"center\" class=\"label\" width=\"15%\">CANTIDAD</td>\n";
					$html .= "					</tr>\n";
					foreach($soluciones['2'] as $key=> $nivel1)
					{
						$html .= "					<tr class=\"normal_10\">\n";
						$html .= "						<td colspan=\"4\">\n";
						foreach($nivel1 as $key0=> $nivel2)
						{
							if($nivel2['sw_solucion'] == '1') 
							{
								$html .= "					<tr class=\"label\">\n";
								$html .= "						<td align=\"center\" >".$nivel2['codigo_producto']." ".$nivel2['item']."</td>\n";
								$html .= "						<td align=\"left\" >".$nivel2['producto']."</td>\n";
								$html .= "						<td align=\"left\" >".$nivel2['principio_activo']."</td>\n";
								$html .= "						<td align=\"right\">".$nivel2['dosis']." ".$nivel2['unidad_dosificacion']."</td>\n";
								$html .= "					</tr>\n";
							}
						}
						foreach($nivel1 as $key1=> $nivel2)
						{
							if($nivel2['sw_solucion'] == '0') 
							{
								$html .= "					<tr class=\"normal_10\">\n";
								$html .= "						<td align=\"center\" >".$nivel2['codigo_producto']." ".$nivel2['item']."</td>\n";
								$html .= "						<td align=\"left\" >".$nivel2['producto']."</td>\n";
								$html .= "						<td align=\"left\" >".$nivel2['principio_activo']."</td>\n";
								$html .= "						<td align=\"right\">".$nivel2['dosis']." ".$nivel2['unidad_dosificacion']."</td>\n";
								$html .= "					</tr>\n";
							}
						}
						$html .= "				<tr class=\"normal_10\">\n";
						$html .= "					<td colspan=\"4\">\n";
						$html .= "						<table class=\"normal_10\" >\n";
						$html .= "							<tr>\n";
						$html .= "								<td >CANTIDAD TOTAL </td>\n";
						$html .= "								<td >". $nivel2['cantidad']."</td><td colspan=\"2\"><b>Unidad(es)</b></td>\n";
						$html .= "							</tr>\n";
						$html .= "							<tr >\n";
						$html .= "								<td >VOLUMEN DE INFUSIÓN</td>\n";
						$html .= "								<td align=\"right\">". $nivel2['volumen_infusion']."</td><td colspan=\"2\">".$nivel1[$key1]['unidad_volumen']."</td>\n";
						$html .= "							</tr>\n";				
						$html .= "						</table>\n";
						$html .= "					</td>\n";
						$html .= "				</tr>\n"; 
					
						if( $nivel2['observacion'] != "")
						{
							$html .= "				<tr class=\"normal_10\">\n";
							$html .= "					<td colspan=\"4\" width=\"100%\" >\n";
							$html .= "						<table width=\"100%\" class=\"normal_10\">\n";
							$html .= "							<tr>\n";
							$html .= "								<td valign=\"top\" width=\"30%\">\n";
							$html .= "									OBSERVACIONES E INDICACIONES DE SUMINISTRO:</td>\n";
							$html .= "								</td>\n";
							$html .= "								<td valign=\"top\" width=\"70%\" align=\"justify\" colspan=\"4\" >\n";
							$html .= "									". $nivel2['observacion']."\n";
							$html .= "								</td>\n";
							$html .= "							</tr>\n";
							$html .= "						</table>\n";
							$html .= "					</td>\n";
							$html .= "				</tr>\n";
						}
						
						$html .= "				<tr>\n";
						$html .= "					<td align=\"center\" class=\"label\">FORMULÓ</td>\n";
						$html .= "					<td class=\"normal_10\">". $nivel2['nombre']."</td>\n";
						$html .= "					<td align=\"center\" class=\"label\">FECHA FORMULACIÓN**</td>\n";
						$html .= "					<td class=\"normal_10\">". $nivel2['fecha']."</td>\n";
						$html .= "				</tr>\n";
						
						if( $nivel2['usuario_suministro'])
						{
							$html .= "					<tr>\n";
							$html .= "						<td align=\"center\" colspan=\"4\">\n";
							$html .= "							<table width=\"100%\" class=\"normal_10\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\">\n";
							if( $nivel2['observacion_suministro'] != " ")
							{							
								$html .= "								<tr>\n";
								$html .= "									<td valign=\"top\" width=\"25%\" class=\"label\">\n";
								$html .= "										OBSERVACIONES DE SUMINISTRO\n";
								$html .= "									</td>\n";
								$html .= "									<td valign=\"top\" width=\"75%\" align=\"justify\" colspan=\"3\" >\n";
								$html .= "										". $nivel2['observacion_suministro']."&nbsp;\n";
								$html .= "									</td>\n";
								$html .= "								</tr>\n";
							}
							$html .= "								<tr>\n";
							$html .= "									<td valign=\"top\" width=\"25%\" class=\"label\">USUARIO SUMISTRO</td>\n";
							$html .= "									<td valign=\"top\" width=\"25%\" align=\"justify\">\n";
							$html .= "										". $nivel2['usuario_suministro']."\n";
							$html .= "									</td>\n";
							$html .= "									<td valign=\"top\" width=\"25%\" class=\"label\">FECHA SUMISTRO</td>\n";
							$html .= "									<td valign=\"top\" width=\"25%\" align=\"justify\">\n";
							$html .= "										". $nivel2['fecha_suministro']."\n";
							$html .= "									</td>\n";
							$html .= "								</tr>\n";
							$html .= "								<tr class=\"label\">\n";
							$html .= "									<td colspan=\"4\">\n";
							$html .= "										<table width=\"100%\" border=\"1\" rules=\"all\" cellspacing=\"0\" cellpading=\"0\">\n";
							$html .= "											<tr class=\"label\">\n";
							$html .= "												<td align=\"center\">PRODUCTO</td>\n";
							$html .= "												<td align=\"center\">CANTIADAD SUMINISTRADA</td>\n";
							$html .= "												<td align=\"center\">DESECHOS</td>\n";
							$html .= "												<td align=\"center\">ENTREGAS AL PACIENTE</td>\n";
							$html .= "											</tr>\n";
							foreach($nivel1 as $keyy=> $nively)
							{
								$html .= "											<tr class=\"label\">\n";
								$html .= "												<td >".$nively['producto']."</td>\n";
								$html .= "												<td align=\"right\">".$this->SeleccionFactorConversion($nively['codigo_producto'], $nively['unidad_id'],$nively['unidad_dosificacion'],$nively['cantidad_suministrada'])."</td>\n";
								$html .= "												<td align=\"right\">".$this->SeleccionFactorConversion($nively['codigo_producto'], $nively['unidad_id'],$nively['unidad_dosificacion'],$nively['cantidad_perdidas'])."</td>\n";
								$html .= "												<td align=\"right\">".$nively['cantidad_aprovechada']."</td>\n";
								$html .= "											</tr>\n";
							}
							$html .= "										</table>\n";
							$html .= "									</td>\n";
							$html .= "								</tr>\n";
							$html .= "							</table>\n";
							$html .= "						</td>\n";
							$html .= "					</tr>\n";
						}
						$j++;
					}
					$html .= "				</table>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
				}
				$html .= "	</table><br>";
			}
			
			if($formulados['0'] || $soluciones['0'])
			{
				$html .= "	<table align=\"center\" width=\"100%\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" rules=\"none\">\n";
				$html .= "		<tr>\n";
				$html .= "  		<td align=\"center\" class=\"normal_11N_menu\">SOLUCIONES FINALIZADAS</td>\n";
				$html .= "		</tr>\n";
				if($soluciones['0'])
				{
					$html .= "		<tr>\n";
					$html .= "			<td>\n";
					$html .= "				<table align=\"center\" width=\"100%\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\">\n";
					$html .= "					<tr class=\"label\">\n";
					$html .= "						<td align=\"center\" colspan=\"4\">SOLUCIONES</td>\n";
					$html .= "					</tr>\n";
					$html .= "					<tr>\n";
					$html .= "						<td align=\"center\" class=\"label\" width=\"10%\">CÓDIGO</td>\n";
					$html .= "						<td align=\"center\" class=\"label\" width=\"40%\">PRODUCTO</td>\n";
					$html .= "						<td align=\"center\" class=\"label\" width=\"35%\">PRINCIPIO ACTIVO</td>\n";
					$html .= "						<td align=\"center\" class=\"label\" width=\"15%\">CANTIDAD</td>\n";
					$html .= "					</tr>\n";

					foreach($soluciones['0'] as $key=> $nivel1)
					{
						$html .= "					<tr class=\"normal_10\">\n";
						$html .= "						<td colspan=\"4\">\n";
						foreach($nivel1 as $key0=> $nivel2)
						{
							if($nivel2['sw_solucion'] == '1') 
							{
								$html .= "					<tr class=\"label\">\n";
								$html .= "						<td align=\"center\" >".$nivel2['codigo_producto']." ".$nivel2['item']."</td>\n";
								$html .= "						<td align=\"left\" >".$nivel2['producto']."</td>\n";
								$html .= "						<td align=\"left\" >".$nivel2['principio_activo']."</td>\n";
								$html .= "						<td align=\"right\">".$nivel2['dosis']." ".$nivel2['unidad_dosificacion']."</td>\n";
								$html .= "					</tr>\n";
							}
						}
						foreach($nivel1 as $key1=> $nivel21)
						{
							if($nivel21['sw_solucion'] == '0') 
							{
								$html .= "					<tr class=\"normal_10\">\n";
								$html .= "						<td align=\"center\" >".$nivel21['codigo_producto']." ".$nivel21['item']."</td>\n";
								$html .= "						<td align=\"left\" >".$nivel21['producto']."</td>\n";
								$html .= "						<td align=\"left\" >".$nivel21['principio_activo']."</td>\n";
								$html .= "						<td align=\"right\">".$nivel21['dosis']." ".$nivel21['unidad_dosificacion']."</td>\n";
								$html .= "					</tr>\n";
							}
						}
						$html .= "				<tr class=\"normal_10\">\n";
						$html .= "					<td colspan=\"4\">\n";
						$html .= "						<table class=\"normal_10\" >\n";
						$html .= "							<tr>\n";
						$html .= "								<td >CANTIDAD TOTAL </td>\n";
						$html .= "								<td >". $nivel2['cantidad']."</td><td colspan=\"2\"><b>Unidad(es)</b></td>\n";
						$html .= "							</tr>\n";
						$html .= "							<tr >\n";
						$html .= "								<td >VOLUMEN DE INFUSIÓN</td>\n";
						$html .= "								<td align=\"right\">". $nivel2['volumen_infusion']."</td><td colspan=\"2\">".$nivel1[$key1]['unidad_volumen']."</td>\n";
						$html .= "							</tr>\n";				
						$html .= "						</table>\n";
						$html .= "					</td>\n";
						$html .= "				</tr>\n"; 
					
						if( $nivel2['observacion'] != "")
						{
							$html .= "				<tr class=\"normal_10\">\n";
							$html .= "					<td colspan=\"4\" width=\"100%\" >\n";
							$html .= "						<table width=\"100%\" class=\"normal_10\">\n";
							$html .= "							<tr>\n";
							$html .= "								<td valign=\"top\" width=\"30%\">\n";
							$html .= "									OBSERVACIONES E INDICACIONES DE SUMINISTRO:</td>\n";
							$html .= "								</td>\n";
							$html .= "								<td valign=\"top\" width=\"70%\" align=\"justify\" colspan=\"4\" >\n";
							$html .= "									". $nivel2['observacion']."\n";
							$html .= "								</td>\n";
							$html .= "							</tr>\n";
							$html .= "						</table>\n";
							$html .= "					</td>\n";
							$html .= "				</tr>\n";
						}
						
						$html .= "				<tr>\n";
						$html .= "					<td align=\"center\" class=\"label\">FORMULÓ</td>\n";
						$html .= "					<td class=\"normal_10\">". $nivel2['nombre']."</td>\n";
						$html .= "					<td align=\"center\" class=\"label\">FECHA FORMULACIÓN2</td>\n";
						$html .= "					<td class=\"normal_10\">". $nivel2['fecha']."</td>\n";
						$html .= "				</tr>\n";
						
						if( $nivel2['usuario_suministro'])
						{
							$html .= "					<tr>\n";
							$html .= "						<td align=\"center\" colspan=\"4\">\n";
							$html .= "							<table width=\"100%\" class=\"normal_10\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\">\n";
							if( $nivel2['observacion_suministro'] != " ")
							{							
								$html .= "								<tr>\n";
								$html .= "									<td valign=\"top\" width=\"25%\" class=\"label\">\n";
								$html .= "										OBSERVACIONES DE SUMINISTRO\n";
								$html .= "									</td>\n";
								$html .= "									<td valign=\"top\" width=\"75%\" align=\"justify\" colspan=\"3\" >\n";
								$html .= "										". $nivel2['observacion_suministro']."&nbsp;\n";
								$html .= "									</td>\n";
								$html .= "								</tr>\n";
							}
							$html .= "								<tr>\n";
							$html .= "									<td valign=\"top\" width=\"25%\" class=\"label\">USUARIO SUMISTRO</td>\n";
							$html .= "									<td valign=\"top\" width=\"25%\" align=\"justify\">\n";
							$html .= "										". $nivel2['usuario_suministro']."\n";
							$html .= "									</td>\n";
							$html .= "									<td valign=\"top\" width=\"25%\" class=\"label\">FECHA SUMISTRO</td>\n";
							$html .= "									<td valign=\"top\" width=\"25%\" align=\"justify\">\n";
							$html .= "										". $nivel2['fecha_suministro']."\n";
							$html .= "									</td>\n";
							$html .= "								</tr>\n";
							$html .= "								<tr class=\"label\">\n";
							$html .= "									<td colspan=\"4\">\n";
							$html .= "										<table width=\"100%\" border=\"1\" rules=\"all\" cellspacing=\"0\" cellpading=\"0\">\n";
							$html .= "											<tr class=\"label\">\n";
							$html .= "												<td align=\"center\">PRODUCTO</td>\n";
							$html .= "												<td align=\"center\">CANTIADAD SUMINISTRADA</td>\n";
							$html .= "												<td align=\"center\">DESECHOS</td>\n";
							$html .= "												<td align=\"center\">ENTREGAS AL PACIENTE</td>\n";
							$html .= "											</tr>\n";
							foreach($nivel1 as $keyy=> $nively)
							{
								$html .= "											<tr class=\"label\">\n";
								$html .= "												<td >".$nively['producto']."</td>\n";
								$html .= "												<td align=\"right\">".$this->SeleccionFactorConversion($nively['codigo_producto'], $nively['unidad_id'],$nively['unidad_dosificacion'],$nively['cantidad_suministrada'])."</td>\n";
								$html .= "												<td align=\"right\">".$this->SeleccionFactorConversion($nively['codigo_producto'], $nively['unidad_id'],$nively['unidad_dosificacion'],$nively['cantidad_perdidas'])."</td>\n";
								$html .= "												<td align=\"right\">".$nively['cantidad_aprovechada']."</td>\n";
								$html .= "											</tr>\n";
							}
							$html .= "										</table>\n";
							$html .= "									</td>\n";
							$html .= "								</tr>\n";
							$html .= "							</table>\n";
							$html .= "						</td>\n";
							$html .= "					</tr>\n";
						}
						$j++;
					}
					$html .= "				</table>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
				}
				$html .= "	</table><br>";
			}
			$html .= $this->FormaConsultaCanastaMedica();
			return $html;
		}
		/********************************************************************************
    * Consulta de los medicamentos de la canasta de Cirugia.
    * Pinta los medicamentos suministrados durante una cirugia realizada al paciente.
		*********************************************************************************/
    function FormaConsultaCanastaMedica()
    {
			$medicamentos = $this->ConsultaCanastaMedica();
			if (!empty ($medicamentos))
			{
				$salida .= "<table width=\"100%\" style=\"border:1px solid #000000\" align=\"center\" cellspacing=\"0\" rules=\"all\">\n";
				$salida .= "	<tr class=\"normal_10N\">";
				$salida .= "		<td align=\"center\"colspan=\"6\">SUMINISTRO DE MEDICAMENTOS (CANASTA DE CIRUGIA)</td>";
				$salida .= "	</tr>\n";
				$salida .= "	<tr class=\"label\">\n";
				$salida .= "		<td align=\"center\">Fecha y hora</td>";
				$salida .= "		<td align=\"center\">Codigo Med.</td>";
				$salida .= "		<td align=\"center\">Nombre Med.</td>";
				$salida .= "		<td align=\"center\">Cantidad</td>";
				$salida .= "		<td align=\"center\">Usuario Orden</td>";
				$salida .= "		<td align=\"center\">Usuario Suministro</td>";
				$salida .= "	</tr>\n";
				for($i=0; $i<sizeof($medicamentos); $i++)
				{
					$fecha = $this->FechaStamp($medicamentos[$i][fecha_registro]);
					$hora = $this->HoraStamp($medicamentos[$i][fecha_registro]);
					
					$salida .= "	<tr class=\"normal_10\">\n";
					$salida .= "		<td align=\"center\">".$fecha." - ".$hora."</td>";
					$salida .= "		<td align=\"center\">".$medicamentos[$i]['codigo_producto']."</td>";
					$salida .= "		<td align=\"center\">".$medicamentos[$i]['descripcion']."</td>";
					$salida .= "		<td align=\"center\">".ceil($medicamentos[$i]['cantidad_suministrada'])."</td>";
					$salida .= "		<td align=\"center\">".$medicamentos[$i]['us_orden']."</td>";
					$salida .= "		<td align=\"center\">".$medicamentos[$i]['us_suministro']."</td>";
					$salida .= "	</tr>";
					if($medicamentos[$i]['observacion'] != $medicamentos[$i+1]['observacion'])
					{
						$salida .= "	<tr>\n";
						$salida .= "		<td align=\"center\" class=\"label\">OBSERVACIONES</td>";
						$salida .= "		<td align=\"justify\" class=\"normal_10\" colspan=\"5\">".$medicamentos[$i]['observacion']."</td>";
						$salida .= "	</tr>\n";
					}
				}
				$salida .= "</table><br>";
			}
			return $salida;
    }
		/********************************************************************************
		*
		*********************************************************************************/
		function FrmConsulta()
		{
			$formulacion = $this->ConsultaMedicamentosFormulados();
			$soluciones = $this->ConsultaSolucionesFormuladas(1);
			$html = "<br>";
			
			$formulados = $formulacion['formulacion'];
			if(sizeof($formulados) > 0)
			{
				$modifica = $this->ConsultaAccionesMedicamentos();
				$datos = array();
				$html .= "	<table align=\"center\" width=\"100%\" class=\"modulo_table_list\">\n";
				$html .= "		<tr>\n";
				$html .= "  		<td align=\"center\" class=\"modulo_table_list_title\">FORMULACION DE MEDICAMENTOS</td>\n";
				$html .= "		</tr>\n";
				$html .= "		<tr>\n";
				$html .= "			<td>\n";

				foreach($formulados as $key => $datos)
				{
					$html .= "				<table align=\"center\" width=\"100%\" class=\"modulo_table_list\">\n";
					$html .= "					<tr class=\"modulo_table_list_title\">\n";
					$html .= "						<td width=\"10%\">CÓDIGO</td>\n";
					$html .= "						<td width=\"50%\">PRODUCTO</td>\n";
					$html .= "						<td colspan=\"2\" align=\"center\" width=\"30%\">PRINCIPIO ACTIVO</td>\n";
					$html .= "					</tr>\n";
					$html .= "					<tr class=\"modulo_list_claro\" >\n";
					$html .= "						<td align=\"center\">".$datos['codigo_producto']." ".$datos['item']."</td>\n";
					$html .= "						<td >".$datos['producto']."</td>\n";
					$html .= "						<td colspan=\"2\">".$datos['principio_activo']."</td>\n";
					$html .= "					</tr>\n";
					$html .= "					<tr class=\"modulo_list_claro\">\n";
					$html .= "						<td align=\"center\" class=\"label\">FORMULÓ</td>\n";
					$html .= "						<td class=\"normal_10\">".$datos['usuario']."</td>\n";
					$html .= "						<td align=\"center\" class=\"label\">FECHA FORMULACIÓN: ".$datos['fecha']."</td>\n";
					$html .= "					</tr>\n";
					$html .= "					<tr class=\"modulo_list_claro\" >\n";
					$html .= "						<td colspan=\"4\">\n";
					$html .= "							<table class=\"modulo_list_claro\" >\n";
					$html .= "								<tr>\n";
					$html .= "									<td >VIA DE ADMINISTRACIÓN: </td>\n";
					$html .= "									<td colspan=\"3\">".$datos['nombre']."</td>\n";
					$html .= "								</tr>\n";
					$html .= "								<tr>\n";
					$html .= "									<td >DOSIS</td>\n";
					$html .= "									<td align=\"right\">".$datos['dosis']."</td><td>".$datos['unidad_dosificacion']."</td>\n";
					$html .= "									<td align=\"left\">".$datos['frecuencia']."</td>\n";
					$html .= "								</tr>\n";				
					$html .= "								<tr >\n";
					$html .= "									<td >CANTIDAD</td>\n";
					$html .= "									<td align=\"right\">".$datos['cantidad']."</td><td colspan=\"2\">".$datos['umm']."</td>\n";
					$html .= "								</tr>\n";
					$html .= "							</table>\n";
					$html .= "						</td>\n";
					$html .= "					</tr>\n";
						
					if($datos['observacion'] != "")
					{
						$html .= "					<tr class=\"modulo_list_claro\">\n";
						$html .= "						<td colspan=\"4\">\n";
						$html .= "							<table width=\"100%\" class=\"modulo_list_claro\">\n";
						$html .= "								<tr>\n";
						$html .= "									<td valign=\"top\" width=\"30%\">\n";
						$html .= "										OBSERVACIONES E INDICACIONES DE SUMINISTRO</td>\n";
						$html .= "									</td>\n";
						$html .= "									<td valign=\"top\" width=\"70%\" align=\"justify\" colspan=\"4\" >\n";
						$html .= "										".$datos['observacion']."\n";
						$html .= "									</td>\n";
						$html .= "								</tr>\n";
						$html .= "							</table>\n";
						$html .= "						</td>\n";
						$html .= "					</tr>\n";
					}
					
					if(sizeof($modifica[$key]) > 0)
					{
						$html .= "					<tr>\n";
						$html .= "						<td colspan=\"4\">\n";
						$html .= "							<table width=\"100%\"  class=\"modulo_table_list\">\n";
						$html .= "								<tr class=\"modulo_table_list_title\">\n";
						$html .= "									<td align=\"center\" colspan=\"3\">HISTORICO FORMULACION</td>\n";
						$html .= "								</tr>\n";
						$html .= "								<tr  class=\"modulo_list_claro\" >\n";
						$html .= "									<td align=\"center\" class=\"normal_10AN\" width=\"25%\">FECHA</td>\n";
						$html .= "									<td align=\"center\" class=\"normal_10AN\" width=\"40%\">FORMULÓ</td>\n";
						$html .= "									<td align=\"center\" class=\"normal_10AN\" width=\"35%\">ACCION</td>\n";
						$html .= "								</tr>\n";

						foreach($modifica[$key] as $key => $datos1)
						{
							$html .= "								<tr class=\"modulo_list_claro\" >\n";
							$html .= "									<td align=\"center\" >".$datos1['fecha']."</td>\n";
							$html .= "									<td align=\"center\" >".$datos1['usuario']."</td>\n";
							$estado = "";
							if($datos1['sw_estado'] == '1')
								$estado = "ACTIVACIÓN / MODIFACIÓN";
							else if($datos1['sw_estado'] == '2')
									$estado = "SUSPENSIÓN";
								else
									$estado = "FINALIZACIÓN";
								
							$html .= "									<td align=\"center\" class=\"label_mark\">".$estado."</td>\n";
							$html .= "								</tr>\n";
							
							if(	$datos1['sw_observacion'] == '1' || $datos1['sw_via_administracion_id'] == '1' || $datos1['sw_unidad_dosificacion'] == '1'
									|| $datos1['sw_dosis'] == '1' ||	$datos1['sw_frecuencia'] == '1' || $datos1['sw_cantidad'] == '1'
								)
							{
								$html .= "					<tr class=\"modulo_list_claro\" >\n";
								$html .= "						<td align=\"center\" class=\"label\">CAMBIOS</td>\n";
								$html .= "						<td colspan=\"2\">\n";
								$html .= "							<table class=\"modulo_list_claro\" >\n";
								if($datos1['sw_via_administracion_id'] == '1')
								{
									$html .= "								<tr>\n";
									$html .= "									<td >VIA DE ADMINISTRACIÓN: </td>\n";
									$html .= "									<td colspan=\"3\">".$datos1['nombre']."</td>\n";
									$html .= "								</tr>\n";
								}
								if($datos1['sw_dosis'] == '1' ||	$datos1['sw_frecuencia'] == '1' || $datos1['sw_unidad_dosificacion'] == '1')
								{
									$html .= "								<tr>\n";
									$html .= "									<td >DOSIS</td>\n";
									$html .= "									<td align=\"right\">".$datos1['dosis']."</td><td>".$datos1['unidad_dosificacion']."</td>\n";
									$html .= "									<td align=\"left\">".$datos1['frecuencia']."</td>\n";
									$html .= "								</tr>\n";
								}
								if($datos1['sw_cantidad'] == '1')
								{
									$html .= "								<tr >\n";
									$html .= "									<td >CANTIDAD</td>\n";
									$html .= "									<td align=\"right\">".$datos1['cantidad']."</td><td colspan=\"2\">".$datos['umm']."</td>\n";
									$html .= "								</tr>\n";
								}	
								if($datos1['observacion'] != "" && $datos1['sw_observacion'] == '1' )
								{
									$html .= "								<tr>\n";
									$html .= "									<td valign=\"top\" width=\"30%\">\n";
									$html .= "										OBSERVACIONES E INDICACIONES DE SUMINISTRO</td>\n";
									$html .= "									</td>\n";
									$html .= "									<td valign=\"top\" width=\"70%\" align=\"justify\" colspan=\"4\" >\n";
									$html .= "										".$datos1['observacion']."\n";
									$html .= "									</td>\n";
									$html .= "								</tr>\n";
								}
								$html .= "							</table>\n";
								$html .= "						</td>\n";
								$html .= "					</tr>\n";
							}
						}
						$html .= "							</table>\n";
						$html .= "						</td>\n";
						$html .= "					</tr>\n";
					}

					if($datos['usuario_suministro'])
					{	
						$html .= "		<tr>\n";
						$html .= "			<td colspan=\"4\">\n";

						$html .= "							<table width=\"100%\" class=\"modulo_table_list\">\n";
						$html .= "								<tr class=\"modulo_table_list_title\">\n";
						$html .= "									<td align=\"center\" colspan=\"6\">REGISTRO DE ADMINISTRACION DE MEDICAMENTOS</td>\n";
						$html .= "								</tr>\n";
						$html .= "								<tr class=\"modulo_list_claro\" >\n";
						$html .= "									<td align=\"center\" class=\"normal_10AN\" width=\"%\">Fecha</td>\n";
						$html .= "									<td align=\"center\" class=\"normal_10AN\" width=\"%\">Usuario</td>\n";
						$html .= "									<td align=\"center\" class=\"normal_10AN\" width=\"%\">Cantidad</td>\n";
						$html .= "									<td align=\"center\" class=\"normal_10AN\" width=\"%\">Desechos</td>\n";
						$html .= "									<td align=\"center\" class=\"normal_10AN\" width=\"%\">Entregas Al Paciente</td>\n";
						$html .= "									<td align=\"center\" class=\"normal_10AN\" width=\"30%\">Observación</td>\n";
						$html .= "								</tr>\n";
						foreach($formulacion['suministro'][$datos['num_reg']] as $keyII => $suministro)
						{
							$html .= "								<tr class=\"modulo_list_claro\">\n";
							$html .= "									<td valign=\"top\" width=\"%\" align=\"center\">\n";
							$html .= "										".$suministro['fecha_suministro']."\n";
							$html .= "									</td>\n";
							$html .= "									<td valign=\"top\" width=\"%\" align=\"justify\">\n";
							$html .= "										".$suministro['usuario_suministro']."\n";
							$html .= "									</td>\n";							
							$html .= "									<td valign=\"top\" width=\"%\" class=\"normal_10\" align=\"right\">".$this->SeleccionFactorConversion($datos['codigo_producto'], $datos['unidad_id'],$datos['unidad_dosificacion'],$suministro['cantidad_suministrada'])."</td>\n";
							$html .= "									<td valign=\"top\" width=\"%\" class=\"normal_10\" align=\"right\">".$this->SeleccionFactorConversion($datos['codigo_producto'], $datos['unidad_id'],$datos['unidad_dosificacion'],$suministro['cantidad_perdidas'])."</td>\n";
							$html .= "									<td valign=\"top\" width=\"%\" class=\"normal_10\" align=\"right\">\n";
							$html .= "										".$suministro['cantidad_aprovechada']."\n";
							$html .= "									</td>\n";
							$html .= "									<td valign=\"top\" width=\"%\" align=\"justify\" colspan=\"3\" >\n";
							$html .= "										".$suministro['observacion_suministro']."&nbsp;\n";
							$html .= "									</td>\n";
							$html .= "								</tr>\n";
						}
						$html .= "							</table>\n";
						$html .= "						</td>\n";
						$html .= "					</tr>\n";
					}
					$html .= "	</table><br>\n";
				}
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$html .= "	</table><br>\n";
			}
			
			if($soluciones['1'])
			{
				$html .= "	<table align=\"center\" width=\"95%\" class=\"modulo_table_list\">\n";
				$html .= "		<tr>\n";
				$html .= "  		<td align=\"center\" class=\"modulo_table_list_title\">SOLUCIONES ACTIVAS </td>\n";
				$html .= "		</tr>\n";
				
				if($soluciones['1'])
				{
					$html .= "		<tr>\n";
					$html .= "			<td>\n";
					$html .= "				<table align=\"center\" width=\"100%\" class=\"modulo_table_list\">\n";
					$html .= "					<tr class=\"modulo_table_list_title\">\n";
					$html .= "						<td align=\"center\" colspan=\"4\">SOLUCIONES</td>\n";
					$html .= "					</tr>\n";
					$html .= "					<tr class=\"modulo_table_list_title\">\n";
					$html .= "						<td width=\"10%\">CÓDIGO</td>\n";
					$html .= "						<td width=\"40%\">PRODUCTO</td>\n";
					$html .= "						<td width=\"35%\">PRINCIPIO ACTIVO</td>\n";
					$html .= "						<td width=\"15%\">CANTIDAD</td>\n";
					$html .= "					</tr>\n";
					foreach($soluciones['1'] as $key=> $nivel1)
					{
						foreach($nivel1 as $key0=> $nivel2)
						{
							if($nivel2['sw_solucion'] == '1') 
							{
								$html .= "					<tr class=\"modulo_list_claro\">\n";
								$html .= "						<td class=\"normal_10AN\" align=\"center\" >".$nivel2['codigo_producto']." ".$nivel2['item']."</td>\n";
								$html .= "						<td class=\"normal_10AN\" align=\"left\" >".$nivel2['producto']."</td>\n";
								$html .= "						<td class=\"normal_10AN\" align=\"left\" >".$nivel2['principio_activo']."</td>\n";
								$html .= "						<td class=\"normal_10AN\" align=\"right\">".$nivel2['dosis']." ".$nivel2['unidad_dosificacion']."</td>\n";
								$html .= "					</tr>\n";
							}
						}
						foreach($nivel1 as $key1=> $nivel2)
						{
							if($nivel2['sw_solucion'] == '0') 
							{
								$html .= "					<tr class=\"modulo_list_claro\">\n";
								$html .= "						<td class=\"normal_10A\" align=\"center\" >".$nivel2['codigo_producto']." ".$nivel2['item']."</td>\n";
								$html .= "						<td class=\"normal_10A\" align=\"left\" >".$nivel2['producto']."</td>\n";
								$html .= "						<td class=\"normal_10A\" align=\"left\" >".$nivel2['principio_activo']."</td>\n";
								$html .= "						<td class=\"normal_10A\" align=\"right\">".$nivel2['dosis']." ".$nivel2['unidad_dosificacion']."</td>\n";
								$html .= "					</tr>\n";
							}
						}
						$html .= "				<tr class=\"modulo_list_claro\">\n";
						$html .= "					<td colspan=\"4\">\n";
						$html .= "						<table class=\"normal_10\" >\n";
						$html .= "							<tr>\n";
						$html .= "								<td >CANTIDAD TOTAL </td>\n";
						$html .= "								<td >". $nivel2['cantidad']."</td><td colspan=\"2\"><b>Unidad(es)</b></td>\n";
						$html .= "							</tr>\n";
						$html .= "							<tr >\n";
						$html .= "								<td >VOLUMEN DE INFUSIÓN</td>\n";
						$html .= "								<td align=\"right\">". $nivel2['volumen_infusion']."</td><td colspan=\"2\">".$nivel1[$key1]['unidad_volumen']."</td>\n";
						$html .= "							</tr>\n";				
						$html .= "						</table>\n";
						$html .= "					</td>\n";
						$html .= "				</tr>\n"; 
					
						if( $nivel2['observacion'] != "")
						{
							$html .= "				<tr class=\"modulo_list_claro\">\n";
							$html .= "					<td colspan=\"4\" width=\"100%\" >\n";
							$html .= "						<table width=\"100%\" class=\"normal_10\">\n";
							$html .= "							<tr>\n";
							$html .= "								<td valign=\"top\" width=\"30%\">\n";
							$html .= "									OBSERVACIONES E INDICACIONES DE SUMINISTRO:</td>\n";
							$html .= "								</td>\n";
							$html .= "								<td valign=\"top\" width=\"70%\" align=\"justify\" colspan=\"4\" >\n";
							$html .= "									". $nivel2['observacion']."\n";
							$html .= "								</td>\n";
							$html .= "							</tr>\n";
							$html .= "						</table>\n";
							$html .= "					</td>\n";
							$html .= "				</tr>\n";
						}
						
						$html .= "				<tr class=\"modulo_list_claro\" >\n";
						$html .= "					<td align=\"center\" class=\"label\">FORMULÓ</td>\n";
						$html .= "					<td class=\"normal_10\">". $nivel2['nombre']."</td>\n";
						$html .= "					<td align=\"center\" class=\"label\">FECHA FORMULACIÓN3</td>\n";
						$html .= "					<td class=\"normal_10\">". $nivel2['fecha']."</td>\n";
						$html .= "				</tr>\n";
						
						if( $nivel2['usuario_suministro'])
						{
							$html .= "					<tr>\n";
							$html .= "						<td align=\"center\" colspan=\"4\">\n";
							$html .= "							<table width=\"100%\" class=\"modulo_list_claro\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\">\n";
							if( $nivel2['observacion_suministro'] != " ")
							{							
								$html .= "								<tr>\n";
								$html .= "									<td valign=\"top\" width=\"25%\" class=\"label\">\n";
								$html .= "										OBSERVACIONES DE SUMINISTRO\n";
								$html .= "									</td>\n";
								$html .= "									<td valign=\"top\" width=\"75%\" align=\"justify\" colspan=\"3\" >\n";
								$html .= "										". $nivel2['observacion_suministro']."&nbsp;\n";
								$html .= "									</td>\n";
								$html .= "								</tr>\n";
							}
							$html .= "								<tr>\n";
							$html .= "									<td valign=\"top\" width=\"25%\" class=\"label\">USUARIO SUMISTRO</td>\n";
							$html .= "									<td valign=\"top\" width=\"25%\" align=\"justify\">\n";
							$html .= "										". $nivel2['usuario_suministro']."\n";
							$html .= "									</td>\n";
							$html .= "									<td valign=\"top\" width=\"25%\" class=\"label\">FECHA SUMISTRO</td>\n";
							$html .= "									<td valign=\"top\" width=\"25%\" align=\"justify\">\n";
							$html .= "										". $nivel2['fecha_suministro']."\n";
							$html .= "									</td>\n";
							$html .= "								</tr>\n";
							$html .= "								<tr class=\"label\">\n";
							$html .= "									<td colspan=\"4\">\n";
							$html .= "										<table width=\"100%\" border=\"1\" rules=\"all\" cellspacing=\"0\" cellpading=\"0\">\n";
							$html .= "											<tr class=\"label\">\n";
							$html .= "												<td align=\"center\">PRODUCTO</td>\n";
							$html .= "												<td align=\"center\">CANTIADAD SUMINISTRADA</td>\n";
							$html .= "												<td align=\"center\">DESECHOS</td>\n";
							$html .= "												<td align=\"center\">ENTREGAS AL PACIENTE</td>\n";
							$html .= "											</tr>\n";
							foreach($nivel1 as $keyy => $nively)
							{
								$html .= "											<tr class=\"label\">\n";
								$html .= "												<td >".$nively['producto']."</td>\n";
								$html .= "												<td align=\"right\">".$this->SeleccionFactorConversion($nively['codigo_producto'], $nively['unidad_id'],$nively['unidad_dosificacion'],$nively['cantidad_suministrada'])."</td>\n";
								$html .= "												<td align=\"right\">".$this->SeleccionFactorConversion($nively['codigo_producto'], $nively['unidad_id'],$nively['unidad_dosificacion'],$nively['cantidad_perdidas'])."</td>\n";
								$html .= "												<td align=\"right\">".$nively['cantidad_aprovechada']."</td>\n";
								$html .= "											</tr>\n";
							}
							$html .= "										</table>\n";
							$html .= "									</td>\n";
							$html .= "								</tr>\n";
							$html .= "							</table>\n";
							$html .= "						</td>\n";
							$html .= "					</tr>\n";
						}
						$j++;
					}
					$html .= "				</table>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
				}
				$html .= "	</table><br>";
			}
			
			$soluciones = $this->ConsultaSolucionesFormuladas(2);
			
			if($soluciones['2'])
			{
				$html .= "	<table align=\"center\" width=\"95%\" class=\"modulo_table_list\">\n";
				$html .= "		<tr>\n";
				$html .= "  		<td align=\"center\" class=\"modulo_table_list_title\">SOLUCIONES SUSPENDIDAS </td>\n";
				$html .= "		</tr>\n";
				if($soluciones['2'])
				{
					$html .= "		<tr>\n";
					$html .= "			<td>\n";
					$html .= "				<table align=\"center\" width=\"100%\" class=\"modulo_table_list\">\n";
					$html .= "					<tr class=\"modulo_table_list_title\">\n";
					$html .= "						<td align=\"center\" colspan=\"4\">SOLUCIONES</td>\n";
					$html .= "					</tr>\n";
					$html .= "					<tr class=\"modulo_table_list_title\">\n";
					$html .= "						<td width=\"10%\">CÓDIGO</td>\n";
					$html .= "						<td width=\"40%\">PRODUCTO</td>\n";
					$html .= "						<td width=\"35%\">PRINCIPIO ACTIVO</td>\n";
					$html .= "						<td width=\"15%\">CANTIDAD</td>\n";
					$html .= "					</tr>\n";
					foreach($soluciones['2'] as $key=> $nivel1)
					{
						foreach($nivel1 as $key0=> $nivel2)
						{
							if($nivel2['sw_solucion'] == '1') 
							{
								$html .= "					<tr class=\"modulo_list_claro\">\n";
								$html .= "						<td class=\"normal_10AN\" align=\"center\" >".$nivel2['codigo_producto']." ".$nivel2['item']."</td>\n";
								$html .= "						<td class=\"normal_10AN\" align=\"left\" >".$nivel2['producto']."</td>\n";
								$html .= "						<td class=\"normal_10AN\" align=\"left\" >".$nivel2['principio_activo']."</td>\n";
								$html .= "						<td class=\"normal_10AN\" align=\"right\">".$nivel2['dosis']." ".$nivel2['unidad_dosificacion']."</td>\n";
								$html .= "					</tr>\n";
							}
						}
						foreach($nivel1 as $key1=> $nivel2)
						{
							if($nivel2['sw_solucion'] == '0') 
							{
								$html .= "					<tr class=\"modulo_list_claro\">\n";
								$html .= "						<td class=\"normal_10A\" align=\"center\" >".$nivel2['codigo_producto']." ".$nivel2['item']."</td>\n";
								$html .= "						<td class=\"normal_10A\" align=\"left\" >".$nivel2['producto']."</td>\n";
								$html .= "						<td class=\"normal_10A\" align=\"left\" >".$nivel2['principio_activo']."</td>\n";
								$html .= "						<td class=\"normal_10A\" align=\"right\">".$nivel2['dosis']." ".$nivel2['unidad_dosificacion']."</td>\n";
								$html .= "					</tr>\n";
							}
						}
						$html .= "				<tr class=\"modulo_list_claro\">\n";
						$html .= "					<td colspan=\"4\">\n";
						$html .= "						<table class=\"normal_10\" >\n";
						$html .= "							<tr>\n";
						$html .= "								<td >CANTIDAD TOTAL </td>\n";
						$html .= "								<td >". $nivel2['cantidad']."</td><td colspan=\"2\"><b>Unidad(es)</b></td>\n";
						$html .= "							</tr>\n";
						$html .= "							<tr >\n";
						$html .= "								<td >VOLUMEN DE INFUSIÓN</td>\n";
						$html .= "								<td align=\"right\">". $nivel2['volumen_infusion']."</td><td colspan=\"2\">".$nivel1[$key1]['unidad_volumen']."</td>\n";
						$html .= "							</tr>\n";				
						$html .= "						</table>\n";
						$html .= "					</td>\n";
						$html .= "				</tr>\n"; 
					
						if( $nivel2['observacion'] != "")
						{
							$html .= "				<tr class=\"modulo_list_claro\">\n";
							$html .= "					<td colspan=\"4\" width=\"100%\" >\n";
							$html .= "						<table width=\"100%\" class=\"normal_10\">\n";
							$html .= "							<tr>\n";
							$html .= "								<td valign=\"top\" width=\"30%\">\n";
							$html .= "									OBSERVACIONES E INDICACIONES DE SUMINISTRO:</td>\n";
							$html .= "								</td>\n";
							$html .= "								<td valign=\"top\" width=\"70%\" align=\"justify\" colspan=\"4\" >\n";
							$html .= "									". $nivel2['observacion']."\n";
							$html .= "								</td>\n";
							$html .= "							</tr>\n";
							$html .= "						</table>\n";
							$html .= "					</td>\n";
							$html .= "				</tr>\n";
						}
						
						$html .= "				<tr class=\"modulo_list_claro\" >\n";
						$html .= "					<td align=\"center\" class=\"label\">FORMULÓ</td>\n";
						$html .= "					<td class=\"normal_10\">". $nivel2['nombre']."</td>\n";
						$html .= "					<td align=\"center\" class=\"label\">FECHA FORMULACIÓN4</td>\n";
						$html .= "					<td class=\"normal_10\">". $nivel2['fecha']."</td>\n";
						$html .= "				</tr>\n";
						
						if( $nivel2['usuario_suministro'])
						{
							$html .= "					<tr>\n";
							$html .= "						<td align=\"center\" colspan=\"4\">\n";
							$html .= "							<table width=\"100%\" class=\"modulo_list_claro\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\">\n";
							if( $nivel2['observacion_suministro'] != " ")
							{							
								$html .= "								<tr>\n";
								$html .= "									<td valign=\"top\" width=\"25%\" class=\"label\">\n";
								$html .= "										OBSERVACIONES DE SUMINISTRO\n";
								$html .= "									</td>\n";
								$html .= "									<td valign=\"top\" width=\"75%\" align=\"justify\" colspan=\"3\" >\n";
								$html .= "										". $nivel2['observacion_suministro']."&nbsp;\n";
								$html .= "									</td>\n";
								$html .= "								</tr>\n";
							}
							$html .= "								<tr>\n";
							$html .= "									<td valign=\"top\" width=\"25%\" class=\"label\">USUARIO SUMISTRO</td>\n";
							$html .= "									<td valign=\"top\" width=\"25%\" align=\"justify\">\n";
							$html .= "										". $nivel2['usuario_suministro']."\n";
							$html .= "									</td>\n";
							$html .= "									<td valign=\"top\" width=\"25%\" class=\"label\">FECHA SUMISTRO</td>\n";
							$html .= "									<td valign=\"top\" width=\"25%\" align=\"justify\">\n";
							$html .= "										". $nivel2['fecha_suministro']."\n";
							$html .= "									</td>\n";
							$html .= "								</tr>\n";
							$html .= "								<tr class=\"label\">\n";
							$html .= "									<td colspan=\"4\">\n";
							$html .= "										<table width=\"100%\" border=\"1\" rules=\"all\" cellspacing=\"0\" cellpading=\"0\">\n";
							$html .= "											<tr class=\"label\">\n";
							$html .= "												<td align=\"center\">PRODUCTO</td>\n";
							$html .= "												<td align=\"center\">CANTIADAD SUMINISTRADA</td>\n";
							$html .= "												<td align=\"center\">DESECHOS</td>\n";
							$html .= "												<td align=\"center\">ENTREGAS AL PACIENTE</td>\n";
							$html .= "											</tr>\n";
							foreach($nivel1 as $keyy => $nively)
							{
								$html .= "											<tr class=\"label\">\n";
								$html .= "												<td >".$nively['producto']."</td>\n";
								$html .= "												<td align=\"right\">".$this->SeleccionFactorConversion($nively['codigo_producto'], $nively['unidad_id'],$nively['unidad_dosificacion'],$nively['cantidad_suministrada'])."</td>\n";
								$html .= "												<td align=\"right\">".$this->SeleccionFactorConversion($nively['codigo_producto'], $nively['unidad_id'],$nively['unidad_dosificacion'],$nively['cantidad_perdidas'])."</td>\n";
								$html .= "												<td align=\"right\">".$nively['cantidad_aprovechada']."</td>\n";
								$html .= "											</tr>\n";
							}
							$html .= "										</table>\n";
							$html .= "									</td>\n";
							$html .= "								</tr>\n";
							$html .= "							</table>\n";
							$html .= "						</td>\n";
							$html .= "					</tr>\n";
						}
						$j++;
					}
					$html .= "				</table>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
				}
				$html .= "	</table><br>";
			}
			
			if($soluciones['0'])
			{
				$html .= "	<table align=\"center\" width=\"95%\" class=\"modulo_table_list\">\n";
				$html .= "		<tr>\n";
				$html .= "  		<td align=\"center\" class=\"modulo_table_list_title\">SOLUCIONES FINALIZADAS </td>\n";
				$html .= "		</tr>\n";
				if($soluciones['0'])
				{
					$html .= "		<tr>\n";
					$html .= "			<td>\n";
					$html .= "				<table align=\"center\" width=\"100%\" class=\"modulo_table_list\">\n";
					$html .= "					<tr class=\"modulo_table_list_title\">\n";
					$html .= "						<td align=\"center\" colspan=\"4\">SOLUCIONES</td>\n";
					$html .= "					</tr>\n";
					$html .= "					<tr class=\"modulo_table_list_title\">\n";
					$html .= "						<td width=\"10%\">CÓDIGO</td>\n";
					$html .= "						<td width=\"40%\">PRODUCTO</td>\n";
					$html .= "						<td width=\"35%\">PRINCIPIO ACTIVO</td>\n";
					$html .= "						<td width=\"15%\">CANTIDAD</td>\n";
					$html .= "					</tr>\n";
					foreach($soluciones['0'] as $key=> $nivel1)
					{
						foreach($nivel1 as $key0=> $nivel2)
						{
							if($nivel2['sw_solucion'] == '1') 
							{
								$html .= "					<tr class=\"modulo_list_claro\">\n";
								$html .= "						<td class=\"normal_10AN\" align=\"center\" >".$nivel2['codigo_producto']." ".$nivel2['item']."</td>\n";
								$html .= "						<td class=\"normal_10AN\" align=\"left\" >".$nivel2['producto']."</td>\n";
								$html .= "						<td class=\"normal_10AN\" align=\"left\" >".$nivel2['principio_activo']."</td>\n";
								$html .= "						<td class=\"normal_10AN\" align=\"right\">".$nivel2['dosis']." ".$nivel2['unidad_dosificacion']."</td>\n";
								$html .= "					</tr>\n";
							}
						}
						foreach($nivel1 as $key1=> $nivel2)
						{
							if($nivel2['sw_solucion'] == '0') 
							{
								$html .= "					<tr class=\"modulo_list_claro\">\n";
								$html .= "						<td class=\"normal_10A\" align=\"center\" >".$nivel2['codigo_producto']." ".$nivel2['item']."</td>\n";
								$html .= "						<td class=\"normal_10A\" align=\"left\" >".$nivel2['producto']."</td>\n";
								$html .= "						<td class=\"normal_10A\" align=\"left\" >".$nivel2['principio_activo']."</td>\n";
								$html .= "						<td class=\"normal_10A\" align=\"right\">".$nivel2['dosis']." ".$nivel2['unidad_dosificacion']."</td>\n";
								$html .= "					</tr>\n";
							}
						}
						$html .= "				<tr class=\"modulo_list_claro\">\n";
						$html .= "					<td colspan=\"4\">\n";
						$html .= "						<table class=\"normal_10\" >\n";
						$html .= "							<tr>\n";
						$html .= "								<td >CANTIDAD TOTAL </td>\n";
						$html .= "								<td >". $nivel2['cantidad']."</td><td colspan=\"2\"><b>Unidad(es)</b></td>\n";
						$html .= "							</tr>\n";
						$html .= "							<tr >\n";
						$html .= "								<td >VOLUMEN DE INFUSIÓN</td>\n";
						$html .= "								<td align=\"right\">". $nivel2['volumen_infusion']."</td><td colspan=\"2\">".$nivel1[$key1]['unidad_volumen']."</td>\n";
						$html .= "							</tr>\n";				
						$html .= "						</table>\n";
						$html .= "					</td>\n";
						$html .= "				</tr>\n"; 
					
						if( $nivel2['observacion'] != "")
						{
							$html .= "				<tr class=\"modulo_list_claro\">\n";
							$html .= "					<td colspan=\"4\" width=\"100%\" >\n";
							$html .= "						<table width=\"100%\" class=\"normal_10\">\n";
							$html .= "							<tr>\n";
							$html .= "								<td valign=\"top\" width=\"30%\">\n";
							$html .= "									OBSERVACIONES E INDICACIONES DE SUMINISTRO:</td>\n";
							$html .= "								</td>\n";
							$html .= "								<td valign=\"top\" width=\"70%\" align=\"justify\" colspan=\"4\" >\n";
							$html .= "									". $nivel2['observacion']."\n";
							$html .= "								</td>\n";
							$html .= "							</tr>\n";
							$html .= "						</table>\n";
							$html .= "					</td>\n";
							$html .= "				</tr>\n";
						}
						
						$html .= "				<tr class=\"modulo_list_claro\" >\n";
						$html .= "					<td align=\"center\" class=\"label\">FORMULÓ</td>\n";
						$html .= "					<td class=\"normal_10\">". $nivel2['nombre']."</td>\n";
						$html .= "					<td align=\"center\" class=\"label\">FECHA FORMULACIÓN5</td>\n";
						$html .= "					<td class=\"normal_10\">". $nivel2['fecha']."</td>\n";
						$html .= "				</tr>\n";
						
						if( $nivel2['usuario_suministro'])
						{
							$html .= "					<tr>\n";
							$html .= "						<td align=\"center\" colspan=\"4\">\n";
							$html .= "							<table width=\"100%\" class=\"modulo_list_claro\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\">\n";
							if( $nivel2['observacion_suministro'] != " ")
							{							
								$html .= "								<tr>\n";
								$html .= "									<td valign=\"top\" width=\"25%\" class=\"label\">\n";
								$html .= "										OBSERVACIONES DE SUMINISTRO\n";
								$html .= "									</td>\n";
								$html .= "									<td valign=\"top\" width=\"75%\" align=\"justify\" colspan=\"3\" >\n";
								$html .= "										". $nivel2['observacion_suministro']."&nbsp;\n";
								$html .= "									</td>\n";
								$html .= "								</tr>\n";
							}
							$html .= "								<tr>\n";
							$html .= "									<td valign=\"top\" width=\"25%\" class=\"label\">USUARIO SUMISTRO</td>\n";
							$html .= "									<td valign=\"top\" width=\"25%\" align=\"justify\">\n";
							$html .= "										". $nivel2['usuario_suministro']."\n";
							$html .= "									</td>\n";
							$html .= "									<td valign=\"top\" width=\"25%\" class=\"label\">FECHA SUMISTRO</td>\n";
							$html .= "									<td valign=\"top\" width=\"25%\" align=\"justify\">\n";
							$html .= "										". $nivel2['fecha_suministro']."\n";
							$html .= "									</td>\n";
							$html .= "								</tr>\n";
							$html .= "								<tr class=\"label\">\n";
							$html .= "									<td colspan=\"4\">\n";
							$html .= "										<table width=\"100%\" border=\"1\" rules=\"all\" cellspacing=\"0\" cellpading=\"0\">\n";
							$html .= "											<tr class=\"label\">\n";
							$html .= "												<td align=\"center\">PRODUCTO</td>\n";
							$html .= "												<td align=\"center\">CANTIADAD SUMINISTRADA</td>\n";
							$html .= "												<td align=\"center\">DESECHOS</td>\n";
							$html .= "												<td align=\"center\">ENTREGAS AL PACIENTE</td>\n";
							$html .= "											</tr>\n";
							foreach($nivel1 as $keyy => $nively)
							{
								$html .= "											<tr class=\"label\">\n";
								$html .= "												<td >".$nively['producto']."</td>\n";
								$html .= "												<td align=\"right\">".$this->SeleccionFactorConversion($nively['codigo_producto'], $nively['unidad_id'],$nively['unidad_dosificacion'],$nively['cantidad_suministrada'])."</td>\n";
								$html .= "												<td align=\"right\">".$this->SeleccionFactorConversion($nively['codigo_producto'], $nively['unidad_id'],$nively['unidad_dosificacion'],$nively['cantidad_perdidas'])."</td>\n";
								$html .= "												<td align=\"right\">".$nively['cantidad_aprovechada']."</td>\n";
								$html .= "											</tr>\n";
							}
							$html .= "										</table>\n";
							$html .= "									</td>\n";
							$html .= "								</tr>\n";
							$html .= "							</table>\n";
							$html .= "						</td>\n";
							$html .= "					</tr>\n";
						}
						$j++;
					}
					$html .= "				</table>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
				}
				$html .= "	</table><br>";
			}
			$html .= $this->FormaConsultaCanastaMedicaII();
			$this->salida = $html;
			return true;
		}
		/********************************************************************************
    * Consulta de los medicamentos de la canasta de Cirugia.
    * Pinta los medicamentos suministrados durante una cirugia realizada al paciente.
		*********************************************************************************/
    function FormaConsultaCanastaMedicaII()
    {
			$medicamentos = $this->ConsultaCanastaMedica();
			if (!empty ($medicamentos))
			{
				$salida .= "<table width=\"95%\" class=\"modulo_table_list\">\n";
				$salida .= "	<tr class=\"modulo_table_list_title\">";
				$salida .= "		<td align=\"center\"colspan=\"6\">SUMINISTRO DE MEDICAMENTOS (CANASTA DE CIRUGIA)</td>";
				$salida .= "	</tr>\n";
				$salida .= "	<tr class=\"formulacion_table_list\">\n";
				$salida .= "		<td align=\"center\">Fecha y hora</td>";
				$salida .= "		<td align=\"center\">Codigo Med.</td>";
				$salida .= "		<td align=\"center\">Nombre Med.</td>";
				$salida .= "		<td align=\"center\">Cantidad</td>";
				$salida .= "		<td align=\"center\">Usuario Orden</td>";
				$salida .= "		<td align=\"center\">Usuario Suministro</td>";
				$salida .= "	</tr>\n";
				for($i=0; $i<sizeof($medicamentos); $i++)
				{
					$fecha = $this->FechaStamp($medicamentos[$i][fecha_registro]);
					$hora = $this->HoraStamp($medicamentos[$i][fecha_registro]);
					
					$salida .= "	<tr class=\"modulo_list_claro\">\n";
					$salida .= "		<td align=\"center\">".$fecha." - ".$hora."</td>";
					$salida .= "		<td align=\"center\">".$medicamentos[$i]['codigo_producto']."</td>";
					$salida .= "		<td align=\"center\">".$medicamentos[$i]['descripcion']."</td>";
					$salida .= "		<td align=\"center\">".ceil($medicamentos[$i]['cantidad_suministrada'])."</td>";
					$salida .= "		<td align=\"center\">".$medicamentos[$i]['us_orden']."</td>";
					$salida .= "		<td align=\"center\">".$medicamentos[$i]['us_suministro']."</td>";
					$salida .= "	</tr>";
					if($medicamentos[$i]['observacion'] != $medicamentos[$i+1]['observacion'])
					{
						$salida .= "	<tr class=\"modulo_list_claro\" >\n";
						$salida .= "		<td align=\"center\" class=\"normal_10AN\">OBSERVACIONES</td>";
						$salida .= "		<td align=\"justify\" class=\"normal_10\" colspan=\"5\">".$medicamentos[$i]['observacion']."</td>";
						$salida .= "	</tr>\n";
					}
				}
				$salida .= "</table><br>";
			}
			return $salida;
    }
	}//End class
?>