<?php
  /******************************************************************************
  * $Id: HabitacionesHTML.class.php,v 1.7 2010/11/29 14:05:53 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @package IPSOFT-SIIS
  *
	* $Revision: 1.7 $ 
	* 
	* @autor
  ********************************************************************************/
  IncludeClass('Habitaciones','','app','Cuentas');
  IncludeLib("funciones_facturacion");
  
	class HabitacionesHTML
	{
		function HabitacionesHTML(){}
		/**********************************************************************************
		* Funcion donde se buscan crea la forma de los cargos de habitaciones
		* 
		* @return array 
		***********************************************************************************/
		function FormaHabitaciones($hab,$Plan,$cuenta,$TipoId,$PacienteId,$Nivel,$Cama,$Fecha,$Ingreso)
		{
						unset($_SESSION['CUENTAS']['MOVIMIENTOS']);                
						//$accion=ModuloGetURL('app','Cuentas','user','CargarHabitacion',array('Cuenta'=>$cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$Plan,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
						$html = "<form name=\"formabuscar\" action=\"$accion\" method=\"post\">";
						$html .= "<table border=\"0\" cellspacing=\"1\" cellpadding=\"1\" width=\"90%\" align=\"center\"  class=\"modulo_table_list\">";
						$html .= "    <tr align=\"center\" class=\"modulo_table_title\">";
						$html .= "    <td colspan=\"6\">HABITACIONES</td>";
						$html .= "    </tr>";
						$html .= "    <tr align=\"center\" class=\"modulo_table_list_title\">";
						$html .= "     <td width=\"8%\">TARIF.</td>";
						$html .= "     <td width=\"8%\">CARGO</td>";
						$html .= "     <td width=\"60%\">DESCRIPCION</td>";
						$html .= "     <td width=\"8%\">PRECIO</td>";
						$html .= "     <td width=\"8%\">CANTIDAD</td>";
						$html .= "     <td width=\"8%\">TOTAL</td>";
						//$html .= "     <td width=\"4%\"></td>";
						$html .= "    </tr>";
						$total=0;
						for($i=0; $i<sizeof($hab); $i++)
						{
										if( $i % 2) $estilo='modulo_list_claro';
										else $estilo='modulo_list_oscuro';
										$html .= "    <tr class=\"$estilo\">";
										$html .= "     <td align=\"center\">".$hab[$i][tarifario_id]."</td>";
										$html .= "     <td align=\"center\">".$hab[$i][cargo]."</td>";
										$html .= "     <td>".$hab[$i][descripcion]."</td>";
										$html .= "     <td align=\"center\">".$hab[$i][precio_plan]."</td>";
										$html .= "     <td align=\"center\">".$hab[$i][cantidad]."</td>";
										$html .= "     <td align=\"center\">".$hab[$i][valor_cargo]."</td>";
										//$html .= "     <td align=\"center\"><input type=\"checkbox\" name=\"HAB$i\" value=\"".$i."\"></td>";
										$html .= "    </tr>";
										$total +=$hab[$i][valor_cargo];
						}
						$html .= "    <tr align=\"center\">";
						$html .= "    <td colspan=\"5\" align=\"right\" class=\"label\">TOTAL ESTANCIA:</td>";
						$html .= "    <td colspan=\"1\" align=\"right\" class=\"label\">".FormatoValor($total)."</td>";
						$html .= "    </tr>";

						$html .= "    <tr align=\"center\">";
						$camasMov=RetornarWinOpenDetalleCamas($Ingreso,$cuenta,'DETALLE DE MOVIMIENTOS','label');
						$html .= "    <td colspan=\"3\" align=\"center\" class=\"label\">$camasMov</td>";
						$egreso = $this->LlamaValidarEgresoPaciente($Ingreso);       
						if(!empty($egreso) OR $this->LlamaValidarCuentaCorte($cuenta))
						{
								$accion=ModuloGetURL('app','Cuentas','user','LlamaFormaLiquidacionManualHabitaciones',array('Cuenta'=>$cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$Plan,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
								$html .= "    <td colspan=\"3\" align=\"left\" class=\"label\"><a href=\"$accion\">LIQUIDACION MANUAL</a></td>";            
								$html .= "    </tr>";
								$html .= "    <tr>";
								$accion=ModuloGetURL('app','Cuentas','user','LlamadoCargarHabitacionCuenta',array("EmpresaId"=>$_SESSION['CUENTAS']['EMPRESA'],'Cuenta'=>$cuenta,'TipoId'=>$TipoId,'PacienteId'=>$PacienteId,'Nivel'=>$Nivel,'PlanId'=>$Plan,'Cama'=>$Cama,'Fecha'=>$Fecha,'Ingreso'=>$Ingreso));
								$html .= "    <td colspan=\"3\" align=\"center\" class=\"label\"><a href=\"$accion\">CARGAR A LA CUENTA</a></td><td colspan=\"3\"><BR>&nbsp;</td>";
								$html .= "</form>";
						}
						else
						{   
								$html .= "  <td colspan=\"3\" align=\"center\" class=\"label_mark\">EL PACIENTE NO TIENE ORDEN DE SALIDA DE LA ESTACION</td>";              
								
						}                
						$html .= "</form>";
						$html .= "    </tr>";
						$html .= "  </table><br>";
						return $html;
		}
		
		/**
		***LlamaValidarEgresoPaciente
		**/
		function LlamaValidarEgresoPaciente($Ingreso)
		{
			$hab = new Habitaciones();
			$fact = $hab->ValidarEgresoPaciente($Ingreso);
			return $fact;
		}
		/**
		***ValidarCuentaCorte
		**/
		function LlamaValidarCuentaCorte($cuenta)
		{
			$hab = new Habitaciones();
			$fact = $hab->ValidarCuentaCorte($cuenta);
			return $fact;
		}
	}
?>