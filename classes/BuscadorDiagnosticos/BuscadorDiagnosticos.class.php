<?php

class BuscadorDiagnosticos{

    var $request = array();
    
    function BuscadorDiagnosticos(){
        $this->request = $_REQUEST;
    }
            
    function FormaBuscadorDiagnosticos(){
            
        //$request = $_REQUEST;
            
        $file = '../../classes/BuscadorDiagnosticos/RemoteXajax/Diagnosticos.php';
        
        list($xajax) = getXajax();
        //$xajax->setFlag("debug", true);
        
        $xajax->registerFunction("obtenListDiagnos", $file);
        $xajax->registerFunction("enviarDiagnos", $file);
        
        $xajax->processRequest();
        
        $html .= ReturnHeader('Buscador');
        $html .= ReturnBody()."<br>\n";
        $html .= $xajax->printJavascript('../../classes/xajax/');
        $html .= ThemeAbrirTabla("DIAGNOSTICOS");
                                  
        //$actBusca = "/SIIS/classes/BuscadorDiagnosticos/BuscadorDiagnosticos.class.php?formBuscaDiagnost1=formBuscaDiagnost1&pacienId=pacienId&tipoIdPacien=tipoIdPacien"; 
        
        $actBusca = "/SIIS/classes/BuscadorDiagnosticos/BuscadorDiagnosticos.class.php?"; 
        $actBusca .= "pacienId=".$this->request['pacienId']."&";
        $actBusca .= "tipoIdPacien=".$this->request['tipoIdPacien']."&";
        $actBusca .= "noReferen=".$this->request['noReferen']."";
        $actBusca .= "&formBuscaDiagnost=formDiagnost";
        $actBusca .= "&nodoAtrap=nodoAtrapado";
        $actBusca .= "&nodUltId=nodoUltimId";
        $actBusca .= "&Nodos=ObNodo";
        $actBusca .= "&NdCola=ObCola";
        $actBusca .= "&NdCabeza=ObCabeza";
        $actBusca .= "&miArr=miArray1";            
        
        
        $html .= "<script> \n";
        
        $html .= "  function Nodo(nomb, val, clase, nomclase, refer){ \n
                        this.nomb = nomb;
                        this.val = val;
                        this.clase = clase;
                        this.nomclase = nomclase;  
                        this.refer = refer;
                        this.marca = '1';
                        return this;
                    }; \n";                

//         $html .= "  function AgregarNodo(){ \n        
//                         ObDiagn = window.opener.".$this->request['Nodos'].";          
//                         window.opener.".$this->request['Nodos']." = new Nodo(document.formBuscaDiagnost1.valNombDiagnos.value, 
//                         document.formBuscaDiagnost1.valDiagnos.value, document.formBuscaDiagnost1.valClaseDiagnos.value, 
//                         document.formBuscaDiagnost1.valNombClaseDiagnos.value, 
//                         ObDiagn);
//             
//                     }; \n";

        $html .= "  function AgregarNodo(){ \n
                        ObDiagn = window.opener.".$this->request['Nodos'].";
                        nodoCola = window.opener.".$this->request['NdCola'].";
                        
                        if(ObDiagn == null){
                            nuevoNodoCola = new Nodo(document.formBuscaDiagnost1.valNombDiagnos.value, 
                            document.formBuscaDiagnost1.valDiagnos.value, document.formBuscaDiagnost1.valClaseDiagnos.value, 
                            document.formBuscaDiagnost1.valNombClaseDiagnos.value, 
                            null);
                            
                            window.opener.".$this->request['NdCola']." = nuevoNodoCola;
                            window.opener.".$this->request['Nodos']." = nuevoNodoCola;
                        }
                        else{
                            nuevoNodoCola = new Nodo(document.formBuscaDiagnost1.valNombDiagnos.value, 
                            document.formBuscaDiagnost1.valDiagnos.value, document.formBuscaDiagnost1.valClaseDiagnos.value, 
                            document.formBuscaDiagnost1.valNombClaseDiagnos.value, 
                            null);                        
                        
                            nodoCola.refer = nuevoNodoCola;
                            window.opener.".$this->request['NdCola']." = nuevoNodoCola;                      
                        }                             
                                                 
                    }; \n";

                    
        $html .= "  function NodoExiste(valor){  \n 
                        nodo = window.opener.".$this->request['Nodos'].";
                        
                        if(nodo == null)
                            return false;
                        
                        while(nodo.refer != null){
                            
                            if(nodo.val == valor){
                                document.getElementById('errorDiagonst').innerHTML = 'El Diagnostico : ' + valor + ', ya se encuentra en la lista!';
																return true;   
                            }
                                
                            nodo = nodo.refer;
                        }
                        
                        if(nodo.val == valor){
                            document.getElementById('errorDiagonst').innerHTML = 'El Diagnostico : ' + valor + ', ya se encuentra en la lista!';
                            return true;   
                        }                                        

                        return false;
                    };  \n";                                    
        
                    
        $html .= "	function crearNuevoNodo(){ \n
											if(!NodoExiste(document.formBuscaDiagnost1.valDiagnos.value)){
												
												if(validaGuardar()){
													AgregarNodo();
													Dibujar();
													document.getElementById('errorDiagonst').innerHTML = '';
												}

													//document.formBuscaDiagnost2.submit(); 
													//window.close(); 

											}
                    }; \n";                    
        
				$html .= "	function validaEnvio(){
											alert('se fue!!');
										}\n ";
										
        $html .= "  function ImprimNodo(ruta, complnodo){ \n
                        
                        var vhtml = '<tr class=\'modulo_list_claro\' onMouseOver=\"mOvr(this,\'#ffffff\');\" onMouseOut=\"mOut(this,\'#DDDDDD\');\" >';
                        vhtml += '  <td colspan=\"1\" align=\"center\" > ' + complnodo.val;
                        vhtml += '  </td> ';
                        vhtml += '  <td colspan=\"1\" align=\"center\" > ' + complnodo.nomb;
                        vhtml += '  </td> ';
                        vhtml += '  <td colspan=\"1\" align=\"center\" > ' + complnodo.nomclase;
                        vhtml += '  </td> ';
                                                                                                                                                                                                                               
                        vhtml += '  <td colspan=\"1\" align=\"center\" > ';
                        //vhtml += '      <input type=\"checkbox\" name=\"diagId\" value=\"1\" onClick=\"cambiaValor(this)\" >'; 

                        //vhtml += '      <input type=\"checkbox\" name=\"diagId\" value=\"1\" onClick=\"EliminarNodo(BuscarNodo(\'' + complnodo.val + '\'));\" >'; 
                        
                        //vhtml += '  <input type=\"checkbox\" name=\"diagId\" value=\"1\" onClick=\"BuscarNodo(\'' + complnodo.val + '\')\" >';
        
                        //vhtml += '      <input type=\"checkbox\" name=\"diagId\" value=\"1\" onClick=\"cambiaValor(this)\" >';                       
                        
                        vhtml += '  <input type=\"checkbox\" name=\"diagId\" value=\"1\" onClick=\"cambiaMarcaNodo(this, \'' + complnodo.val + '\');\" >';
                        
                        
                        vhtml += '  </td> ';
                        vhtml += '</tr>' ;
                                                                         
                        return vhtml;         
        
                    }; \n";                    
                            
        $html .= "  function Iterar(ruta, complnodo){ \n
                        
                        var cad = ''; 
                        i = 0;
                        
                        if(complnodo == null){
                            window.opener.document.".$this->request['formBuscaDiagnost'].".numTotNodo.value = i;
                            return cad;       
                        }
                            
                        while(complnodo.refer != null){
                          i++;                          
                          cad += ImprimNodo(ruta, complnodo);
                          //document.formDiagnost.numTotNodo.value = i;
                          complnodo = complnodo.refer;  
                        };
                        
                        i++;
                        window.opener.document.".$this->request['formBuscaDiagnost'].".numTotNodo.value = i;
                        cad += ImprimNodo(ruta, complnodo);
                        return cad;
                        
                    }; \n";
                    
        $html .= "  function crearHiddenPrueba(){ \n
                        vhtml = '';  
                        
                        vhtml += '<table width=\"100%\" align=\"center\" class=\"modulo_table_list\" >';
                        vhtml += '<tr>';
                        vhtml += '   <td> varTxtPrueba: ';
                        vhtml += '   </td>';
                        vhtml += '   <td>';
                        vhtml += '      <input type=\"text\" class=\"input-text\" name=\"varTxtPrueba\" size=\"30\" maxlength=\"30\" value=\"456487\" >';                        
                        vhtml += '   </td>';
                        vhtml += '   <td>';
                        vhtml += '      <input type=\"button\" class=\"input-submit\" name=\"aceptarPr\" value=\"ProbVar \" onclick=\"alert(\'' + 'hehehhehbsjf' + '\')\" >';
                        vhtml += '   </td>'; 
                                                
                        vhtml += '</tr>'
                        vhtml += '</table>';  
                        
                        return '' + vhtml;
                    } \n";                                          
        
        $html .= "</script> \n";        
        
        
        
        $html .= "<form id=\"formBuscaDiagnost1\" name=\"formBuscaDiagnost1\" method=\"post\" action=\"".$actBusca."\" > \n";

        $html .= "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\" > \n";
        $html .= "  <tr> \n";
        $html .= "      <td class=\"modulo_table_title\" align=\"center\" colspan=\"100%\" > BUSQUEDA DIAGNOSTICOS \n";
        $html .= "      </td> \n";	
        $html .= "  </tr> \n";
        $html .= "  <tr> \n";
//         $html .= "      <td class=\"hc_table_submodulo_list_title\" align=\"center\" > TIPO \n";
//         $html .= "      </td> \n";
//         $html .= "      <td class=\"hc_table_submodulo_list_title\" align=\"center\" > CARGO \n";
//         $html .= "      </td> \n";
        $html .= "      <td class=\"hc_table_submodulo_list_title\" align=\"center\" > DESCRIPCION \n";
        $html .= "      </td> \n";
        $html .= "      <td class=\"hc_table_submodulo_list_title\" align=\"center\" > \n";
        $html .= "          <input type=\"text\" class=\"input-text\" name=\"descrip\" size=\"30\" maxlength=\"20\" value=\"\" > \n";
        $html .= "      </td> \n";
        $html .= "      <td class=\"hc_table_submodulo_list_title\" align=\"center\" > \n";
        $html .= "          <input type=\"button\" class=\"input-submit\" name=\"aceptar\" value=\"BUSCAR\" onclick=\"validaBusqueda()\">\n";
        $html .= "      </td> \n";
        $html .= "  </tr> \n";
        $html .= "</table> \n";
        
        $html .= "  <input type=\"hidden\" name=\"offset\" value=\"1\" > \n";
        $html .= "  <input type=\"hidden\" name=\"valDiagnos\" value=\"\" > \n";
        $html .= "  <input type=\"hidden\" name=\"valNombDiagnos\" value=\"\" > \n";
        $html .= "  <input type=\"hidden\" name=\"valClaseDiagnos\" value=\"\" > \n";
        $html .= "  <input type=\"hidden\" name=\"valNombClaseDiagnos\" value=\"\" > \n";
                
        $html .= "<br> \n";
        
        //$html .= "<div id=\"listDiagnost\"> \n";
        $actPagin = "/SIIS/classes/BuscadorDiagnosticos/BuscadorDiagnosticos.class.php?";
        //$actPagin .= "formBuscaDiagnost=formBuscaDiagnost&";
        $actPagin .= "pacienId=".$this->request['pacienId']."&";
        $actPagin .= "tipoIdPacien=".$this->request['tipoIdPacien']."&";
        $actPagin .= "descrip=".$this->request['descrip']."&";
        $actPagin .= "noReferen=".$this->request['noReferen']."";
        $actPagin .= "&formBuscaDiagnost=formDiagnost";
        $actPagin .= "&nodoAtrap=nodoAtrapado";
        $actPagin .= "&nodUltId=nodoUltimId";
        $actPagin .= "&Nodos=ObNodo";
        $actPagin .= "&NdCola=ObCola";
        $actPagin .= "&NdCabeza=ObCabeza";        
        $actPagin .= "&miArr=miArray1";             
                     
        
        $html .= $this->frmListDiagnost($actPagin, $this->request['descrip'], $this->request['offset']);
        //else
        //    $html .= $this->frmListDiagnost($actBusca, "MARC", $this->request['offset']);    
        //$html .= "</div> \n";        
        $html .= "</form> \n";
        
        $actGuarda = "/SIIS/classes/BuscadorDiagnosticos/BuscadorDiagnosticos.class.php?";
        //$actGuarda .= "formBuscaDiagnost2=formBuscaDiagnost2";
        $actGuarda .= "pacienId=".$this->request['pacienId']."&";
        $actGuarda .= "tipoIdPacien=".$this->request['tipoIdPacien']."&";
        $actGuarda .= "noReferen=".$this->request['noReferen']."";
        //$actGuarda .= "valDiagnos=".$this->request['valDiagnos']."";
        $actGuarda .= "&formBuscaDiagnost=formDiagnost";
        $actGuarda .= "&nodoAtrap=nodoAtrapado";
        $actGuarda .= "&nodUltId=nodoUltimId";
        $actGuarda .= "&Nodos=ObNodo";
        $actGuarda .= "&NdCola=ObCola";
        $actGuarda .= "&NdCabeza=ObCabeza";        
        $actGuarda .= "&miArr=miArray1";        
        
				$html .= "<center>\n
            <div id=\"errorDiagonst\" class=\"label_error\"> </div>\n
            </center> \n";
				
        $html .= "<form id=\"formBuscaDiagnost2\" name=\"formBuscaDiagnost2\" method=\"post\" action=\"".$actGuarda."\" > \n";
        $html .= "<table width=\"100%\" align=\"center\" > \n";
        $html .= "  <tr> \n";
        $html .= "      <td align=\"center\" > \n";
        $html .= "          <input type=\"button\" class=\"input-submit\" name=\"crear\" value=\"CREAR\" onclick=\"crearNuevoNodo();\"> \n";
        $html .= "      </td> \n";
        
				$html .= "  </tr> \n";        
        $html .= "</table> \n";
        
        $bls = new BuscadorDiagnosticosSQL();
                
        $html .= "</form> \n";
                
        $html .= ThemeCerrarTabla();
        $html .= "</body>\n";
        $html .= "</html>\n";
                    
        $html .= "<script> ";
        $html .= "function validaBusqueda(){                                           
                  
                    document.formBuscaDiagnost1.submit();
                }; \n";
                            
        $html .= "function validaGuardar(){

//                     alert('pacienId: ' + '".$this->request['pacienId']."' + 
//                         '\\ntipoIdPacien: ' + '".$this->request['tipoIdPacien']."' + 
//                         '\\nvalDiagnos: ' + document.formBuscaDiagnost1.valDiagnos.value + 
//                         '\\nvalClaseDiagnos: ' + document.formBuscaDiagnost1.valClaseDiagnos.value +
//                         '\\nnoReferen: ' + '".$this->request['noReferen']."'
//                     );
                     
                    if(document.formBuscaDiagnost1.valDiagnos.value == ''){
                        //alert('Debe elegir un Diagnostico para guardar!');
                        document.getElementById('errorDiagonst').innerHTML = 'Debe elegir un Diagnostico para guardar!'; \n
                        //document.formDiagnost.empTrabaTxt.focus(); \n                        
                        return false;
                    }
                    
                    if(document.formBuscaDiagnost1.valClaseDiagnos.value == -1){
                        //alert('Debe elegir una Clase de  Diagnostico a guardar!');
                        document.getElementById('errorDiagonst').innerHTML = 'Debe elegir una Clase de Diagnostico a guardar!'; \n
                        return false;
                    }                                 
									return true;
									
									}; \n
									
									
									";
                                        
    $html .= "  function Dibujar(){ \n ";
    $html .= "      var vhtml = ''; \n";
    $html .= "      vhtml += '<table width=\"100%\" align=\"center\" class=\"modulo_table_list\" >'; \n";
    
    $html .= "      vhtml += '<tr>'; 
                    vhtml += '  <td class=\"modulo_table_title\" width=\"10%\" > CIE </td>';
                    vhtml += '  <td class=\"modulo_table_title\" width=\"60%\" > DIAGNOSTICO </td>';
                    vhtml += '  <td class=\"modulo_table_title\"  width=\"25%\" > CLASE </td>';
                    vhtml += '  <td class=\"modulo_table_title\"  width=\"5%\" > REMOVER </td>';
                    vhtml += '</tr>';";
    
    $html .= "  rutaNodo = window.opener; \n
                valComplNodo = window.opener.".$this->request['Nodos']."; \n
                //valNodo = ".$this->request['Nodos']."; \n";
        
    $html .= "      vhtml += Iterar(rutaNodo, valComplNodo); \n";         
    $html .= "      vhtml += '</table>'; \n";    
    
    $html .= "      window.opener.document.getElementById('diagnostPacie').innerHTML = vhtml; \n";
    $html .= "  }; \n";    
    
    
    $html .= "</script> ";
                    
        //$html .= "<pre>".print_r($this->request,true)."</pre> \n";
            
        return $html;                
    }
                
    //Metodo para listar los diagnosticos
    function frmListDiagnost($action, $nombDiag, $valOff){
        
        $bls = new BuscadorDiagnosticosSQL();
        
        $arrClaseDiagnos = $bls->ConsClaseDiagnos();
        
        $html .= "<script> ";
        $html .= "  function mOvr(src,clrOver){
                        src.style.background = clrOver;
                    } \n 
        
                    function mOut(src,clrIn){
                        src.style.background = clrIn;
                    } \n ;";
                    
        $html .= "  function selDiagnos(objDiag, objNombDiag, objClaDiag){";
        
        for($i=0; $i<5; $i++){
            $html .= "document.formBuscaDiagnost1.claseDiagnos_".$i.".disabled = true; ";
        }
                                  
        $html .= "      objClaDiag.disabled = false;
                        
                        document.formBuscaDiagnost1.valClaseDiagnos.value = -1;
                        document.formBuscaDiagnost1.valNombDiagnos.value = objNombDiag.value;
                        document.formBuscaDiagnost1.valDiagnos.value = objDiag.value;";
                                                  
/*       $html .= "                 alert('diagnosId: ' + document.formBuscaDiagnost1.valDiagnos.value +
                              '\\nnombDiagnos: ' + document.formBuscaDiagnost1.valNombDiagnos.value +     
                              '\\nclaseDiagnos: ' + document.formBuscaDiagnost1.valClaseDiagnos.value + 
                              '\\nnomclasDiagno: ' + document.formBuscaDiagnost1.valNombClaseDiagnos.value);
								\n";*/       

       $html .= "		} \n"; 
                    
        $html .= "function selClaseDiagnos(objClaDiag){
                        document.formBuscaDiagnost1.valClaseDiagnos.value = objClaDiag.value;
                  
                  ";
        foreach($arrClaseDiagnos as $key2 => $vecClaseDiagnos){
            $html .= "   objClaDiag_".$key2." =  ".$vecClaseDiagnos['clase_diagnost_id'].";
                         objNomClaDiag_".$key2." = '".$vecClaseDiagnos['descripcion']."'; \n ";
        }        
        
        $html .= "  if(objClaDiag_0 == document.formBuscaDiagnost1.valClaseDiagnos.value)
                        document.formBuscaDiagnost1.valNombClaseDiagnos.value = objNomClaDiag_0;
                    else if(objClaDiag_1 == document.formBuscaDiagnost1.valClaseDiagnos.value)
                        document.formBuscaDiagnost1.valNombClaseDiagnos.value = objNomClaDiag_1;
                    else
                        document.formBuscaDiagnost1.valNombClaseDiagnos.value = 'No Hay';
                    \n";       
                            
/*        $html .=    "alert('Nombre: ' + objClaDiag.name);

                    alert('diagnosId: ' + document.formBuscaDiagnost1.valDiagnos.value +
                              '\\nnombDiagnos: ' + document.formBuscaDiagnost1.valNombDiagnos.value +     
                              '\\nclaseDiagnos: ' + document.formBuscaDiagnost1.valClaseDiagnos.value + 
                              '\\nnomclasDiagno: ' + document.formBuscaDiagnost1.valNombClaseDiagnos.value);
										\n";  */                                                                    
                                                                    
          $html .= "} \n ";                   
        
        //$this->request['valDiagnos'] = "document.formBuscaDiagnost1.valDiagnos.value;";
        
        $html .= "</script> ";

        $html .= "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\" > \n";
        $html .= "  <tr > \n";
        $html .= "      <td class=\"modulo_table_title\" align=\"center\" > CODIGO \n";
        $html .= "      </td> \n";
        $html .= "      <td class=\"modulo_table_title\" align=\"center\" > DIAGNOSTICO \n";
        $html .= "      </td> \n";
        $html .= "      <td class=\"modulo_table_title\" align=\"center\" > CLASE \n";
        $html .= "      </td> \n";            
        $html .= "      <td class=\"modulo_table_title\" align=\"center\" > \n";
        $html .= "      </td> \n";        
        $html .= "  </tr> \n";
                        
        //$obCons = AutoCarga::factory('ReferenciasMetodos','','hc1','Referencias');
        $arrDiagnos = $bls->ObtenDiagnosticos(strtoupper($nombDiag), $valOff);
        //$arrDiagnos = $bls->ObtenDiagnosticos($nombDiag);
                        
        $conteo = $bls->conteo;
        $pagina = $bls->pagina;
                        
        //$arrClaseDiagnos = $bls->ConsClaseDiagnos();
                        
        foreach($arrDiagnos as $key1 => $vecDiagnos){
            $html .= "  <tr class='modulo_list_claro' onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" > \n";
            $html .= "      <td align=\"center\" > ".$vecDiagnos['diagnostico_id']." \n";
            $html .= "      </td> \n";
            $html .= "      <td align=\"center\" > ".$vecDiagnos['diagnostico_nombre']." \n";
            $html .= "      </td> \n";
            
            $html .= "  <input type=\"hidden\" name=\"nombDiagnos_".$key1."\" value=\"".$vecDiagnos['diagnostico_nombre']."\" > \n";
            
            
            //$html .= "      <td align=\"center\" > claseDiagnos_".$key1." \n";
						$html .= "      <td align=\"center\" > \n";
            $html .= "          <select name=\"claseDiagnos_".$key1."\" class=\"select\" onChange=\"selClaseDiagnos(this)\" disabled >\n";
            
//             $html .= "          <select name=\"claseDiagnos[]\" class=\"select\" onChange=\"selClaseDiagnos(this)\" >\n";            
            
            $html .= "              <option value=\"-1\">-- Seleccionar --</option>\n";
            
            //$arrClaseDiagnos = $bls->ConsClaseDiagnos();
            
            foreach($arrClaseDiagnos as $key2 => $vecClaseDiagnos){
                $html .= "      <option value=\"".$vecClaseDiagnos['clase_diagnost_id']."\" > ".$vecClaseDiagnos['descripcion']." </option> \n";
            }
            
            $html .= "          </select> \n";
            $html .= "      </td> \n";            
                        
            //$html .= "      <td align=\"center\" > [".$key1."]:  \n";
						$html .= "      <td align=\"center\" > \n"; 
            $html .= "          <input type=\"radio\" name=\"diagnosId\" value=\"".$vecDiagnos['diagnostico_id']."\" onClick=\"selDiagnos(this, 
                                document.formBuscaDiagnost1.nombDiagnos_".$key1.", document.formBuscaDiagnost1.claseDiagnos_".$key1.");\" >";            
                        
            $html .= "      </td> \n";
                        
            $html .= "	</tr> \n";
        }
                                                                                               
        $pghtml = AutoCarga::factory('ClaseHTML');
        $html .= $pghtml->ObtenerPaginado($conteo,$pagina,$action,5);           
        
        $html .= "</table> \n";

        //$html .= "<pre>".print_r($this->request, true)."</pre> \n"; 
                       
        return $html;
    }
		

}
	
$VISTA='HTML';
$_ROOT='../../';
include $_ROOT.'includes/enviroment.inc.php';

IncludeClass("AutoCarga");

include $_ROOT.'classes/BuscadorDiagnosticos/BuscadorDiagnosticosSQL.class.php';

$fileName = "themes/$VISTA/".GetTheme()."/module_theme.php";
IncludeFile($fileName);

$bsc = new BuscadorDiagnosticos();
echo $bsc->FormaBuscadorDiagnosticos();
?>