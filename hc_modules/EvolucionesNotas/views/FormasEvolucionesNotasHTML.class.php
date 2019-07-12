<?php

	class FormasEvolucionesNotasHTML{
		
		//Contructor de la Clase
		function FormasEvolucionesNotasHTML(){
		}
		
		/**
		*	Forma principal donde se muestra la entidad, el profesional y la evolucion
		*	que se va registrar en la Nota de Evolucion
		*/
		function FormaEvolucion($action, $empreNomb, $usuarNomb, $evoluc, $pacientId, $tipoIdpacient, $ingrId){
				
			$html .= ThemeAbrirTablaSubModulo("EVOLUCIONES");
			
			$html .= "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\" > \n";
			$html .= "	<tr > \n";
			$html .= "		<td class=\"modulo_table_title\" align=\"center\" > ENTIDAD: \n";
			$html .= "		</td>";
			$html .= "		<td align=\"center\" > ".$empreNomb." \n";
			$html .= "		</td>";
			$html .= "	</tr>";
			$html .= "	<tr >";
			$html .= "		<td class=\"modulo_table_title\" align=\"center\" > PROFESIONAL:";
			$html .= "		</td>";
			$html .= "		<td align=\"center\" > ".$usuarNomb." \n";
			$html .= "		</td>";
			$html .= "	</tr>";
			$html .= "	<tr >";
			$html .= "		<td class=\"modulo_table_title\" align=\"center\" > EVOLUCION:";
			$html .= "		</td>";
			$html .= "		<td align=\"center\" > ".$evoluc." \n";
			$html .= "		</td>";
			$html .= "	</tr>";
// 			$html .= "	<tr >";
// 			$html .= "		<td class=\"modulo_table_title\" align=\"center\" > EVOLUCION:";
// 			$html .= "		</td>";
// 			$html .= "		<td align=\"center\" > ".$pacientId." \n";
// 			$html .= "		</td>";
// 			$html .= "	</tr>";
// 			$html .= "	<tr >";
// 			$html .= "		<td class=\"modulo_table_title\" align=\"center\" > EVOLUCION:";
// 			$html .= "		</td>";
// 			$html .= "		<td align=\"center\" > ".$tipoIdpacient." \n";
// 			$html .= "		</td>";
// 			$html .= "	</tr>";
			
			$html .= "</table>";
		
			$html .= "<br>";
			$html .= $this->frmIngNotaEvolucion($action);
			$html .= "<br>";
			$html .= $this->fmrNotasEvolucion($evoluc);
			//$html .= $this->fmrNotasEvolucionHisto(false, $ingrId);
			
			$html .= "<br>";
			
			$html .= ThemeCerrarTablaSubModulo();
			
			$html .= "<script>
						function validar(){
							//alert('Hola!!!!');
							
							if(document.formIngNotaEvol.txtNotEvolu.value == \"\"){
								document.getElementById('errorIngNotaEvol').innerHTML = 'Debe ingresar texto en la Nota de Evolucion!'; \n
								document.formIngNotaEvol.txtNotEvolu.focus(); \n
								return false; 
							}
							
							document.formIngNotaEvol.submit();
						}	
					</script>";
			
			return $html;
		}
		
		/**
		*	Forma que permite listar las Notas de Evolucion, filtradas 
		*	por evolucion
		*/
		function fmrNotasEvolucion($evoluc){
			
			$obConn = AutoCarga::factory('EvolucionesNotasMetodos', '', 'hc1', 'EvolucionesNotas');
			
			$html .= "<table width=\"100%\" align=\"center\" class=\"modulo_table_list\" > \n";
			
			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td> FECHA \n";
			$html .= "		</td> \n";
			$html .= "		<td> HORA \n";
			$html .= "		</td> \n";
			$html .= "		<td> NOTAS DE EVOLUCION \n";
			$html .= "		</td> \n";
			$html .= "		<td> EVOLUCION \n";
			$html .= "		</td> \n";
			$html .= "		<td> INGRESO \n";
			$html .= "		</td> \n";
			$html .= "		<td> PROFESIONAL \n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
// 			$html .= "	<tr>";
// 			$html .= "		<td> fecha \n";
// 			$html .= "		</td>";
// 			$html .= "		<td> hora \n";
// 			$html .= "		</td>";
// 			$html .= "		<td> texto";
// 			$html .= "		</td>";
// 			$html .= "	</tr>";
			
			$arrDatos = $obConn->ConsultarNotasEvoluc($evoluc);
			
			foreach($arrDatos as $key => $posvec){
				$html .= "	<tr> \n";
				
				$fh = explode(" ", $posvec['fecha']);
				
				$f = explode("-", $fh[0]);
				if(sizeof($f) == 3) $fecha = $f[2]."/".$f[1]."/".$f[0];
				
				$h = explode(".", $fh[1]);

				$html .= "		<td align=\"center\"> ".$fecha." \n";
				$html .= "		</td> \n";
				$html .= "		<td> ".$h[0]." \n";
				$html .= "		</td> \n";
				$html .= "		<td> ".$posvec['txt_nota_evol']." \n";
				$html .= "		</td> \n";
				$html .= "		<td> ".$posvec['evolucion_id']." \n";
				$html .= "		</td> \n";
				$html .= "		<td> ".$posvec['ingreso']." \n";
				$html .= "		</td> \n";
								
				$profesional = $obConn->ConsulProfesional($posvec['usuario_id']);
				
				$html .= "		<td> ".$profesional[0]['nombre']." \n";
				$html .= "		</td> \n";
				$html .= "	</tr> \n";
			}
			
			$html .= "</table>";
			
			if($arrDatos == false)
				$html = "";
				
			return $html;
		}
		
		/**
		*	Forma que permite listar las Notas de Evolucion, filtradas por 
		*	numero de Ingreso
		*/
		function fmrNotasEvolucionHisto($evoluc, $ingrId){
			
			$obConn = AutoCarga::factory('EvolucionesNotasMetodos', '', 'hc1', 'EvolucionesNotas');
			
			$html .= "<table border=\"1\" width=\"100%\" align=\"center\" > \n";
			
			$html .= "	<tr class=\"label\" align=\"center\" > \n";
			$html .= "		<td> FECHA \n";
			$html .= "		</td> \n";
			$html .= "		<td> HORA \n";
			$html .= "		</td> \n";
			$html .= "		<td> NOTAS DE EVOLUCION \n";
			$html .= "		</td> \n";
			$html .= "		<td> EVOLUCION \n";
			$html .= "		</td> \n";
			$html .= "		<td> INGRESO \n";
			$html .= "		</td> \n";
			$html .= "		<td> PROFESIONAL \n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
// 			$html .= "	<tr>";
// 			$html .= "		<td> fecha \n";
// 			$html .= "		</td>";
// 			$html .= "		<td> hora \n";
// 			$html .= "		</td>";
// 			$html .= "		<td> texto";
// 			$html .= "		</td>";
// 			$html .= "	</tr>";
			
			$arrDatos = $obConn->ConsultarNotasEvoluc($evoluc, $ingrId);
			
			foreach($arrDatos as $key => $posvec){
				$html .= "	<tr class=\"normal_10\" align=\"center\" > \n";
				
				$fh = explode(" ", $posvec['fecha']);
				
				$f = explode("-", $fh[0]);
				if(sizeof($f) == 3) $fecha = $f[2]."/".$f[1]."/".$f[0];
				
				$h = explode(".", $fh[1]);

				$html .= "		<td > ".$fecha." \n";
				$html .= "		</td> \n";
				$html .= "		<td> ".$h[0]." \n";
				$html .= "		</td> \n";
				$html .= "		<td> ".$posvec['txt_nota_evol']." \n";
				$html .= "		</td> \n";
				$html .= "		<td> ".$posvec['evolucion_id']." \n";
				$html .= "		</td> \n";
				$html .= "		<td> ".$posvec['ingreso']." \n";
				$html .= "		</td> \n";
				
				$profesional = $obConn->ConsulProfesional($posvec['usuario_id']);
				
				$html .= "		<td> ".$profesional[0]['nombre']." \n";
				$html .= "		</td> \n";
				$html .= "	</tr> \n";
			}
			
			$html .= "</table>";
			
			return $html;
		}
		
		/**
		*	Forma donde es posible Ingresar las notas de evolucion 
		*/
		function frmIngNotaEvolucion($action){
		
			//$html .= "<form id=\"formFichaFam\" name=\"formFichaFam\" action=\"".$action['IngFichaFamiliar']."\" method=\"post\"> \n";
			
			$html .= "<form id=\"formIngNotaEvol\" name=\"formIngNotaEvol\" action=\"".$action['IngNotEvol']."\" method=\"post\" > \n";
			
			$html .= "<table width=\"60%\" align=\"center\" class=\"modulo_table_list\" > \n";
			$html .= "	<tr class=\"modulo_table_title\" > \n";
			$html .= "		<td align=\"center\" > NOTA EVOLUCION \n";
			$html .= "		</td>";
			$html .= "	</tr>";
			$html .= "	<tr> \n";
			$html .= "		<td align=\"center\" > \n";
			$html .= "			<textarea cols=45 rows=2 name=\"txtNotEvolu\"></textarea> \n";
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			$html .= "	<tr> \n";
			$html .= "		<td align=\"center\"> \n";
			$html .= "			<input type=\"button\" class=\"input-submit\" name=\"btnNotEvolu\" value=\"Ingresar\" onclick=\"validar()\" > \n"; 
			$html .= "		</td> \n";
			$html .= "	</tr> \n";
			$html .= "</table>";
			
			$html .= "</form>";
			
			$html .=	"<center>\n
							<div id=\"errorIngNotaEvol\" class=\"label_error\"></div>\n
						</center> <br>\n";
			
			
			return $html;
		}
		
		/**
		* Forma que Muestra en el mensaje de exito o fracaso en la creacion de una 
		* Nota de Evolucion 
		*/
		function fmrMsjIngrNotaEvoluc($action, $mensaje){
			
			$html  = ThemeAbrirTabla('MENSAJE NOTA EVOLUCION');
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
			$html .= "      <form name=\"form\" action=\"".$action['volver']."\" method=\"post\">";
			$html .= "        <input class=\"input-submit\" type=\"submit\" name=\"btnVolver\" value=\"Volver\">";
			$html .= "      </form>";
			$html .= "    </td>";
			$html .= "  </tr>";
			$html .= "</table>";
			$html .= ThemeCerrarTabla();
			      
			return $html; 
		} 
		
		
	}
?>