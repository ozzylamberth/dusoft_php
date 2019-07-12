<?php

/**
* Submodulo de Atencion (HTML).
*
* Submodulo para manejar el tipo de atencion (rips) de un paciente en una evolucion.
* @author Jaime Andres Valencia Salazar <jaimeandresvalencia@telesat.com.co
* @version 1.0
* @package SIIS
* $Id: hc_NotasOperatoriasCOC_HTML.php,v 1.2 2009/08/11 21:28:44 hugo Exp $
*/

/**
* Atencion_HTML
*
* Clase para retornar la presentacion en pantalla en html de los formularios de insercion y de consulta de los datos
* del submodulo atencion, se extiende la clase Atencion y asi pueden ser
* utilizados los metodos de esta clase en la anterior.
*/
IncludeClass("ClaseHTML");
class NotasOperatoriasCOC_HTML extends NotasOperatoriasCOC
{
	function NotasOperatoriasCOC_HTML()
	{
		$this->NotasOperatoriasCOC();//constructor del padre
		return true;
	}
    /**
    * Esta funcion retorna los datos de concernientes a la version del submodulo
    * @access private
    */
    function GetVersion()
    {
      $informacion=array(
      'version'=>'1',
      'subversion'=>'0',
      'revision'=>'0',
      'fecha'=>'01/27/2005',
      'autor'=>'JAIME ANDRES VALENCIA',
      'descripcion_cambio' => '',
      'requiere_sql' => false,
      'requerimientos_adicionales' => '',
      'version_kernel' => '1.0'
      );
      return $informacion;
    }
    /**
    * Forma donde se listan los actos quirurgicos y sus notas operatorias
    *
    * @param String $mensaje Mensaje a mostrar
    *
    * @returns boolean
    */
    function frmFormaActoQ($mensaje)
    {
      $pfj=$this->frmPrefijo;     
      if(empty($this->titulo))
        $this->salida  = ThemeAbrirTablaSubModulo('NOTA OPERATORIA DE LA CIRUGIA');
  	  else
      	$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
     
      $accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$this->frmPrefijo.""=>'FrmInicialNotasOperatorias'));
      $accionE=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$this->frmPrefijo.""=>'AnularNota'));
     
      $actos = $this->ActoQuir($this->ingreso);
      
      if($mensaje != "")
      {
        $this->salida .= "<center>\n";
        $this->salida .= "  <div class=\"normal_10AN\">".$mensaje."</div>\n";
        $this->salida .= "</center>\n";
      }
      
      $this->salida .= "<table border=\"0\" width=\"98%\" align=\"center\" class=\"modulo_table_list\">\n";
  		$this->salida .= "  <tr class=\"formulacion_table_list\">\n";
      $this->salida .= "    <td colspan=\"4\" align=\"center\">ACTO QUIRURGICO</td>\n";
      $this->salida .= "  </tr>\n";
      $this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
    	$this->salida .= "    <td colspan=\"4\">\n";
      $this->salida .= "      <a href=\"".$accionI.URLRequest(array("crear_nota"=>"1"))." \"class=\"label\">CREAR ACTO QUIRURGICO</a>\n";
      $this->salida .= "    </td>\n";
      $this->salida .= "  </tr>\n";
      $this->salida .= "  <tr class=\"formulacion_table_list\">\n";
      $this->salida .= "    <td width=\"30%\">NOTA OPERATORIA</td>\n";
      $this->salida .= "    <td width=\"50%\">USUARIO</td>\n";
      $this->salida .= "    <td colspan=\"2\"></td>\n";
      $this->salida .= "  </tr>\n";
    
      foreach($actos as $k1 => $dtl1)
      {
        $this->salida .= "  <tr class=\"formulacion_table_list\">\n";
        $this->salida .= "    <td colspan=\"4\" align=\"center\">ACTO QUIRURGICO - ".$k1."</td>";
        $this->salida .= "  </tr>\n";
        foreach($dtl1 as $k2 => $dtl2)
        {
          $this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
          $this->salida .= "    <td>".$dtl2['hc_nota_operatoria_cirugia_id']."</td>\n";
          $this->salida .= "    <td align=\"left\">".$dtl2['nombre_c']."</td>\n";
          $this->salida .= "    <td >\n";
          $this->salida .= "      <a href=\"".$accionI.URLRequest(array("acto_quirurgico"=>$dtl2['acto_quiru'],"usuario_registro"=>$dtl2['usuario_id'],"hc_nota_operatoria_cirugia_id"=>$dtl2['hc_nota_operatoria_cirugia_id']))." \"class=\center\"label\">\n";
          $this->salida .= "        <img src=\"".GetThemePath()."/images/pconsultar.png\" border = \"0\"> VER\n";
          $this->salida .= "      </a>\n";
          $this->salida .= "    </td>";          
          $this->salida .= "    <td >\n";
          $this->salida .= "      <a title=\"ELIMINAR NOTA\" href=\"".$accionE.URLRequest(array("acto_quirurgico"=>$dtl2['acto_quiru'],"hc_nota_operatoria_cirugia_id"=>$dtl2['hc_nota_operatoria_cirugia_id']))." \"class=\center\"label\">\n";
          $this->salida .= "        <img src=\"".GetThemePath()."/images/elimina.png\" border = \"0\"> ELI\n";
          $this->salida .= "      </a>\n";
          $this->salida .= "    </td>";
          $this->salida .= "  </tr>\n"; 
        }
        $this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">\n";
        $this->salida .= "    <td colspan=\"4\" align=\"center\">\n";
        $this->salida .= "      <a href=\"".$accionI.URLRequest(array("acto_quirurgico"=>$dtl2['acto_quiru'],"crear_nota"=>"1"))." \"class=\"label\">CREAR NOTA OPERATORIA</a>\n";
        $this->salida .= "    </td>\n";
        $this->salida .= "  </tr>\n";
      }
      $this->salida .= "</table>";
      $this->salida .= "</form>";
      $this->salida .= ThemeCerrarTablaSubModulo();
  	
  		return true;
    }
  
	function frmForma()
	{
		if(empty($this->titulo))
		{
			$this->salida  = ThemeAbrirTablaSubModulo('NOTA OPERATORIA DE LA CIRUGIA');
		}
		else
		{
			$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
		}
		$pfj=$this->frmPrefijo;
		
		$perfil = $this->PerfilProfesional();
        
		if($perfil=='0')
		{
			$disabled = 'disabled';	
			$enabled = 'enabled';
		}
		else if ($perfil=='1')
		{
			$disabled ='enabled';
			$enabled = 'disabled';
			
		}	
		else
		{
			$disabled = 'disabled';
			$enabled = 'disabled';
		}
		
		//$this->IncludeJS("ScriptRemoting/gases.js",'hc','NotasOperatoriasCOC');
		include('/hc_modules/NotasOperatoriasCOC/ScriptRemoting/gases.js');
		$this->salida.="<script language='javascript'>\n";
		$this->salida.="function CambioSuministro(valor){
  					//jsrsExecute('app_modules/DatosLiquidacionQX/ScriptRemoting/gases.php', valores_resultado, 'TiposFrecuenciasSuministrosGases', valor);  
					jsrsExecute('hc_modules/NotasOperatoriasCOC/ScriptRemoting/gases.php', valores_resultado, 'TiposFrecuenciasSuministrosGases', valor);  
				}

				function valores_resultado(html)
        {
				document.getElementById('frecuencia').innerHTML=html;
				}
				
				function valores_resultado_insercion(html)
        {
				document.getElementById('MostrarDatosGases').innerHTML=html;
				Cerrar('d2Container');
				}
				
				function EliminarGasAnestesico(vectorContador){
				jsrsExecute('app_modules/DatosLiquidacionQX/ScriptRemoting/gases.php', valores_resultado_insercion, 'EliminarGasAnestesicoVector', vectorContador);  
				}";
		
		$this->salida.="  function desabilita(frm,valor){";
		$this->salida.="    cadena=valor.split('/');";
		$this->salida.="    if(cadena[1]==0 || valor==-1){";
		$this->salida.="        frm.gasAnestesico.disabled=true;\n";
		$this->salida.="        frm.gasAnestesicoMe.disabled=true;\n";
		$this->salida.="        frm.DuracionGas.disabled=true;\n";
		$this->salida.="        frm.nogas.value='0';\n";
		$this->salida.="    }else{\n";
		$this->salida.="        frm.gasAnestesico.disabled=false;\n";
		$this->salida.="        frm.gasAnestesicoMe.disabled=false;\n";
		$this->salida.="        frm.DuracionGas.disabled=false;\n";
		$this->salida.="        frm.nogas.value='1';\n";
		$this->salida.="    }\n";
		$this->salida.="  }\n";
		$this->salida.="  function desabilitaQuirofano(frm,valor){";
		$this->salida.="    cadena=valor.split('/');";
		$this->salida.="    if(cadena[1]==0 || valor==-1){";
		$this->salida.="        frm.quirofano.disabled=true;\n";
		$this->salida.="        frm.noquiro.value='0';\n";
		$this->salida.="    }else{\n";
		$this->salida.="        frm.quirofano.disabled=false;\n";
		$this->salida.="        frm.noquiro.value='1';\n";
		$this->salida.="    }\n";
		$this->salida.="  }\n";
		$this->salida.="  function desabilitaPolitrauma(frm,valor){";
		$this->salida.="    if(valor==true){";
		$this->salida.="        frm.TipoPolitrauma.disabled=false;\n";
		$this->salida.="    }else{\n";
		$this->salida.="        frm.TipoPolitrauma.disabled=true;\n";
		$this->salida.="    }\n";
		$this->salida.="  }\n";
		$this->salida .= "  function Iniciar(capita,envios)\n";
		$this->salida .= "  {\n";        
		$this->salida .= "    document.getElementById('titulo').innerHTML = '<center>GASES ANESTESICOS</center>';\n";
		$this->salida .= "    document.getElementById('error').innerHTML = '';\n";                
		$this->salida .= "    contenedor = 'd2Container';\n";
		$this->salida .= "    titulo = 'titulo';\n";
		$this->salida .= "    ele = xGetElementById('d2Container');\n";
		$this->salida .= "    xMoveTo(ele, xClientWidth()/3, xScrollTop()+24);\n";
		$this->salida .= "    ele = xGetElementById('titulo');\n";
		$this->salida .= "    xResizeTo(ele,280, 20);\n";
		$this->salida .= "    xMoveTo(ele, 0, 0);\n";
		$this->salida .= "    xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
		$this->salida .= "    ele = xGetElementById('cerrar');\n";
		$this->salida .= "    xResizeTo(ele,20, 20);\n";
		$this->salida .= "    xMoveTo(ele, 280, 0);\n";
		$this->salida .= "  }\n";
		$this->salida .= "  function myOnDragStart(ele, mx, my)\n";
		$this->salida .= "  {\n";
		$this->salida .= "    window.status = '';\n";
		$this->salida .= "    if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
		$this->salida .= "    else xZIndex(ele, hiZ++);\n";
		$this->salida .= "    ele.myTotalMX = 0;\n";
		$this->salida .= "    ele.myTotalMY = 0;\n";
		$this->salida .= "  }\n";
		$this->salida .= "  function myOnDrag(ele, mdx, mdy)\n";
		$this->salida .= "  {\n";
		$this->salida .= "    if (ele.id == titulo) {\n";
		$this->salida .= "      xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
		$this->salida .= "    }\n";
		$this->salida .= "    else {\n";
		$this->salida .= "      xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
		$this->salida .= "    }  \n";
		$this->salida .= "    ele.myTotalMX += mdx;\n";
		$this->salida .= "    ele.myTotalMY += mdy;\n";
		$this->salida .= "  }\n";
		$this->salida .= "  function myOnDragEnd(ele, mx, my)\n";
		$this->salida .= "  {\n";
		$this->salida .= "  }\n";
		$this->salida .= "  function MostrarSpan(Seccion)\n";
		$this->salida .= "  { \n";
		$this->salida .= "    e = xGetElementById(Seccion);\n";
		$this->salida .= "    e.style.display = \"\";\n";
		$this->salida .= "  }\n";
		$this->salida .= "  function Cerrar(Seccion)\n";
		$this->salida .= "  { \n";
		$this->salida .= "    e = xGetElementById(Seccion);\n";
		$this->salida .= "    e.style.display = \"none\";\n";
		$this->salida .= "  }\n";
		$this->salida .= "  function MostrarVentana(Seccion)\n";
		$this->salida .= "  { \n";
		$this->salida .= "    e = xGetElementById(Seccion);\n";
		$this->salida .= "    e.style.display = \"block\";\n";
		$this->salida .= "  }\n";
		$this->salida .= "  function InsertarDatosFrecuencia(frm)\n";
		$this->salida .= "  { \n";
		$this->salida .= "    if(frm.gasAnestesico.value==-1){;\n";        
		$this->salida .= "      alert('Todos los Datos son Obligatorios');\n";        
		$this->salida .= "      return false;\n";        
		$this->salida .= "    };\n";        
		$this->salida .= "    if(frm.SuministroGas.value==-1){;\n";        
		$this->salida .= "      alert('Todos los Datos son Obligatorios');\n";        
		$this->salida .= "      return false;\n";        
		$this->salida .= "    };\n";        
		$this->salida .= "    if(frm.FrecuenciaSuministroGas.value==-1){;\n";        
		$this->salida .= "      alert('Todos los Datos son Obligatorios');\n";        
		$this->salida .= "      return false;\n";        
		$this->salida .= "    };\n";        
		$this->salida .= "    if(frm.MinutosSuministroGas.value==0){;\n";        
		$this->salida .= "      alert('Todos los Datos son Obligatorios');\n";        
		$this->salida .= "      return false;\n";        
		$this->salida .= "    };\n";        
		$this->salida .= "    var cadena=new Array();\n";        
		$this->salida .= "    cadena[0]=frm.gasAnestesico.value;\n";                
		$this->salida .= "    var indice=frm.gasAnestesico.selectedIndex;\n";                
		$this->salida .= "    cadena[1]=frm.gasAnestesico.options[indice].text;\n";                
		$this->salida .= "    cadena[2]=frm.SuministroGas.value;\n";                
		$this->salida .= "    var indice1=frm.SuministroGas.selectedIndex;\n";                
		$this->salida .= "    cadena[3]=frm.SuministroGas.options[indice1].text;\n";         
		$this->salida .= "    cadena[4]=frm.FrecuenciaSuministroGas.value;\n";        
		$this->salida .= "    var indice2=frm.FrecuenciaSuministroGas.selectedIndex;\n";     
		$this->salida .= "    cadena[5]=frm.FrecuenciaSuministroGas.options[indice2].text;\n";                           
		$this->salida .= "    cadena[6]=frm.MinutosSuministroGas.value;\n";                                                          
		$this->salida .= "    jsrsExecute(\"hc_modules/NotasOperatoriasCOC/ScriptRemoting/gases.php\", valores_resultado_insercion, \"InsertarDatosGasesSuministrados\",cadena);";        
		$this->salida .= "  }\n";        
		$this->salida.="</script>\n";
	
	
	      $ventana = "  <div id='d2Container' class='d2Container' style=\"display:none\">\n";
        $ventana.= "  <div id='titulo' class='draggable' style=\" text-transform: uppercase;\"></div>\n";
        $ventana.= "  <div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('d2Container')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
        $ventana.= "  <div id='error' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
        $ventana.= "  <div id='d2Contents'>\n";
        $ventana.= "  <form name=\"formaGas\" action=\"$accion\" method=\"post\">";        
        $ventana.= "  <table align=\"center\">";
        $ventana.= "  <tr class=\"modulo_list_claro\" width=\"100%\">";
        $ventana.= "  <td class=\"".$this->SetStyle("gasAnestesico")."\">TIPO GAS</td>";
        $ventana.= "  <td><select name=\"gasAnestesico\" class=\"select\" $disabled>";
        $ventana.= "    <option value=\"-1\" selected>---seleccione---</option>";
        $TipoGases=$this->TiposGasesAnestesicos();
        foreach($TipoGases as $value=>$titulo)
        {          
          $ventana.="  <option value=\"$value\">$titulo</option>";          
        }
        $ventana.= "   </select></td>";
        $ventana.= "</td></tr>";
        $ventana.="<tr class=\"modulo_list_claro\">";
        $ventana.="<td class=\"".$this->SetStyle("SuministroGas")."\">METODO SUMINISTRO</td>";
        $ventana.="<td><select name=\"SuministroGas\" class=\"select\" onchange=\"CambioSuministro(this.value)\" $disabled>";
        $ventana.="    <option value=\"-1\" selected>---seleccione---</option>";
        $TipoSuministros=$this->TiposMetodosSuministrosGases();
        foreach($TipoSuministros as $value=>$titulo)
        {          
          $ventana.="  <option value=\"$value\">$titulo</option>";          
        }
        $ventana.= "   </select></td>";
        $ventana.= "</td></tr>";
        $ventana.="<tr class=\"modulo_list_claro\">";
        $ventana.="<td class=\"".$this->SetStyle("FrecuenciaSuministroGas")."\">FRECUENCIA SUMINISTRO</td>";
        $ventana.="<td id=\"frecuencia\"><select name=\"FrecuenciaSuministroGas\" class=\"select\" $disabled>";
        $ventana.="    <option value=\"-1\" selected>---seleccione---</option>";        
        $ventana.= "   </select></td>";
        $ventana.= "</td></tr>";
        $ventana.="<tr class=\"modulo_list_claro\">";
        $ventana.="<td class=\"".$this->SetStyle("MinutosSuministroGas")."\">MINUTOS</td>";
        $ventana.="<td><input class=\"input-text\" type=\"text\" size=\"4\" name=\"MinutosSuministroGas\" value=\"0\" $disabled></td>";
        $ventana.="</tr>";
        $ventana.="<tr class=\"modulo_list_claro\">";          
        $ventana.="<td align=\"center\" colspan=\"2\"><input class=\"input-submit\" type=\"button\" onclick=\"InsertarDatosFrecuencia(document.formaGas)\" name=\"INSERTAR\" value=\"INSERTAR\" $disabled></td>";
        $ventana.="</tr>";
        $ventana.="</table>"; 
        $ventana.="</form>";
        $ventana.="</div>";
        $ventana.="</div>";
		
	///////////////////////////////////////////	
		
		$this->salida.=$ventana;
		$this->salida.="<script>";
		$this->salida.="function CargarAccion(url){\n";
		$this->salida.="HabilitaCampo(); document.formauno$pfj.action=url;\n";
		$this->salida.="document.formauno$pfj.submit();}";
		
		$this->salida.="function Msg2(){\n";
		$this->salida.="document.formauno$pfj.activa.value=1;\n";
		$this->salida.="}";
		
		
		$this->salida.="function HabilitaCampo(){\n";
		//$this->salida.="alert(document.formauno$pfj.anestesista$pfj.value);\n";
		$this->salida.="document.formauno$pfj.cirujano$pfj.disabled = false;\n";
		$this->salida.="document.formauno$pfj.anestesista$pfj.disabled = false;\n";
		
		$this->salida.="document.formauno$pfj.ayudante$pfj.disabled = false;\n";
		$this->salida.="document.formauno$pfj.instrumentista$pfj.disabled = false;\n";
		$this->salida.="document.formauno$pfj.circulante$pfj.disabled = false;\n";
		$this->salida.="document.formauno$pfj.fechainicio$pfj.disabled = false;\n";
		$this->salida.="document.formauno$pfj.hora$pfj.disabled = false;\n";
		$this->salida.="document.formauno$pfj.minutos$pfj.disabled = false;\n";
		$this->salida.="document.formauno$pfj.horafin$pfj.disabled = false;\n";
		$this->salida.="document.formauno$pfj.minutosfin$pfj.disabled = false;\n";
		$this->salida.="document.formauno$pfj.fechafinal$pfj.disabled = false;\n";
		$this->salida.="document.formauno$pfj.horadur$pfj.disabled = false;\n";
		$this->salida.="document.formauno$pfj.minutosdur$pfj.disabled = false;\n";
		$this->salida.="document.formauno$pfj.quirofano$pfj.disabled = false;\n";
		$this->salida.="document.formauno$pfj.viaAcceso$pfj.disabled = false;\n";
		$this->salida.="document.formauno$pfj.tipoCirugia$pfj.disabled = false;\n";
		$this->salida.="document.formauno$pfj.ambitoCirugia$pfj.disabled = false;\n";
		$this->salida.="document.formauno$pfj.finalidadCirugia$pfj.disabled = false;\n";
		$this->salida.="document.formauno$pfj.tipoanestesia$pfj.disabled = false;\n";
		if($perfil == '0')
		{	
			$this->salida.="document.formauno$pfj.MaterialPat$pfj.disabled = false;\n";
			$this->salida.= "document.formauno$pfj.SelMaterialPat$pfj.checked = false;\n";
			$this->salida.= "document.formauno$pfj.SelMaterialPat$pfj.disabled = true;\n";
			$this->salida.= "document.formauno$pfj.SelCultivo$pfj.disabled = true;\n";
			$this->salida.= "document.formauno$pfj.SelCultivo$pfj.checked = false;\n";
			$this->salida.="document.formauno$pfj.Cultivo$pfj.disabled = false;\n";
		}
		
		$this->salida.="}";
		$this->salida.= "function Msg5(obj){";
		$this->salida.= "HabilitaCampo();
				document.formauno$pfj.submit();";
		$this->salida.= "}";
		$this->salida.="</script>";
		
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$this->frmPrefijo.""=>'FrmNotasOperatorias'));
		$this->salida.="    <form name='formauno".$this->frmPrefijo."' action=\"$accion\" method=post>";
		
		if(empty($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]))
    {
			$this->salida .= "  <table border=\"0\" width=\"98%\" align=\"center\">";
			$this->salida .= "  <tr><td align=\"center\" class=\"label_error\">";
			$this->salida .= "   EL PACIENTE NO TIENE UNA PROGRAMACION ACTIVA PARA REALIZAR UNA NOTA OPERATORIA";
			$this->salida .= "  </td></tr>";
			$this->salida .= "  </table>";	
			$this->salida.= " </form>";
			$this->salida .= ThemeCerrarTablaSubModulo();
    			return true;
		}
		$this->salida .= "  <table border=\"0\" width=\"98%\" align=\"center\">";
		$this->salida .= "  <tr><td align=\"center\">";
		$this->salida .=    $this->SetStyle("MensajeError");
		$this->salida .= "  </td></tr>";
		$this->salida .= "  </table>\n";
    $this->salida .= "  <table border=\"0\" width=\"98%\" align=\"center\">";
    $this->salida .= "    <tr class=\"formulacion_table_list\"><td colspan=\"4\" align=\"center\">PROFESIONALES QUE PARTICIPARON EN LA CIRUGIA</td></tr>";		
    $this->salida .= "    <tr class=\"hc_table_submodulo_list_title\">\n";
    $this->salida .= "      <td >CIRUJANO</td>\n";
    $this->salida .= "      <td colspan=\"3\" align=\"left\" class=\"hc_submodulo_list_oscuro\">\n";
    $this->salida .= "        <select name=\"cirujano".$this->frmPrefijo."\" class=\"select\" $disabled>\N";
    
    $profesionales1 = $this->profesionalesEspecialista();
    $this->salida .= "          <option value=\"-1\">---Seleccione---</option>";
			
    for($i=0;$i<sizeof($profesionales1);$i++)
    {
      $value = $profesionales1[$i]['tercero_id'].'/'.$profesionales1[$i]['tipo_id_tercero'];
      $titulo = $profesionales1[$i]['nombre'];
      ($value==$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['cirujano'])? $chk="selected": $chk="";

      $this->salida .= "          <option value=\"".$value."\" ".$chk.">".$titulo."</option>\n";
    }	  	
    $this->salida .= "        </select>\n";
    $this->salida .= "      </td>\n";
    $this->salida .= "    </tr>\n";
    $this->salida .= "    <tr class=\"hc_table_submodulo_list_title\">\n";
    $this->salida .= "      <td >ANESTESIOLOGO</td>\n";
    $this->salida .= "      <td colspan=\"3\" align=\"left\" class=\"hc_submodulo_list_oscuro\">";
    $this->salida .= "        <select name=\"anestesista".$this->frmPrefijo."\" class=\"select\" $disabled>";
    $anestesiologos=$this->profesionalesEspecialistaAnestecistas();
    $this->salida .=" 	<option value=\"-1\">---Seleccione---</option>";
    for($i=0;$i<sizeof($anestesiologos);$i++)
    {
      $value=$anestesiologos[$i]['tercero_id'].'/'.$anestesiologos[$i]['tipo_id_tercero'];
      $titulo=$anestesiologos[$i]['nombre'];
      if($value==$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['anestesiologo'])
      {
        $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
      }
      else
      {
        $this->salida .=" <option value=\"$value\">$titulo</option>";
      }
    }	  
    $this->salida .= "	</select>";
    $this->salida .= "	</td>";				
    $this->salida .= "</tr>";	
    
    $this->salida .= "<tr>";
    $this->salida .= "<td class=\"hc_table_submodulo_list_title\">AYUDANTE</td>";
    $this->salida .= "<td colspan=\"3\" class=\"hc_submodulo_list_oscuro\">";
    $this->salida .= "   <select name=\"ayudante".$this->frmPrefijo."\" class=\"select\" $disabled>";
    $profesionales=$this->profesionalesAyudantes();
    $this->salida .=" 	 <option value=\"-1\">---Seleccione---</option>";
    for($i=0;$i<sizeof($profesionales);$i++)
    {
      $value=$profesionales[$i]['tercero_id'].'/'.$profesionales[$i]['tipo_id_tercero'];
      $titulo=$profesionales[$i]['nombre'];
      if($value==$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['ayudante'])
      {
        $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
      }
      else
      {
        $this->salida .=" <option value=\"$value\">$titulo</option>";
			}
		}
    $this->salida .= "    </select>";
    $this->salida .= "</td>";
    $this->salida .= "</tr>";
            
    $this->salida .= "<tr>";
    $this->salida .= "<td class=\"hc_table_submodulo_list_title\">INSTRUMENTADOR</td>";
    $this->salida .= "<td colspan=\"3\" class=\"hc_submodulo_list_oscuro\">";
    $this->salida .= "<select name=\"instrumentista".$this->frmPrefijo."\" class=\"select\" $disabled>";
    $instrumentistas=$this->profesionalesEspecialistaInstrumentistas();
    $this->salida .=" 	 <option value=\"-1\">---Seleccione---</option>";
    for($i=0;$i<sizeof($instrumentistas);$i++)
    {
      $value=$instrumentistas[$i]['tercero_id'].'/'.$instrumentistas[$i]['tipo_id_tercero'];
      $titulo=$instrumentistas[$i]['nombre'];
      if($value==$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['instrumentador'])
      {
        $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
      }
      else
      {
        $this->salida .=" <option value=\"$value\">$titulo</option>";
      }
    }
    $this->salida .= "    </select>";
    $this->salida .= "</td>";  		
    $this->salida .= "</tr>";		 
    $this->salida .= "<tr>";
    $this->salida .= "<td class=\"hc_table_submodulo_list_title\">CIRCULANTE</td>";
    $this->salida .= "<td colspan=\"3\" class=\"hc_submodulo_list_oscuro\">";
    $this->salida .= "<select name=\"circulante".$this->frmPrefijo."\" class=\"select\" $disabled>";
    $ciruculantes=$this->profesionalesEspecialistaCiculantes();
    $this->salida .=" 	 <option value=\"-1\">---Seleccione---</option>";
    
    for($i=0;$i<sizeof($ciruculantes);$i++)
    {
      $value=$ciruculantes[$i]['tercero_id'].'/'.$ciruculantes[$i]['tipo_id_tercero'];
      $titulo=$ciruculantes[$i]['nombre'];
      if($value==$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['circulante'])
      {
        $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
      }
      else
      {
        $this->salida .=" <option value=\"$value\">$titulo</option>";
      }
    }
      
    $this->salida .= "  </select>";
    $this->salida .= "</td>";  
    $this->salida .= "</tr>";	
    //print_r($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]);
    $chk1 = ""; $chk2="";
    if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['sw_urg_prog'] == '1')
    {
      $chk2 = "checked"; $chk1="";
    }
    else if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['sw_urg_prog'] == '0')
    {
      $chk1 = "checked"; $chk2="";
    }
      
    if ($_REQUEST['sw_urg_prog']== '1')
    {
        $chk2 = "checked"; $chk1="";
    }
    else if( $_REQUEST['sw_urg_prog']== '0')  
    {
        $chk1 = "checked"; $chk2="";
    }
    $this->salida .= "  <tr>";
    $this->salida .= "    <td class=\"hc_table_submodulo_list_title\">PROGRAMADA</td><td class=\"hc_submodulo_list_oscuro\"><input type=\"radio\" name=\"sw_urg_prog\" value='0' $chk1></td>";
    $this->salida .= "    <td class=\"hc_table_submodulo_list_title\">URGENTE</td><td class=\"hc_submodulo_list_oscuro\"><input type=\"radio\" name=\"sw_urg_prog\" value='1' $chk2></td>";
    $this->salida .= "  </tr>";
    $this->salida .= "  <tr>";
    $this->salida .= "    <td>";
    $this->salida .= "      <div id=\"divVal\"class=\"label_error\">";
    $this->salida .= "      </div>";      
    $this->salida .= "    </td>";
    $this->salida .= "  </tr>";
    $this->salida .= "</table><BR>\n";
    
    $this->salida .= "  <table border=\"0\" width=\"98%\" align=\"center\">";
    $this->salida .= "  <tr class=\"modulo_table_title\"><td colspan=\"4\" align=\"center\">CLASIFICACION DE LA CIRUGIA</td></tr>";
    $this->salida .= "  <tr>";
    $this->salida .= "  <td class=\"hc_table_submodulo_list_title\">FECHA INICIO</td>";
			
    if($perfil=='0')
    {		
      $this->salida .= "  <td align=\"left\" class=\"hc_submodulo_list_oscuro\"><input type=\"text\" size=\"10\" maxlength=\"10\" class=\"input-text\" name=\"fechainicio".$this->frmPrefijo."\" value=\"".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['fechainicio']."\" class=\"text-input\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\" $disabled></td>";
    }
    else if ($perfil=='1')
    {
      $this->salida .= "  <td align=\"left\" class=\"hc_submodulo_list_oscuro\"><input type=\"text\" size=\"10\" maxlength=\"10\" class=\"input-text\" name=\"fechainicio".$this->frmPrefijo."\" value=\"".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['fechainicio']."\" class=\"text-input\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\" $disabled>";
      $this->salida .= "  &nbsp&nbsp&nbsp;".ReturnOpenCalendario('formauno'.$this->frmPrefijo,'fechainicio'.$this->frmPrefijo,'-')."</td>";			
    }
    else
    {
      $this->salida .= "  <td align=\"left\" class=\"hc_submodulo_list_oscuro\"><input type=\"text\" size=\"10\" maxlength=\"10\" class=\"input-text\" name=\"fechainicio".$this->frmPrefijo."\" value=\"".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['fechainicio']."\" class=\"text-input\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\" $disabled></td>";
    }
		   
    $this->salida .= "  <td class=\"hc_table_submodulo_list_title\">HORA INICIO</td>";
    $this->salida .= "  <td class=\"hc_submodulo_list_oscuro\">";
    $this->salida .= "  <select size=\"1\" name=\"hora".$this->frmPrefijo."\" class=\"select\" $disabled>";
    $this->salida .= "  <option value = -1>Hora Inicio</option>";
    for($j=0;$j<=23; $j++)
    {
      if (($j >= 0) AND ($j<= 9))
      {
        $hora = '0'.$j;
        if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['hora']==$hora)
        {
          $this->salida.="    <option selected value = \"$hora\">0$j</option>";
        }
        else
        {
          $this->salida.="    <option value = \"$hora\">0$j</option>";
        }
      }
      else
      {
        if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['hora']==$j)
        {
          $this->salida.="    <option selected value = $j>$j</option>";
        }
        else
        {
          $this->salida.="    <option value = $j>$j</option>";
        }
      }
		}
    $this->salida.="   </select>";
    $this->salida.="   <select size=\"1\"  name=\"minutos".$this->frmPrefijo."\" class=\"select\" $disabled>";
    $this->salida.="   <option value = -1>Minutos Inicio</option>";
    for($j=0;$j<=59; $j++)
    {
      if(($j >= 0) AND ($j<= 9))
      {
        $min = '0'.$j;
        if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['minutos']==$min)
        {
          $this->salida.="<option selected value = \"$min\" >0$j</option>";
        }
        else
        {
          $this->salida.="<option value=\"$min\">0$j</option>";
        }
      }
      else
      {
        if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['minutos']==$j)
        {
          $this->salida.="<option selected value=$j>$j</option>";
				}
        else
        {
						$this->salida.="<option value=$j>$j</option>";
				}
			}
		}
    $this->salida .= "  </select>";
    $this->salida .= "  </td>";
    $this->salida .= "  </tr>";
    
    $this->salida .= "  <tr>";
    $this->salida .= "  <td class=\"hc_table_submodulo_list_title\">FECHA TERMINACION</td>";
			
    if($perfil=='0')
    {
      $this->salida .= "  <td align=\"left\" class=\"hc_submodulo_list_oscuro\"><input type=\"text\" size=\"10\" maxlength=\"10\" class=\"input-text\" name=\"fechafinal".$this->frmPrefijo."\" value=\"".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['fechafinal']."\" class=\"text-input\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\" $disabled></td>";
    }
    else if($perfil=='1')
    {
      $this->salida .= "  <td align=\"left\" class=\"hc_submodulo_list_oscuro\"><input type=\"text\" size=\"10\" maxlength=\"10\" class=\"input-text\" name=\"fechafinal".$this->frmPrefijo."\" value=\"".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['fechafinal']."\" class=\"text-input\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\" $disabled>";
      $this->salida .= "  &nbsp&nbsp&nbsp;".ReturnOpenCalendario('formauno'.$this->frmPrefijo,'fechafinal'.$this->frmPrefijo,'-')."</td>";
    }
    else 
    {
      $this->salida .= "  <td align=\"left\" class=\"hc_submodulo_list_oscuro\"><input type=\"text\" size=\"10\" maxlength=\"10\" class=\"input-text\" name=\"fechafinal".$this->frmPrefijo."\" value=\"".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['fechafinal']."\" class=\"text-input\" onFocus=\"this.select();\" onBlur=\"IsValidDate(this,'dd/mm/yyyy')\" onKeyUp=\"setDate(this,'dd/mm/yyyy','es')\" $disabled></td>";
    }
    
    $this->salida .= "  <td class=\"hc_table_submodulo_list_title\">HORA FINAL</td>";
    $this->salida .= "  <td class=\"hc_submodulo_list_oscuro\">";
    $this->salida .= "  <select size=\"1\" name=\"horafin".$this->frmPrefijo."\" class=\"select\" $disabled>";
    $this->salida .= "  <option value = -1>Hora Terminacion</option>";
    
    for($j=0;$j<=23; $j++)
    {
      if (($j >= 0) AND ($j<= 9))
      {
        $hora = '0'.$j;
        if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['horafin']==$hora){
          $this->salida.="    <option selected value = \"$hora\">0$j</option>";
        }
        else
        {
          $this->salida.="    <option value = \"$hora\">0$j</option>";
        }
      }
      else
      {
        if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['horafin']==$j)
        {
          $this->salida.="    <option selected value = $j>$j</option>";
        }
        else
        {
          $this->salida.="    <option value = $j>$j</option>";
        }
      }
    }
			$this->salida.="   </select>";
			$this->salida.="   <select size=\"1\"  name=\"minutosfin".$this->frmPrefijo."\" class=\"select\" $disabled>";
			$this->salida.="   <option value = -1>Minutos Final</option>";
			for($j=0;$j<=59; $j++)
      {
				if(($j >= 0) AND ($j<= 9))
        {
					$min = '0'.$j;
					if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['minutosfin']==$min)
          {
						$this->salida.="<option selected value = \"$min\" >0$j</option>";
					}
          else
          {
						$this->salida.="<option value=\"$min\">0$j</option>";
					}
				}
        else
        {
					if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['minutosfin']==$j)
          {
						$this->salida.="<option selected value=$j>$j</option>";
					}
          else
          {
						$this->salida.="<option value=$j>$j</option>";
					}
				}
			}
			$this->salida.="      </select>";
			$this->salida .= "  </td>";
			$this->salida .= "  </tr>";
			
			$this->salida .= "  <tr>";
			$this->salida .= "  <td class=\"hc_table_submodulo_list_title\">DURACION</td>";
			$this->salida .= "  <td class=\"hc_submodulo_list_oscuro\">";
		
			$this->salida .= "  <select size=\"1\" name=\"horadur".$this->frmPrefijo."\" $disabled>";
			$this->salida .= "  <option value = -1>Horas</option>";
			for($j=0;$j<=23; $j++)
      {
				if (($j >= 0) AND ($j<= 9))
        {
					$horadur = '0'.$j;
					if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['horadur']==$horadur)
          {
						$this->salida.="    <option selected value = \"$horadur\">0$j</option>";
					}
          else
          {
						$this->salida.="    <option value = \"$horadur\">0$j</option>";
					}
				}
        else
        {
					if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['horadur']==$j)
          {
						$this->salida.="    <option selected value = $j>$j</option>";
					}
          else
          {
						$this->salida.="    <option value = $j>$j</option>";
					}
				}
			}
			$this->salida.="   </select>";
			
			$this->salida.="   <select size=\"1\"  name=\"minutosdur".$this->frmPrefijo."\" $disabled>";
			$this->salida.="   <option value = -1>Minutos</option>";
			for($j=0;$j<=59; $j++)
      {
				if(($j >= 0) AND ($j<= 9))
        {
					$mindur = '0'.$j;
					if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['minutosdur']==$mindur)
          {
						$this->salida.="<option selected value = \"$mindur\" >0$j</option>";
					}
          else
          {
						$this->salida.="<option value=\"$mindur\">0$j</option>";
					}
				}
        else
        {
					if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['minutosdur']==$j)
          {
						$this->salida.="<option selected value=$j>$j</option>";
					}
          else
          {
						$this->salida.="<option value=$j>$j</option>";
					}
				}
			}
			$this->salida.="      </select>";
			$this->salida .= "  </td>";
			$this->salida .= "  <td class=\"hc_table_submodulo_list_title\">QUIROFANO</td><td align=\"left\" class=\"hc_submodulo_list_oscuro\"><select name=\"quirofano".$this->frmPrefijo."\" class=\"select\" $disabled>";
			$quirofanos=$this->TotalQuirofanos();
			$this->MostrasSelect($quirofanos,'False',$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['quirofano']);
			$this->salida .= "  </select></td>";
			$this->salida .= "  </tr></table>";
			
	
	/////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////GAS ANESTESICO///////////////
	/////////////////////////////////////////////////////////////////////////
		
	    $this->salida .= "  <table border=\"0\" width=\"98%\" align=\"center\">\n";
      $this->salida .= "    <tr>\n";
      $this->salida .= "      <td colspan=\"4\" class=\"modulo_table_list_title\">GASES ANESTESICOS</td>\n";
      $this->salida .= "    </tr>\n";
      $this->salida .= "      <tr class=\"modulo_list_claro\">\n";
      $this->salida .= "        <td width=\"10%\" nowrap class=\"".$this->SetStyle("TipoAnestesia")."\">TIPO ANESTESIA</td>\n";
      $this->salida .= "        <td width=\"20%\" nowrap>\n";
      $this->salida .= "          <select onchange=\"desabilita(this.form,this.value)\" name=\"tipoanestesia".$this->frmPrefijo."\" class=\"select\" $disabled>\n";
      $this->salida .= "            <option value=\"-1\" >---seleccione---</option>\n";
      $TiposAnestesias=$this->TiposDeAnestesias();
    	for($i=0;$i<sizeof($TiposAnestesias);$i++)
      {
      	$value=$TiposAnestesias[$i]['qx_tipo_anestesia_id'].'/'.$TiposAnestesias[$i]['sw_uso_gases'];
        $titulo=$TiposAnestesias[$i]['descripcion'];
        if($value==$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['tipoanestesia'])
        {
            $this->salida .="     <option value=\"$value\" selected>$titulo</option>";
        }
        else
        {
            $this->salida .="     <option value=\"$value\">$titulo</option>";
        }
      }  
      $this->salida .= "       </select></td>";
    	$this->salida .= "           <td width=\"100%\">";
        if(empty($_SESSION['Liquidacion_QX']['TIPO_ANESTESIA']) || $_SESSION['Liquidacion_QX']['NO_GAS']!='1')
        {
      		$desabilitar='disabled';
        }
        $this->salida .= "                  <table width=\"100%\" align=\"center\" border=\"0\">\n";
        $this->salida .= "                  <tr class=\"modulo_list_oscuro\"><td>";
        $this->salida .= "                  <a href=\"javascript:Iniciar();MostrarVentana(d2Container)\" class=\"label\">INSERTAR GAS ANESTESICO</a>\n";
        $this->salida .= "                  </td></tr>";        
	      $this->salida .= "                  </table>";
        $this->salida .= "           </td>";
        $this->salida .= "           </tr>";
        $this->salida .= "           <tr><td colspan=\"3\" id=\"MostrarDatosGases\">";                
        $this->salida .= "<table width=\"100%\" align=\"center\" border=\"0\">\n";
        $this->salida .= "<tr class=\"modulo_list_oscuro\">";
        $this->salida .= "<td align=\"center\" width=\"30%\" class=\"label\">TIPO GAS</td>";
        $this->salida .= "<td align=\"center\" width=\"30%\" class=\"label\">METODO SUMINISTRO</td>";
        $this->salida .= "<td align=\"center\" width=\"20%\" class=\"label\">FRECUENCIA SUMINISTRO(L/m)</td>";
        $this->salida .= "<td align=\"center\" width=\"15%\" class=\"label\">MINUTOS</td>";
        $this->salida .= "<td align=\"center\" width=\"5%\" class=\"label\">&nbsp;</td>";
        $this->salida .= "</tr>";
	
        foreach($_SESSION['Liquidacion_QX']['GASES'] as $i=>$vector)
        {
          $this->salida .= "<tr class=\"modulo_list_oscuro\">";
          $this->salida .= "<td width=\"30%\">".$vector[TipoGasDes]."</td>";
          $this->salida .= "<td width=\"30%\">".$vector[MetodoGasDes]."</td>";
          $this->salida .= "<td width=\"20%\">".$vector[FrecuenciaGas]."/".$vector[FrecuenciaGasDes]."</td>";
          $this->salida .= "<td width=\"20%\">".$vector[MinutosGas]."</td>";          
          $ActionEliminar = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$this->frmPrefijo.""=>'FrmNotasOperatorias', "array"=>array($i)));
      	  if($perfil=='1')
      	  	  $this->salida .= "<td width=\"5%\"><a href='javascript:CargarAccion(\"$ActionEliminar\")' ><img title=\"Eliminar Gas\" border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"></a></td>";
              $this->salida .= "</tr>";
        }
        $this->salida .= "</table>";
        $this->salida .= "           </td></tr>";
        $this->salida .= "       </table><BR>";		
			
	////////////////////////////////////////////FIN GAS		
			
			$cargos = $this->ConsultaCupsNota();
		
			$this->salida.="<BR><table  align=\"center\" border=\"0\" width=\"98%\" cellpadding='0' cellspacing='0'>";
			$this->salida.="<tr class=\"modulo_table_title\" >";
			$this->salida.="  <td align=\"center\" colspan='6'>CARGOS CUPS</td>";
			$this->salida.="</tr>";
			
			$this->salida.="<tr align='justify'>";	
			$i=0;
			$nombrecups = 0;
			while(!$cargos->EOF)
      {			
				if($i<3)
        {
					if($this->ExisteCupsNota($cargos->fields[0]))
						$this->salida.="<td class=\"hc_submodulo_list_oscuro\">".$cargos->fields[0]."-".$cargos->fields[1]."</td><td class=\"hc_submodulo_list_oscuro\"><input type=\"checkbox\" name=".$cargos->fields[0]." value=".$cargos->fields[0]." checked OnClick= Msg2(); $disabled></td>";	
					else
						$this->salida.="<td class=\"hc_submodulo_list_oscuro\">".$cargos->fields[0]."-".$cargos->fields[1]."</td><td class=\"hc_submodulo_list_oscuro\"><input type=\"checkbox\" name=".$cargos->fields[0]." value=".$cargos->fields[0]." OnClick= Msg2(); $disabled></td>";
					$i++;
				}
				else
        {
					if($this->ExisteCupsNota($cargos->fields[0]))
						$this->salida.="</tr><tr align='justify'><td class=\"hc_submodulo_list_oscuro\">".$cargos->fields[0]."-".$cargos->fields[1]."</td><td class=\"hc_submodulo_list_oscuro\"><input type=\"checkbox\" name=".$cargos->fields[0]." value=".$cargos->fields[0]." checked OnClick= Msg2(); $disabled></td>";
					else
						$this->salida.="</tr><tr align='justify'><td class=\"hc_submodulo_list_oscuro\">".$cargos->fields[0]."-".$cargos->fields[1]."</td><td class=\"hc_submodulo_list_oscuro\"><input type=\"checkbox\" name=".$cargos->fields[0]." value=".$cargos->fields[0]." OnClick= Msg2(); $disabled></td>";
					$i=1;
				}
				$cargos->MoveNext();
				$nombrecups+=1;
			}
			
			$this->salida.="<input type=\"hidden\" name=\"activa\" value=\"0\" >";	
			$this->salida.="</tr>";
			$this->salida.="";	
			$cargos->Close();
			$this->salida.= "  </table><BR><BR>";	
		
  		$this->salida .= "  <table border=\"0\" width=\"98%\" align=\"center\">";
  		$this->salida .= "    <tr class=\"modulo_table_title\"><td colspan=\"4\" align=\"center\">CLASIFICACION DE LA CIRUGIA</td></tr>";
  		$this->salida .= "    <tr>\n";
  		$this->salida .= "		  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">VIA ACCESO</td>\n";
      $this->salida .= "      <td align=\"left\" colspan=\"3\" class=\"hc_submodulo_list_oscuro\">\n";
      $this->salida .= "        <select name=\"viaAcceso".$this->frmPrefijo."\" class=\"select\" $enabled>";
  		$accesos=$this->ViaAccesosCirugia();
  		$this->MostrasSelect($accesos,'False',$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['viaAcceso']);
  		$this->salida .= "        </select>\n";
      $this->salida .= "      </td>\n";
  		$this->salida .= "    </tr>\n";
  		$this->salida .= "		<tr>\n";
      $this->salida .= "		  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">TIPO CIRUGIA</td>\n";
      $this->salida .= "      <td align=\"left\" class=\"hc_submodulo_list_oscuro\">\n";
      $this->salida .= "        <select name=\"tipoCirugia".$this->frmPrefijo."\" class=\"select\" $enabled>";
  		$tiposCirugias=$this->TiposdeCirugia();
  		$this->MostrasSelect($tiposCirugias,'False',$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['tipoCirugia']);
  		$this->salida .= "        </select>\n";
      $this->salida .= "      </td>\n";
  		$this->salida .= "		  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">AMBITO CIRUGIA</td>\n";
      $this->salida .= "      <td align=\"left\" class=\"hc_submodulo_list_oscuro\">\n";
      $this->salida .= "        <select name=\"ambitoCirugia".$this->frmPrefijo."\" class=\"select\" $enabled>";
  		$tiposAmbitos=$this->TiposdeAmbitosdeCirugia();
  		$this->MostrasSelect($tiposAmbitos,'False',$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['ambitoCirugia']);
  		$this->salida .= "        </select>\n";
      $this->salida .= "      </td>\n";
   		$this->salida .= "    </tr>\n";
  		$this->salida .= "		<tr>\n";
  		$this->salida .= "		  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">FINALIDAD CIRUGIA</td>\n";
      $this->salida .= "      <td align=\"left\" colspan=\"3\" class=\"hc_submodulo_list_oscuro\"><select name=\"finalidadCirugia".$this->frmPrefijo."\" class=\"select\" $enabled>";
  		$tiposFinalidades=$this->TiposfinalidadesCirugia();
  		$this->MostrasSelect($tiposFinalidades,'False',$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['finalidadCirugia']);
  		$this->salida .= "    </select></td>";
  		$this->salida.= "     </tr>";
  		$this->salida.= "  </table>";
		
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['1'])
    {		
			$this->salida.="<BR><table  align=\"center\" border=\"0\" width=\"98%\">";
		  $this->salida.="<tr class=\"modulo_table_title\">";
		  $this->salida.="  <td align=\"center\" colspan=\"5\">CUMPLIMIENTO DE  PROCEDIMIENTOS PROGRAMADOS</td>";
		  $this->salida.="</tr>";	
		  $this->salida.="<tr class=\"hc_table_submodulo_list_title\">";			
		  $this->salida.="  <td width=\"9%\">CARGO</td>";
		  $this->salida.="  <td width=\"51%\">DESCRIPCION</td>";
		  $this->salida.="  <td colspan= 3 width=\"13%\">OPCION</td>";
		  $this->salida.="</tr>";
			foreach($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['1'] as $codigo=>$procedimiento)
			{
				if( $i % 2)
				{ 
          $estilo='modulo_list_claro';
        }
				else
        {
          $estilo='modulo_list_oscuro';
        }
				$this->salida.="<tr class=\"$estilo\">";
				$row=4;					
				$this->salida.="  <td align=\"center\" width=\"9%\">$codigo</td>";
				$this->salida.="  <td align=\"left\" width=\"52%\">$procedimiento</td>";
				if($perfil == '0')
				{
  				$pfj=$this->frmPrefijo;
  				$accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$this->frmPrefijo.""=>'FrmNotasOperatorias'));
				
  				$accion2=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'EliminaProcedimientoVec4', 'codigo'=>$codigo));
  				$link2 = "href=\"$accion2\"";
				}
				$this->salida .= "<td width=\"5%\" align=\"center\"><img title=\"Procedimiento Realizado\" border=\"0\" src=\"".GetThemePath()."/images/ok.png\" $enabled></td>";
				$this->salida.="  <td align=\"center\" width=\"6%\"><a $link2><img title=\"Eliminar\" src=\"".GetThemePath()."/images/elimina.png\"  border='0' $enabled></a></td>";	
					
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="  <td align=\"left\" width=\"52%\" colspan='5'><b>Observacion: </b>".
				$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['1'][$i]['observaciones']."</td>";
				$this->salida.="</tr>";           				
			}			 
		}		
		
    if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['2'])
    {
			$con=0;
			$this->salida.= "          <BR><table border=\"0\" width=\"98%\" align=\"center\">";
			$this->salida .= "         <tr class=\"hc_table_submodulo_list_title\"><td colspan=\"4\">PROCEDIMIENTOS NO REALIZADOS Y JUSTIFICACION</td></tr>";
			foreach($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['2'] as $codigo=>$procedimiento)
      {				
				$this->salida .= "<tr class=\"hc_submodulo_list_oscuro\">";
				$this->salida .= "<td width=\"10%\">".$codigo."</td>";
				$this->salida .= "<td>".$procedimiento."</td>";
				if($con==0)
        {
					$this->salida .= "<td align=\"center\" rowspan=\"".sizeof($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['2'])."\">";
					$this->salida .= "<textarea name=\"justificacion".$this->frmPrefijo."\" cols=\"50\" rows=\"3\" class=\"textarea\" $enabled>".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['justificacion']."</textarea>";
					$this->salida .= "</td>";				
					$con++;
				}
				$this->salida .= "</tr>";																
			}
			$this->salida.= "        </table>";
		}
    $this->salida.= "<table width=\"98%\" align=\"center\">\n";
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['3'])
    {
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"5\">PROCEDIMIENTOS PARA ADICIONAR</td>";
			$this->salida.="</tr>";		
			foreach($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['3'] as $codigo=>$procedimiento)
      {
				if( $i % 2)
        { 
          $estilo='modulo_list_claro';
        }
        else
        {
          $estilo='modulo_list_oscuro';
        }
				$this->salida.="<tr class=\"$estilo\">";
				$row=4;					
				$this->salida.="  <td align=\"center\" width=\"9%\">$codigo</td>";
				$this->salida.="  <td align=\"left\" width=\"52%\">$procedimiento</td>";	
				
				if($perfil == '0')
				{
  				$pfj=$this->frmPrefijo;
  				$accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$this->frmPrefijo.""=>'FrmNotasOperatorias'));
				  $accion2=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'EliminaProcedimientoV', 'codigo'=>$codigo));
  				$link2 = "href=\"$accion2\"";
				}
				$this->salida .= "<td width=\"5%\" align=\"center\"><img title=\"Procedimiento Realizado\" border=\"0\" src=\"".GetThemePath()."/images/ok.png\" $enabled></td>";
				$this->salida.="  <td align=\"center\" width=\"6%\"><a $link2><img title=\"Eliminar\" src=\"".GetThemePath()."/images/elimina.png\"  border='0' $enabled></a></td>";
				
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="  <td colspan = 1 align=\"left\" width=\"9%\">Observacion</td>";
				$this->salida.="  <td colspan = 4 align=\"left\" width=\"64%\">".$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['3']['observacioness']."</td>";
				$this->salida.="</tr>";				
					
				$i++;
			}		
		}
		$Notaii = $this->ProcedimientosNotaOperatoria($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['NOTA_ID']);
		$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['9']=$Notaii;
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['9'])
    {
		
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"5\">PROCEDIMIENTOS ADICIONADOS</td>";
			$this->salida.="</tr>";		
			
			for($i=0;$i<sizeof($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['9']);$i++)
      {
				if( $i % 2)
				{ 
          $estilo='modulo_list_claro';
        }
				else
        {
          $estilo='modulo_list_oscuro';
        }
				
				$this->salida.="<tr class=\"$estilo\">";
				$row=4;					
				$this->salida.="  <td align=\"center\" width=\"9%\">".
				$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['9'][$i]['procedimiento_qx']."</td>";
				$this->salida.="  <td align=\"left\" width=\"52%\">".
				$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['9'][$i]['descripcion']."</td>";
				
				$codigo = $_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['9'][$i]['procedimiento_qx'];
				if($perfil == '0')
				{
  				$pfj=$this->frmPrefijo;
  				$accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$this->frmPrefijo.""=>'FrmNotasOperatorias'));
  				
  				$accion2=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'EliminaProcedimientoVec3', 'codigo'=>$codigo));
  				$link2 = "href=\"$accion2\"";
				}
				$this->salida .= "<td width=\"5%\" align=\"center\"><img title=\"Procedimiento Realizado\" border=\"0\" src=\"".GetThemePath()."/images/ok.png\" $enabled></td>";
				$this->salida.="  <td align=\"center\" width=\"6%\"><a $link2><img title=\"Eliminar\" src=\"".GetThemePath()."/images/elimina.png\"  border='0' $enabled></a></td>";	
							
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="  <td align=\"left\" width=\"52%\" colspan='5'><b>Observacion: </b>".
				$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['PROCEDIMIENTOS']['9'][$i]['observaciones']."</td>";
				$this->salida.="</tr>";
			}
		}
			 
		$this->salida.="</table>";
		
		$this->salida.= "        <table border=\"0\" width=\"98%\" align=\"center\">";
		$this->salida.= "        <tr><td align=\"center\">";
		$this->salida.= "        <input type=\"submit\" type=\"submit\" value=\"Adicionar Procedimiento\" name=\"AdicionarProc".$this->frmPrefijo."\" class=\"input-submit\" OnClick = HabilitaCampo(); $enabled>";
		$this->salida.= "        </td>
		</tr>";
		$this->salida.= "        </table>";	
			
		$this->salida.= "    <BR><table border=\"0\" width=\"98%\" align=\"center\">";
		$this->salida.= "    <tr class=\"modulo_table_title\"><td colspan=\"5\" align=\"center\">DIAGNOSTICOS</td></tr>";
		$this->salida.= "    <tr class=\"modulo_table_title\">";
		$this->salida.= "    <td width=\"15%\" align=\"center\">&nbsp;</td>";
		$this->salida.= "    <td width=\"15%\" align=\"center\">TIPO DX</td>";
		$this->salida.= "    <td width=\"15%\" align=\"center\">CODIGO</td>";
		$this->salida.= "    <td align=\"center\">DIAGNOSTICO</td>";
		$this->salida.= "    <td width=\"15%\" align=\"center\">&nbsp;</td>";
		$this->salida.= "    </tr>";
		
    $this->salida.= "     <tr class=\"hc_submodulo_list_oscuro\">";
		$this->salida.= "     <td>POST-QUIRURGICO</td>";
		if(!$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['POST_QUIRURGICO'])
    {
			$this->salida.= "     <td>&nbsp;</td>";
			$this->salida.= "     <td>&nbsp;</td>";
			$this->salida.= "     <td>&nbsp;</td>";
		}
    else
    {
			foreach($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['POST_QUIRURGICO'] as $codigo=>$vect)
      {
				foreach($vect as $tipo=>$diagnostico)
        {
					if($tipo == '1')
          {
						$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresion Diagnostica\"></td>";
					}
          elseif($tipo == '2')
          {
						$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
					}
          else
          {
						$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
					}
					$this->salida.= "     <td>$codigo</td>";
					$this->salida.= "     <td>$diagnostico</td>";
				}	
			}		
		}	
		$this->salida.= "     <td align=\"center\"><input type=\"submit\" name=\"BuscarPostQX".$this->frmPrefijo."\" value=\"BUSCAR\" class=\"input-submit\" OnClick = HabilitaCampo(); $enabled ></td>";
		$this->salida.= "    </tr>";
		$this->salida.= "     <tr class=\"hc_submodulo_list_oscuro\">";
		$this->salida.= "     <td>COMPLICACION</td>";
		if(!$_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['COMPLICACION'])
    {
			$this->salida.= "     <td>&nbsp;</td>";
			$this->salida.= "     <td>&nbsp;</td>";
			$this->salida.= "     <td>&nbsp;</td>";
		}
    else
    {
			foreach($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['COMPLICACION'] as $codigo=>$vect)
      {
				foreach($vect as $tipo=>$diagnostico)
        {
					if($tipo == '1')
          {
						$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresion Diagnostica\"></td>";
					}
          elseif($tipo == '2')
          {
						$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
					}
          else
          {
						$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
					}
					$this->salida.= "     <td>$codigo</td>";
					$this->salida.= "     <td>$diagnostico</td>";
				}	
			}
		}	
		$this->salida.= "     <td align=\"center\"><input type=\"submit\" name=\"BuscarComplicacion".$this->frmPrefijo."\" value=\"BUSCAR\" class=\"input-submit\" OnClick = HabilitaCampo(); $enabled></td>";
		$this->salida.= "    </tr>";		
		$this->salida.= "    </table>";		 		
		
		$this->salida.= "    <BR><table border=\"0\" width=\"98%\" align=\"center\">";
		$che='';
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['SelMaterialPat']==1)
    {
      $che1='checked';
    }
		$this->salida.= "    <tr class=\"modulo_table_title\"><td align=\"center\">MATERIAL ENVIADO A PATOLOGIA&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"SelMaterialPat".$this->frmPrefijo."\" value=\"1\" $che1 $enabled></td></tr>";
		$this->salida.= "    <tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.= "    <td><label class=\"label\">CLASE DE MATERIAL ENVIADO</label><BR><textarea name=\"MaterialPat\" class=\"textarea\" cols=\"80\" rows=\"3\"  $enabled>".$_REQUEST['MaterialPat']."</textarea></td>";
		$this->salida.= "    </tr>";			
		$this->salida.= "    </table><BR>";		 		
		$this->salida.= "    <BR><table border=\"0\" width=\"98%\" align=\"center\">";
		
    $che1='';
		if($_SESSION['NOTAS_OPERATORIAS'.$this->frmPrefijo]['SelCultivo']==1)
    {
      $che1='checked';
    }
		$this->salida.= "    <tr class=\"modulo_table_title\"><td align=\"center\">CULTIVO ENVIADO&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"SelCultivo".$this->frmPrefijo."\" value=\"1\" $che1 $enabled></td></tr>";
		$this->salida.= "    <tr class=\"hc_table_submodulo_list_title\">";
		//$this->salida.="<pre>".print_r($_REQUEST,true)."</pre>";
    $this->salida.= "    <td><label class=\"label\">DESCRIPCION DEL CULTIVO</label><BR>
                         <textarea name=\"Cultivo\" class=\"textarea\" cols=\"80\" rows=\"3\" $enabled>".$_REQUEST['Cultivo']."</textarea></td>";
		$this->salida.= "    </tr>";			
		$this->salida.= "    </table><BR>";				
		$this->salida.="<table width=\"98%\" border=\"0\" align=\"center\">";
		$this->salida.="<tr class='modulo_table_title'>";
		$this->salida.="<td align='center'>HALLAZGOS";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td align='center' >";
		$this->salida.="<br><textarea name=\"hallazgos\" cols=\"80\" rows=\"3\"  class=\"textarea\" $enabled>".$_REQUEST['hallazgos']."</textarea>";
		$this->salida.="<p align=\"center\">";
		$this->salida.="</p><p></p>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="</table>";
			
		$this->salida.="<table width=\"98%\" border=\"0\" align=\"center\">";
		$this->salida.="<tr class='modulo_table_title'>";
		$this->salida.="<td align='center'>DESCRIPCIONES";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td align='center'>";
		$this->salida.="<br><textarea name=\"descripciones\" cols=\"80\" rows=\"3\" class=\"textarea\" $enabled>".$_REQUEST['descripciones']."</textarea>";
		$this->salida.="<p align=\"center\">";
		$this->salida.="</p><p></p>";
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.= "</table>";
		$this->salida .= "<BR>\n";
    $this->salida .= "<table border=\"0\" width=\"98%\" align=\"center\">\n";
		$this->salida .= "  <tr>\n";
    $this->salida .= "    <td align=\"center\">\n";
    $this->salida .= "      <input type=\"submit\" name=\"GuardarNota".$this->frmPrefijo."\" value=\"Guardar Nota\" class=\"input-submit\" OnClick = HabilitaCampo();>\n";
    $this->salida .= "    </td>\n";
		$this->salida .= "  </form>\n";
    $accionV = ModuloHCGetURL($this->evolucion,$this->paso,0,'',false);
		$this->salida .= "  <form name=\"volver\" action=\"".$accionV."\" method=\"post\">\n";
    $this->salida .= "    <td align=\"center\">\n";
    $this->salida .= "      <input type=\"submit\" name=\"Volver\" value=\"Volver\" class=\"input-submit\" >\n";
    $this->salida .= "    </td>\n";
    $this->salida .= "  </form>\n";
    $this->salida .= "  </tr>\n";
		$this->salida .= "</table>";		 		
    

		$this->frmReporteHallazgos($enabled);
		$this->frmReporteDescripcion($enabled);
		$this->frmReportePatologia($enabled);
		$this->frmReporteCultivo($enabled);
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}
	
	
	function frmReporteDescripcion($enabled)
	{
		$pfj=$this->frmPrefijo;
		$datos=$this->DescripcionCirugia_Reporte();
		$accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$this->frmPrefijo.""=>'FrmNotasOperatorias'));
		
		if($datos===false)
		{
			return false;
		}
		if(!empty($datos))
		{
			$this->salida .="<br><table width=\"90%\" border=\"0\" align=\"center\">";
			$this->salida .="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida .="<td>FECHA</td>";
			$this->salida .="<td align=\"center\">DESCRIPCION DE LA CIRUGIA</td>";
			$this->salida .="</tr>";

			$spy=0;
			foreach($datos as $k=>$v)
			{
				if($spy==0)
				{
					$this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
					$spy=1;
				}
				else
				{
					$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
					$spy=0;
				}

				$this->salida .="<td width='10%' align='center'>$k</td>";
				$this->salida .="<td><table border='0' width='100%'>";
				foreach($v as $k2=>$vector)
        {
					$this->salida .="<tr class=\"hc_submodulo_list_oscuro\">";
					$this->salida .="<td><b>$vector[hora]</b></td>";
					$this->salida .="<td><b>";
					$this->salida .=$vector[usuario].' - '.$vector[nombre];
					$this->salida .="</b></td>";
					$this->salida .="</tr>";
					$this->salida .="<tr class=\"hc_submodulo_list_claro\">";
					$this->salida .="<td class=\"hc_submodulo_list_oscuro\">&nbsp;</td>";
					$this->salida .="<td width='100%'>$vector[descripcion]</td>";
					$this->salida .="</tr>";
					$this->salida .="<tr>";

				}
				$this->salida .="</table>";
				$this->salida .="</td>";
				$this->salida .="</tr>";
			}
			$this->salida.="</table>";
			//$this->salida .= "</form>";
    }
		else
		{
			$this->salida.="<table width=\"100%\" border=\"0\" align=\"center\">";
			$this->salida.="<tr  align=\"center\"><br><td><label class='label_mark'>NO HAY RESUMEN PARA ESTE PACIENTE</label>";
			$this->salida.="</td></tr>";
			$this->salida.="</table>";
			return false;
		}
    return true;
	}
	
	
	function frmReporteHallazgos($enabled)
	{
		$datos=$this->HallazgosQuirurgicos_Reporte();
		$pfj = $this->frmPrefijo;
		$accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$this->frmPrefijo.""=>'FrmNotasOperatorias'));
		
		if($datos===false)
		{
			return false;
		}
		if(!empty($datos))
		{
			$this->salida .="<br><table width=\"90%\" border=\"0\" align=\"center\">";
			$this->salida .="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida .="<td>FECHA</td>";
			$this->salida .="<td align=\"center\">HALLAZGOS DE LA CIRUGIA</td>";
			$this->salida .="</tr>";
			$spy=0;
			foreach($datos as $k=>$v)
			{
				if($spy==0)
				{
					$this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
					$spy=1;
				}
				else
				{
					$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
					$spy=0;
				}

				$this->salida .="<td width='10%' align='center'>$k</td>";
				$this->salida .="<td><table border='0' width='100%'>";
				foreach($v as $k2=>$vector)
        {
					$this->salida .="<tr class=\"hc_submodulo_list_oscuro\">";
					$this->salida .="<td><b>$vector[hora]</b></td>";
					$this->salida .="<td><b>";
					$this->salida .=$vector[usuario].' - '.$vector[nombre];
					$this->salida .="</b></td>";
					$this->salida .="</tr>";
					$this->salida .="<tr class=\"hc_submodulo_list_claro\">";
					$this->salida .="<td class=\"hc_submodulo_list_oscuro\">&nbsp;</td>";
					$this->salida .="<td width='100%'>$vector[descripcion]</td>";
					
					$this->salida .="</tr>";
					$this->salida .="<tr>";
        }
				$this->salida .="</table>";
				$this->salida .="</td>";
				$this->salida .="</tr>";
			}
			$this->salida.="</table>";
    }
		else
		{
			$this->salida.="<table width=\"100%\" border=\"0\" align=\"center\">";
			$this->salida.="<tr  align=\"center\"><br><td><label class='label_mark'>NO HAY RESUMEN PARA ESTE PACIENTE</label>";
			$this->salida.="</td></tr>";
			$this->salida.="</table>";
			return false;
		}
  return true;
	}
	
	function frmReportePatologia($enabled)
	{
		$datos=$this->PatologiaQuirurgicos_Reporte();
		$pfj = $this->frmPrefijo;
		$accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$this->frmPrefijo.""=>'FrmNotasOperatorias'));
		
		if($datos===false)
		{
			return false;
		}
		if(!empty($datos))
		{
			$this->salida .="<br><table width=\"90%\" border=\"0\" align=\"center\">";
			$this->salida .="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida .="<td>FECHA</td>";
			$this->salida .="<td align=\"center\">PATOLOGIAS DE LA CIRUGIA</td>";
			$this->salida .="</tr>";
    	$spy=0;
			foreach($datos as $k=>$v)
			{
				if($spy==0)
				{
					$this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
					$spy=1;
				}
				else
				{
					$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
					$spy=0;
				}
				$this->salida .="<td width='10%' align='center'>$k</td>";
				$this->salida .="<td><table border='0' width='100%'>";
				foreach($v as $k2=>$vector)
        {
					$this->salida .="<tr class=\"hc_submodulo_list_oscuro\">";
					$this->salida .="<td><b>$vector[hora]</b></td>";
					$this->salida .="<td><b>";
					$this->salida .=$vector[usuario].' - '.$vector[nombre];
					$this->salida .="</b></td>";
					$this->salida .="</tr>";
					$this->salida .="<tr class=\"hc_submodulo_list_claro\">";
					$this->salida .="<td class=\"hc_submodulo_list_oscuro\">&nbsp;</td>";
					$this->salida .="<td width='100%'>$vector[descripcion]</td>";
					$this->salida .="</tr>";
					$this->salida .="<tr>";
        }
				$this->salida .="</table>";
				$this->salida .="</td>";
				$this->salida .="</tr>";
			}
			$this->salida.="</table>";
    }
		else
		{
			$this->salida.="<table width=\"100%\" border=\"0\" align=\"center\">";
			$this->salida.="<tr  align=\"center\"><br><td><label class='label_mark'>NO HAY RESUMEN PARA ESTE PACIENTE</label>";
			$this->salida.="</td></tr>";
			$this->salida.="</table>";
			return false;
		}
    return true;
	}
	
	function frmReporteCultivo($enabled)
	{
		$datos=$this->CultivoQuirurgicos_Reporte();
		$pfj = $this->frmPrefijo;
		$accionI=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$this->frmPrefijo.""=>'FrmNotasOperatorias'));	
		if($datos===false)
		{
			return false;
		}
		if(!empty($datos))
		{
			$this->salida .="<br><table width=\"90%\" border=\"0\" align=\"center\">";
			$this->salida .="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida .="<td>FECHA</td>";
			$this->salida .="<td align=\"center\">CULTIVOS DE LA CIRUGIA</td>";
			$this->salida .="</tr>";

			$spy=0;
			foreach($datos as $k=>$v)
			{
				if($spy==0)
				{
					$this->salida.="<tr class=\"hc_submodulo_list_oscuro\">";
					$spy=1;
				}
				else
				{
					$this->salida.="<tr class=\"hc_submodulo_list_claro\">";
					$spy=0;
				}
				$this->salida .="<td width='10%' align='center'>$k</td>";
				$this->salida .="<td><table border='0' width='100%'>";
				foreach($v as $k2=>$vector)
        {
					$this->salida .="<tr class=\"hc_submodulo_list_oscuro\">";
					$this->salida .="<td><b>$vector[hora]</b></td>";
					$this->salida .="<td><b>";
					$this->salida .=$vector[usuario].' - '.$vector[nombre];
					$this->salida .="</b></td>";
					$this->salida .="</tr>";
					$this->salida .="<tr class=\"hc_submodulo_list_claro\">";
					$this->salida .="<td class=\"hc_submodulo_list_oscuro\">&nbsp;</td>";
					$this->salida .="<td width='100%'>$vector[descripcion]</td>";
					
					$this->salida .="</tr>";
					$this->salida .="<tr>";
				}
				$this->salida .="</table>";
				$this->salida .="</td>";
				$this->salida .="</tr>";
			}
			$this->salida.="</table>";
    }
		else
		{
			$this->salida.="<table width=\"100%\" border=\"0\" align=\"center\">";
			$this->salida.="<tr  align=\"center\"><br><td><label class='label_mark'>NO HAY RESUMEN PARA ESTE PACIENTE</label>";
			$this->salida.="</td></tr>";
			$this->salida.="</table>";
			return false;
		}
   return true;
	}
	
	function frmForma_BuscarDiagnosticos($tipoDiag)
  {
		$pfj=$this->frmPrefijo;
		$this->paginaActual = 1;
    $this->offset = 0;
    if($_REQUEST['offset'])
    {
      $this->paginaActual = intval($_REQUEST['offset']);
      if($this->paginaActual > 1)
      {
        $this->offset = ($this->paginaActual - 1) * ($this->limit);
      }
    }
		$this->salida= ThemeAbrirTablaSubModulo('BUSQUEDA DIAGNOSTICO');
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$this->frmPrefijo=>'FrmBuscarDiagnosticosPost',"tipoDiag".$this->frmPrefijo=>$tipoDiag));
		$this->salida .= "<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";		
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA DE DIAGNOSTICOS </td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"4%\">CODIGO:</td>";
		$this->salida .="<td width=\"5%\" align='center'><input type='text' class='input-text' size = 6 maxlength = 6	name = codigo".$this->frmPrefijo." value=\"".$_REQUEST['codigo'.$this->frmPrefijo]."\"></td>" ;
		//la misma pero con el value $this->salida .="<td width=\"5%\" align='center'><input type='text' class='input-text' size = 6 maxlength = 6	name = 'codigo$pfj'  value =\"".$_REQUEST['codigo'.$pfj]."\"    ></td>" ;
		$this->salida.="<td width=\"9%\">DIAGNOSTICO:</td>";
		$this->salida .="<td width=\"55%\" align='center'><input type='text' size =50 class='input-text' 	name = \"diagnostico".$this->frmPrefijo."\"   value =\"".$_REQUEST['diagnostico'.$this->frmPrefijo]."\"></td>" ;
		$this->salida .= "<td  width=\"7%\" align=\"center\"><input class=\"input-submit\" name=\"buscar".$this->frmPrefijo."\" type=\"submit\" value=\"BUSQUEDA\"></td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";
		$vectorD=$this->Busqueda_Avanzada_Diagnosticos($_REQUEST['codigo'.$this->frmPrefijo],$_REQUEST['diagnostico'.$this->frmPrefijo]);
    if ($vectorD)
    {
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="<td align=\"center\" colspan=\"4\">RESULTADO DE LA BUSQUEDA</td>";
			$this->salida.="</tr>";
			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td width=\"8%\">CODIGO</td>";
			$this->salida.="  <td width=\"60%\">DIAGNOSTICO</td>";
			$this->salida.="  <td width=\"17%\">TIPO DX</td>";
			$this->salida.="  <td width=\"5%\">OPCION</td>";
			$this->salida.="</tr>";
			for($i=0;$i<sizeof($vectorD);$i++)
      {	
				$codigo          = $vectorD[$i][diagnostico_id];
				$diagnostico    = $vectorD[$i][diagnostico_nombre];
				if( $i % 2)
        { 
          $estilo='modulo_list_claro';
        }
				else 
        {
          $estilo='modulo_list_oscuro';
        }
				$this->salida.="<tr class=\"$estilo\">";

				$this->salida.="  <td align=\"center\" width=\"8%\">$codigo</td>";
				$this->salida.="  <td align=\"left\" width=\"60%\">$diagnostico</td>";
				$this->salida.="<td align=\"center\" width=\"17%\">";
				$this->salida.="<input type=\"radio\" name=\"dx".$this->frmPrefijo."[$codigo]\" value=\"1\">&nbsp;ID&nbsp;&nbsp;";
				$this->salida.="<input type=\"radio\" name=\"dx".$this->frmPrefijo."[$codigo]\" value=\"2\">&nbsp;CN&nbsp;&nbsp;";
				$this->salida.="<input type=\"radio\" name=\"dx".$this->frmPrefijo."[$codigo]\" value=\"3\">&nbsp;CR&nbsp;&nbsp;</td>";
				$this->salida.="<td align=\"center\" width=\"5%\"><input type = checkbox name= \"opD".$this->frmPrefijo."[]\" value = \"".$codigo."||//".$diagnostico."\"></td>";
				$this->salida.="</tr>";
      }
      $this->salida.="<tr class=\"$estilo\">";
			$this->salida.="<td align=\"center\" colspan=\"4\" valign=\"top\"><img src=\"".GetThemePath()."/images/id.png\"  border='0' title=\"ID\">&nbsp;( ID )&nbsp;-&nbsp;IMPRESION DIAGNOSTICA&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"CN\">&nbsp;( CN )&nbsp;-&nbsp;CONFIRMADO NUEVO&nbsp;&nbsp;&nbsp;<img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"CR\">&nbsp;( CR )&nbsp;-&nbsp;CONFIRMADO REPETIDO&nbsp;&nbsp;&nbsp;</td>";
			$this->salida.="</tr>";         
			$this->salida.="<tr class=\"$estilo\">";
			$this->salida.= "<td align=\"right\" colspan=\"4\"><input class=\"input-submit\" name=\"guardar".$this->frmPrefijo."\" type=\"submit\" value=\"GUARDAR\"></td>";
			$this->salida.="</tr>";
			$this->salida.="</table><br>";
			$Paginador = new ClaseHTML();
      $this->actionPaginador=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$this->frmPrefijo=>'FrmBuscarDiagnosticosPost',"buscar".$this->frmPrefijo=>'BUSCAR',"tipoDiag".$this->frmPrefijo=>$tipoDiag,"codigo".$pfj=>$_REQUEST['codigo'.$pfj],'diagnostico'.$pfj=>$_REQUEST['diagnostico'.$pfj]));
			$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionPaginador);	
    }
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"90%\">";
		$this->salida.="<tr><td align=\"center\"><input type=\"submit\" name=\"Volver".$this->frmPrefijo."\" value=\"VOLVER\" class=\"input-submit\"></td></tr>";
		$this->salida.="</table>";
    $this->salida .= "</form>";
		$this->salida .= ThemeCerrarTablaSubModulo();
    return true;
	}
	
	
	function frmForma_Modificar_Observacion($codigo)
  {
    $pfj=$this->frmPrefijo;
    //echo '<br><br><br><br>'.$codigo;
    $opcionesProcedimientos=$this->BuscarOpcionesProcedimientos($codigo);   
    //echo $opcionesProcedimientos[0]['cargo'];
    //print_r($opcionesProcedimientos); 
		$this->paginaActual = 1;
    $this->offset = 0;
    if($_REQUEST['offset'])
    {
      $this->paginaActual = intval($_REQUEST['offset']);
      if($this->paginaActual > 1)
      {
        $this->offset = ($this->paginaActual - 1) * ($this->limit);
      }
    }
		$this->salida= ThemeAbrirTablaSubModulo('MODIFICAR PROCEDIMIENTO QUIRURGICO');
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'FrmModificarProcedimientos'));
		$this->salida .= "<form name=\"formades".$this->frmPrefijo."\" action=\"$accion\" method=\"post\">";		
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"7\">PROCEDIMIENTO </td>";
		$this->salida.="</tr>";
		$_SESSION['MProcedimiento']['cargo']=$opcionesProcedimientos[0]['cargo'];
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"5%\">CARGO</td>";		
		$this->salida .="<td><input type='text' size =10 class='input-text' size = 10 maxlength = 10 name ='cargo'  value =\"".$opcionesProcedimientos[0]['cargo']."\" disabled='disabled'   ></td>" ;
		$this->salida.="<td width=\"6%\">DESCRIPCION:</td>";
		$this->salida .="<td><input type='text' size =80 class='input-text' maxlength = 80	name ='descripcion'  value =\"".$opcionesProcedimientos[0]['descripcion']."\"  disabled='disabled'  ></td>" ;
		$this->salida.="</tr>";	
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida .= "<td   align=\"right\">&nbsp;</td>";
		$this->salida.="<td>&nbsp;</td>";
		$this->salida.="<td>OBSERVACION:</td>";
		$this->salida .="<td><input type='text' size =80 class='input-text' maxlength = 80	name ='observacion'  value =\"".$opcionesProcedimientos[0]['observaciones']."\"    ></td>" ;
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida .= "<td colspan='4' align=\"right\"><input class=\"input-submit\" name=\"buscar".$this->frmPrefijo."\" type=\"submit\" value=\"GUARDAR\"></td>" ;
		$this->salida.="</tr>";
		$this->salida.="</table><br>";	
		$this->salida .= "</form>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
  }
					
	function frmForma_ProcedimientosQX()
  {
		$pfj=$this->frmPrefijo;
		$this->paginaActual = 1;
    $this->offset = 0;
    if($_REQUEST['offset'])
    {
      $this->paginaActual = intval($_REQUEST['offset']);
      if($this->paginaActual > 1)
      {
        $this->offset = ($this->paginaActual - 1) * ($this->limit);
      }
    }
		$this->salida= ThemeAbrirTablaSubModulo('PROCEDIMIENTOS QUIRURGICOS');
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'FrmBuscadorProcedimientos'));
		$this->salida .= "<form name=\"formades".$this->frmPrefijo."\" action=\"$accion\" method=\"post\">";		
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr class=\"modulo_table_title\">";
		$this->salida.="  <td align=\"center\" colspan=\"5\">BUSQUEDA AVANZADA </td>";
		$this->salida.="</tr>";

		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"5%\">TIPO</td>";		
		$this->salida.="<td colspan=\"4\" align = left >";
		$this->salida.="<select name=\"tipoProcedimiento".$this->frmPrefijo."\" class=\"select\">";
	  $tiposProcedimientos=$this->tiposdeProcedimientos();
	  $this->MostrartiposdeProcedimientos($tiposProcedimientos,'False',$_REQUEST['tipoProcedimiento'.$this->frmPrefijo]);
	  $this->salida .= "</select>";		
		
		$this->salida.="</td>";
		$this->salida.="</tr>";
		$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
		$this->salida.="<td width=\"6%\">CARGO:</td>";
		$this->salida .="<td width=\"11%\" align='center'><input type='text' size =10 class='input-text' size = 10 maxlength = 10	name =\"cargo".$this->frmPrefijo."\"  value =\"".$_REQUEST['cargo'.$this->frmPrefijo]."\"    ></td>" ;

		$this->salida.="<td width=\"10%\">DESCRIPCION:</td>";
		$this->salida .="<td width=\"25%\" align='center'><input type='text' class='input-text' 	name = \"descripcion".$this->frmPrefijo."\"   value =\"".$_REQUEST['descripcion'.$this->frmPrefijo]."\"        ></td>" ;

		$this->salida .= "<td  width=\"6%\" align=\"center\"><input class=\"input-submit\" name=\"buscar".$this->frmPrefijo."\" type=\"submit\" value=\"BUSQUEDA\"></td>";
		$this->salida.="</tr>";
		$this->salida.="</table><br>";

		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida .= $this->SetStyle("MensajeError");
		$this->salida.="</table>";		
		$vectorA=$this->RegistrosCargosCups($_REQUEST['tipoProcedimiento'.$this->frmPrefijo],$_REQUEST['cargo'.$this->frmPrefijo],$_REQUEST['descripcion'.$this->frmPrefijo]);
		if($vectorA)
    {
			$this->salida.="<table  align=\"center\" border=\"0\"  width=\"100%\">";
			$this->salida.="<tr class=\"modulo_table_title\">";
			$this->salida.="  <td align=\"center\" colspan=\"4\">RESULTADO DE LA BUSQUEDA</td>";
			$this->salida.="</tr>";

			$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="  <td width=\"30%\">TIPO</td>";
			$this->salida.="  <td width=\"10%\">CARGO</td>";
			$this->salida.="  <td>DESCRIPCION</td>";
			$this->salida.="  <td width=\"3%\">OPCION</td>";
			$this->salida.="</tr>";
			for($i=0;$i<sizeof($vectorA);$i++)
      {
				if( $i % 2){ $estilo='modulo_list_claro';}
				else {$estilo='modulo_list_oscuro';}
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="  <td align=\"center\" width=\"15%\">".$vectorA[$i][tipo]."</td>";
				$this->salida.="  <td align=\"center\" width=\"10%\">".$vectorA[$i][cargo]."</td>";
				$this->salida.="  <td align=\"left\" width=\"50%\">".$vectorA[$i][descripcion]."</td>";
				$this->salida.="  <td align=\"center\" width=\"5%\"><input type = checkbox name= \"op".$this->frmPrefijo."[".$vectorA[$i][cargo]."]\" value = \"".$vectorA[$i][descripcion]."\"></td>";
				$this->salida.="</tr>";
			}
			$this->salida.="<tr class=\"$estilo\">";
			$this->salida .= "<td align=\"right\" colspan=\"4\"><input class=\"input-submit\" name=\"guardar".$this->frmPrefijo."\" type=\"submit\" value=\"GUARDAR\"></td>";
			$this->salida.="</tr>";
			$this->salida.="</table><br>";			
			$Paginador = new ClaseHTML();
      $this->actionPaginador=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$this->frmPrefijo=>'FrmBuscadorProcedimientos',"buscar".$this->frmPrefijo=>'1',"cargo".$this->frmPrefijo=>$_REQUEST['cargo'.$this->frmPrefijo],"descripcion".$this->frmPrefijo=>$_REQUEST['descripcion'.$this->frmPrefijo],"tipoProcedimiento".$this->frmPrefijo=>$_REQUEST['tipoProcedimiento'.$this->frmPrefijo]));
			$this->salida .= "		".$Paginador->ObtenerPaginado($this->conteo,$this->paginaActual,$this->actionPaginador);
		}
		$this->salida.="<BR><table align=\"center\" border=\"0\"  width=\"80%\">";
		$this->salida.="<tr><td align=\"center\"><input class=\"input-submit\" name=\"volver".$this->frmPrefijo."\" type=\"submit\" value=\"VOLVER\"></td></tr>";
		$this->salida.="</table>";			
		$this->salida .= "</form>";
		
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
  }

/**
* Funcion que se encarga de listar los nombres de los tipos de origen de una cirugia
* @return array
* @param array codigos y valores de los tipos de origen de la base de datos
* @param boolean indicador de selecion de un elemento en el objeto donde se imprimen los tipos de origen
* @param string elemento seleccionado en el objeto donde se imprimen los tipo de origen
*/
	function MostrasSelect($arreglo,$Seleccionado='False',$valor='')
  {
		switch($Seleccionado)
    {
			case 'False':
      {
        $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
				foreach($arreglo as $value=>$titulo)
        {
					if($value==$valor)
          {
					$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				  }
          else
          {
            $this->salida .=" <option value=\"$value\">$titulo</option>";
				  }
			  }
			  break;
		  }
      case 'True':
      {
			  foreach($arreglo as $value=>$titulo)
        {
				  if($value==$valor)
          {
				    $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				  }
				  $this->salida .=" <option value=\"$value\">$titulo</option>";
			  }
			  break;
		  }
	  }
	}


	function SetStyle($campo)
	{
	  if ($this->frmError[$campo]||$campo=="MensajeError")
		{
		  if ($campo=="MensajeError")
			{
			  return ("<tr><td align=\"center\" class=\"label_error\">".$this->frmError["MensajeError"]."</td></tr>");
			}
			return ("label_error");
		}
		return ("label");
	}

	/**
	* Funcion que se encarga de separar la fecha del formato timestamp
	* @return array
	*/
	function FechaStamp($fecha)
  {
    if($fecha)
    {
			$fech = strtok ($fecha,"-");
			for($l=0;$l<3;$l++)
			{
				$date[$l]=$fech;
				$fech = strtok ("-");
			}
			return  ceil($date[2])."/".ceil($date[1])."/".ceil($date[0]);
    }
  }
  
	/**
	* Funcion que se encarga de separar la hora del formato timestamp
	* @return array
	*/
  function HoraStamp($hora)
  {
   $hor = strtok ($hora," ");
   for($l=0;$l<4;$l++)
   {
		 $time[$l]=$hor;
     $hor = strtok (":");
   }
    return  $time[1].":".$time[2].":".$time[3];
  }

 /**
* Funcion que se encarga de listar los nombres de los profesionales especialistas y visualizarlos
* @return array
* @param array codigos y valores de los profesionales de la base de datos
* @param boolean indicador de selecion de un elemento en el objeto donde se imprimen los profesionales
* @param string elemento seleccionado en el objeto donde se imprimen los profesionales
*/
	function BuscarProfesionlesEspecialistas($profesionales,$Seleccionado='False',$Profesionales='')
  {
		switch($Seleccionado)
    {
			case 'False':
      {
        $this->salida .=" <option value=\"-1\">---Seleccione---</option>";
				for($i=0;$i<sizeof($profesionales);$i++)
        {
				  $value=$profesionales[$i]['tercero_id'].'/'.$profesionales[$i]['tipo_id_tercero'];
					$titulo=$profesionales[$i]['nombre'];
					if($value==$Profesionales)
          {
					$this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				  }
          else
          {
            $this->salida .=" <option value=\"$value\">$titulo</option>";
				  }
			  }
			  break;
		  }
      case 'True':
      {
			  for($i=0;$i<sizeof($profesionales);$i++)
        {
			    $value=$profesionales[$i]['tercero_id'].'/'.$profesionales[$i]['tipo_id_tercero'];
					$titulo=$profesionales[$i]['nombre'];
				  if($value==$Profesionales)
          {
				    $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				  }
				  $this->salida .=" <option value=\"$value\">$titulo</option>";
			  }
			  break;
		  }
	  }
	}

 function BuscadorDiagnostico($codigoDes,$descripcionDes)
 {
    $this->salida  = ThemeAbrirTablaSubModulo('BUSQUEDA DE DIAGNOSTICOS');
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$this->frmPrefijo.""=>'SeleccionDiagnostico',"buscar".$this->frmPrefijo.""=>$_REQUEST['buscar'.$this->frmPrefijo],"buscar1".$this->frmPrefijo.""=>$_REQUEST['buscar1'.$this->frmPrefijo],
		"cargo".$this->frmPrefijo=>$_REQUEST['cargo'.$this->frmPrefijo],
		"codigo".$this->frmPrefijo=>$_REQUEST['codigo'.$this->frmPrefijo],
		"cargo1".$this->frmPrefijo=>$_REQUEST['cargo1'.$this->frmPrefijo],
		"codigo1".$this->frmPrefijo=>$_REQUEST['codigo1'.$this->frmPrefijo]));
		$this->salida .= "  <form name='formauno".$this->frmPrefijo."' action=$accion method='post'>";
    $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "  <tr class=\"modulo_table_title\"><td colspan=\"5\" align=\"center\">DIAGNOSTICOS</td></tr>";
    $this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">";
		$this->salida .= "  <td width=\"10%\">CODIGO</td><td align=\"left\" class=\"hc_submodulo_list_oscuro\"><input size=\"6\" type=\"text\" name=\"codigoDes".$this->frmPrefijo."\" value=\"$codigoDes\"></td>";
		$this->salida .= "  <td width=\"13%\">DESCRIPCION</td><td align=\"left\" class=\"hc_submodulo_list_oscuro\"><input size=\"60\" type=\"text\" name=\"descripcionDes".$this->frmPrefijo."\" value=\"$descripcionDes\"></td>";
		$this->salida .= "  <td width=\"10%\"><input type=\"submit\" name=\"Buscar".$this->frmPrefijo."\" value=\"BUSCAR\"></td>";
		$this->salida .= "  </tr>";
    $this->salida .= "  </table><BR>";
		$diagnosticos=$this->RegistrosDiagnosticos($codigoDes,$descripcionDes);
		if($diagnosticos)
    {
      $this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
			$this->salida .= "  <tr class=\"modulo_table_title\">";
			$this->salida .= "  <td>CODIGO</td>";
			$this->salida .= "  <td>DESCRIPCION</td>";
			$this->salida .= "  <td>&nbsp;</td>";
			$this->salida .= "  </tr>";
			$y=0;
			for($i=0;$i<sizeof($diagnosticos);$i++)
      {
			  if($y % 2){$estilo='hc_submodulo_list_claro';}else{$estilo='hc_submodulo_list_oscuro';}
        $this->salida .= "  <tr class=\"$estilo\">";
        $this->salida .= "  <td width=\"15%\">".$diagnosticos[$i]['diagnostico_id']."</td>";
			  $this->salida .= "  <td>".$diagnosticos[$i]['diagnostico_nombre']."</td>";
				$this->salida .= "  <td align=\"center\" width=\"5%\">";
				$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$this->frmPrefijo.""=>'SeleccionDiagnostico',"codigoDiagnostico".$this->frmPrefijo.""=>$diagnosticos[$i]['diagnostico_id'],"nombreDiagnostico".$this->frmPrefijo.""=>$diagnosticos[$i]['diagnostico_nombre'],
				"buscar".$this->frmPrefijo.""=>$_REQUEST['buscar'.$this->frmPrefijo],"buscar1".$this->frmPrefijo.""=>$_REQUEST['buscar1'.$this->frmPrefijo],"bandera".$this->frmPrefijo.""=>1,
				"cargo".$this->frmPrefijo=>$_REQUEST['cargo'.$this->frmPrefijo],
				"codigo".$this->frmPrefijo=>$_REQUEST['codigo'.$this->frmPrefijo],
				"cargo1".$this->frmPrefijo=>$_REQUEST['cargo1'.$this->frmPrefijo],
				"codigo1".$this->frmPrefijo=>$_REQUEST['codigo1'.$this->frmPrefijo]));
				$this->salida .= "  <a href=\"$accion\" class=\"link\"><b><img border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"></b></a>";
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$y++;
			}
			$this->salida .= "  </table><BR>";
			$this->salida .=$this->RetornarBarra(1);
		}
		$this->salida .= "  <BR><table border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "  <tr><td class=\"input-submit\" align=\"center\"><input type=\"submit\" value=\"SALIR\" name=\"salir".$this->frmPrefijo."\"></td></tr>";
		$this->salida .= "  </table>";
    $this->salida .= "  </form>";
		$this->salida .= ThemeCerrarTablaSubModulo();
    return true;
  }

/**
* Funcion que se encarga de listar los nombres de los profesionales especialistas y visualizarlos
* @return array
* @param array codigos y valores de los profesionales de la base de datos
* @param boolean indicador de selecion de un elemento en el objeto donde se imprimen los profesionales
* @param string elemento seleccionado en el objeto donde se imprimen los profesionales
*/
	function MostrartiposdeProcedimientos($tiposProcedimientos,$Seleccionado='False',$tipoProcedimiento='')
  {
		switch($Seleccionado)
    {
			case 'False':
      {
        $this->salida .=" <option value=\"-1\">----------Todos-------</option>";
				for($i=0;$i<sizeof($tiposProcedimientos);$i++)
        {
				  $value=$tiposProcedimientos[$i]['tipo_cargo'].'/'.$tiposProcedimientos[$i]['grupo_tipo_cargo'];
					$titulo=$tiposProcedimientos[$i]['descripcion'];
					if($value==$tipoProcedimiento)
          {
					  $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				  }
          else
          {
            $this->salida .=" <option value=\"$value\">$titulo</option>";
				  }
			  }
			  break;
		  }
      case 'True':
      {
			  for($i=0;$i<sizeof($tiposProcedimientos);$i++)
        {
				  $value=$tiposProcedimientos[$i]['tipo_cargo'].'/'.$tiposProcedimientos[$i]['grupo_tipo_cargo'];
					$titulo=$tiposProcedimientos[$i]['descripcion'];
				  if($value==$tipoProcedimiento)
          {
				    $this->salida .=" <option value=\"$value\" selected>$titulo</option>";
				  }
				  $this->salida .=" <option value=\"$value\">$titulo</option>";
			  }
			  break;
		  }
	  }
	}

	function CalcularNumeroPasos($conteo)
	{
		$numpaso=ceil($conteo/$this->limit);
		return $numpaso;
	}

	//cor - jea - ads
	function CalcularBarra($paso)
	{
		$barra=floor($paso/10)*10;
		if(($paso%10)==0)
		{
			$barra=$barra-10;
		}
		return $barra;
	}

	//cor - jea - ads
	function CalcularOffset($paso)
	{
		$offset=($paso*$this->limit)-$this->limit;
		return $offset;
	}

	//cor - jea - ads
	function RetornarBarra($origen)//Barra paginadora de los planes clientes
	{
		$pfj=$this->frmPrefijo;
		if($this->limit>=$this->conteo)
		{
			return '';
		}
		$paso=$_REQUEST['paso1'.$pfj];
		if(empty($paso))
		{
			$paso=1;
		}
		if($origen==1)
    {
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'Bucardiagnostico',
		'conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],'criterio1'.$pfj=>$_REQUEST['criterio1'.$pfj],
		'cargo'.$pfj=>$_REQUEST['cargo'.$pfj],'descripcion'.$pfj=>$_REQUEST['descripcion'.$pfj],
		"fechainicio".$pfj=>$_REQUEST['fechainicio'.$pfj],"hora".$pfj=>$_REQUEST['hora'.$pfj],"minutos".$pfj=>$_REQUEST['minutos'.$pfj],
		"horadur".$pfj=>$_REQUEST['horadur'.$pfj],"minutosdur".$pfj=>$_REQUEST['minutosdur'.$pfj],
		"viaAcceso".$pfj=>$_REQUEST['viaAcceso'.$pfj],"tipoCirugia".$pfj=>$_REQUEST['tipoCirugia'.$pfj],
		"ambitoCirugia".$pfj=>$_REQUEST['ambitoCirugia'.$pfj],"finalidadCirugia".$pfj=>$_REQUEST['finalidadCirugia'.$pfj],
		"quirofano".$pfj=>$_REQUEST['quirofano'.$pfj],"cargo".$pfj=>$_REQUEST['cargo'.$pfj],"codigo".$pfj=>$_REQUEST['codigo'.$pfj],
		"cargo1".$pfj=>$_REQUEST['cargo1'.$pfj],"codigo1".$pfj=>$_REQUEST['codigo1'.$pfj],"descripcionDes".$pfj=>$_REQUEST['descripcionDes'.$pfj],
		"codigoDes".$pfj=>$_REQUEST['codigoDes'.$pfj],"buscar".$pfj=>$_REQUEST['buscar'.$pfj],"buscar1".$pfj=>$_REQUEST['buscar1'.$pfj]));
		}
    elseif($origen==2)
    {
    $accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array('accion'.$pfj=>'InsercionProcedimientosNota',
		'conteo'.$pfj=>$this->conteo,'paso1'.$pfj=>$_REQUEST['paso1'.$pfj],
		'criterio1'.$pfj=>$_REQUEST['criterio1'.$pfj],
		'buscarProc'.$pfj=>1,'tipoProcedimiento'.$pfj=>$_REQUEST['tipoProcedimiento'.$pfj],
		'codigoPro'.$pfj=>$_REQUEST['codigoPro'.$pfj],
		'descripcionPro'.$pfj=>$_REQUEST['descripcionPro'.$pfj]));
		}
		$barra=$this->CalcularBarra($paso);
		$numpasos=$this->CalcularNumeroPasos($this->conteo);
		$colspan=1;
		$salida .= "<table border='1' class='modulo_table' align='center' cellpadding='4'><tr><td class='label' bgcolor=\"#D3DCE3\">Paginas :</td>";
		if($paso > 1)
		{
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset(1)."&paso1$pfj=1'>&lt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso-1)."&paso1$pfj=".($paso-1)."'>&lt;&lt;</a></td>";
			$colspan+=2;
		}
		$barra++;
		if(($barra+10)<=$numpasos)
		{
			for($i=($barra);$i<($barra+10);$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#D3DCE3\">$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i' >$i</a></td>";
				}
				$colspan++;
			}
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
			$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
			$colspan+=2;
		}
		else
		{
			$diferencia=$numpasos-9;
			if($diferencia<=0)
			{
				$diferencia=1;
			}
			for($i=($diferencia);$i<=$numpasos;$i++)
			{
				if($paso==$i)
				{
					$salida .= "<td bgcolor=\"#DDDDDD\" >$i</td>";
				}
				else
				{
					$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($i)."&paso1$pfj=$i'>$i</a></td>";
				}
				$colspan++;
			}
			if($paso!=$numpasos)
			{
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($paso+1)."&paso1$pfj=".($paso+1)."' >&gt;&gt;</a></td>";
				$salida .= "<td bgcolor=\"#DDDDDD\"><a href='$accion&Of$pfj=".$this->CalcularOffset($numpasos)."&paso1$pfj=$numpasos'>&gt;</a></td>";
				$colspan++;
			}
		}
		if(($_REQUEST['Of'.$pfj])==0 OR ($paso==$numpasos))
		{
			if($numpasos>10)
			{
				$valor=10+3;
			}
			else
			{
				$valor=$numpasos+3;
			}
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Pagina $paso de $numpasos</td><tr></table>";
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
			$salida .= "</tr><tr><td bgcolor=\"#D3DCE3\"  class=\"label\"  colspan=".$valor." align='center'>Pagina $paso de $numpasos</td><tr></table>";
		}
		return $salida;
	}


	function frmConsulta()
	{
		$datos=$this->ConsultaNotasOperatoriasRealizadasHis();
		if($datos)
    {
		for($i=0;$i<sizeof($datos);$i++)
    {
			$this->salida .= "  <table border=\"0\" width=\"95%\" align=\"center\">";
			$this->salida .= "  <tr class=\"modulo_table_title\"><td colspan=\"4\" align=\"center\">NOTA OPERATORIA</td></tr>";
			(list($fechaIn,$horaIn)=explode(' ',$datos[$i]['hora_inicio']));
			(list($anoIn,$mesIn,$diaIn)=explode('-',$fechaIn));
			(list($hhIn,$mmIn)=explode(':',$horaIn));				
			(list($fechaFn,$horaFn)=explode(' ',$datos[$i]['hora_fin']));				
			(list($anoFn,$mesFn,$diaFn)=explode('-',$fechaFn));
			(list($hhFn,$mmFn)=explode(':',$horaFn));
			$segundos=(mktime($hhFn,$mmFn+1,0,$mesFn,$diaFn,$anoFn)-mktime($hhIn,$mmIn,0,$mesIn,$diaIn,$anoIn))/60;
			$Horas=(int)($segundos/60);				
			$Minutos=($segundos%60);
			$this->salida .= "  <tr>";			
			$this->salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">FECHA INICIO</td>";
			$this->salida .= "  <td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$fechaIn." ".$hhIn.":".$mmIn."</td>";				
			$this->salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">DURACION</td>";
			$this->salida .= "  <td width=\"15%\" class=\"hc_submodulo_list_oscuro\">".str_pad($Horas,2,0,STR_PAD_LEFT).":".str_pad($Minutos,2,0,STR_PAD_LEFT)."&nbsp;&nbsp;&nbsp;(HH:mm)</td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">QUIROFANO</td>";
			$this->salida .= "  <td align=\"left\" colspan=\"3\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['nom_quirofano']."</td>";
			$this->salida .= "  </tr>";
			$this->salida .= "		<tr>";
			$this->salida .= "		<td width=\"15%\" class=\"hc_table_submodulo_list_title\">VIA ACCESO</td>";
			$this->salida .= "		<td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['via']."</td>";		
			$this->salida .= "		<td width=\"15%\" class=\"hc_table_submodulo_list_title\">TIPO CIRUGIA</td>";
			$this->salida .= "		<td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['tipo']."</td>";		
			$this->salida.= "     </tr>";
			$this->salida .= "		<tr>";
			$this->salida .= "		<td width=\"15%\" class=\"hc_table_submodulo_list_title\">AMBITO CIRUGIA</td>";
			$this->salida .= "		<td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['ambito']."</td>";
			$this->salida .= "		<td width=\"15%\" class=\"hc_table_submodulo_list_title\">FINALIDAD CIRUGIA</td>";
			$this->salida .= "		<td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['finalidad']."</td>";
			$this->salida.= "     </tr>";		
			$this->salida.= "  </table>";		
      $this->salida .= "  <table border=\"0\" width=\"95%\" align=\"center\">";
      $this->salida .= "  <tr class=\"modulo_table_title\"><td colspan=\"4\" align=\"center\">PROFESIONALES</td></tr>";      
      $this->salida .= "  <tr>";      
      $this->salida .= "  <td width=\"20%\" nowrap class=\"hc_table_submodulo_list_title\">ANESTESIOLOGO</td>";
      if($datos[$i]['anestesiologo'])
      {     
        $this->salida .= "  <td width=\"30%\" nowrap class=\"hc_submodulo_list_oscuro\">".$datos[$i]['anestesiologo']."</td>";       
      }
      else
      {
        $this->salida .= "  <td width=\"30%\" nowrap class=\"hc_submodulo_list_oscuro\">&nbsp;</td>";       
      }  
      $this->salida .= "  <td width=\"20%\" nowrap class=\"hc_table_submodulo_list_title\">AYUDANTE</td>";      
      if($datos[$i]['ayudante'])
      {     
        $this->salida .= "  <td width=\"30%\" nowrap class=\"hc_submodulo_list_oscuro\">".$datos[$i]['ayudante']."</td>";
      }
      else
      {
        $this->salida .= "  <td width=\"30%\" nowrap class=\"hc_submodulo_list_oscuro\">&nbsp;</td>";       
      } 
      $this->salida .= "  </tr>";      
      $this->salida .= "    <tr>";      
      $this->salida .= "    <td width=\"20%\" nowrap class=\"hc_table_submodulo_list_title\">INSTRUMENTADOR</td>";
      if($datos[$i]['instrumentador'])
      {
        $this->salida .= "    <td width=\"30%\" nowrap class=\"hc_submodulo_list_oscuro\">".$datos[$i]['instrumentador']."</td>";    
      }
      else
      {
        $this->salida .= "  <td width=\"30%\" nowrap class=\"hc_submodulo_list_oscuro\">&nbsp;</td>";       
      }       
      $this->salida .= "    <td width=\"20%\" nowrap class=\"hc_table_submodulo_list_title\">CIRCULANTE</td>";
      if($datos[$i]['circulante'])
      {
        $this->salida .= "    <td class=\"hc_submodulo_list_oscuro\">".$datos[$i]['circulante']."</td>";   
      }
      else
      {
        $this->salida .= "  <td  class=\"hc_submodulo_list_oscuro\">&nbsp;</td>";       
      }   
      $this->salida.= "     </tr>";      
      $this->salida.= "  </table>"; 	
			$this->salida.="	 <table  align=\"center\" border=\"0\" width=\"95%\">";
			$this->salida.="	 <tr class=\"modulo_table_title\">";
			$this->salida.="   <td align=\"center\" colspan=\"3\">PROCEDIMIENTOS REALIZADOS</td>";
			$this->salida.="	 </tr>";	
			$this->salida.="	 <tr class=\"hc_table_submodulo_list_title\">";			
			$this->salida.="  <td width=\"20%\">CARGO</td>";
			$this->salida.="  <td colspan=\"2\">DESCRIPCION</td>";			
			$this->salida.="	</tr>";
			$procedimientos=$this->ProcedimientosNotaOperatoria($datos[$i]['hc_nota_operatoria_cirugia_id']);
			for($j=0;$j<sizeof($procedimientos);$j++)
      {
				$this->salida.="<tr class=\"hc_submodulo_list_claro\">";				
				$this->salida.="  <td align=\"center\" width=\"20%\" rowspan=\"3\">".$procedimientos[$j]['procedimiento_qx']."</td>";
				$this->salida.="  <td align=\"left\" colspan=\"2\">".$procedimientos[$j]['descripcion']."</td>";											        
        
        $procedimientosOpc=$this->BuscarProcedimientosInsertadosNotaOperatoria($datos[$i]['hc_nota_operatoria_cirugia_id'],$procedimientos[$j]['procedimiento_qx']); 
        $this->salida.="<tr class=\"modulo_list_claro\"><td id=\"MostrarProcedimientoOpc\" colspan=\"5\">";    
        if($procedimientosOpc)
        {          
          $this->salida.="<table border=\"1\" width=\"100%\" align=\"center\" class=\"normal_10\">";
          $this->salida.="<tr class=\"modulo_table_list_title\">";
          $this->salida.="<td width=\"10%\">CODIGO</td>";
          $this->salida.="<td>PROCEDIMIENTO</td>";                
          $this->salida.="</tr>";        
          for($m=0;$m<sizeof($procedimientosOpc);$m++)
          {
            $this->salida.="<tr class=\"modulo_list_oscuro\">";
            $this->salida.="<td width=\"20%\">".$procedimientosOpc[$m]['procedimiento_opcion']."</td>";
            $this->salida.="<td>".$procedimientosOpc[$m]['descripcion']."</td>";             
            $this->salida.="</tr>";
          }        
          $this->salida.="</table>";      
        }
        $this->salida.="</td></tr>";
    
				$this->salida.="</tr>";	
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="  <td class=\"hc_table_submodulo_list_title\" colspan = 1 align=\"left\" width=\"10%\">Observacion</td>";
				$this->salida.="  <td class=\"hc_submodulo_list_claro\" align=\"left\" width=\"64%\">".$procedimientos[$j]['observaciones']."</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="	<td class=\"hc_table_submodulo_list_title\" align=\"center\" width=\"10%\">Diagnosticos Pre-QX</td>";
				$this->salida.="	<td colspan=\"2\" class=\"hc_submodulo_list_claro\">";				
				$diag =$this->Diagnosticos_ProcedimientosNO($datos[$i]['hc_nota_operatoria_cirugia_id'],$procedimientos[$j]['procedimiento_qx']);
				if($diag)
        {					
					$this->salida.="<table width=\"100%\">";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="<td width=\"10%\">PRIMARIO</td>";
					$this->salida.="<td width=\"10%\">TIPO DX</td>";
					$this->salida.="<td width=\"8%\">CODIGO</td>";
					$this->salida.="<td width=\"60%\">DIAGNOSTICO</td>";					
					$this->salida.="</tr>";					
					for($m=0;$m<sizeof($diag);$m++)
          {							
						$this->salida.="<tr class=\"$estilo\">";
						if($diag[$m]['sw_principal']=='1')
            {
							$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/checksi.png\"  border='0'></td>";
						}
            else
            {								
							$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/checkno.png\"  border='0'></td>";
						}
						if($diag[$m]['tipo_diagnostico'] == '1')
            {
							$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresion Diagnostica\"></td>";
						}
            elseif($diag[$m]['tipo_diagnostico'] == '2')
            {
							$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
						}
            else
            {
							$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
						}
						$this->salida.="<td class=\"hc_submodulo_list_claro\" align=\"center\" width=\"8%\">".$diag[$m]['diagnostico_id']."</td>";
						$this->salida.="<td class=\"hc_submodulo_list_claro\" align=\"justify\" width=\"60%\">".$diag[$m]['diagnostico_nombre']."</td>";																					
						$this->salida.="<tr>";										
					}
					$this->salida.="</table>";					
				}				
			}
			$this->salida.="		</td></tr>";	
			$this->salida.= "		</table>";
			$this->salida .= "  <table border=\"0\" width=\"95%\" align=\"center\">";
			$this->salida .= "  <tr class=\"modulo_table_title\"><td colspan=\"4\" align=\"center\">DIAGNOSTICOS</td></tr>";
			
			$this->salida .= "  <tr>";
			$this->salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">POST QX</td>";
			$this->salida .= "  <td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['diag_nom']."</td>";				
			$this->salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">TIPO</td>";
			if($datos[$i]['tipo_diagnostico_post_qx'] == '1')
      {
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresion Diagnostica\"></td>";
			}
      elseif($datos[$i]['tipo_diagnostico_post_qx'] == '2')
      {
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
			}
      elseif($datos[$i]['tipo_diagnostico_post_qx'] == '3')
      {
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
			}
      else
      {
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\">&nbsp;</td>";
			}			
			$this->salida .= "  </tr>";			
			$this->salida .= "  <tr>";
			$this->salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">COMPLICACION</td>";
			$this->salida .= "  <td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['diag_nom1']."</td>";				
			$this->salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">TIPO</td>";
			if($datos[$i]['tipo_diagnostico_complicacion'] == '1')
      {
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresion Diagnostica\"></td>";
			}
      elseif($datos[$i]['tipo_diagnostico_complicacion'] == '2')
      {
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
			}
      elseif($datos[$i]['tipo_diagnostico_complicacion'] == '3')
      {		
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
			}
      else
      {
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\">&nbsp;</td>";
			}			
			$this->salida .= "  </tr>";			
			$this->salida.= "</table>";			
			if($datos[$i]['descripcion_envio_patologico'])
      {
				$this->salida .="<table border=\"0\" width=\"95%\" align=\"center\">";
				$this->salida.= "<tr>";
				if($datos[$i]['envio_patologico']==1)
        {
					$this->salida.= "<td class=\"modulo_table_title\">MATERIAL ENVIADO A PATOLOGIA:&nbsp;&nbsp;&nbsp;SI</td>";						
				}
        else
        {
					$this->salida.= "<td class=\"modulo_table_title\">MATERIAL ENVIADO A PATOLOGIA:&nbsp;&nbsp;&nbsp;NO</td>";						
				}
				$this->salida.= "</tr>";
				$this->salida.= "<tr>";					
				$this->salida.= "<td class=\"hc_submodulo_list_oscuro\"><label class=\"normal_10N\">CLASE DE MATERIAL ENVIADO:</label><BR>".$datos[$i]['descripcion_envio_patologico']."</td>";						
				$this->salida.= "</tr>";
				$this->salida.= "</table>";
			}
			if($datos[$i]['descripcion_envio_cultivo'])
      {
				$this->salida .="<table border=\"0\" width=\"95%\" align=\"center\">";
				$this->salida.= "<tr>";
				if($datos[$i]['envio_cultivo']==1)
        {
					$this->salida.= "<td class=\"modulo_table_title\">CULTIVO ENVIADO:&nbsp;&nbsp;&nbsp;SI</td>";						
				}
        else
        {
					$this->salida.= "<td class=\"modulo_table_title\">CULTIVO ENVIADO:&nbsp;&nbsp;&nbsp;NO</td>";						
				}
				$this->salida.= "</tr>";
				$this->salida.= "<tr>";					
				$this->salida.= "<td class=\"hc_submodulo_list_oscuro\"><label class=\"normal_10N\">CLASE DE MATERIAL ENVIADO:</label><BR>".$datos[$i]['descripcion_envio_cultivo']."</td>";						
				$this->salida.= "</tr>";			
				$this->salida.= "</table>";			
			}
			$this->salida.= "<BR>";		
		}
		}else
    {
			$this->salida .="<br><table width=\"100%\" border=\"0\" align=\"center\">";
			$this->salida .="<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida .="<div class='label_mark' align='center'><BR>EL PACIENTE AUN NO PRESENTA REPORTES DE HALLAZGOS QUIRURGICOS<br><br>";
			$this->salida .="</tr>";
			$this->salida .="</table>";
		}
		return true;	
	}
	
	function frmHistoria()
	{
		$datos=$this->ConsultaNotasOperatoriasRealizadasHis();
		if($datos)
    {
      for($i=0;$i<sizeof($datos);$i++)
      {
  			$salida .= "  <table border=\"1\" width=\"95%\" align=\"center\">";
  			$salida .= "  <tr class=\"modulo_table_title\"><td colspan=\"4\" align=\"center\"><b>NOTA OPERATORIA</b></td></tr>";
  			(list($fechaIn,$horaIn)=explode(' ',$datos[$i]['hora_inicio']));
  			(list($anoIn,$mesIn,$diaIn)=explode('-',$fechaIn));
  			(list($hhIn,$mmIn)=explode(':',$horaIn));				
  			(list($fechaFn,$horaFn)=explode(' ',$datos[$i]['hora_fin']));				
  			(list($anoFn,$mesFn,$diaFn)=explode('-',$fechaFn));
  			(list($hhFn,$mmFn)=explode(':',$horaFn));
  			$segundos=(mktime($hhFn,$mmFn+1,0,$mesFn,$diaFn,$anoFn)-mktime($hhIn,$mmIn,0,$mesIn,$diaIn,$anoIn))/60;
  			$Horas=(int)($segundos/60);				
  			$Minutos=($segundos%60);
  			$salida .= "  <tr>";			
  			$salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">FECHA INICIO</td>";
  			$salida .= "  <td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$fechaIn." ".$hhIn.":".$mmIn."</td>";				
  			$salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">DURACION</td>";
  			$salida .= "  <td width=\"15%\" class=\"hc_submodulo_list_oscuro\">".str_pad($Horas,2,0,STR_PAD_LEFT).":".str_pad($Minutos,2,0,STR_PAD_LEFT)."&nbsp;&nbsp;&nbsp;(HH:mm)</td>";
  			$salida .= "  </tr>";
  			$salida .= "  <tr>";
  			$salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">QUIROFANO</td>";
  			$salida .= "  <td align=\"left\" colspan=\"3\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['nom_quirofano']."</td>";
  			$salida .= "  </tr>";
  			$salida .= "		<tr>";
  			$salida .= "		<td width=\"15%\" class=\"hc_table_submodulo_list_title\">VIA ACCESO</td>";
  			$salida .= "		<td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['via']."</td>";		
  			$salida .= "		<td width=\"15%\" class=\"hc_table_submodulo_list_title\">TIPO CIRUGIA</td>";
  			$salida .= "		<td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['tipo']."</td>";		
  			$salida.= "     </tr>";
  			$salida .= "		<tr>";
  			$salida .= "		<td width=\"15%\" class=\"hc_table_submodulo_list_title\">AMBITO CIRUGIA</td>";
  			$salida .= "		<td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['ambito']."</td>";
  			$salida .= "		<td width=\"15%\" class=\"hc_table_submodulo_list_title\">FINALIDAD CIRUGIA</td>";
  			$salida .= "		<td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['finalidad']."</td>";
  			$salida.= "     </tr>";		
  			$salida.= "  </table>";		
        $salida.= "  <table border=\"1\" width=\"95%\" align=\"center\">";
        $salida.= "  <tr class=\"modulo_table_title\"><td colspan=\"4\" align=\"center\"><b>PROFESIONALES</b></td></tr>";   
        $id_pro = $this->IdProfesionalesCirujano($datos[$i]['programacion_id']); 
        $nombre_pro=$this->NombreProfesional($id_pro['cirujano_id']);
        $salida.= "  <tr>";  
        $salida .= "  <td width=\"20%\"  class=\"hc_table_submodulo_list_title\"><b>CIRUJANO</b></td>";
        $salida.= "  <td class=\"hc_table_submodulo_list_title\" align=\"left\" colspan=\"2\">".$nombre_pro."</td>";
        $salida .= "</td></tr>";    
        $salida.= "  <td width=\"20%\" nowrap class=\"hc_table_submodulo_list_title\"><b>ANESTESIOLOGO</b></td>";
        if($datos[$i]['anestesiologo'])
        {     
          $salida.= "  <td width=\"30%\" nowrap class=\"hc_submodulo_list_oscuro\">".$datos[$i]['anestesiologo']."</td>";       
        }
        else
        {
          $salida.= "  <td width=\"30%\" nowrap class=\"hc_submodulo_list_oscuro\">&nbsp;</td>";       
        }  
        $salida.= "  <td width=\"20%\" nowrap class=\"hc_table_submodulo_list_title\"><b>AYUDANTE</b></td>";      
        if($datos[$i]['ayudante'])
        {     
          $salida.= "  <td width=\"30%\" nowrap class=\"hc_submodulo_list_oscuro\">".$datos[$i]['ayudante']."</td>";
        }
        else
        {
          $salida.= "  <td width=\"30%\" nowrap class=\"hc_submodulo_list_oscuro\">&nbsp;</td>";       
        } 
        $salida.= "  </tr>";      
        $salida.= "    <tr>";      
        $salida.= "    <td width=\"20%\" nowrap class=\"hc_table_submodulo_list_title\"><b>INSTRUMENTADOR</b></td>";
        if($datos[$i]['instrumentador'])
        {
          $salida.= "    <td width=\"30%\" nowrap class=\"hc_submodulo_list_oscuro\">".$datos[$i]['instrumentador']."</td>";    
        }
        else
        {
          $salida.= "  <td width=\"30%\" nowrap class=\"hc_submodulo_list_oscuro\">&nbsp;</td>";       
        }       
        $salida.= "    <td width=\"20%\" nowrap class=\"hc_table_submodulo_list_title\"><b>CIRCULANTE</b></td>";
        if($datos[$i]['circulante'])
        {
          $salida.= "    <td class=\"hc_submodulo_list_oscuro\">".$datos[$i]['circulante']."</td>";   
        }
        else
        {
          $salida.= "  <td  class=\"hc_submodulo_list_oscuro\">&nbsp;</td>";       
        }   
        $salida.= "     </tr>";
        $salida.= "  <tr>";  
        $salida .= "  <td width=\"20%\"  class=\"hc_table_submodulo_list_title\"><b>TIPO ANESTESIA</b></td>";
        if($datos[$i]['tipo_anestesia'])
        {
          $salida.= "  <td class=\"hc_table_submodulo_list_title\" align=\"left\" colspan=\"2\">".$datos[$i]['tipo_anestesia']."</td>";
        }
        else
        {
          $salida.= "  <td class=\"hc_table_submodulo_list_title\" align=\"left\" colspan=\"2\">&nbsp;</td>";
        }
        $salida .= "</td></tr>";      
        $salida.= "  </table><br>"; 	
      	unset($_SESSION['Liquidacion_QX']['GASES']);
      		$gases = $this->ConsultarGasesImpresion($datos[$i]['programacion_id']);
					if($gases)
					{
						for($ii=0;$ii<sizeof($gases);$ii++)
						{
							$_SESSION['Liquidacion_QX']['GASES'][$ii]['TipoGasDes']=$this->consultartipogas($gases[$ii][0]);           
							$_SESSION['Liquidacion_QX']['GASES'][$ii]['MetodoGas']=$gases[$ii][1];
							$_SESSION['Liquidacion_QX']['GASES'][$ii]['MetodoGasDes']=$this->consultartiposuministro($gases[$ii][1]);  
							$_SESSION['Liquidacion_QX']['GASES'][$ii]['FrecuenciaGas']=$gases[$ii][2];
							$_SESSION['Liquidacion_QX']['GASES'][$ii]['FrecuenciaGasDes']=$gases[$ii][4];
							$_SESSION['Liquidacion_QX']['GASES'][$ii]['MinutosGas']=$gases[$ii][3];
						
						}
					}
      $salida.="	 <table  align=\"center\" border=\"1\" width=\"95%\">";
			$salida.="	 <tr class=\"modulo_table_title\">";
			$salida.="   <td align=\"center\" colspan=\"4\"><b>GASES UTILIZADOS</b></td>";
			$salida.="	 </tr>";	
			$salida.="	 <tr class=\"hc_table_submodulo_list_title\">";			
			$salida.="  <td ><b>TIPO GAS</b></td>";
			$salida.="  <td ><b>METODO SUMINISTRO</b></td>";
			$salida.="  <td ><b>FRECUENCIA SUMINISTRO(L/m)</b></td>";
			$salida.="  <td ><b>MINUTOS</b></td>";
			$salida.="	</tr>";
			foreach($_SESSION['Liquidacion_QX']['GASES'] as $ii=>$vector)
      {
        $salida.="<tr class=\"normal_10\">";
        $salida.="<td width=\"30%\">".$vector[TipoGasDes]."</td>";
        $salida.="<td width=\"30%\">".$vector[MetodoGasDes]."</td>";
        $salida.="<td width=\"20%\">".$vector[FrecuenciaGas]."/".$vector[FrecuenciaGasDes]."</td>";
        $salida.="<td width=\"20%\">".$vector[MinutosGas]."</td>";          
        $salida.="</tr>";
			}
			$salida.= "  </table><br>"; 	
			$salida.="	 <table  align=\"center\" border=\"1\" width=\"95%\">";
			$salida.="	 <tr class=\"modulo_table_title\">";
			$salida.="   <td align=\"center\" colspan=\"3\"><b>PROCEDIMIENTOS REALIZADOS</b></td>";
			$salida.="	 </tr>";	
			$salida.="	 <tr class=\"hc_table_submodulo_list_title\">";			
			$salida.="  <td width=\"20%\"><b>CARGO</b></td>";
			$salida.="  <td colspan=\"2\"><b>DESCRIPCION</b></td>";
			$procedimientos=$this->ProcedimientosNotaOperatoria($datos[$i]['hc_nota_operatoria_cirugia_id']);
			for($j=0;$j<sizeof($procedimientos);$j++)
      {
				$salida.="<tr class=\"hc_submodulo_list_claro\">";				
				$salida.="  <td align=\"center\" width=\"20%\" rowspan=\"3\">".$procedimientos[$j]['procedimiento_qx']."</td>";
				$salida.="  <td align=\"left\" colspan=\"2\">".$procedimientos[$j]['descripcion']."</td>";
				$salida.="</tr>"; 
        $procedimientosOpc=$this->BuscarProcedimientosInsertadosNotaOperatoria($datos[$i]['hc_nota_operatoria_cirugia_id'],$procedimientos[$j]['procedimiento_qx']); 
        $salida.="<tr class=\"modulo_list_claro\"><td id=\"MostrarProcedimientoOpc\" colspan=\"5\">";    
        if($procedimientosOpc)
        {         
          $salida.="<table border=\"1\" width=\"100%\" align=\"center\" class=\"normal_10\">";
          $salida.="<tr class=\"modulo_table_list_title\">";
          $salida.="<td width=\"10%\"><b>CODIGO</b></td>";
          $salida.="<td><b>PROCEDIMIENTO</b></td>";                
          $salida.="</tr>";        
          for($m=0;$m<sizeof($procedimientosOpc);$m++)
          {
            $salida.="<tr class=\"modulo_list_oscuro\">";
            $salida.="<td width=\"20%\">".$procedimientosOpc[$m]['procedimiento_opcion']."</td>";
            $salida.="<td>".$procedimientosOpc[$m]['descripcion']."</td>";             
            $salida.="</tr>";
          }        
          $salida.="</table>";      
        }
        $salida.="</td></tr>";	
				$salida.="<tr class=\"$estilo\">";
				$salida.="  <td class=\"hc_table_submodulo_list_title\" align=\"left\" width=\"50%\"><b>PROFESIONAL:</b> ".$nombre_pro."</td>";
				$salida.="</tr>";
				$salida.="<tr class=\"$estilo\">";
				$salida.="  <td class=\"hc_table_submodulo_list_title\" align=\"left\" colspan=\"2\"><b>Observacion:</b>
				".$procedimientos[$j]['observaciones']."</td>";							
			}
			$salida.="</table>";					
			$salida.="		</td></tr>";	
			$salida.= "		</table>";
			$salida .= "  <table border=\"1\" width=\"95%\" align=\"center\">";
			$salida.="<tr class=\"$estilo\">";
			$salida.="	<td class=\"hc_table_submodulo_list_title\" align=\"center\" width=\"10%\">Diagnosticos Pre-QX</td>";
			$salida.="<tr class=\"$estilo\">";
				
			$diag =$this->Diagnosticos_ProcedimientosNO($datos[$i]['hc_nota_operatoria_cirugia_id'],$procedimientos[$i]['procedimiento_qx']);
								
			if($diag)
      {					
        $salida.="<table width=\"100%\" border=\"1\">";
        $salida.="<tr class=\"hc_table_submodulo_list_title\">";
        $salida.="<td width=\"10%\">PRIMARIO</td>";
        $salida.="<td width=\"10%\">TIPO DX</td>";
        $salida.="<td width=\"8%\">CODIGO</td>";
        $salida.="<td width=\"60%\">DIAGNOSTICO</td>";					
        $salida.="</tr>";					
        for($m=0;$m<sizeof($diag);$m++)
        {							
          $salida.="<tr class=\"$estilo\">";
          if($diag[$m]['sw_principal']=='1')
          {
            $salida.="<td align=\"center\" width=\"10%\">X</td>";
          }
          else
          {								
            $salida.="<td align=\"center\" width=\"10%\">&nbsp;</td>";
          }
          if($diag[$m]['tipo_diagnostico'] == '1')
          {
            $salida.="<td align=\"center\" width=\"10%\">ID</td>";
          }
          elseif($diag[$m]['tipo_diagnostico'] == '2')
          {
            $salida.="<td align=\"center\" width=\"10%\">CN</td>";
          }
          else
          {
            $salida.="<td align=\"center\" width=\"10%\">CR</td>";
          }
          $salida.="<td class=\"hc_submodulo_list_claro\" align=\"center\" width=\"8%\">".$diag[$m]['diagnostico_id']."</td>";
          $salida.="<td class=\"hc_submodulo_list_claro\" align=\"justify\" width=\"60%\">".$diag[$m]['diagnostico_nombre']."</td>";
				}
      }
			$salida.= "<BR>";		
				
		  if($datos[$i]['diag_nom'] || $datos[$i]['diag_nom1'] || $datos[$i]['diag_nom2'])
      {
		    $salida .= "  <table border=\"1\" width=\"95%\" align=\"center\">";
				$salida .= "  <tr class=\"normal_10N\"><td colspan=\"4\" align=\"center\">DIAGNOSTICOS</td></tr>";
				if($datos[$i]['diag_nom'])
        {
					$salida .= "  <tr>";
					$salida .= "  <td width=\"15%\" class=\"normal_10N\">POST QX</td>";
					$salida .= "  <td align=\"left\" class=\"normal_10\">".$datos[$i]['diag_nom']."</td>";				
					$salida .= "  <td width=\"15%\" class=\"normal_10N\">TIPO</td>";
					if($datos[$i]['tipo_diagnostico_post_qx'] == '1')
          {
						$salida.="<td class=\"normal_10N\" align=\"center\" width=\"10%\">ID</td>";
					}
          elseif($datos[$i]['tipo_diagnostico_post_qx'] == '2')
          {
						$salida.="<td class=\"normal_10N\" align=\"center\" width=\"10%\">CN</td>";
					}
          else
          {
						$salida.="<td class=\"normal_10N\" align=\"center\" width=\"10%\">CR</td>";
					}			
					$salida .= "  </tr>";			
				}
				if($datos[$i]['diag_nom1'])
        {	
					$salida .= "  <tr>";
					$salida .= "  <td width=\"15%\" class=\"normal_10N\">COMPLICACION</td>";
					$salida .= "  <td align=\"left\" class=\"normal_10\">".$datos[$i]['diag_nom1']."</td>";				
					$salida .= "  <td width=\"15%\" class=\"normal_10N\">TIPO</td>";
					if($datos[$i]['tipo_diagnostico_complicacion'] == '1')
          {
						$salida.="<td class=\"normal_10N\" align=\"center\" width=\"10%\">ID</td>";
					}
          elseif($datos[$i]['tipo_diagnostico_complicacion'] == '2')
          {
						$salida.="<td class=\"normal_10N\" align=\"center\" width=\"10%\">CN</td>";
					}
          else
          {
						$salida.="<td class=\"normal_10N\" align=\"center\" width=\"10%\">CR</td>";
					}			
					$salida .= "  </tr>";
				}							
				$salida.= "</table><BR>";			
			}
      $Tecnicas=$this->DescripcionTecnicaQX($datos[$i]['programacion_id']);
			if($Tecnicas)
      {
				$salida .= "  <table border=\"1\" width=\"95%\" align=\"center\">";
				$salida .= "  <tr class=\"normal_10N\"><td align=\"center\">DESCRIPCIONES TECNICAS QUIRURGICAS</td></tr>";				
				for($j=0;$j<sizeof($Tecnicas);$j++)
        {
					$salida .= "  <tr>";
					$salida .= "  <td class=\"normal_10N\">".$Tecnicas[$j]['nombre_tercero']."</td>";						
					$salida .= "  </tr>";
					$salida .= "  <tr>";
					$salida .= "  <td class=\"normal_10\">".$Tecnicas[$j]['descripcion']."</td>";						
					$salida .= "  </tr>";
				}														
				$salida.= "		</table><BR>";			
			}
      $Hallazgos=$this->HallazgosQX($datos[$i]['programacion_id']); 
			if($Hallazgos)
      {
				$salida .= "  <table border=\"1\" width=\"95%\" align=\"center\">";
				$salida .= "  <tr class=\"normal_10N\"><td align=\"center\">HALLAZGOS QUIRURGICOS</td></tr>";				
				for($j=0;$j<sizeof($Hallazgos);$j++)
        {
					$salida .= "  <tr>";
					$salida .= "  <td class=\"normal_10N\">".$Hallazgos[$j]['nombre_tercero']."</td>";						
					$salida .= "  </tr>";
					$salida .= "  <tr>";
					$salida .= "  <td class=\"normal_10\">".$Hallazgos[$j]['descripcion']."</td>";						
					$salida .= "  </tr>";
				}														
				$salida.= "		</table><BR>";			
			}
      $materialesPatologicos=$this->RegistroPatologias($datos[$i]['programacion_id']);			
			if($materialesPatologicos)
      {
				$salida .= "  <table border=\"1\" width=\"95%\" align=\"center\">";
				$salida .= "  <tr class=\"normal_10N\"><td align=\"center\">MATERIALES PATOLOGICOS</td></tr>";				
				for($j=0;$j<sizeof($materialesPatologicos);$j++)
        {
					if($materialesPatologicos[$j]['descripcion'])
          {					
						$salida .= "  <tr>";
						if($materialesPatologicos[$j]['envio_patologico']==1)
            {
							$Salida .= "  <td class=\"normal_10N\">".$materialesPatologicos[$j]['nombre']." - MATERIAL ENVIADO A PATOLOGIA:&nbsp;&nbsp;&nbsp;SI</td>";						
						}
            else
            {
							$salida .= "  <td class=\"normal_10N\">".$materialesPatologicos[$j]['nombre']." - MATERIAL ENVIADO A PATOLOGIA:&nbsp;&nbsp;&nbsp;NO</td>";						
						}
						$salida .= "  </tr>";
						$salida .= "  <tr>";					
						$salida .= "  <td class=\"normal_10\"><label class=\"normal_10N\">CLASE DE MATERIAL ENVIADO:</label><BR>".$materialesPatologicos[$j]['descripcion']."</td>";						
						$salida .= "  </tr>";
					}
				}
				$salida.= "		</table><BR>";							
			}	
				
			$cultivos=$this->RegistroCultivos($datos[$i]['programacion_id']);
			if($cultivos)
      {
				$salida .= "  <table border=\"1\" width=\"95%\" align=\"center\">";
				$salida .= "  <tr class=\"normal_10N\"><td align=\"center\">CULTIVOS</td></tr>";								
				for($j=0;$j<sizeof($cultivos);$j++)
        {					
					if($cultivos[$j]['descripcion'])
          {
						$salida .= "  <tr>";
						if($cultivos[$j]['envio_cultivo']==1)
            {
							$salida .= "  <td class=\"normal_10N\">".$cultivos[$j]['nombre']." - CULTIVO ENVIADO:&nbsp;&nbsp;&nbsp;SI</td>";						
						}
            else
            {
							$salida .= "  <td class=\"normal_10N\">".$cultivos[$j]['nombre']." - CULTIVO ENVIADO:&nbsp;&nbsp;&nbsp;NO</td>";						
						}					
						$salida .= "  </tr>";
						$salida .= "  <tr>";
						$salida .= "  <td class=\"normal_10\"><label class=\"normal_10N\">DESCRIPCION DEL CULTIVO:</label><br>".$cultivos[$j]['descripcion']."</td>";						
						$salida .= "  </tr>";
					}	
				}					
				$salida.= "		</table><BR>";							
			}
		}
  }
	return $salida;
  }

	function BuscadorCups($tipoProcedimiento,$codigoPro,$descripcionPro)
  {
		$this->salida  = ThemeAbrirTablaSubModulo('BUSQUEDA DE CARGOS CUPS');
		$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$this->frmPrefijo.""=>'InsercionProcedimientosNota',"tipoProcedimiento".$this->frmPrefijo.""=>$tipoProcedimiento));
		$this->salida .= "  <form name='formauno".$this->frmPrefijo."' action=$accion method='post'>";
		$this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "  <tr class=\"modulo_table_title\"><td colspan=\"5\" align=\"center\">PROCEDIMIENTO</td></tr>";
		$this->salida .= "  <tr class=\"hc_table_submodulo_list_title\">";
		$this->salida .= "  <td width=\"10%\">CODIGO</td><td align=\"left\" class=\"hc_submodulo_list_oscuro\"><input size=\"6\" type=\"text\" name=\"codigoPro".$this->frmPrefijo."\" value=\"$codigoPro\"></td>";
		$this->salida .= "  <td width=\"13%\">DESCRIPCION</td><td align=\"left\" class=\"hc_submodulo_list_oscuro\"><input size=\"60\" type=\"text\" name=\"descripcionPro".$this->frmPrefijo."\" value=\"$descripcionPro\"></td>";
		$this->salida .= "  <td width=\"10%\"><input type=\"submit\" name=\"buscarProc".$this->frmPrefijo."\" value=\"BUSCAR\"></td>";
		$this->salida .= "  </tr>";
		$this->salida .= "  </table><BR>";
		$cargos=$this->RegistrosCargosCups($tipoProcedimiento,$codigoPro,$descripcionPro);
		if($cargos)
    {
			$this->salida .= "  <table border=\"0\" width=\"90%\" align=\"center\">";
			$this->salida .= "  <tr class=\"modulo_table_title\">";
			$this->salida .= "  <td>CODIGO</td>";
			$this->salida .= "  <td>DESCRIPCION</td>";
			$this->salida .= "  <td>&nbsp;</td>";
			$this->salida .= "  </tr>";
			$y=0;
			for($i=0;$i<sizeof($cargos);$i++)
      {
				if($y % 2)
        {
          $estilo='hc_submodulo_list_claro';
        }
        else
        {
          $estilo='hc_submodulo_list_oscuro';
        }
				$this->salida .= "  <tr class=\"$estilo\">";
				$this->salida .= "  <td width=\"15%\">".$cargos[$i]['cargo']."</td>";
				$this->salida .= "  <td>".$cargos[$i]['descripcion']."</td>";
				$this->salida .= "  <td align=\"center\" width=\"5%\">";
				$accion=ModuloHCGetURL($this->evolucion,$this->paso,0,'',false,array("accion".$this->frmPrefijo.""=>'InsercionProcedimientosNota',"cargo".$this->frmPrefijo.""=>$cargos[$i]['cargo'],"nombreProcedimiento".$this->frmPrefijo.""=>$cargos[$i]['descripcion'],
				"buscarProc".$this->frmPrefijo.""=>1,"bandera".$this->frmPrefijo.""=>1));
				$this->salida .= "  <a href=\"$accion\" class=\"link\"><b><img border=\"0\" src=\"".GetThemePath()."/images/checkno.png\"></b></a>";
				$this->salida .= "  </td>";
				$this->salida .= "  </tr>";
				$y++;
			}
			$this->salida .= "  </table><BR>";
			$this->salida .=$this->RetornarBarra(2);
		}
		$this->salida .= "  <BR><table border=\"0\" width=\"90%\" align=\"center\">";
		$this->salida .= "  <tr><td class=\"input-submit\" align=\"center\"><input type=\"submit\" value=\"SALIR\" name=\"salir".$this->frmPrefijo."\"></td></tr>";
		$this->salida .= "  </table>";
		$this->salida .= "  </form>";
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;
	}
	
	function FrmConsultaNotasOperatoriasRealizadas($datos)
  {
		$this->salida  = ThemeAbrirTablaSubModulo('NOTAS OPERATORIAS REALIZADAS');
		for($i=0;$i<sizeof($datos);$i++)
    {
			$this->salida .= "  <table border=\"0\" width=\"95%\" align=\"center\">";
			$this->salida .= "  <tr class=\"modulo_table_title\"><td colspan=\"4\" align=\"center\">DATOS DE LA CIRUGIA</td></tr>";
			(list($fechaIn,$horaIn)=explode(' ',$datos[$i]['hora_inicio']));
			(list($anoIn,$mesIn,$diaIn)=explode('-',$fechaIn));
			(list($hhIn,$mmIn)=explode(':',$horaIn));				
			(list($fechaFn,$horaFn)=explode(' ',$datos[$i]['hora_fin']));				
			(list($anoFn,$mesFn,$diaFn)=explode('-',$fechaFn));
			(list($hhFn,$mmFn)=explode(':',$horaFn));
			$segundos=(mktime($hhFn,$mmFn+1,0,$mesFn,$diaFn,$anoFn)-mktime($hhIn,$mmIn,0,$mesIn,$diaIn,$anoIn))/60;
			$Horas=(int)($segundos/60);				
			$Minutos=($segundos%60);
			$this->salida .= "  <tr>";			
			$this->salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">FECHA INICIO</td>";
			$this->salida .= "  <td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$fechaIn." ".$hhIn.":".$mmIn."</td>";				
			$this->salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">DURACION</td>";
			$this->salida .= "  <td width=\"15%\" class=\"hc_submodulo_list_oscuro\">".str_pad($Horas,2,0,STR_PAD_LEFT).":".str_pad($Minutos,2,0,STR_PAD_LEFT)."&nbsp;&nbsp;&nbsp;(HH:mm)</td>";
			$this->salida .= "  </tr>";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">QUIROFANO</td>";
			$this->salida .= "  <td align=\"left\" colspan=\"3\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['nom_quirofano']."</td>";
			$this->salida .= "  </tr>";
			$this->salida .= "		<tr>";
			$this->salida .= "		<td width=\"15%\" class=\"hc_table_submodulo_list_title\">VIA ACCESO</td>";
			$this->salida .= "		<td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['via']."</td>";		
			$this->salida .= "		<td width=\"15%\" class=\"hc_table_submodulo_list_title\">TIPO CIRUGIA</td>";
			$this->salida .= "		<td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['tipo']."</td>";		
			$this->salida.= "     </tr>";
			$this->salida .= "		<tr>";
			$this->salida .= "		<td width=\"15%\" class=\"hc_table_submodulo_list_title\">AMBITO CIRUGIA</td>";
			$this->salida .= "		<td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['ambito']."</td>";
			$this->salida .= "		<td width=\"15%\" class=\"hc_table_submodulo_list_title\">FINALIDAD CIRUGIA</td>";
			$this->salida .= "		<td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['finalidad']."</td>";
			$this->salida.= "     </tr>";		
			$this->salida.= "  </table>";	
      $this->salida .= "  <table border=\"0\" width=\"95%\" align=\"center\">";
      $this->salida .= "  <tr class=\"modulo_table_title\"><td colspan=\"4\" align=\"center\">PROFESIONALES</td></tr>";      
      $this->salida .= "  <tr>";      
      $this->salida .= "  <td width=\"20%\" nowrap class=\"hc_table_submodulo_list_title\">ANESTESIOLOGO</td>";
      if($datos[$i]['anestesiologo'])
      {     
        $this->salida .= "  <td width=\"30%\" nowrap class=\"hc_submodulo_list_oscuro\">".$datos[$i]['anestesiologo']."</td>";       
      }
      else
      {
        $this->salida .= "  <td width=\"30%\" nowrap class=\"hc_submodulo_list_oscuro\">&nbsp;</td>";       
      }  
      $this->salida .= "  <td width=\"20%\" nowrap class=\"hc_table_submodulo_list_title\">AYUDANTE</td>";      
      if($datos[$i]['ayudante'])
      {     
        $this->salida .= "  <td width=\"30%\" nowrap class=\"hc_submodulo_list_oscuro\">".$datos[$i]['ayudante']."</td>";
      }
      else
      {
        $this->salida .= "  <td width=\"30%\" nowrap class=\"hc_submodulo_list_oscuro\">&nbsp;</td>";       
      } 
      $this->salida .= "  </tr>";      
      $this->salida .= "    <tr>";      
      $this->salida .= "    <td width=\"20%\" nowrap class=\"hc_table_submodulo_list_title\">INSTRUMENTADOR</td>";
      if($datos[$i]['instrumentador'])
      {
        $this->salida .= "    <td width=\"30%\" nowrap class=\"hc_submodulo_list_oscuro\">".$datos[$i]['instrumentador']."</td>";    
      }
      else
      {
        $this->salida .= "  <td width=\"30%\" nowrap class=\"hc_submodulo_list_oscuro\">&nbsp;</td>";       
      }       
      $this->salida .= "    <td width=\"20%\" nowrap class=\"hc_table_submodulo_list_title\">CIRCULANTE</td>";
      if($datos[$i]['circulante'])
      {
        $this->salida .= "    <td class=\"hc_submodulo_list_oscuro\">".$datos[$i]['circulante']."</td>";   
      }
      else
      {
        $this->salida .= "  <td  class=\"hc_submodulo_list_oscuro\">&nbsp;</td>";       
      }   
      $this->salida.= "     </tr>";      
      $this->salida.= "  </table>"; 		
			$this->salida.="	 <table  align=\"center\" border=\"0\" width=\"95%\">";
			$this->salida.="	 <tr class=\"modulo_table_title\">";
			$this->salida.="   <td align=\"center\" colspan=\"3\">PROCEDIMIENTOS REALIZADOS</td>";
			$this->salida.="	 </tr>";	
			$this->salida.="	 <tr class=\"hc_table_submodulo_list_title\">";			
			$this->salida.="  <td width=\"20%\">CARGO</td>";
			$this->salida.="  <td colspan=\"2\">DESCRIPCION</td>";			
			$this->salida.="	</tr>";
			$procedimientos=$this->ProcedimientosNotaOperatoria($datos[$i]['hc_nota_operatoria_cirugia_id']);
			for($j=0;$j<sizeof($procedimientos);$j++)
      {
				$this->salida.="<tr class=\"hc_submodulo_list_claro\">";				
				$this->salida.="  <td align=\"center\" width=\"20%\" rowspan=\"3\">".$procedimientos[$j]['procedimiento_qx']."</td>";
				$this->salida.="  <td align=\"left\" colspan=\"2\">".$procedimientos[$j]['descripcion']."</td>";											
				$this->salida.="</tr>";	        
        
        $procedimientosOpc=$this->BuscarProcedimientosInsertadosNotaOperatoria($datos[$i]['hc_nota_operatoria_cirugia_id'],$procedimientos[$j]['procedimiento_qx']); 
        $this->salida.="<tr class=\"modulo_list_claro\"><td id=\"MostrarProcedimientoOpc\" colspan=\"5\">";    
        if($procedimientosOpc)
        {          
          $this->salida.="<table border=\"1\" width=\"100%\" align=\"center\" class=\"normal_10\">";
          $this->salida.="<tr class=\"modulo_table_list_title\">";
          $this->salida.="<td width=\"10%\">CODIGO</td>";
          $this->salida.="<td>PROCEDIMIENTO</td>";                
          $this->salida.="</tr>";        
          for($m=0;$m<sizeof($procedimientosOpc);$m++)
          {
            $this->salida.="<tr class=\"modulo_list_oscuro\">";
            $this->salida.="<td width=\"20%\">".$procedimientosOpc[$m]['procedimiento_opcion']."</td>";
            $this->salida.="<td>".$procedimientosOpc[$m]['descripcion']."</td>";             
            $this->salida.="</tr>";
          }        
          $this->salida.="</table>";      
        }
        $this->salida.="</td></tr>";
        
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="  <td class=\"hc_table_submodulo_list_title\" colspan = 1 align=\"left\" width=\"10%\">Observacion</td>";
				$this->salida.="  <td class=\"hc_submodulo_list_claro\" align=\"left\" width=\"64%\">".$procedimientos[$j]['observaciones']."</td>";
				$this->salida.="</tr>";
				$this->salida.="<tr class=\"$estilo\">";
				$this->salida.="	<td class=\"hc_table_submodulo_list_title\" align=\"center\" width=\"10%\">Diagnosticos Pre-QX</td>";
				$this->salida.="	<td colspan=\"2\" class=\"hc_submodulo_list_claro\">";				
				$diag =$this->Diagnosticos_ProcedimientosNO($datos[$i]['hc_nota_operatoria_cirugia_id'],$procedimientos[$j]['procedimiento_qx']);
				if($diag)
        {					
					$this->salida.="<table width=\"100%\">";
					$this->salida.="<tr class=\"hc_table_submodulo_list_title\">";
					$this->salida.="<td width=\"10%\">PRIMARIO</td>";
					$this->salida.="<td width=\"10%\">TIPO DX</td>";
					$this->salida.="<td width=\"8%\">CODIGO</td>";
					$this->salida.="<td width=\"60%\">DIAGNOSTICO</td>";					
					$this->salida.="</tr>";					
					for($m=0;$m<sizeof($diag);$m++)
          {							
						$this->salida.="<tr class=\"$estilo\">";
						if($diag[$m]['sw_principal']=='1')
            {
							$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/checksi.png\"  border='0'></td>";
						}
            else
            {								
							$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/checkno.png\"  border='0'></td>";
						}
						if($diag[$m]['tipo_diagnostico'] == '1')
            {
							$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresion Diagnostica\"></td>";
						}
            elseif($diag[$m]['tipo_diagnostico'] == '2')
            {
							$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
						}
            else
            {
							$this->salida.="<td align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
						}
						$this->salida.="<td class=\"hc_submodulo_list_claro\" align=\"center\" width=\"8%\">".$diag[$m]['diagnostico_id']."</td>";
						$this->salida.="<td class=\"hc_submodulo_list_claro\" align=\"justify\" width=\"60%\">".$diag[$m]['diagnostico_nombre']."</td>";																					
						$this->salida.="<tr>";										
					}
					$this->salida.="</table>";					
				}				
			}
			$this->salida.="		</td></tr>";	
			$this->salida.= "		</table>";
			$this->salida .= "  <table border=\"0\" width=\"95%\" align=\"center\">";
			$this->salida .= "  <tr class=\"modulo_table_title\"><td colspan=\"4\" align=\"center\">DIAGNOSTICOS</td></tr>";
			$this->salida .= "  <tr>";
			$this->salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">POST QX</td>";
			$this->salida .= "  <td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['diag_nom']."</td>";				
			$this->salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">TIPO</td>";
			if($datos[$i]['tipo_diagnostico_post_qx'] == '1')
      {
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresion Diagnostica\"></td>";
			}
      elseif($datos[$i]['tipo_diagnostico_post_qx'] == '2')
      {
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
			}
      elseif($datos[$i]['tipo_diagnostico_post_qx'] == '3')
      {
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
			}
      else
      {
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\">&nbsp;</td>";
			}			
			$this->salida .= "  </tr>";			
			$this->salida .= "  <tr>";
			$this->salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">COMPLICACION</td>";
			$this->salida .= "  <td align=\"left\" class=\"hc_submodulo_list_oscuro\">".$datos[$i]['diag_nom1']."</td>";				
			$this->salida .= "  <td width=\"15%\" class=\"hc_table_submodulo_list_title\">TIPO</td>";
			if($datos[$i]['tipo_diagnostico_complicacion'] == '1')
      {
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/id.png\" border='0' title=\"Impresion Diagnostica\"></td>";
			}
      elseif($datos[$i]['tipo_diagnostico_complicacion'] == '2')
      {
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cn.png\" border='0' title=\"Confirmado Nuevo\"></td>";
			}
      elseif($datos[$i]['tipo_diagnostico_complicacion'] == '3')
      {
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\"><img src=\"".GetThemePath()."/images/cr.png\" border='0' title=\"Confirmado Repetido\"></td>";
			}
      else
      {
				$this->salida.="<td class=\"hc_submodulo_list_oscuro\" align=\"center\" width=\"10%\">&nbsp;</td>";
			}			
			$this->salida .= "  </tr>";			
			$this->salida.= "</table>";
			if($datos[$i]['descripcion_envio_patologico'])
      {
				$this->salida .="<table border=\"0\" width=\"95%\" align=\"center\">";
				$this->salida.= "<tr>";
				if($datos[$i]['envio_patologico']==1)
        {
					$this->salida.= "<td class=\"modulo_table_title\">MATERIAL ENVIADO A PATOLOGIA:&nbsp;&nbsp;&nbsp;SI</td>";						
				}
        else
        {
					$this->salida.= "<td class=\"modulo_table_title\">MATERIAL ENVIADO A PATOLOGIA:&nbsp;&nbsp;&nbsp;NO</td>";						
				}
				$this->salida.= "</tr>";
				$this->salida.= "<tr>";					
				$this->salida.= "<td class=\"hc_submodulo_list_oscuro\"><label class=\"normal_10N\">CLASE DE MATERIAL ENVIADO:</label><BR>".$datos[$i]['descripcion_envio_patologico']."</td>";						
				$this->salida.= "</tr>";
				$this->salida.= "</table>";
			}
			if($datos[$i]['descripcion_envio_cultivo'])
      {
				$this->salida .="<table border=\"0\" width=\"95%\" align=\"center\">";
				$this->salida.= "<tr>";
				if($datos[$i]['envio_cultivo']==1)
        {
					$this->salida.= "<td class=\"modulo_table_title\">CULTIVO ENVIADO:&nbsp;&nbsp;&nbsp;SI</td>";						
				}
        else
        {
					$this->salida.= "<td class=\"modulo_table_title\">CULTIVO ENVIADO:&nbsp;&nbsp;&nbsp;NO</td>";						
				}
				$this->salida.= "</tr>";
				$this->salida.= "<tr>";					
				$this->salida.= "<td class=\"hc_submodulo_list_oscuro\"><label class=\"normal_10N\">CLASE DE MATERIAL ENVIADO:</label><BR>".$datos[$i]['descripcion_envio_cultivo']."</td>";						
				$this->salida.= "</tr>";			
				$this->salida.= "</table>";			
			}
			$this->salida.= "<BR>";	
		}
		$this->salida .= ThemeCerrarTablaSubModulo();
		return true;	
	}
    /**
		* Crea una forma, para mostrar mensajes informativos con un solo boton
		*
		* @param array $action vector que continen los link de la aplicacion
    * @param string $mensaje Cadena con el texto del mensaje a mostrar 
    *         en pantalla
    *
		* @return string
		*/
		function FormaMensajeModulo($action,$mensaje)
		{
			$html  = ThemeAbrirTabla('MENSAJE');
			$html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
			$html .= "	<tr>\n";
			$html .= "		<td>\n";
			$html .= "		  <table width=\"100%\" class=\"modulo_table_list\">\n";
			$html .= "		    <tr class=\"normal_10AN\">\n";
			$html .= "		      <td align=\"center\">\n".$mensaje."</td>\n";
			$html .= "		    </tr>\n";
			$html .= "		  </table>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
			$html .= "</table>";
			$html .= ThemeCerrarTabla();
			$this->salida .= $html;
      return true;
		}
}
?>