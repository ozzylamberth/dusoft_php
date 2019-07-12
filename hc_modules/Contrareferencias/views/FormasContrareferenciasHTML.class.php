<?php

class FormasContrareferenciasHTML{
		
		//Contructor
    function FormasContrareferenciasHTML(){
    }
    
    //Forma que permite crear una nueva contrareferencia
    function frmNuevaContraReferen($action){
         
        $html .= "<script>";
        $html .= "function crearContrarefer(){
                    document.formContrarefer.submit();                    
                  };";
        $html .= "</script>";
       
        $html .= "<form id=\"formContrarefer\" name=\"formContrarefer\" action=\"".$action['CrearContraref']."\" method=\"post\" > \n";    
        $html .= "<table width=\"80%\" align=\"center\" class=\"modulo_table_list\" > \n";
        $html .= "  <tr> \n";
        $html .= "      <td class=\"modulo_table_title\" align=\"center\" > CREAR CONTRAREFERENCIA NUEVA \n";
        $html .= "      </td> \n";
        $html .= "      <td class=\"modulo_table_title\" align=\"center\" > \n";
        $html .= "          <input type=\"button\" class=\"input-submit\" name=\"aceptar\" value=\"ACEPTAR\" onclick=\"crearContrarefer()\" > ";    
        $html .= "      </td> \n";        
        $html .= "  </tr> \n";
  
        $html .= "</table> \n";
        $html .= "</form>";    

        return $html;    
    }    
    
    
    
    function frmInfoContrarefer($pacienteId, $tipoIdPaciente, $pacienteEdad, $pacienteSexo, $contraReferId, $bls, $action){
				$html .= "<script> \n";  
					
				$html .= "    ObNodo = null;
												ObCola = null;
												ObCabeza = null; \n";
					
				$html .= "</script> \n";

				$html .= "<form id=\"formDiagnost\" name=\"formDiagnost\" action=\"".$action['ProcesContraref']."\" method=\"post\" > \n";

        $html .= "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\" > \n";
        $html .= "  <tr > \n";
        $html .= "      <td class=\"modulo_table_title\" align=\"center\" > FECHA \n";
        $html .= "      </td> \n";
//         $html .= "      <td class=\"modulo_table_title\" align=\"center\" > HORA \n";
//         $html .= "      </td> \n";
        $html .= "      <td class=\"modulo_table_title\" align=\"center\" > EDAD \n";
        $html .= "      </td> \n";
        $html .= "      <td class=\"modulo_table_title\" align=\"center\" > GENERO \n";
        $html .= "      </td> \n";
        $html .= "      <td class=\"modulo_table_title\" align=\"center\" > ESTADO CIVIL \n";
        $html .= "      </td> \n";
//         $html .= "      <td class=\"modulo_table_title\" align=\"center\" > INST.ULT.A�.APROB \n";
//         $html .= "      </td> \n";
        $html .= "      <td class=\"modulo_table_title\" align=\"center\" > EMPRESA DONDE TRABAJA \n";
        $html .= "      </td> \n";
        //$html .= "      <td class=\"modulo_table_title\" align=\"center\" > SEGURO SALUD \n";
        //$html .= "      </td> \n";
        
				$html .= "      <td class=\"modulo_table_title\" align=\"center\" > CONTRAREFERENCIA \n";
        $html .= "      </td> \n";
				
				$html .= "  </tr> \n";
        
        $hoy = date("d/m/Y");
        
        $ahora = date("H:i:s");
        
        $html .= "  <tr > \n";
        $html .= "      <td class=\"hc_table_submodulo_list_title\" align=\"center\" > ".$hoy." \n";
				$html .= "	<input type=\"hidden\" name=\"fechaCR\" value=\"".$hoy."\" > \n ";
        $html .= "      </td> \n";
//         $html .= "      <td class=\"hc_table_submodulo_list_title\" align=\"center\" > ".$ahora." \n";
//         $html .= "      </td> \n";
        $html .= "      <td class=\"hc_table_submodulo_list_title\" align=\"center\" > ".$pacienteEdad." \n";
        $html .= "      </td> \n";
        $html .= "      <td class=\"hc_table_submodulo_list_title\" align=\"center\" > ".$pacienteSexo." \n";
        $html .= "      </td> \n";
        
        $bls = AutoCarga::factory('ContrareferenciasMetodos','','hc1','Contrareferencias');        
        
        $arrEstCivil = $bls->ObtenEstCivil($pacienteId, $tipoIdPaciente);
        
        $html .= "      <td class=\"hc_table_submodulo_list_title\" align=\"center\" > ".$arrEstCivil[0]['tipo_estado_civil_id']." \n";        
        $html .= "      </td> \n";
        
//         $html .= "      <td class=\"hc_table_submodulo_list_title\" align=\"center\" > INST.ULT.A�.APROB \n";
//         $html .= "      </td> \n";
        $html .= "      <td class=\"hc_table_submodulo_list_title\" align=\"center\" > \n";
        $html .= "          <input type=\"text\" class=\"input-text\" name=\"empTrabaTxt\" size=\"30\" maxlength=\"30\" value=\"\" > \n";
        $html .= "      </td> \n";
        $html .= "      <td class=\"hc_table_submodulo_list_title\" align=\"center\" > ".$contraReferId." \n";
        $html .= "      </td> \n";
				$html .= "	<input type=\"hidden\" name=\"noContraReferenId\" value=\"".$contraReferId."\">\n";
        $html .= "  </tr> \n";
        $html .= "</table> \n";
//         $bls = AutoCarga::factory('ContrareferenciasMetodos','','hc1','Contrareferencias');
        
        $html .= "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\" > \n";
        $html .= "  <tr > \n";
        $html .= "      <td class=\"modulo_table_title\" align=\"center\" colspan=\"2\" > ESTABLECIMIENTO AL QUE SE CONTRAREFERENCIA \n";
        $html .= "      </td> \n";
        $html .= "      <td class=\"hc_table_submodulo_list_title\" align=\"center\" colspan=\"3\" > \n";
        
//         $html .= "          <input type=\"text\" class=\"input-text\" name=\"establRefeTxt\" size=\"10\" maxlength=\"10\" value=\"\" > \n";        
        
        $html .= "          <select name=\"estableci\" class=\"select\" onChange=\"\"  >\n";
        $html .= "              <option value=\"-1\">-- Seleccionar --</option>\n";
        
        $arrEstableci = $bls->ObtenEstablecimientos();
        foreach($arrEstableci as $key => $vecEstableci){
            $html .= "      <option value=\"".$vecEstableci['centro_remision']."\" > ".$vecEstableci['descripcion']." </option> \n";
        }
        
        $html .= "          </select> \n";

        $html .= "      </td> \n";
        
        $html .= "      <td class=\"modulo_table_title\" align=\"center\" colspan=\"2\" > SERVICIO QUE SE CONTRAREFIERE \n";
        $html .= "      </td> \n";
        $html .= "      <td class=\"hc_table_submodulo_list_title\" align=\"center\" colspan=\"2\" > \n";
        
//         $html .= "          <input type=\"text\" class=\"input-text\" name=\"servReferTxt\" size=\"10\" maxlength=\"10\" value=\"\" > \n";        
        
        $html .= "          <select name=\"servicio\" class=\"select\" onChange=\"\"  >\n";
        $html .= "              <option value=\"-1\">-- Seleccionar --</option>\n";
        
        $arrServicio = $bls->ObtenServicios();
        foreach($arrServicio as $key => $vecServicio){
            $html .= "      <option value=\"".$vecServicio['servicio']."\" > ".$vecServicio['descripcion']." </option> \n";
        }
            
        $html .= "          </select> \n";

        $html .= "      </td> \n";
        $html .= "  </tr> \n";
        
        $html .= "</table> \n";

        //$html .= "</form> \n";
        return $html; 
    }
    
    //Forma para Diagnosticos
    function frmDiagnosticos($pacienteId, $tipoIdPaciente ,$action){
                
        $html .= "<script> ";
        
        $html .= "  function mOvr(src,clrOver){
                        src.style.background = clrOver;
                    } \n 
        
                    function mOut(src,clrIn){
                        src.style.background = clrIn;
                    } \n ";
										
        $html .= "  function Nodo(nomb, val, clase, nomclase, refer){ \n
                        this.nomb = nomb;
                        this.val = val;
                        this.clase = clase;
                        this.nomclase = nomclase;
                        this.refer = refer;
                        this.marca = '1';
                        return this;
                    }; \n";   
										
        $html .= "  function EliminarNodo(arrNodo){ \n                        
                        
                        nodo = arrNodo[0];                
                        nodo1 = arrNodo[1];
                             
                       //alert('\\nElimina = ' + PrintNodo(nodo) + 
                       //      '\\n\\nAnteriorElimina = ' + PrintNodo(nodo1));     
                        
                        //Una forma que me invente para eliminar la cabeza                    
                        if(nodo.val == nodo1.val){  
                            ObNodo = nodo.refer;
                            nodo1 = ObNodo;
                        }    
                        else{
                            nodo1.refer = nodo.refer;
                            nodo = null;
                        }                  
                    
                    }; \n"; 
										
        $html .= "  function PrintNodo(nodo){ \n                                                
                        return '\\n\\nNomb: ' + nodo.nomb + '\\nVal: ' + nodo.val + 
                        '\\nClase: ' + nodo.clase + '\\nMarca: ' + nodo.marca + '\\nRefer: ' + nodo.refer;                                                                    
                    }; \n";
                                                  
        $html .= "  function Recorrer(nodo){ \n
                        
                        var cad = '';
                         
                        //i = 0;
                              
                        while(nodo.refer != null){
                          //i++;
                          cad += PrintNodo(nodo);
                          nodo = nodo.refer;  
                        };
                        
                        cad += PrintNodo(nodo);
                        
                        //i++;
                        
                        //document.formDiagnost.numTotNodo.value = i; 
                        return cad;
                        
                    }; \n";    

										
				$html .= "		function pruebaClase(){ \n
													alert(Recorrer(ObNodo));
											}; \n";    
										
										
		$html .= "  function cambiaValor(val){ \n
                        
                        if(val.checked)
                            val.value = '2';
                        else 
                            val.value = '1';
                        
                        //alert('Valor diagId: ' + val.value);
                    }; \n";
						
						
        $html .= "	function cambiaMarcaNodo(val, ident){ \n
        
                        nodo = BuscarNodo(ident)[0];
                            
        
                        if(val.checked){
                            val.value = '2';
                            nodo.marca = '2';
                        }
                        else{ 
                            val.value = '1';
                            nodo.marca = '1';
                        }
                        
//                         alert('\\nValor diagId: ' + val.value + 
//                                '\\n\\nNodoNomb = ' + nodo.nomb + 
//                                '\\nNodoVal = ' + nodo.val + 
//                                '\\nNodoClase = ' + nodo.clase + 
//                                '\\nNodoMarca = ' + nodo.marca);
                                                                        
                    }; \n"; 
									
										
		$html .= "    function BuscarNodo(valor){ \n 
                            nodo = ObNodo; 
                            nodo1 = nodo;
                            
                            arrNodo = new Array(2);
                                                        
                            while(nodo.refer != null){
                            
                                if(nodo.val == valor){
                                    arrNodo[0] = nodo;
                                    arrNodo[1] = nodo1;
                                    
                                    return arrNodo;
                                }
                                
                                nodo1 = nodo;
                                nodo = nodo.refer;  
                            }
                           
                            if(nodo.val == valor){
                                arrNodo[0] = nodo;
                                arrNodo[1] = nodo1;

                                return arrNodo;
                            }
                            
                            return null;                                
                        }; \n";  
										
          $html .= "    function SeekAndDestroy(){ \n
                            
                           nodo = ObNodo;
                           nodo1 = nodo;
                           
                           arrNodo = new Array(2);

                           sw = false;
                           str = '';
                           
                           while(nodo.refer != null){
                            
                            //nodo1 = nodo; 
                            //nodo = nodo.refer; 
                            nodoSig = nodo.refer;
                            
                            if(nodo.marca == '2'){
                                arrNodo[0] = nodo;
                                arrNodo[1] = nodo1;
                                //nodoSig = nodo.refer;
                                sw = true;                               
                                str += PrintNodo(nodo);
                                //alert(PrintNodo(nodo));
                                EliminarNodo(arrNodo);
                                nodo = nodo1; 
                                //return nodo;
                            }
                            
                            nodo1 = nodo; 
                            nodo = nodoSig; 
                            //nodo = nodo.refer;  
                           };
                           
                            if(nodo.marca == '2'){
                                arrNodo[0] = nodo;
                                arrNodo[1] = nodo1;
                                sw = true;
                                str += PrintNodo(nodo);
                                //alert(PrintNodo(nodo));
                                EliminarNodo(arrNodo);
                                //nodo = nodo1;
                            }
														
														if(sw)
															Pintar();
														else
															alert('No se encontraron elementos seleccionados!!!');
														
														                     
                        }; \n";
												
												
        
    $html .= "  function Pintar(){ \n ";
    $html .= "      var vhtml = ''; \n";
    $html .= "      vhtml += '<table width=\"100%\" align=\"center\" class=\"modulo_table_list\" >'; \n";
		
    $html .= "      vhtml += '<tr>'; 
                    vhtml += '  <td class=\"modulo_table_title\" width=\"10%\" > CIE </td>';
                    vhtml += '  <td class=\"modulo_table_title\" width=\"60%\" > DIAGNOSTICO </td>';
                    vhtml += '  <td class=\"modulo_table_title\"  width=\"25%\" > CLASE </td>';
                    vhtml += '  <td class=\"modulo_table_title\"  width=\"5%\" > REMOVER </td>';
                    vhtml += '</tr>';";
										        
    $html .= "      vhtml += Listar(ObNodo); \n";         
    $html .= "      vhtml += '</table>'; \n";    
    $html .= "      document.getElementById('diagnostPacie').innerHTML = vhtml; \n";
    $html .= "  }; \n";  

		$html .= "  function Listar(complnodo){ \n
										
									var cad = ''; 
									i = 0;
										
									if(complnodo == null){
											document.formDiagnost.numTotNodo.value = i;
											return cad;       
									}
												
									while(complnodo.refer != null){
										i++;                          
										cad += ImprimDiagn(complnodo);
										//document.formDiagnost.numTotNodo.value = i;
										complnodo = complnodo.refer;  
									};
									
									i++;
									document.formDiagnost.numTotNodo.value = i;
									cad += ImprimDiagn(complnodo);
									return cad;
										
								}; \n";
								
		$html .= "	function ImprimDiagn(complnodo){ \n
										
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
												
        $html .= "</script> ";
                        
//         $html .= "<form id=\"formDiagnost\" name=\"formDiagnost\" action=\"".$action['ProcesContraref']."\" method=\"post\" > \n";
        
        
        $html .= $this->frmParte1();

        $html .= "<br> \n";
        
        $html .= "<div id=\"diagnostPacie\"> \n";
				//$html .= $this->frmListDiagnost($pacienteId, $tipoIdPaciente);
				$html .= "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\" > \n";
        $html .= "  <tr > \n";
        $html .= "      <td class=\"modulo_table_title\"  width=\"10%\" > CIE \n";
        $html .= "      </td> \n";
                
        $html .= "      <td class=\"modulo_table_title\"  width=\"60%\" > DIAGNOSTICO \n";
        $html .= "      </td> \n";
                
        $html .= "      <td class=\"modulo_table_title\"  width=\"23%\" > CLASE \n";
        $html .= "      </td> \n";
                
				// 		$html .= "		<td class=\"modulo_table_title\" align=\"center\" > PRE \n";
				// 		$html .= "		</td> \n";
				// 		$html .= "		<td class=\"modulo_table_title\" align=\"center\" > DEF \n";
				// 		$html .= "		</td> \n";
				//$html .= "			<input type=\"button\" class=\"input-submit\" name=\"aceptar\" value=\"INGRESAR\" target=\"diagnosticos\" onclick=\"window.open('".$url."','diagnosticos','toolbar=no,width=500,height=350,resizable=no,scrollbars=yes').focus(); return false;\"\">\n";
        $html .= "      <td class=\"modulo_table_title\"  width=\"7%\" > REMOVER \n";                
        $html .= "      </td> \n";
        $html .= "  </tr> \n";
                          
        $html .= "	<input type=\"hidden\" name=\"pacienId1\" value=\"".$pacienteId."\" >\n";
        $html .= "	<input type=\"hidden\" name=\"tipoIdPacien1\" value=\"".$tipoIdPaciente."\" >\n";
         
           
        $html .= "</table> \n";
                
        $html .= "</div> \n"; 

                
		$url = "classes/BuscadorDiagnosticos/BuscadorDiagnosticos.class.php?pacienId=".$pacienteId."&tipoIdPacien=".$tipoIdPaciente."";  
					
					
		$url = "classes/BuscadorDiagnosticos/BuscadorDiagnosticos.class.php?";
		$url .= "pacienId=".$pacienteId."&tipoIdPacien=".$tipoIdPaciente."";
		$url .= "&noReferen=".$referId."";  
		$url .= "&formBuscaDiagnost=formDiagnost";
		$url .= "&nodoAtrap=nodoAtrapado";
		$url .= "&nodUltId=nodoUltimId";
		$url .= "&Nodos=ObNodo";
		$url .= "&NdCola=ObCola";
		$url .= "&NdCabeza=ObCabeza";
				  
        
		//$url = "classes/BuscadorLocalizacion/BuscadorLocalizacion.class.php?pais=$pais&dept=$dpto&mpio=$mpio&forma=forma".URLRequest($param);        
		$html .= "<table width=\"100%\" align=\"center\"  > \n";        
		$html .= "  <tr > \n";
		$html .= "      <td align=\"center\" > \n ";
		
		$html .= "      <input type=\"button\" class=\"input-submit\" name=\"aceptar\" value=\"INGRESAR DIAGNOSTICO\" target=\"diagnosticos\" onclick=\"window.open('".$url."','diagnosticos','toolbar=no,width=500,height=350,resizable=no,scrollbars=yes').focus(); return false;\"\" > \n";
		
// 		$html .= "<input type=\"button\" class=\"input-submit\" name=\"ejecutar\" value=\"MOSTRAR DATO\" onclick=\"pruebaClase()\" > \n";
		
		$html .= "<input type=\"button\" class=\"input-submit\" name=\"eliminar\" value=\"QUITAR DIAGNOSTICOS\" onclick=\"SeekAndDestroy();\" > \n";
		
		
		//$html .= "HOLA MUNDO";

		$html .= " <input type=\"hidden\" name=\"numTotNodo\" value =\"null\" > \n";
		$html .= " <input type=\"hidden\" name=\"validGuar\" value =\"null\" > \n";
		
		$html .= "      </td> \n";
		$html .= "  </tr> \n";
		$html .= "</table> \n";
		$html .= "<br>";
		$html .= $this->frmParte2();
		$html .= "<script> ";
		$html .= "	function PruebaDatos(){
		
			if(document.formDiagnost.empTrabaTxt.value == \"\"){
					document.getElementById('errorContraReferen').innerHTML = 'Debe ingresar la Empresa que Trabaja!'; \n
					document.formDiagnost.empTrabaTxt.focus(); \n
					return false; \n
			}
															
			if(document.formDiagnost.estableci.value == \"-1\"){
					document.getElementById('errorContraReferen').innerHTML = 'Debe Seleccionar un Establecimiento!'; \n
					document.formDiagnost.estableci.focus(); \n
					return false; \n
			}
															
			if(document.formDiagnost.servicio.value == \"-1\"){
					document.getElementById('errorContraReferen').innerHTML = 'Debe Seleccionar un Servicio!'; \n
					document.formDiagnost.servicio.focus(); \n
					return false; \n
			}

			if(document.formDiagnost.salaTxt.value == \"\"){
					document.getElementById('errorContraReferen').innerHTML = 'Debe Ingresar la Sala!'; \n
					document.formDiagnost.salaTxt.focus(); \n
					return false; \n
			}
			
			if(document.formDiagnost.camaTxt.value == \"\"){
					document.getElementById('errorContraReferen').innerHTML = 'Debe Ingresar la Cama!'; \n
					document.formDiagnost.camaTxt.focus(); \n
					return false; \n
			}
		
		
		document.getElementById('errorContraReferen').innerHTML = '';
		document.formDiagnost.validGuar.value = creaDiagHidden(ObNodo);
		    
// 		alert(
// 		'\\nfechaCR: ' + document.formDiagnost.fechaCR.value + 
// 		//'\\nhoraCR: ' + document.formDiagnost.horaCR.value +
// 		'\\nempTrabaTxt: ' + document.formDiagnost.empTrabaTxt.value +
// 		'\\nestableci: ' + document.formDiagnost.estableci.value +
// 		'\\nservicio: ' + document.formDiagnost.servicio.value +
// 		
// 		'\\nresCuadClinTxt: ' + document.formDiagnost.resCuadClinTxt.value + '\\nhallRevExamTxt: ' + document.formDiagnost.hallRevExamTxt.value + '\\ntratProcTeraRealiTxt: ' + document.formDiagnost.tratProcTeraRealiTxt.value + '\\nplanTratRecomTxt: ' + document.formDiagnost.planTratRecomTxt.value +
// 		
// 		'\\nsalaTxt: ' + document.formDiagnost.salaTxt.value + 
// 		'\\ncamaTxt: ' + document.formDiagnost.camaTxt.value + 
// 		'\\nprofesNomb: ' + document.formDiagnost.profesNomb.value +
// 		'\\nprofesCod: ' + document.formDiagnost.profesCod.value + 
// 		'\\nvalidGuar: ' + document.formDiagnost.validGuar.value
// 		
// 		);
		
		document.formDiagnost.submit(); 
		
		};\n";
		

	$html .= "		function creaDiagHidden(nodo){
										var cad = '';
										i = 0;
										
										if(nodo == null)
											return cad; 
											
										while(nodo.refer != null){  \n
											
												//nodo.nomb, nodo.val, nodo.clase
												//nodo.nomb_nodo.val_nodo.clase 
												//str = nodo.nomb + '_' + nodo.val + '_' + nodo.clase
												
												//document.formDiagnost.numTotNodo.value = numTotNodo
																											
												cad += i + '_' + nodo.val + '_' + nodo.clase + ';';
												i++;
												nodo = nodo.refer;  
										};  \n
										
										
										//'diagnHidden'
											
										//cad += i + '_' + nodo.val + '_' + nodo.clase + ';';
										cad += i + '_' + nodo.val + '_' + nodo.clase;
										
										return cad;        
							
								}; \n";
		
		$html .= "</script> ";
		$html .= "<table width=\"100%\" align=\"center\"  > \n";
		$html .= "  <tr > \n";
		$html .= "      <td align=\"center\" > \n ";
		$html .= "      <input type=\"button\" class=\"input-submit\" name=\"aceptar\" value=\"PROCESAR CONTRAREFERENCIA\" onclick=\"PruebaDatos()\" > \n";
		$html .= "      </td> \n";
		$html .= "  </tr> \n";
		$html .= "</table> \n";
		
		$html .= "<center>\n
									<div id=\"errorContraReferen\" class=\"label_error\"></div>\n
							</center> <br>\n"; 
		
		$html .= "</form> \n";
        return $html;
    }
    
    //Forma que Lista los diagnosticos
    //function frmListDiagnost($action, $nomDiag, $valOff){
    function frmListDiagnost($pacienteId, $tipoIdPaciente){
            
        $html .= "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\" > \n";
                
        $obCons = AutoCarga::factory('ContrareferenciasMetodos','','hc1','Contrareferencias');
       
        $arrDiagnos = $obCons->ObtenDiagnPacien($pacienteId, $tipoIdPaciente);
        
        foreach($arrDiagnos as $key => $vecDiagnos){
            $html .= "	<tr  class='modulo_list_claro' onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\" > \n";
            $html .= "      <td align=\"center\" width=\"20%\" > ".$vecDiagnos['diagnostico_id']." \n";
            $html .= "      </td> \n";            
            $html .= "      <td align=\"center\" width=\"50%\" > ".$vecDiagnos['diagnostico_nombre']." \n";
            $html .= "      </td> \n";
            $html .= "      <td align=\"center\" width=\"30%\" > ".$vecDiagnos['descripcion']." \n";
            $html .= "      </td> \n";
//  $html .= "      <td align=\"center\" > ".$vecDiagnos['diagnostico_nombre']." \n";
//  $html .= "      </td> \n";
            $html .= "	</tr> \n";
        }

        $html .= "</table> \n";
        
        return $html;
    }
    
		//Forma de la primera parte del formulario de contrareferencias
    function frmParte1(){
        $html .= "<table width=\"80%\" align=\"center\" class=\"modulo_table_list\" > \n";
        
        $html .= "  <tr > \n";
        $html .= "      <td class=\"modulo_table_title\" align=\"center\" > RESUMEN CUADRO CLINICO \n";
        $html .= "      </td> \n";
        $html .= "  </tr> \n";
        $html .= "  <tr > \n";
        $html .= "      <td class=\"hc_table_submodulo_list_title\" align=\"center\" > \n";
        $html .= "          <textarea cols=50 rows=2 name=\"resCuadClinTxt\"></textarea> \n";
        $html .= "      </td> \n";
        $html .= "  </tr> \n";
        
        $html .= "  <tr > \n";
        $html .= "      <td class=\"modulo_table_title\" align=\"center\" > HALLAZGOS RELEVANTES DE EXAMENES Y PROCEDIMIENTOS DIAGNOSTICOS \n";
        $html .= "      </td> \n";
        $html .= "  </tr> \n";
        $html .= "  <tr > \n";
        $html .= "      <td class=\"hc_table_submodulo_list_title\" align=\"center\" > \n";
        $html .= "          <textarea cols=50 rows=2 name=\"hallRevExamTxt\"></textarea> \n";
        $html .= "      </td> \n";
        $html .= "  </tr> \n";
        
        $html .= "  <tr > \n";
        $html .= "      <td class=\"modulo_table_title\" align=\"center\" > TRATAMIENTO Y PROCEDIMIENTOS TERAPEUTICOS REALIZADOS \n";
        $html .= "      </td> \n";
        $html .= "  </tr> \n";
        $html .= "  <tr > \n";
        $html .= "      <td class=\"hc_table_submodulo_list_title\" align=\"center\" > \n";
        $html .= "          <textarea cols=50 rows=2 name=\"tratProcTeraRealiTxt\"></textarea> \n";
        $html .= "      </td> \n";
        $html .= "  </tr> \n";
        
        $html .= "</table> \n";
        
        return $html;
    }
    
		//Forma de la segunda del formulario de Contrareferencias    
    function frmParte2(){
       
        $obCons = AutoCarga::factory('ContrareferenciasMetodos','','hc1','Contrareferencias');    
    
        $arrUsuarioRespon  = $obCons->ObtenUsuario();
    
        $html .= "<table width=\"80%\" align=\"center\" class=\"modulo_table_list\" > \n";
        $html .= "  <tr > \n";
        $html .= "      <td class=\"modulo_table_title\" align=\"center\" colspan=\"8\" > PLAN TRATAMIENTO RECOMENDADO \n";
        $html .= "      </td> \n";
        $html .= "  </tr> \n";
        
        $html .= "  <tr > \n";
        $html .= "      <td class=\"hc_table_submodulo_list_title\" align=\"center\" colspan=\"8\"> \n";
        $html .= "          <textarea cols=50 rows=2 name=\"planTratRecomTxt\"></textarea> \n";
        $html .= "      </td> \n";
        $html .= "  </tr> \n";
        
        $html .= "  <tr > \n";
        $html .= "      <td class=\"modulo_table_title\" align=\"center\" > SALA \n";
        $html .= "      </td> \n";
        $html .= "      <td class=\"hc_table_submodulo_list_title\" align=\"center\" > \n";        
        $html .= "          <input type=\"text\" class=\"input-text\" name=\"salaTxt\" size=\"6\" maxlength=\"6\" value=\"\" >\n";
        $html .= "      </td> \n";
        $html .= "      <td class=\"modulo_table_title\" align=\"center\" > CAMA \n";
        $html .= "      </td> \n";
        $html .= "      <td class=\"hc_table_submodulo_list_title\" align=\"center\" > \n";
        $html .= "          <input type=\"text\" class=\"input-text\" name=\"camaTxt\" size=\"6\" maxlength=\"6\" value=\"\" >\n";        
        $html .= "      </td> \n";
        
        $html .= "	<input type=\"hidden\" name=\"profesNomb\" value=\"".$arrUsuarioRespon[0]['nombre']."\" >\n";
        
        $html .= "      <td class=\"modulo_table_title\" align=\"center\" > PROFESIONAL \n";
        $html .= "      </td> \n";
        $html .= "      <td class=\"hc_table_submodulo_list_title\" align=\"center\" > ".$arrUsuarioRespon[0]['nombre']." \n";
        //$html .= "          <input type=\"text\" class=\"input-text\" name=\"profesTxt\" size=\"30\" maxlength=\"30\" value=\"".$arrUsuarioRespon[0]['nombre']."\" >\n";        
        $html .= "      </td> \n";
        
        $html .= "	<input type=\"hidden\" name=\"profesCod\" value=\"".$arrUsuarioRespon[0]['usuario_id']."\" >\n";
        
        $html .= "      <td class=\"modulo_table_title\" align=\"center\" > CODIGO \n";
        $html .= "      </td> \n";
        $html .= "      <td class=\"hc_table_submodulo_list_title\" align=\"center\" > ".$arrUsuarioRespon[0]['usuario_id']." \n";       
        $html .= "      </td> \n";
        $html .= "  </tr> \n";       
        
        $html .= "</table> \n";
        return $html;
    }
    
  /**
  * Forma que Muestra en el mensaje de exito o fracaso en la creacion de una Contrareferencia 
  */
  function fmrMsjIngrContraReferen($action, $mensaje, $objNod){
  	$html  = ThemeAbrirTabla('MENSAJE INGRESO CONTRAREFERENCIA');
	$html .= "      <form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
	$html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
	$html .= "  <tr>\n";
	$html .= "    <td>\n";
	$html .= "      <table width=\"100%\" class=\"modulo_table_list\">\n";
	$html .= "        <tr class=\"normal_10AN\">\n";
	$html .= "          <td align=\"center\">\n".$mensaje."</td>\n";
	$html .= "        </tr>\n";
	$html .= "      </table>\n";
	$html .= "    </td>\n";
	$html .= "  </tr>\n";
	$html .= "  <tr>\n";
	$html .= "    <td align=\"center\"><br>\n";
	
	$html .= "        <input class=\"input-submit\" type=\"submit\" name=\"btnVolver\" value=\"Volver\">";
	      
	$html .= "    </td>";
	$html .= "  </tr>";
	$html .= "</table> \n";
	
	$html .= "      </form> \n";   
	
	$html .= "<script> \n";            
	$html .= "  function muestra(val){
					return val;
				}; \n";                    
	$html .= "</script> \n";       
		
	$html .= ThemeCerrarTabla();      
      return $html; 

  }                 

}
?>